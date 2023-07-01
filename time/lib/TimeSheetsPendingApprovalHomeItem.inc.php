<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class TimeSheetsPendingApprovalHomeItem extends HomeItem
{
    public function __construct()
    {
        parent::__construct(
            'pending_time_list',
            'Time Sheets Pending Manager',
            'time',
            'narrow',
            23,
        );
    }

    public function visible(): bool
    {
        $current_user = &singleton('current_user');
        if (!isset($current_user) || !$current_user->is_employee()) {
            return false;
        }

        return (bool) has_pending_timesheet();
    }

    public function render(): bool
    {
        return true;
    }

    public function show_pending_time_sheets($template_name, $doAdmin = false)
    {
        show_time_sheets_list_for_classes($template_name, $doAdmin);
    }

    public function getHTML(): string
    {
        return <<<HTML
            <table class="list sortable">
                <tr>
                    <th>Time Sheet</th>
                    <th>Person</th>
                    <th class="right">Date</th>
                </tr>
                {$this->show_pending_time_sheets('pendingApprovalTimeSheetHomeR.tpl')}
            </table>
            HTML;
    }
}

function show_time_sheets_list_for_classes($template_name, $doAdmin = false)
{
    $date = null;
    $current_user = &singleton('current_user');
    global $TPL;

    $db = $doAdmin ? get_pending_admin_timesheet_db() : get_pending_timesheet_db();

    $people = &get_cached_table('person');

    while ($db->next_record()) {
        $timeSheet = new timeSheet();
        $timeSheet->read_db_record($db);
        $timeSheet->set_values();

        unset($date);
        if ('manager' == $timeSheet->get_value('status')) {
            $date = $timeSheet->get_value('dateSubmittedToManager');
        } elseif ('admin' == $timeSheet->get_value('status')) {
            $date = $timeSheet->get_value('dateSubmittedToAdmin');
        }

        unset($TPL['warning']);

        // older than $current_user->prefs["timeSheetDaysWarn"] days
        if ($date && (isset($current_user->prefs['timeSheetDaysWarn']) && (bool) strlen($current_user->prefs['timeSheetDaysWarn'])) && (time() - format_date('U', $date)) / 60 / 60 / 24 > $current_user->prefs['timeSheetDaysWarn']) {
            $TPL['warning'] = Page::help('This time sheet was submitted to you over ' . $current_user->prefs['timeSheetDaysWarn'] . ' days ago.', Page::warn());
        }

        $TPL['date'] = '<a href="' . $TPL['url_alloc_timeSheet'] . 'timeSheetID=' . $timeSheet->get_id() . '">' . $date . '</a>';
        $TPL['user'] = $people[$timeSheet->get_value('personID')]['name'];
        $TPL['projectName'] = $db->f('projectName');

        include_template('../time/templates/' . $template_name);
    }
}

function get_pending_timesheet_db()
{
    /*
      -----------     -----------------     --------
      | project |  <  | projectPerson |  <  | role |
      -----------     -----------------     --------
      /\
      -------------
      | timeSheet |
      -------------
      /\
      -----------------
      | timeSheetItem |
      -----------------
    */

    $current_user = &singleton('current_user');
    $db = new AllocDatabase();

    // Get all the time sheets that are in status manager, and are the responsibility of only the default manager
    if (in_array($current_user->get_id(), config::get_config_item('defaultTimeSheetManagerList'))) {
        // First get the blacklist of projects that we don't want to include below
        $query = unsafe_prepare("SELECT timeSheet.*, sum(timeSheetItem.timeSheetItemDuration * timeSheetItem.rate) as total_dollars
                               , COALESCE(projectShortName, projectName) as projectName
                            FROM timeSheet
                                 LEFT JOIN timeSheetItem ON timeSheet.timeSheetID = timeSheetItem.timeSheetID
                                 LEFT JOIN project on project.projectID = timeSheet.projectID
                           WHERE timeSheet.status='manager'
                             AND timeSheet.projectID NOT IN
                                 (SELECT projectID FROM projectPerson WHERE personID != %d AND roleID = 3)
                        GROUP BY timeSheet.timeSheetID
                        ORDER BY timeSheet.dateSubmittedToManager
                     ", $current_user->get_id());

        // Get all the time sheets that are in status manager, where the currently logged in user is the manager
    } else {
        $query = unsafe_prepare(
            "SELECT timeSheet.*, sum(timeSheetItem.timeSheetItemDuration * timeSheetItem.rate) as total_dollars
                  , COALESCE(projectShortName, projectName) as projectName
               FROM timeSheet
                    LEFT JOIN timeSheetItem ON timeSheet.timeSheetID = timeSheetItem.timeSheetID
                    LEFT JOIN project on project.projectID = timeSheet.projectID
                    LEFT JOIN projectPerson on project.projectID = projectPerson.projectID
                    LEFT JOIN role on projectPerson.roleID = role.roleID
              WHERE projectPerson.personID = %d AND role.roleHandle = 'timeSheetRecipient' AND timeSheet.status='manager'
           GROUP BY timeSheet.timeSheetID
           ORDER BY timeSheet.dateSubmittedToManager",
            $current_user->get_id()
        );
    }

    $db->query($query);

    return $db;
}

function get_pending_admin_timesheet_db()
{
    $query = null;
    $current_user = &singleton('current_user');
    $db = new AllocDatabase();

    $timeSheetAdminPersonIDs = config::get_config_item('defaultTimeSheetAdminList');

    if (in_array($current_user->get_id(), $timeSheetAdminPersonIDs)) {
        $query = "SELECT timeSheet.*, sum(timeSheetItem.timeSheetItemDuration * timeSheetItem.rate) as total_dollars, COALESCE(projectShortName, projectName) as projectName
                    FROM timeSheet
               LEFT JOIN timeSheetItem ON timeSheet.timeSheetID = timeSheetItem.timeSheetID
               LEFT JOIN project on project.projectID = timeSheet.projectID
                   WHERE timeSheet.status='admin'
                GROUP BY timeSheet.timeSheetID
                ORDER BY timeSheet.dateSubmittedToAdmin";
    }

    $db->query($query);

    return $db;
}

function has_pending_admin_timesheet(): bool
{
    $db = get_pending_admin_timesheet_db();

    return (bool) $db->next_record();
}

function has_pending_timesheet(): bool
{
    $db = get_pending_timesheet_db();

    return (bool) $db->next_record();
}
