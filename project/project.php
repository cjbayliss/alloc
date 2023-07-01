<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

$page = new Page();

$current_user = &singleton('current_user');

$projectID = $_POST['projectID'] ?? $_GET['projectID'] ?? '';
$project = new project();

function show_attachments()
{
    global $projectID;
    util_show_attachments('project', $projectID);
}

function list_attachments($template_name)
{
    global $TPL;
    global $projectID;

    if ($projectID) {
        $rows = get_attachments('project', $projectID);
        foreach ($rows as $row) {
            $TPL = array_merge($TPL, $row);
            include_template($template_name);
        }
    }
}

function show_transaction($template)
{
    global $db;
    global $TPL;
    global $projectID;
    $current_user = &singleton('current_user');

    $transaction = new transaction();

    if (isset($projectID) && $projectID) {
        $query = unsafe_prepare('SELECT transaction.*
                            FROM transaction
                           WHERE transaction.projectID = %d
                        ORDER BY transactionModifiedTime desc
                         ', $projectID);
        $db->query($query);
        while ($db->next_record()) {
            $transaction = new transaction();
            $transaction->read_db_record($db);
            $transaction->set_values('transaction_');

            $tf = $transaction->get_foreign_object('tf');
            $tf->set_values();
            $tf->set_values('tf_');

            $TPL['transaction_username'] = $db->f('username');
            $TPL['transaction_amount'] = Page::money($TPL['transaction_currenyTypeID'], $TPL['transaction_amount'], '%s%mo');
            ($TPL['transaction_type_link'] = $transaction->get_transaction_type_link()) || ($TPL['transaction_link'] = $transaction->get_value('transactionType'));

            include_template($template);
        }
    }
}

function show_invoices()
{
    $_FORM = [];
    $current_user = &singleton('current_user');
    global $project;
    $clientID = $project->get_value('clientID');
    $projectID = $project->get_id();

    $_FORM['showHeader'] = true;
    $_FORM['showInvoiceNumber'] = true;
    $_FORM['showInvoiceClient'] = true;
    $_FORM['showInvoiceName'] = true;
    $_FORM['showInvoiceAmount'] = true;
    $_FORM['showInvoiceAmountPaid'] = true;
    $_FORM['showInvoiceDate'] = true;
    $_FORM['showInvoiceStatus'] = true;
    $_FORM['clientID'] = $clientID;
    $_FORM['projectID'] = $projectID;

    // Restrict non-admin users records
    if (!$current_user->have_role('admin')) {
        $_FORM['personID'] = $current_user->get_id();
    }

    $rows = invoice::get_list($_FORM);
    echo invoice::get_list_html($rows, $_FORM);
}

function show_projectHistory(project $project): string
{
    $changeHistory = $project->get_changes_list();

    return <<<HTML
            <table class="box">
                <tr>
                  <th class="header">Project History</th>
                </tr>
                <tr>
                  <td>
                    <table class="sortable list">
                        <tr>
                          <th>Date</th>
                          <th>Change</th>
                          <th>Created by</th>
                        </tr>
                        {$changeHistory}
                    </table>
                  </td>
                </tr>
            </table>
        HTML;
}

function show_commission_list($template_name)
{
    global $TPL;
    global $db;
    global $projectID;

    if ($projectID) {
        $query = unsafe_prepare('SELECT * from projectCommissionPerson WHERE projectID= %d', $projectID);
        $db->query($query);

        while ($db->next_record()) {
            $commission_item = new projectCommissionPerson();
            $commission_item->read_db_record($db);
            $commission_item->set_values('commission_');
            $tf = $commission_item->get_foreign_object('tf');
            $TPL['save_label'] = 'Save';
            include_template($template_name);
        }
    }
}

function show_new_commission($template_name)
{
    global $TPL;
    global $projectID;

    // Don't show entry form for new projects
    if (!$projectID) {
        return;
    }

    $TPL['commission_new'] = true;
    $commission_item = new projectCommissionPerson();
    $commission_item->set_values('commission_');
    $TPL['commission_projectID'] = $projectID;
    $TPL['save_label'] = 'Add Commission';
    include_template($template_name);
}

function show_person_list($template)
{
    global $db;
    global $TPL;
    global $projectID;
    global $email_type_array;
    global $rate_type_array;
    global $project_person_role_array;

    if ($projectID) {
        $query = unsafe_prepare('SELECT projectPerson.*, roleSequence
                            FROM projectPerson
                       LEFT JOIN role ON role.roleID = projectPerson.roleID
                           WHERE projectID=%d ORDER BY roleSequence DESC,projectPersonID ASC', $projectID);
        $db->query($query);

        while ($db->next_record()) {
            $projectPerson = new projectPerson();
            $projectPerson->read_db_record($db);
            $projectPerson->set_values('person_');
            $person = $projectPerson->get_foreign_object('person');
            $TPL['person_username'] = $person->get_value('username');
            $TPL['person_emailType_options'] = Page::select_options($email_type_array, $TPL['person_emailType']);
            $TPL['person_role_options'] = Page::select_options($project_person_role_array, $TPL['person_roleID']);
            $TPL['rateType_options'] = Page::select_options($rate_type_array, $TPL['person_rateUnitID']);
            include_template($template);
        }
    }
}

function show_projectPerson_list(): string
{
    global $db;
    global $projectID;
    $html = '';

    if ($projectID) {
        $query = unsafe_prepare('SELECT personID, roleName
                            FROM projectPerson
                       LEFT JOIN role ON role.roleID = projectPerson.roleID
                           WHERE projectID = %d
                        GROUP BY projectPerson.personID
                        ORDER BY roleSequence DESC, personID ASC', $projectID);
        $db->query($query);
        while ($db->next_record()) {
            $projectPerson = new projectPerson();
            $projectPerson->read_db_record($db);
            $person_roleName = $db->f('roleName');
            $person_name = person::get_fullname($projectPerson->get_value('personID'));

            $html .= <<<HTML
                <tr>
                  <td>
                    {$person_name}
                  </td>
                  <td>
                    {$person_roleName}
                  </td>
                </tr>
                HTML;
        }
    }

    return $html;
}

function show_new_person($template)
{
    global $TPL;
    global $email_type_array;
    global $rate_type_array;
    global $projectID;
    global $project_person_role_array;

    // Don't show entry form for new projects
    if (!$projectID) {
        return;
    }

    $project_person = new projectPerson();
    $project_person->set_values('person_');
    $TPL['person_emailType_options'] = Page::select_options($email_type_array, $TPL['person_emailType']);
    $TPL['person_role_options'] = Page::select_options($project_person_role_array, false);
    $TPL['rateType_options'] = Page::select_options($rate_type_array);
    include_template($template);
}

function show_time_sheets(int $projectID, array $timeSheets, array $timeSheetOptions): string
{
    $current_user = &singleton('current_user');

    if ($current_user->is_employee()) {
        $page = new Page();
        $totalTimeSheetRecords = count($timeSheets);
        $timeSheetListHTML = (new timeSheet())->listHTML($timeSheets, $timeSheetOptions);
        $allocTimeSheetURL = $page->getURL('url_alloc_timeSheet');

        return <<<HTML
                <table class="box">
                  <tr>
                    <th class="header">Time Sheets
                      <b> - {$totalTimeSheetRecords} records</b>
                      <span>
                        <a href="{$allocTimeSheetURL}?newTimeSheet_projectID={$projectID}">Time Sheet</a>
                      </span>
                    </th>
                  </tr>
                  <tr>
                    <td>
                      {$timeSheetListHTML}
                    </td>
                  </tr>
                </table>
            HTML;
    }

    return '';
}

function show_project_managers($template_name)
{
    include_template($template_name);
}

function show_transactions($template_name)
{
    $current_user = &singleton('current_user');

    if ($current_user->is_employee()) {
        include_template($template_name);
    }
}

function show_person_options()
{
    global $TPL;
    echo Page::select_options(person::get_username_list($TPL['person_personID']), $TPL['person_personID']);
}

function show_tf_options($commission_tfID)
{
    global $tf_array;
    global $TPL;
    echo Page::select_options($tf_array, $TPL[$commission_tfID]);
}

function show_comments()
{
    global $projectID;
    global $TPL;
    global $project;
    $TPL['commentsR'] = comment::util_get_comments('project', $projectID);
    $TPL['commentsR'] && ($TPL['class_new_comment'] = 'hidden');
    $interestedPartyOptions = $project->get_all_parties();
    $interestedPartyOptions = InterestedParty::get_interested_parties(
        'project',
        $project->get_id(),
        $interestedPartyOptions
    );
    ($TPL['allParties'] = $interestedPartyOptions) || ($TPL['allParties'] = []);
    $TPL['entity'] = 'project';
    $TPL['entityID'] = $project->get_id();
    $TPL['clientID'] = $project->get_value('clientID');

    $commentTemplate = new commentTemplate();
    $ops = $commentTemplate->get_assoc_array(
        'commentTemplateID',
        'commentTemplateName',
        '',
        ['commentTemplateType' => 'project']
    );
    $TPL['commentTemplateOptions'] = '<option value="">Comment Templates</option>' . Page::select_options($ops);

    $ops = [
        ''          => 'Format as...',
        'pdf'       => 'PDF',
        'pdf_plus'  => 'PDF+',
        'html'      => 'HTML',
        'html_plus' => 'HTML+',
    ];

    // FIXME: is this supposed to always attach a task report?
    $TPL['attach_extra_files'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    $TPL['attach_extra_files'] .= 'Attach Task Report ';
    $TPL['attach_extra_files'] .= '<select name="attach_tasks">' . Page::select_options($ops) . '</select><br>';

    $comment = new comment();
    $comment->commentSectionHTML();
}

function show_tasks(project $project): string
{
    $options = [];
    $projectID = $project->get_id();
    $options['showHeader'] = true;
    $options['taskView'] = 'byProject';
    $options['projectIDs'] = [$projectID];
    $options['taskStatus'] = ['open', 'pending'];
    $options['showTaskID'] = true;
    $options['showAssigned'] = true;
    $options['showStatus'] = true;
    $options['showManager'] = true;
    $options['showDates'] = true;
    // $options["showTimes"] = true; // performance hit
    $options['return'] = 'html';

    $task = new Task();
    $page = new Page();
    $taskListRows = $task->get_list($options);
    $totalRecords = is_countable($taskListRows) ? count($taskListRows) : 0;
    $taskListHTML = $task->listHTML($taskListRows, $options);
    $allocTaskURL = $page->getURL('url_alloc_task');

    return <<<HTML
            <table class="box">
              <tr>
                <th class="header">Uncompleted Tasks
                  <b> - {$totalRecords} records</b>
                  <span>
                    <a href="{$allocTaskURL}?projectID={$projectID}">New Task</a>
                  </span>
                </th>
              </tr>
              <tr>
                <td>
                  {$taskListHTML}
                </td>
              </tr>
            </table>
        HTML;
}

if ($projectID) {
    $project->set_id($projectID);
    $project->select();
    $new_project = false;
    if (!$project->have_perm(PERM_UPDATE)) {
        $TPL['message_help'][] = 'Project is read-only for you.';
    }
} else {
    $new_project = true;
}

if (isset($_POST['save'])) {
    $project->read_globals();
    if (!$project->get_id()) {    // brand new project
        $definitely_new_project = true;
    }

    if (!$project->get_value('projectName')) {
        alloc_error('Please enter a name for the Project.');
    }

    // enforced at the database, but show a friendlier error here if possible
    $query = unsafe_prepare("SELECT COUNT(*) as count FROM project WHERE projectShortName = '%s'", $db->esc($project->get_value('projectShortName')));
    if (!$definitely_new_project) {
        $query .= unsafe_prepare(' AND projectID != %d', $project->get_id());
    }

    $db->query($query);
    $db->next_record();
    if ($db->f('count') > 0) {
        alloc_error('A project with that nickname already exists.');
    }

    if (!isset($TPL['message'])) {
        $project->set_value('projectComments', rtrim($project->get_value('projectComments')));
        $project->save();
        $projectID = $project->get_id();
        InterestedParty::make_interested_parties('project', $project->get_id(), $_POST['interestedParty'] ?? []);

        $client = new client();
        $client->set_id($project->get_value('clientID'));
        $client->select();
        if ('Potential' == $client->get_value('clientStatus')) {
            $client->set_value('clientStatus', 'Current');
            $client->save();
        }

        if ($definitely_new_project) {
            $projectPerson = new projectPerson();
            $projectPerson->currency = $project->get_value('currencyTypeID');
            $projectPerson->set_value('projectID', $projectID);
            $projectPerson->set_value_role('isManager');
            $projectPerson->set_value('personID', $current_user->get_id());
            $projectPerson->save();
        }

        alloc_redirect($TPL['url_alloc_project'] . 'projectID=' . $project->get_id());
    }
} elseif (isset($_POST['delete'])) {
    $project->read_globals();
    $project->delete();
    alloc_redirect($TPL['url_alloc_projectList']);
    // If they are creating a new project that is based on an existing one
} elseif (
    isset($_POST['copy_project_save'], $_POST['copy_projectID'], $_POST['copy_project_name'])
) {
    $p = new project();
    $p->set_id($_POST['copy_projectID']);
    if ($p->select()) {
        $p2 = new project();
        $p2->read_row_record($p->row());
        $p2->set_id('');
        $p2->set_value('projectName', $_POST['copy_project_name']);
        $p2->set_value('projectShortName', '');
        $p2->save();
        $TPL['message_good'][] = 'Project details copied successfully.';

        // Copy project people
        $q = unsafe_prepare('SELECT * FROM projectPerson WHERE projectID = %d', $p->get_id());
        $db = new AllocDatabase();
        $db->query($q);
        while ($row = $db->row()) {
            $projectPerson = new projectPerson();
            $projectPerson->currency = $p->get_value('currencyTypeID');
            $projectPerson->read_row_record($row);
            $projectPerson->set_id('');
            $projectPerson->set_value('projectID', $p2->get_id());
            $projectPerson->save();
            $TPL['message_good']['projectPeople'] = 'Project people copied successfully.';
        }

        // Copy commissions
        $q = unsafe_prepare('SELECT * FROM projectCommissionPerson WHERE projectID = %d', $p->get_id());
        $db = new AllocDatabase();
        $db->query($q);
        while ($row = $db->row()) {
            $projectCommissionPerson = new projectCommissionPerson();
            $projectCommissionPerson->read_row_record($row);
            $projectCommissionPerson->set_id('');
            $projectCommissionPerson->set_value('projectID', $p2->get_id());
            $projectCommissionPerson->save();
            $TPL['message_good']['projectCommissions'] = 'Project commissions copied successfully.';
        }

        alloc_redirect($TPL['url_alloc_project'] . 'projectID=' . $p2->get_id());
    }
}

if ($projectID) {
    if (isset($_POST['person_save'])) {
        $q = unsafe_prepare('SELECT * FROM projectPerson WHERE projectID = %d', $project->get_id());
        $db = new AllocDatabase();
        $db->query($q);
        while ($db->next_record()) {
            $pp = new projectPerson();
            $pp->read_db_record($db);
            $delete[] = $pp->get_id();
            // $pp->delete(); // need to delete them after, cause we'll accidently wipe out the current user
        }

        if (isset($_POST['person_personID']) && is_array($_POST['person_personID'])) {
            foreach ($_POST['person_personID'] as $k => $personID) {
                if ($personID) {
                    $pp = new projectPerson();
                    $pp->currency = $project->get_value('currencyTypeID');
                    $pp->set_value('projectID', $project->get_id());
                    $pp->set_value('personID', $personID);
                    $pp->set_value('roleID', $_POST['person_roleID'][$k]);
                    $pp->set_value('rate', $_POST['person_rate'][$k]);
                    $pp->set_value('rateUnitID', $_POST['person_rateUnitID'][$k]);
                    $pp->set_value('projectPersonModifiedUser', $current_user->get_id());
                    $pp->save();
                }
            }
        }

        if (isset($delete) && is_array($delete)) {
            foreach ($delete as $projectPersonID) {
                $pp = new projectPerson();
                $pp->set_id($projectPersonID);
                $pp->delete();
            }
        }
    } elseif (isset($_POST['commission_save']) || isset($_POST['commission_delete'])) {
        $commission_item = new projectCommissionPerson();
        $commission_item->read_globals();
        $commission_item->read_globals('commission_');
        if (isset($_POST['commission_save'])) {
            if (!isset($_POST['commission_tfID'])) {
                alloc_error('No TF selected.');
            } else {
                $commission_item->save();
            }
        } elseif (isset($_POST['commission_delete'])) {
            $commission_item->delete();
        }
    }

    // Displaying a record
    $project->set_id($projectID);
    $project->select() || alloc_error(sprintf('Could not load project %s', $projectID));
} else {
    // Creating a new record
    $project->read_globals();
    $projectID = $project->get_id();
    $project->select();
}

// Comments
$TPL['comment_buttons'] = '<input type="submit" name="comment_save" value="Save Comment">';

// if someone uploads an attachment
if (isset($_POST['save_attachment'])) {
    move_attachment('project', $projectID);
    alloc_redirect($TPL['url_alloc_project'] . 'projectID=' . $projectID . '&sbs_link=attachments');
}

$project->set_values('project_');

$db = new AllocDatabase();

$clientID = $project->get_value('clientID') ?? $_GET['clientID'] ?? '';
$client = new client();
$client->set_id($clientID);
$client->select();
$client->set_tpl_values('client_');

// If a client has been chosen
if ($clientID) {
    $query = unsafe_prepare(
        'SELECT *
           FROM clientContact
          WHERE clientContact.clientID = %d AND clientContact.primaryContact = true',
        $clientID
    );
    $db->query($query);
    $cc = new clientContact();
    $cc->read_db_record($db);

    $one = $client->format_address('postal');
    $two = $client->format_address('street');
    $thr = $cc->format_contact();
    $fou = $project->format_client_old();

    $temp = str_replace('<br>', '', $fou);
    $temp && ($thr = $fou);

    $url = $TPL['url_alloc_client'] . 'clientID=' . $clientID;

    if ($project->get_value('clientContactID')) {
        $cc = new clientContact();
        $cc->set_id($project->get_value('clientContactID'));
        $cc->select();
        $fiv = $cc->format_contact();
        $temp = str_replace('<br>', '', $fiv);
        $temp && ($thr = $fiv);
    }

    $TPL['clientDetails'] = '<table width="100%">';
    $TPL['clientDetails'] .= '<tr>';
    $TPL['clientDetails'] .= '<td colspan="3"><h2 style="margin-bottom:0px; display:inline;"><a href=' . $url . '>' . $TPL['client_clientName'] . '</a></h2></td>    ';
    $TPL['clientDetails'] .= '</tr>';
    $TPL['clientDetails'] .= '<tr>';
    $one && ($TPL['clientDetails'] .= '<td class="nobr"><u>Postal Address</u></td>');
    $two && ($TPL['clientDetails'] .= '<td class="nobr"><u>Street Address</u></td>');
    $thr && ($TPL['clientDetails'] .= '<td><u>Contact</u></td>');
    $TPL['clientDetails'] .= '</tr>';
    $TPL['clientDetails'] .= '<tr>';
    $one && ($TPL['clientDetails'] .= '<td valign="top">' . $one . '</td>');
    $two && ($TPL['clientDetails'] .= '<td valign="top">' . $two . '</td>');
    $thr && ($TPL['clientDetails'] .= '<td valign="top">' . $thr . '</td>');
    $TPL['clientDetails'] .= '</tr>';
    $TPL['clientDetails'] .= '</table>';
}

$db->query(unsafe_prepare("SELECT fullName, emailAddress, clientContactPhone, clientContactMobile, interestedPartyActive
                      FROM interestedParty
                 LEFT JOIN clientContact ON interestedParty.clientContactID = clientContact.clientContactID
                     WHERE entity='project'
                       AND entityID = %d
                       AND interestedPartyActive = 1
                  ORDER BY fullName", $project->get_id()));
while ($db->next_record()) {
    $value = InterestedParty::get_encoded_interested_party_identifier($db->f('fullName'));
    $phone = ['p' => $db->f('clientContactPhone'), 'm' => $db->f('clientContactMobile')];
    $TPL['interestedParties'][] = ['key' => $value, 'name' => $db->f('fullName'), 'email' => $db->f('emailAddress'), 'phone' => $phone];
}

$TPL['interestedPartyOptions'] = $project->get_cc_list_select();

$TPL['clientContactDropdown'] = '<input type="hidden" name="clientContactID" value="' . $project->get_value('clientContactID') . '">';
$TPL['clientHidden'] = '<input type="hidden" id="clientID" name="clientID" value="' . $clientID . '">';
$TPL['clientHidden'] .= '<input type="hidden" id="clientContactID" name="clientContactID" value="' . $project->get_value('clientContactID') . '">';

// Gets $ per hour, even if user uses metric like $200 Daily
function get_projectPerson_hourly_rate($personID, $projectID)
{
    $hourly_rate = null;
    $db = new AllocDatabase();
    $q = unsafe_prepare('SELECT rate,rateUnitID FROM projectPerson WHERE personID = %d AND projectID = %d', $personID, $projectID);
    $db->query($q);
    $db->next_record();

    $rate = $db->f('rate');
    $unitID = $db->f('rateUnitID');
    $t = new timeUnit();
    $timeUnits = $t->get_assoc_array('timeUnitID', 'timeUnitSeconds', $unitID);
    if ('' === $rate || '0' === $rate) {
        return $hourly_rate;
    }

    if (!$timeUnits[$unitID]) {
        return $hourly_rate;
    }

    return $rate / ($timeUnits[$unitID] / 60 / 60);
}

if (is_object($project) && $project->get_id()) {
    // $tasks is a global defined in show_tasks() for performance reasons
    if (isset($TPL['taskListRows']) && is_array($TPL['taskListRows'])) {
        $task = new Task();
        foreach ($TPL['taskListRows'] as $tid => $t) {
            $hourly_rate = get_projectPerson_hourly_rate($t['personID'], $t['projectID']);
            $time_remaining = $t['timeLimit'] - ($task->get_time_billed($t['taskID']) / 60 / 60);

            $cost_remaining = $hourly_rate * $time_remaining;

            if ($cost_remaining > 0) {
                // echo "<br>Tally: ".$TPL["cost_remaining"] += $cost_remaining;
                $TPL['cost_remaining'] += $cost_remaining;
                $TPL['time_remaining'] += $time_remaining;
            }

            $t['timeLimit'] && $count_quoted_tasks++;
        }

        if (isset($TPL['time_remaining'])) {
            $TPL['time_remaining'] = sprintf('%0.1f', $TPL['time_remaining']) . ' Hours.';
        }

        $TPL['count_incomplete_tasks'] = is_countable($TPL['taskListRows']) ? count($TPL['taskListRows']) : 0;
        $not_quoted = (is_countable($TPL['taskListRows']) ? count($TPL['taskListRows']) : 0) - $count_quoted_tasks;
        $not_quoted && ($TPL['count_not_quoted_tasks'] = '(' . sprintf('%d', $not_quoted) . ' tasks not included in estimate)');
    }

    $TPL['invoice_links'] ??= '';
    $TPL['invoice_links'] .= '<a href="' . $TPL['url_alloc_invoice'] . 'clientID=' . $clientID . '&projectID=' . $project->get_id() . '">New Invoice</a>';
}

$TPL['navigation_links'] = $project->get_navigation_links();

$query = unsafe_prepare('SELECT tfID AS value, tfName AS label
                    FROM tf
                   WHERE tfActive = 1
                ORDER BY tfName');
$TPL['commission_tf_options'] = Page::select_options($query, $TPL['commission_tfID'] ?? '');
$TPL['cost_centre_tfID_options'] = Page::select_options($query, $TPL['project_cost_centre_tfID']);

$db->query($query);
while ($db->row()) {
    $tf_array[$db->f('value')] = $db->f('label');
}

if ($TPL['project_cost_centre_tfID']) {
    $tf = new tf();
    $tf->set_id($TPL['project_cost_centre_tfID']);
    $tf->select();
    $TPL['cost_centre_tfID_label'] = $tf->get_link();
}

$query = unsafe_prepare("SELECT roleName,roleID FROM role WHERE roleLevel = 'project' ORDER BY roleSequence");
$db->query($query);
// $project_person_role_array[] = "";
while ($db->next_record()) {
    $project_person_role_array[$db->f('roleID')] = $db->f('roleName');
}

$email_type_array = [
    'None'           => 'None',
    'Assigned Tasks' => 'Assigned Tasks',
    'All Tasks'      => 'All Tasks',
];

$t = new Meta('currencyType');
$currency_array = $t->get_assoc_array('currencyTypeID', 'currencyTypeID');
$projectType_array = project::get_project_type_array();

$m = new Meta('projectStatus');
$projectStatus_array = $m->get_assoc_array('projectStatusID', 'projectStatusID');
$timeUnit = new timeUnit();
$rate_type_array = $timeUnit->get_assoc_array('timeUnitID', 'timeUnitLabelB');
$TPL['project_projectType'] = $projectType_array[$TPL['project_projectType']] ?? '';
$TPL['projectType_options'] = Page::select_options($projectType_array, $TPL['project_projectType']);
$TPL['projectStatus_options'] = Page::select_options($projectStatus_array, $TPL['project_projectStatus']);
$TPL['project_projectPriority'] || ($TPL['project_projectPriority'] = 3);
($projectPriorities = config::get_config_item('projectPriorities')) || ($projectPriorities = []);
$tp = [];
foreach ($projectPriorities as $key => $arr) {
    $tp[$key] = $arr['label'];
}

$TPL['projectPriority_options'] = Page::select_options($tp, $TPL['project_projectPriority']);
$TPL['project_projectPriority'] && ($TPL['priorityLabel'] = ' <div style="display:inline; color:' . $projectPriorities[$TPL['project_projectPriority']]['colour'] . '">[' . $tp[$TPL['project_projectPriority']] . ']</div>');

$TPL['defaultTimeSheetRate'] = $project->get_value('defaultTimeSheetRate');
$TPL['defaultTimeSheetUnit_options'] = Page::select_options($rate_type_array, $project->get_value('defaultTimeSheetRateUnitID'));
$TPL['defaultTimeSheetRateUnits'] = $rate_type_array[$project->get_value('defaultTimeSheetRateUnitID')] ?? '';

$TPL['currencyType_options'] = Page::select_options($currency_array, $TPL['project_currencyTypeID']);

if (
    isset($_GET['projectID'])
    || isset($_POST['projectID'])
    || isset($TPL['project_projectID'])
) {
    define('PROJECT_EXISTS', 1);
}

if ($new_project && !(is_object($project) && $project->get_id())) {
    $TPL['main_alloc_title'] = 'New Project - ' . APPLICATION_NAME;
    $TPL['projectSelfLink'] = 'New Project';
    $p = new project();
    $TPL['message_help_no_esc'][] = 'Create a new Project by inputting the Project Name and any other details, and clicking the Save button.';
    $TPL['message_help_no_esc'][] = '';
    $TPL['message_help_no_esc'][] = '<a href="#x" class="magic" id="copy_project_link">Or copy an existing project</a>';
    $str = <<<HTML
            <div id="copy_project" style="display:none; margin-top:10px;">
              <form action="{$TPL['url_alloc_project']}" method="post">
                <table>
                  <tr>
                    <td colspan="2">
                      <label for="project_status_current">Current Projects</label>
                      <input id="project_status_current" type="radio" name="project_status"  value="Current" checked>
                      &nbsp;&nbsp;&nbsp;
                      <label for="project_status_potential">Potential Projects</label>
                      <input id="project_status_potential" type="radio" name="project_status"  value="Potential">
                      &nbsp;&nbsp;&nbsp;
                      <label for="project_status_archived">Archived Projects</label>
                      <input id="project_status_archived" type="radio" name="project_status"  value="Archived">
                    </td>
                  </tr>
                  <tr>
                    <td>Existing Project</td><td><div id="projectDropdown"><select name="copy_projectID"></select></div></td>
                  </tr>
                  <tr>
                    <td>New Project Name</td><td><input type="text" size="50" name="copy_project_name"></td>
                  </tr>
                  <tr>
                    <td colspan="2" align="center"><input type="submit" name="copy_project_save" value="Copy Project"></td>
                  </tr>
                </table>
              <input type="hidden" name="sessID" value="{$TPL['sessID']}">
              </form>
            </div>
        HTML;
    $TPL['message_help_no_esc'][] = $str;
} else {
    $TPL['main_alloc_title'] = 'Project ' . $project->get_id() . ': ' . $project->get_name() . ' - ' . APPLICATION_NAME;
    $TPL['projectSelfLink'] = '<a href="' . $project->get_url() . '">';
    $TPL['projectSelfLink'] .= sprintf('%d %s', $project->get_id(), $project->get_name(['return' => 'html']));
    $TPL['projectSelfLink'] .= '</a>';
}

$TPL['taxName'] = config::get_config_item('taxName');

// Need to html-ise projectName and description
$TPL['project_projectName_html'] = Page::to_html($project->get_value('projectName'));
$TPL['project_projectComments_html'] = Page::to_html($project->get_value('projectComments'));

$db = new AllocDatabase();

$q = unsafe_prepare("SELECT SUM((amount * pow(10,-currencyType.numberToBasic)))
                  AS amount, transaction.currencyTypeID as currency
                FROM transaction
           LEFT JOIN timeSheet on timeSheet.timeSheetID = transaction.timeSheetID
           LEFT JOIN currencyType on currencyType.currencyTypeID = timeSheet.currencyTypeID
               WHERE timeSheet.projectID = %d
                 AND transaction.status = 'pending'
            GROUP BY transaction.currencyTypeID
             ", $project->get_id());
$db->query($q);
$rows = [];
while ($row = $db->row()) {
    $rows[] = $row;
}

$TPL['total_timeSheet_transactions_pending'] = Page::money_print($rows);

$q = unsafe_prepare('SELECT SUM(customerBilledDollars * timeSheetItemDuration * multiplier * pow(10,-currencyType.numberToBasic))
                  AS amount, timeSheet.currencyTypeID as currency
                FROM timeSheetItem
           LEFT JOIN timeSheet ON timeSheetItem.timeSheetID = timeSheet.timeSheetID
           LEFT JOIN currencyType on currencyType.currencyTypeID = timeSheet.currencyTypeID
               WHERE timeSheet.projectID = %d
            GROUP BY timeSheetItemID
             ', $project->get_id());
$db->query($q);
$rows = [];
while ($row = $db->row()) {
    $rows[] = $row;
}

$TPL['total_timeSheet_customerBilledDollars'] = Page::money_print($rows);

$q = unsafe_prepare("SELECT SUM((amount * pow(10,-currencyType.numberToBasic)))
                  AS amount, transaction.currencyTypeID as currency
                FROM transaction
           LEFT JOIN timeSheet on timeSheet.timeSheetID = transaction.timeSheetID
           LEFT JOIN currencyType on currencyType.currencyTypeID = timeSheet.currencyTypeID
               WHERE timeSheet.projectID = %d
                 AND transaction.status = 'approved'
            GROUP BY transaction.currencyTypeID
             ", $project->get_id());
$db->query($q);
$rows = [];
while ($row = $db->row()) {
    $rows[] = $row;
}

$TPL['total_timeSheet_transactions_approved'] = Page::money_print($rows);

$q = unsafe_prepare("SELECT SUM((amount * pow(10,-currencyType.numberToBasic)))
                  AS amount, transaction.currencyTypeID as currency
                FROM transaction
           LEFT JOIN invoiceItem on invoiceItem.invoiceItemID = transaction.invoiceItemID
           LEFT JOIN invoice on invoice.invoiceID = invoiceItem.invoiceID
           LEFT JOIN currencyType on currencyType.currencyTypeID = invoice.currencyTypeID
               WHERE invoice.projectID = %d
                 AND transaction.status = 'pending'
            GROUP BY transaction.currencyTypeID
             ", $project->get_id());
$db->query($q);
$rows = [];
while ($row = $db->row()) {
    $rows[] = $row;
}

$TPL['total_invoice_transactions_pending'] = Page::money_print($rows);

$q = unsafe_prepare("SELECT SUM((amount * pow(10,-currencyType.numberToBasic)))
                  AS amount, transaction.currencyTypeID as currency
                FROM transaction
           LEFT JOIN invoiceItem on invoiceItem.invoiceItemID = transaction.invoiceItemID
           LEFT JOIN invoice on invoice.invoiceID = invoiceItem.invoiceID
           LEFT JOIN currencyType on currencyType.currencyTypeID = invoice.currencyTypeID
               WHERE invoice.projectID = %d
                 AND transaction.status = 'approved'
            GROUP BY transaction.currencyTypeID
             ", $project->get_id());
$db->query($q);
$rows = [];
while ($row = $db->row()) {
    $rows[] = $row;
}

$TPL['total_invoice_transactions_approved'] = Page::money_print($rows);

$q = unsafe_prepare("SELECT SUM((amount * pow(10,-currencyType.numberToBasic)))
                  AS amount, transaction.currencyTypeID as currency
                FROM transaction
           LEFT JOIN currencyType on currencyType.currencyTypeID = transaction.currencyTypeID
               WHERE transaction.projectID = %d
                 AND transaction.status = 'approved'
            GROUP BY transaction.currencyTypeID
             ", $project->get_id());
$db->query($q);
$rows = [];
while ($row = $db->row()) {
    $rows[] = $row;
}

$TPL['total_expenses_transactions_approved'] = Page::money_print($rows);

if ($project->get_id()) {
    $defaults['projectID'] = $project->get_id();
    $defaults['showFinances'] = true;
    if (!$project->have_perm(PERM_READ_WRITE)) {
        $defaults['personID'] = $current_user->get_id();
    }

    $rtn = timeSheet::get_list($defaults);
    $TPL['timeSheets'] = $rtn['rows'];
    $TPL['timeSheetOptions'] = $rtn['extra'];
}

// TODO: remove global variables
if (is_array($TPL)) {
    extract($TPL, EXTR_OVERWRITE);
}

echo $page->header();
echo $page->toolbar();

if ($project->have_perm(PERM_READ_WRITE)) {
    ?>
<script type="text/javascript" language="javascript">
    $(document).ready(function() {
        <?php if (!$project_projectID) { ?>
        toggle_view_edit();
        $('#projectName').focus();
        // fake a click to the client status radio button
        clickClientStatus();
        <?php } else { ?>
        $('#editProject').focus();
        <?php }
        ?>

        // This listens to the client radio buttons and refreshes the client dropdown
        $('input[name=client_status]').bind("click", clickClientStatus);

        // This listens to the client dropdown and refreshes the client contact
        $(document).on("change", 'select[name=clientID]', function(e) {
            url =
                '<?php echo $url_alloc_updateProjectClientContactList; ?>clientID=' +
                this.value;
            makeAjaxRequest(url, 'clientContactDropdown');
        });

        // This listens to the Copy Project radio buttons
        $('input[name=project_status]').bind("click", function(e) {
            url =
                '<?php echo $url_alloc_updateCopyProjectList; ?>projectStatus=' +
                this.value;
            makeAjaxRequest(url, 'projectDropdown')
        });

        // This opens up the copy_project div and loads the dropdown list
        $('#copy_project_link').bind("click", function(e) {
            $('#copy_project').slideToggle();
            url =
                '<?php echo $url_alloc_updateCopyProjectList; ?>projectStatus=Current';
            makeAjaxRequest(url, 'projectDropdown')
        });

    });

    function updatePersonRate(dropdown) {
        var personID = dropdown.value;
        var tr = $(dropdown).parent().parent();
        var ratebox = tr.find('input[name=person_rate\\[\\]]');
        var rateunit = tr.find('select[name=person_rateUnitID\\[\\]]');

        // ratebox.data['value'] is the auto-set value - only change it if the user
        // hasn't touched it.
        if (!ratebox[0].value || !ratebox.data('value') || ratebox[0].value == ratebox.data('value')) {
            $.getJSON(
                '<?php echo $url_alloc_updateProjectPersonRate; ?>project=<?php echo $project_projectID; ?>&person=' +
                personID,
                function(data) {
                    ratebox[0].value = data['rate'];
                    rateunit[0].selectedIndex = data['unit'];
                    ratebox.data('value', data['rate']);
                });
        }
    }

    function clickClientStatus(e) {

        if (!$('input[name=client_status]:checked').val()) {
            $('#client_status_current').attr("checked", "checked");
            this.value = 'current';
        }

        clientID = $('#clientID').val()
        url = '<?php echo $url_alloc_updateProjectClientList; ?>clientStatus=' +
            this.value + '&clientID=' + clientID;
        makeAjaxRequest(url, 'clientDropdown')

        // If there's a clientID update the Client Contact dropdown as well
        if (clientID) {
            clientContactID = $('#clientContactID').val()
            url =
                '<?php echo $url_alloc_updateProjectClientContactList; ?>clientID=' +
                clientID + '&clientContactID=' + clientContactID;
            makeAjaxRequest(url, 'clientContactDropdown')
        }
    }
</script>

    <?php if (defined('PROJECT_EXISTS')) { ?>
        <?php $first_div = 'hidden'; ?>
        <?php echo Page::side_by_side_links(
            $url_alloc_project . 'projectID=' . $project_projectID,
            [
                'project'      => 'Main',
                'people'       => 'People',
                'commissions'  => 'Commissions',
                'comments'     => 'Comments',
                'attachments'  => 'Attachments',
                'tasks'        => 'Tasks',
                'reminders'    => 'Reminders',
                'time'         => 'Time Sheets',
                'transactions' => 'Transactions',
                'invoices'     => 'Invoices',
                'sales'        => 'Sales',
                'history'      => 'History',
                'sbsAll'       => 'All',
            ],
            null,
            $projectSelfLink
        ); ?>
    <?php }
    ?>

<div id="project"
    class="<?php $first_div ?? ''; ?>">
    <form action="<?php echo $url_alloc_project; ?>" method="post"
        id="projectForm">
        <input type="hidden" name="projectID"
            value="<?php echo $project_projectID; ?>">
        <table class="box">
            <tr>
                <th class="header" colspan="5">Project Details
                    <span>
                        <?php if (defined('PROJECT_EXISTS')) { ?>
                            <?php echo $navigation_links; ?>
                            <?php echo Page::star('project', $project_projectID); ?>
                        <?php }
                        ?>
                    </span>
                </th>
            </tr>
            <tr>
                <td colspan="5" valign="top">
                    <div style="min-width:400px; width:47%; float:left; padding:0px 12px; vertical-align:top;">

                        <div class="view">
                            <h6><?php echo $project_projectType; ?><?php echo Page::mandatory($project_projectName); ?>
                            </h6>
                            <h2 style="margin-bottom:0px; display:inline;">
                                <?php echo $project_projectID; ?>
                                <?php echo Page::htmlentities($project_projectName); ?>
                            </h2>&nbsp;<?php echo $priorityLabel; ?>
                        </div>

                        <div class="edit">
                            <h6><?php echo $project_projectType ?: 'Project'; ?><?php echo Page::mandatory($project_projectName); ?>
                            </h6>
                            <input type="text" name="projectName" id="projectName"
                                value="<?php echo $project_projectName_html; ?>"
                                size="45">
                            <select
                                name="projectPriority"><?php echo $projectPriority_options; ?></select>
                            <select
                                name="projectType"><?php echo $projectType_options; ?></select>
                        </div>

                        <?php if ($project_projectComments_html) { ?>
                        <div class="view">
                            <h6>Description</h6>
                            <?php echo $project_projectComments_html; ?>
                        </div>
                        <?php }
                        ?>
                        <div class="edit">
                            <h6>Description</h6>
                            <?php echo Page::textarea('projectComments', $project_projectComments, ['height' => 'medium', 'width' => '100%']); ?>
                        </div>

                        <?php if (isset($clientDetails)) { ?>
                        <div class="view">
                            <h6>Client</h6>
                            <?php echo $clientDetails; ?>
                        </div>
                        <?php }
                        ?>
                        <div class="edit">
                            <h6>Client</h6>
                            <?php echo $clientHidden; ?>
                            <label for="client_status_current">Current Clients</label>
                            <input id="client_status_current" type="radio" name="client_status" value="Current">
                            &nbsp;&nbsp;&nbsp;
                            <label for="client_status_potential">Potential Clients</label>
                            <input id="client_status_potential" type="radio" name="client_status" value="Potential">
                            &nbsp;&nbsp;&nbsp;
                            <label for="client_status_archived">Archived Clients</label>
                            <input id="client_status_archived" type="radio" name="client_status" value="Archived">
                            <div id="clientDropdown">
                                <?php $clientDropdown ?? ''; ?>
                            </div>
                            <div id="clientContactDropdown" style="margin-top:10px;">
                                <?php $clientContactDropdown ?? ''; ?>
                            </div>
                        </div>

                    </div>

                    <div style="min-width:400px; width:47%; float:left; margin:0px 12px; vertical-align:top;">

                        <div class="view">
                            <h6>Project Nickname<div><span
                                        style='width:50%; display:inline-block;'>Currency</span><span>Status</span>
                                </div>
                            </h6>
                            <div style="float:left; width:40%;">
                                <?php echo Page::htmlentities($project_projectShortName); ?>
                            </div>
                            <div style="float:right; width:50%;">
                                <span
                                    style='width:50%; display:inline-block;'><?php echo Page::money($project_currencyTypeID, 0, '%n'); ?></span>
                                <span><?php echo $project_projectStatus; ?></span>
                            </div>
                        </div>

                        <div class="edit">
                            <h6>Project Nickname<div><span
                                        style='width:50%; display:inline-block;'>Currency</span><span>Status</span>
                                </div>
                            </h6>
                            <div style="float:left; width:40%;">
                                <input type="text" name="projectShortName"
                                    value="<?php echo $project_projectShortName; ?>"
                                    size="10">
                            </div>
                            <div style="float:right; width:50%;">
                                <span style='width:50%; display:inline-block;'><select
                                        name="currencyTypeID"><?php echo $currencyType_options; ?></select></span>
                                <span><select
                                        name="projectStatus"><?php echo $projectStatus_options; ?></select></span>
                            </div>
                        </div>

                        <?php if ((isset($project_projectBudget) && (bool) strlen($project_projectBudget)) || isset($cost_centre_tfID_label)) { ?>
                        <div class="view">
                            <h6>Budget<div>Cost Centre TF</div>
                            </h6>
                            <div style="float:left; width:40%;">
                                <?php echo Page::money($project_currencyTypeID, $project_projectBudget, '%s%mo %c'); ?>
                                <?php if ($taxName && (isset($project_projectBudget) && (bool) strlen($project_projectBudget))) {
                                    echo sprintf(' (inc. %s)', $taxName);
                                }
                            ?>
                            </div>
                            <div style="float:right; width:50%;">
                                <?php echo $cost_centre_tfID_label; ?>
                            </div>
                        </div>
                        <?php }
                        ?>

                        <div class="edit">
                            <h6>Budget<div>Cost Centre TF</div>
                            </h6>
                            <div style="float:left; width:40%;">
                                <input type="text" name="projectBudget"
                                    value="<?php echo Page::money($project_currencyTypeID, $project_projectBudget, '%mo'); ?>"
                                    size="10">
                                <?php $taxName && (print sprintf(' (inc. %s)', $taxName)); ?>
                            </div>
                            <div style="float:right; width:50%;" class="nobr">
                                <select name="cost_centre_tfID" style="width:95%">
                                    <option value="">&nbsp;</option>
                                    <?php echo $cost_centre_tfID_options; ?>
                                </select>
                                <?php echo Page::help('project_cost_centre_tf'); ?>
                            </div>
                        </div>

                        <?php $tax_string2 = sprintf(' (per unit%s)', $taxName ? ', inc. ' . $taxName : ''); ?>
                        <?php if ((isset($project_customerBilledDollars) && (bool) strlen($project_customerBilledDollars)) || (bool) strlen($project_defaultTaskLimit)) { ?>
                        <div class="view">
                            <h6>Client Billed At<div>Default Task Limit</div>
                            </h6>
                            <div style="float:left; width:40%;">
                                <?php echo Page::money($project_currencyTypeID, $project_customerBilledDollars, '%s%mo %c'); ?>
                                <?php if (isset($project_customerBilledDollars) && (bool) strlen($project_customerBilledDollars)) {
                                    echo $tax_string2;
                                }
                            ?>
                            </div>
                            <div style="float:right; width:50%;">
                                <span><?php echo $project_defaultTaskLimit; ?></span>
                            </div>
                        </div>
                        <?php }
                        ?>

                        <div class="edit">
                            <h6>Client Billed At<div>Default Task Limit</div>
                            </h6>
                            <div style="float:left; width:40%;">
                                <input type="text" name="customerBilledDollars"
                                    value="<?php echo Page::money($project_currencyTypeID, $project_customerBilledDollars, '%mo'); ?>"
                                    size="10">
                                <?php echo $tax_string2; ?>
                            </div>
                            <div style="float:right; width:50%;">
                                <span><input type="text" size="5" name="defaultTaskLimit"
                                        value="<?php echo $project_defaultTaskLimit; ?>">
                                    <?php echo Page::help('project_defaultTaskLimit'); ?></span>
                            </div>
                        </div>

                        <div class="edit">
                            <h6>Default Interested Parties</h6>
                            <div id="interestedPartyDropdown" style="display:inline">
                                <?php echo $interestedPartyOptions; ?>
                            </div>
                            <?php echo Page::help('project_interested_parties'); ?>
                        </div>
                        <?php if (isset($interestedParties)) { ?>
                        <div class="view">
                            <h6>Default Interested Parties</h6>
                            <table class="nopad" style="width:100%;">
                                <?php foreach ($interestedParties as $interestedParty) { ?>
                                <tr class="hover">
                                    <td style="width:50%;">
                                        <a class='undecorated'
                                            href='mailto:<?php echo Page::htmlentities($interestedParty['name']); ?> <<?php echo Page::htmlentities($interestedParty['email']); ?>>'><?php echo Page::htmlentities($interestedParty['name']); ?></a>
                                    </td>
                                    <td style="width:50%;">
                                        <?php if ($interestedParty['phone']['p']) {
                                            ?>Ph:
                                            <?php echo Page::htmlentities($interestedParty['phone']['p']); ?><?php
                                        }
                                    ?>
                                        <?php if ($interestedParty['phone']['p'] && $interestedParty['phone']['m']) { ?>
                                        /
                                        <?php }
                                        ?>
                                        <?php if ($interestedParty['phone']['m']) {
                                            ?>Mob:
                                            <?php echo Page::htmlentities($interestedParty['phone']['m']); ?><?php
                                        }
                                    ?>
                                    </td>
                                </tr>
                                <?php }
                                ?>
                            </table>
                        </div>
                        <?php }
                        ?>

                        <?php if ($project_defaultTimeSheetRate || $defaultTimeSheetRateUnits) { ?>
                        <div class="view">
                            <h6>Default timesheet rate<div>Default timesheet unit</div>
                            </h6>
                            <div style="float:left; width:40%;">
                                <?php echo Page::money($project_currencyTypeID, $project_defaultTimeSheetRate); ?>
                            </div>
                            <div style="float:right; width:50%;">
                                <?php echo $defaultTimeSheetRateUnits; ?>
                            </div>
                        </div>
                        <?php }
                        ?>

                        <div class="edit">
                            <h6>Default timesheet rate<div>Default timesheet unit</div>
                            </h6>
                            <div style="float:left; width:40%;">
                                <input type="text" name="defaultTimeSheetRate"
                                    value="<?php echo Page::money($project_currencyTypeID, $project_defaultTimeSheetRate, '%mo'); ?>"
                                    size="10">
                            </div>
                            <div style="float:right; width:50%;">
                                <select name="defaultTimeSheetRateUnitID">
                                    <option value="">
                                        <?php echo $defaultTimeSheetUnit_options; ?>
                                </select>
                            </div>
                        </div>

                        <?php if ($project_dateTargetStart || $project_dateTargetCompletion) { ?>
                        <div class="view">
                            <h6>Estimated Start<div>Estimated Completion</div>
                            </h6>
                            <div style="float:left; width:40%;">
                                <?php echo $project_dateTargetStart; ?>
                            </div>
                            <div style="float:right; width:50%;">
                                <?php echo $project_dateTargetCompletion; ?>
                            </div>
                        </div>
                        <?php }
                        ?>

                        <div class="edit">
                            <h6>Estimated Start<div>Estimated Completion</div>
                            </h6>
                            <div style="float:left; width:40%;">
                                <?php echo Page::calendar('dateTargetStart', $project_dateTargetStart); ?>
                            </div>
                            <div style="float:right; width:50%;">
                                <?php echo Page::calendar('dateTargetCompletion', $project_dateTargetCompletion); ?>
                            </div>
                        </div>

                        <?php if ($project_dateActualStart || $project_dateActualCompletion) { ?>
                        <div class="view">
                            <h6>Actual Start<div>Actual Completion</div>
                            </h6>
                            <div style="float:left; width:40%;">
                                <?php echo $project_dateActualStart; ?>
                            </div>
                            <div style="float:right; width:50%;">
                                <?php echo $project_dateActualCompletion; ?>
                            </div>
                        </div>
                        <?php }
                        ?>

                        <div class="edit">
                            <h6>Actual Start<div>Actual Completion</div>
                            </h6>
                            <div style="float:left; width:40%;">
                                <?php echo Page::calendar('dateActualStart', $project_dateActualStart); ?>
                            </div>
                            <div style="float:right; width:50%;">
                                <?php echo Page::calendar('dateActualCompletion', $project_dateActualCompletion); ?>
                            </div>
                        </div>

                    </div>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="5">
                    <div class="view" style="margin-top:20px">
                        <button type="button" id="editProject" value="1"
                            onClick="toggle_view_edit();clickClientStatus();">Edit Project<i
                                class="icon-edit"></i></button>
                    </div>
                    <div class="edit" style="margin-top:20px">
                        <button type="submit" name="delete" value="1" class="delete_button">Delete<i
                                class="icon-trash"></i></button>
                        <button type="submit" name="save" value="1" class="save_button default">Save<i
                                class="icon-ok-sign"></i></button>
                        <br><br>
                        <a href="" onClick="return toggle_view_edit(true);">Cancel edit</a>
                    </div>
                </td>
            </tr>
        </table>
        <input type="hidden" name="sessID"
            value="<?php echo $sessID; ?>">
    </form>

    <?php if (defined('PROJECT_EXISTS')) { ?>
    <table class="box">
        <tr>
            <th class="nobr" width="10%">Financial Summary</th>
            <th class="right" colspan="3">
                <?php echo Page::help('project_financial_summary'); ?>
            </th>
        </tr>
        <tr>
            <td class="right nobr">Outstanding Invoices</td>
            <td class="right">
                <?php echo $total_invoice_transactions_pending; ?>
            </td>
            <td class="right nobr">Pending time sheets</td>
            <td class="right">
                <?php echo $total_timeSheet_transactions_pending; ?>
            </td>
        </tr>
        <tr>
            <td class="right">Paid Invoices</td>
            <td class="right">
                <?php echo $total_invoice_transactions_approved; ?>
            </td>
            <td class="right">Paid time sheets</td>
            <td class="right">
                <?php echo $total_timeSheet_transactions_approved; ?>
            </td>
        </tr>
        <tr>
            <td class="right nobr">Task Time Estimate</td>
            <td class="right">
                <?php $time_remaining ?? ''; ?>
                <?php echo Page::money($project_currencyTypeID, $cost_remaining ?? ''); ?>
                <?php $count_not_quoted_tasks ?? ''; ?>
            </td>
            <td class="right">Sum Customer Billed for Time Sheets</td>
            <td class="right">
                <?php echo $total_timeSheet_customerBilledDollars; ?>
            </td>
        </tr>
        <tr>
            <td class="right nobr">Expenses</td>
            <td class="right">
                <?php echo $total_expenses_transactions_approved; ?>
            </td>
            <td colspan="2"></td>
        </tr>
    </table>
    <?php }
    ?>

</div>

    <?php if (defined('PROJECT_EXISTS')) { ?>
<div id="people">
    <form action="<?php echo $url_alloc_project; ?>" method="post">


        <table class="box">
            <tr>
                <th class="header" align="left">Project People
                    <span>
                        <a href="#x" class="magic"
                            onClick="$('#project_people_footer').before('<tr>'+$('#new_projectPerson').html()+'</tr>');">New
                            Project Person</a>
                    </span>
                </th>
            </tr>
            <tr>
                <td>
                    <table class="list">
                        <tr>
                            <th>Person</th>
                            <th>Role</th>
                            <th>Rate</th>
                            <th colspan="2">Unit</th>
                        </tr>
                        <?php show_person_list('templates/projectPersonListR.tpl'); ?>
                        <?php show_new_person('templates/projectPersonListR.tpl'); ?>
                        <tr id="project_people_footer">
                            <td colspan="5" class="center">
                                <button type="submit" name="person_save" value="1" class="save_button">Save Project
                                    People<i class="icon-ok-sign"></i></button>
                                <input type="hidden" name="projectID"
                                    value="<?php echo $project_projectID; ?>">
                                <input type="hidden" name="sbs_link" value="people">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>


        <input type="hidden" name="sessID"
            value="<?php echo $sessID; ?>">
    </form>
</div>

<div id="comments">
        <?php show_comments(); ?>
</div>


<div id="commissions">
    <table class="box">
        <tr>
            <th class="header">
                Time Sheet Commissions
                <span>
                    <?php echo Page::help('timesheet_commission'); ?>
                </span>
            </th>
        </tr>
        <tr>
            <td>
                <table class="list">
                    <tr>
                        <th>Tagged Fund</th>
                        <th colspan="3">Percentage</th>
                    </tr>
                    <?php show_commission_list('templates/commissionListR.tpl'); ?>
                    <?php show_new_commission('templates/commissionListR.tpl'); ?>
                </table>
            </td>
        </tr>
    </table>
</div>


<div id="attachments">
        <?php show_attachments(); ?>
</div>

<div id="tasks">
        <?php echo show_tasks($project); ?>
</div>

<div id="reminders">
    <table class="box">
        <tr>
            <th class="header">Reminders
                <span>
                    <a
                        href="<?php echo $url_alloc_reminder; ?>step=3&parentType=project&parentID=<?php echo $project_projectID; ?>&returnToParent=project">New
                        Reminder</a>
                </span>
            </th>
        </tr>
        <tr>
            <td>
                <?php reminder::get_list_html('project', $project_projectID); ?>
            </td>
        </tr>
    </table>
</div>

<div id="time">
        <?php echo show_time_sheets($projectID, $timeSheets, $timeSheetOptions); ?>
</div>

<div id="transactions">
        <?php show_transactions('templates/projectTransactionS.tpl'); ?>
</div>

<div id="invoices">
    <table class="box">
        <tr>
            <th class="header">Invoices
                <span>
                    <?php echo $invoice_links; ?>
                </span>
            </th>
        </tr>
        <tr>
            <td>
                <?php show_invoices(); ?>
            </td>
        </tr>
    </table>
</div>

<div id="sales">
    <table class="box">
        <tr>
            <th class="header">Product Sales
                <span>
                    <a
                        href="<?php echo $url_alloc_productSale; ?>projectID=<?php echo $project_projectID; ?>">New
                        Sale</a>
                </span>
            </th>
        </tr>
        <tr>
            <td>
                <?php $productSaleRows = productSale::get_list(['projectID' => $project_projectID]); ?>
                <?php echo productSale::get_list_html($productSaleRows); ?>
            </td>
        </tr>
    </table>
</div>

<div id="history">
        <?php echo show_projectHistory($project); ?>
</div>
    <?php }
    ?>

    <?php
} else {
    $projectPersonList = show_projectPerson_list();
    echo <<<HTML
            <table class="box">
              <tr>
                <th><nobr>Project: {$projectSelfLink} </nobr></th>
                <th class="right">{$navigation_links}</th>
              </tr>
              <tr>
                <td>Name</td>
                <td>{$project_projectName}</td>
              </tr>
              <tr>
                <td>Priority</td>
                <td>{$priorityLabel}</td>
              </tr>
              <tr>
                <td>Client</td>
                <td>{$client_clientName}</td>
              </tr>
              <tr>
                <td>Comments</td>
                <td>{$project_projectComments}</td>
              </tr>
            </table>
            <table class="box">
              <tr>
                <th colspan="2">Project People</th>
              </tr>
              <tr>
                <td colspan="2">
                  {$projectPersonList}
                </td>
              </tr>
            </table>
        HTML;

    echo show_time_sheets($projectID, $timeSheets, $timeSheetOptions);

    show_tasks($project);
    show_comments();
}

echo $page->footer();
?>