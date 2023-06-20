<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Document\Field;

define("PERM_PROJECT_READ_TASK_DETAIL", 256);

class Task extends DatabaseEntity
{
    public $all_row_fields;

    public $classname = "Task";

    public $data_table = "task";

    public $display_field_name = "taskName";

    public $key_field = "taskID";

    public $data_fields = [
        "taskName",
        "taskDescription",
        "creatorID",
        "closerID",
        "priority",
        "timeLimit",
        "timeBest",
        "timeWorst",
        "timeExpected",
        "dateCreated",
        "dateAssigned",
        "dateClosed",
        "dateTargetStart",
        "dateTargetCompletion",
        "dateActualStart",
        "dateActualCompletion",
        "taskStatus",
        "taskModifiedUser",
        "projectID",
        "parentTaskID",
        "taskTypeID",
        "personID",
        "managerID",
        "estimatorID",
        "duplicateTaskID",
    ];

    public $permissions = [PERM_PROJECT_READ_TASK_DETAIL => "read details"];

    private bool $skip_perms_check = false;

    public function save()
    {
        $current_user = &singleton("current_user");
        global $TPL;

        $errors = $this->validate();
        if ($errors) {
            alloc_error($errors);
        } else {
            $existing = $this->all_row_fields;
            if ($existing["taskStatus"] != $this->get_value("taskStatus")) {
                $allocDatabase = new AllocDatabase();
                $allocDatabase->query(["call change_task_status(%d,'%s')", $this->get_id(), $this->get_value("taskStatus")]);
                $row = $allocDatabase->qr(["SELECT taskStatus
                                      ,dateActualCompletion
                                      ,dateActualStart
                                      ,dateClosed
                                      ,closerID
                                  FROM task
                                 WHERE taskID = %d", $this->get_id()]);
                // Changing a task's status changes these fields.
                // Unfortunately the call to save() below erroneously nukes these fields.
                // So we manually set them to whatever change_task_status() has dictated.
                $this->set_value("taskStatus", $row["taskStatus"]);
                $this->set_value("dateActualCompletion", $row["dateActualCompletion"]);
                $this->set_value("dateActualStart", $row["dateActualStart"]);
                $this->set_value("dateClosed", $row["dateClosed"]);
                $this->set_value("closerID", $row["closerID"]);
            }

            return parent::save();
        }
    }

    public function delete()
    {
        if ($this->can_be_deleted()) {
            return parent::delete();
        }
    }

    public function validate($_ = null)
    {
        $err = [];
        // Validate/coerce the fields
        $coerce = [
            "inprogress" => "open_inprogress",
            "notstarted" => "open_notstarted",
            "info"       => "pending_info",
            "client"     => "pending_client",
            "manager"    => "pending_manager",
            "tasks"      => "pending_tasks",
            "invalid"    => "closed_invalid",
            "duplicate"  => "closed_duplicate",
            "incomplete" => "closed_incomplete",
            "complete"   => "closed_complete",
            "archived"   => "closed_archived",
            "open"       => "open_inprogress",
            "pending"    => "pending_info",
            "close"      => "closed_complete",
            "closed"     => "closed_complete",
        ];

        if ($this->get_value("taskStatus") && !in_array($this->get_value("taskStatus"), $coerce)) {
            $orig = $this->get_value("taskStatus");
            $cleaned = str_replace("-", "_", strtolower($orig));
            if (in_array($cleaned, $coerce)) {
                $this->set_value("taskStatus", $cleaned);
            } elseif ($coerce[$cleaned] !== '' && $coerce[$cleaned] !== '0') {
                $this->set_value("taskStatus", $coerce[$cleaned]);
            }

            if (!in_array($this->get_value("taskStatus"), $coerce)) {
                $err[] = "Unrecognised task status: " . $orig;
            }
        }

        if (!in_array($this->get_value("priority"), [1, 2, 3, 4, 5])) {
            $err[] = "Invalid priority.";
        }

        if (!in_array(ucwords($this->get_value("taskTypeID")), [
            "Task",
            "Fault",
            "Message",
            "Milestone",
            "Parent",
        ])) {
            $err[] = "Invalid Task Type.";
        }

        $this->get_value("taskName") || ($err[] = "Please enter a name for the Task.");
        $this->get_value("taskDescription") && $this->set_value("taskDescription", rtrim($this->get_value("taskDescription")));
        return parent::validate($err);
    }

    public function add_pending_tasks($str)
    {
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query(["SELECT * FROM pendingTask WHERE taskID = %d", $this->get_id()]);

        $rows = [];
        while ($row = $allocDatabase->row()) {
            $rows[] = $row["pendingTaskID"];
        }

        asort($rows);

        $bits = preg_split("/\b/", $str);
        $bits || ($bits = []);
        asort($bits);

        $str1 = implode(",", (array)$rows);
        $str2 = implode(",", (array)$bits);

        if ($str1 !== $str2) {
            $allocDatabase->query(["DELETE FROM pendingTask WHERE taskID = %d", $this->get_id()]);
            foreach ((array)$bits as $id) {
                if (is_numeric($id)) {
                    $allocDatabase->query(["INSERT INTO pendingTask (taskID,pendingTaskID) VALUES (%d,%d)", $this->get_id(), $id]);
                }
            }
        }
    }

    public function add_tags($tags = [])
    {
        if ((is_countable($tags) ? count($tags) : 0) == 1) {
            $tags = explode(",", current($tags));
        }

        $allocDatabase = new AllocDatabase();
        $allocDatabase->query(["DELETE FROM tag WHERE taskID = %d", $this->get_id()]);
        foreach ((array)$tags as $tag) {
            if (trim($tag) === '') {
                continue;
            }

            if (trim($tag) === '0') {
                continue;
            }

            $allocDatabase->query(["INSERT INTO tag (taskID,name) VALUES (%d,'%s')", $this->get_id(), trim($tag)]);
        }
    }

    public function get_tags($all = false)
    {
        $allocDatabase = new AllocDatabase();
        if ($all) {
            $q = unsafe_prepare("SELECT DISTINCT name FROM tag ORDER BY name");
        } else {
            $q = unsafe_prepare("SELECT name FROM tag WHERE taskID = %d ORDER BY name", $this->get_id());
        }

        $allocDatabase->query($q);
        $arr = [];
        while ($row = $allocDatabase->row()) {
            $row["name"] && ($arr[$row["name"]] = $row["name"]);
        }

        return (array)$arr;
    }

    public function get_pending_tasks($invert = false)
    {
        $rows = [];
        $allocDatabase = new AllocDatabase();
        $q = unsafe_prepare("SELECT * FROM pendingTask WHERE %s = %d", ($invert ? "pendingTaskID" : "taskID"), $this->get_id());
        $allocDatabase->query($q);
        while ($row = $allocDatabase->row()) {
            $rows[] = $row[($invert ? "taskID" : "pendingTaskID")];
        }

        return (array)$rows;
    }

    public function get_reopen_reminders()
    {
        $rows = [];
        $q = unsafe_prepare("SELECT reminder.*,token.*,tokenAction.*, reminder.reminderID as rID
                        FROM reminder
                   LEFT JOIN token ON reminder.reminderHash = token.tokenHash
                   LEFT JOIN tokenAction ON token.tokenActionID = tokenAction.tokenActionID
                       WHERE token.tokenEntity = 'task'
                         AND token.tokenEntityID = %d
                         AND reminder.reminderActive = 1
                         AND token.tokenActionID = 4
                    GROUP BY reminder.reminderID
                     ", $this->get_id());

        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);
        while ($row = $allocDatabase->row()) {
            $rows[] = $row;
        }

        return (array)$rows;
    }

    public function add_reopen_reminder($date)
    {
        $check_date = null;
        $maxUsed = null;
        $rows = $this->get_reopen_reminders();
        // If this reopen event already exists, do nothing
        // Allows the form to be saved without changing anything
        // the 8:30 is the same as creation, below.
        if ($date) {
            $check_date = strtotime($date . " 08:30:00");
            foreach ($rows as $r) {
                if (strtotime($r['reminderTime']) == $check_date) {
                    return;
                }
            }
        }

        foreach ($rows as $row) {
            $reminder = new reminder();
            $reminder->set_id($row['rID']);
            $reminder->select();
            $reminder->deactivate();
        }

        // alloc-cli can pass 'null' to kill future reopening
        // Removing the field in the web UI does the same
        if ($check_date && $date != 'null') {
            $tokenActionID = 4;
            // $maxUsed = 1; nope, so people can have recurring reminders
            $name = "Task reopened: " . $this->get_name(["prefixTaskID" => true]);
            $desc = "This reminder will have automatically reopened this task, if it was pending:\n\n" . $this->get_name(["prefixTaskID" => true]);
            $recipients = [[
                "field" => "metaPersonID",
                "who"   => -2,
            ], [
                "field" => "metaPersonID",
                "who"   => -3,
            ]];
            if (strlen($date) <= "10") {
                $date .= " 08:30:00";
            }

            $this->add_notification($tokenActionID, $maxUsed, $name, $desc, $recipients, $date);
        }
    }

    public function create_task_reminder()
    {
        $people = [];
        // Create a reminder for this task based on the priority.
        $current_user = &singleton("current_user");

        // Get the task type
        $taskTypeName = $this->get_value("taskTypeID");
        $label = $this->get_priority_label();
        $reminderInterval = "Day";
        $intervalValue = $this->get_value("priority");
        if ($taskTypeName == "Parent") {
            $taskTypeName .= " Task";
        }

        $subject = $taskTypeName . " Reminder: " . $this->get_id() . " " . $this->get_name() . " [" . $label . "]";
        $message = "\n\n" . $subject;
        $message .= "\n\n" . $this->get_url(true);
        $this->get_value("taskDescription") && ($message .= "\n\n" . $this->get_value("taskDescription"));
        $message .= "\n\n-- \nReminder created by " . $current_user->get_name() . " at " . date("Y-m-d H:i:s");
        $people[] = $this->get_value("personID");

        $label = $this->get_priority_label();

        $reminder = new reminder();
        $reminder->set_value('reminderType', "task");
        $reminder->set_value('reminderLinkID', $this->get_id());
        $reminder->set_value('reminderRecuringInterval', $reminderInterval);
        $reminder->set_value('reminderRecuringValue', $intervalValue);
        $reminder->set_value('reminderSubject', $subject);
        $reminder->set_value('reminderContent', $message);
        $reminder->set_value('reminderAdvNoticeSent', "0");
        if ($this->get_value("dateTargetStart") && $this->get_value("dateTargetStart") != date("Y-m-d")) {
            $date = $this->get_value("dateTargetStart") . " 09:00:00";
            $reminder->set_value('reminderAdvNoticeInterval', "Hour");
            $reminder->set_value('reminderAdvNoticeValue', "24");
        } else {
            $date = date("Y-m-d") . " 09:00:00";
            $reminder->set_value('reminderAdvNoticeInterval', "No");
            $reminder->set_value('reminderAdvNoticeValue', "0");
        }

        $reminder->set_value('reminderTime', $date);
        $reminder->save();
        // the negative is due to ugly reminder internals
        $reminder->update_recipients([-REMINDER_METAPERSON_TASK_ASSIGNEE]);
    }

    public function is_owner($person = "")
    {

        $p = null;
        if (!is_object($person)) {
            return false;
        }

        // A user owns a task if they 'own' the project
        if ($this->get_id()) {
            // Check for existing task
            has("project") && ($p = $this->get_foreign_object("project"));
        } elseif (has("project") && $_POST["projectID"]) {
            // Or maybe they are creating a new task
            $p = new project();
            $p->set_id($_POST["projectID"]);
        }

        // if this task doesn't exist (no ID)
        // OR the person has isManager or canEditTasks for this tasks project
        // OR if this person is the Creator of this task.
        // OR if this person is the For Person of this task.
        // OR if this person has super 'manage' perms
        // OR if we're skipping the perms checking because i.e. we're having our task status updated by a timesheet
        if (
            !$this->get_id()
            || (
                is_object($p) && ($p->has_project_permission($person, [
                    "isManager",
                    "canEditTasks",
                    "timeSheetRecipient",
                ]))
                || $this->get_value("creatorID") == $person->get_id()
                || $this->get_value("personID") == $person->get_id()
                || $this->get_value("managerID") == $person->get_id()
                || $person->have_role("manage")
                || $this->skip_perms_check
            )
        ) {
            return true;
        }
    }

    public function has_attachment_permission($person)
    {
        return $this->is_owner($person);
    }

    public function has_attachment_permission_delete($person)
    {
        return $this->is_owner($person);
    }

    public function update_children($field, $value = "")
    {
        $q = unsafe_prepare("SELECT * FROM task WHERE parentTaskID = %d", $this->get_id());
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);
        while ($allocDatabase->row()) {
            $t = new Task();
            $t->read_db_record($allocDatabase);
            $t->set_value($field, $value);
            $t->save();
            if ($t->get_value("taskTypeID") == "Parent") {
                $t->update_children($field, $value);
            }
        }
    }

    public function get_parent_task_select($projectID = ""): string
    {
        $options = null;
        global $TPL;

        if (is_object($this)) {
            $projectID = $this->get_value("projectID");
            $parentTaskID = $this->get_value("parentTaskID");
        }

        $projectID || ($projectID = $_GET["projectID"]);
        $parentTaskID || ($parentTaskID = $_GET["parentTaskID"]);

        $allocDatabase = new AllocDatabase();
        if ($projectID) {
            [$ts_open, $ts_pending, $ts_closed] = Task::get_task_status_in_set_sql();
            // Status may be closed_<something>
            $query = unsafe_prepare("SELECT taskID AS value, taskName AS label
                                FROM task
                               WHERE projectID= '%d'
                                 AND taskTypeID = 'Parent'
                                 AND (taskStatus NOT IN (" . $ts_closed . ") OR taskID = %d)
                            ORDER BY taskName", $projectID, $parentTaskID);
            $options = Page::select_options($query, $parentTaskID, 70);
        }

        return '<select name="parentTaskID"><option value="">' . $options . "</select>";
    }

    public function get_task_cc_list_select($projectID = ""): string
    {

        $interestedPartyOptions = [];
        $options = [];
        $selected = [];
        // If task exists, grab existing IPs
        if (is_object($this)) {
            $interestedPartyOptions = $this->get_all_parties($projectID);

            // Else get default IPs from project and config
        } else {
            if ($_GET["projectID"]) {
                $projectID = $_GET["projectID"];
            }

            if ($projectID) {
                $project = new project($projectID);
                $interestedPartyOptions = $project->get_all_parties();
            }

            $extra_interested_parties = config::get_config_item("defaultInterestedParties");
            foreach ((array)$extra_interested_parties as $name => $email) {
                $interestedPartyOptions[$email]["name"] = $name;
            }

            $interestedPartyOptions = InterestedParty::get_interested_parties("task", null, $interestedPartyOptions);
        }

        foreach ((array)$interestedPartyOptions as $email => $info) {
            if ($info["role"] == "interested" && $info["selected"]) {
                $selected[] = $info["identifier"];
            }

            if ($email) {
                $options[$info["identifier"]] = trim(Page::htmlentities(trim($info["name"]) . " <" . $email . ">"));
            }
        }

        return '<select name="interestedParty[]" multiple="true">' . Page::select_options($options, $selected, 100, false) . "</select>";
    }

    public function get_all_parties($projectID = "")
    {
        $allocDatabase = new AllocDatabase();
        $interestedPartyOptions = [];
        if ($_GET["projectID"]) {
            $projectID = $_GET["projectID"];
        } elseif (!$projectID) {
            $projectID = $this->get_value("projectID");
        }

        if ($projectID) {
            $project = new project($projectID);
            $interestedPartyOptions = $project->get_all_parties(false, $this->get_id());
        }

        ($extra_interested_parties = config::get_config_item("defaultInterestedParties")) || ($extra_interested_parties = []);
        foreach ($extra_interested_parties as $name => $email) {
            $interestedPartyOptions[$email]["name"] = $name;
        }

        if ($this->get_value("creatorID")) {
            $p = new person();
            $p->set_id($this->get_value("creatorID"));
            $p->select();
            if ($p->get_value("emailAddress")) {
                $interestedPartyOptions[$p->get_value("emailAddress")]["name"] = $p->get_name();
                $interestedPartyOptions[$p->get_value("emailAddress")]["role"] = "creator";
                $interestedPartyOptions[$p->get_value("emailAddress")]["personID"] = $this->get_value("creatorID");
            }
        }

        if ($this->get_value("personID")) {
            $p = new person();
            $p->set_id($this->get_value("personID"));
            $p->select();
            if ($p->get_value("emailAddress")) {
                $interestedPartyOptions[$p->get_value("emailAddress")]["name"] = $p->get_name();
                $interestedPartyOptions[$p->get_value("emailAddress")]["role"] = "assignee";
                $interestedPartyOptions[$p->get_value("emailAddress")]["personID"] = $this->get_value("personID");
                $interestedPartyOptions[$p->get_value("emailAddress")]["selected"] = 1;
            }
        }

        if ($this->get_value("managerID")) {
            $p = new person();
            $p->set_id($this->get_value("managerID"));
            $p->select();
            if ($p->get_value("emailAddress")) {
                $interestedPartyOptions[$p->get_value("emailAddress")]["name"] = $p->get_name();
                $interestedPartyOptions[$p->get_value("emailAddress")]["role"] = "manager";
                $interestedPartyOptions[$p->get_value("emailAddress")]["personID"] = $this->get_value("managerID");
                $interestedPartyOptions[$p->get_value("emailAddress")]["selected"] = 1;
            }
        }

        // return an aggregation of the current task/proj/client parties + the existing interested parties
        $interestedPartyOptions = InterestedParty::get_interested_parties("task", $this->get_id(), $interestedPartyOptions);
        return $interestedPartyOptions;
    }

    public function get_personList_dropdown($projectID, $field, $selected = null)
    {
        $manager_sql = null;
        $managers_only = null;
        $ops = [];
        $current_user_is_manager = null;
        $current_user = &singleton("current_user");

        $allocDatabase = new AllocDatabase();

        $origval = $this->get_id() ? $this->get_value($field) : $current_user->get_id();

        $peoplenames = person::get_username_list($origval);

        if ($projectID) {
            if ($field == "managerID") {
                $manager_sql = " AND role.roleHandle in ('isManager','timeSheetRecipient')";
                $managers_only = true;
            }

            $q = unsafe_prepare("SELECT *
                            FROM projectPerson
                       LEFT JOIN person ON person.personID = projectPerson.personID
                       LEFT JOIN role ON role.roleID = projectPerson.roleID
                           WHERE person.personActive = 1 " . $manager_sql . "
                             AND projectID = %d
                        ORDER BY firstName, username
                         ", $projectID);
            $allocDatabase->query($q);
            while ($row = $allocDatabase->row()) {
                if ($managers_only && $current_user->get_id() == $row["personID"]) {
                    $current_user_is_manager = true;
                }

                $ops[$row["personID"]] = $peoplenames[$row["personID"]];
            }

            // Everyone
        } else {
            $ops = $peoplenames;
        }

        $origval && ($ops[$origval] = $peoplenames[$origval]);

        if ($managers_only && !$current_user_is_manager) {
            unset($ops[$current_user->get_id()]);
        }

        if ($selected === null) {
            $selected = $origval;
        }

        return Page::select_options($ops, $selected);
    }

    public function get_project_options($projectID = "")
    {
        $projectID || ($projectID = $_GET["projectID"]);
        // Project Options - Select all projects
        $allocDatabase = new AllocDatabase();
        $query = unsafe_prepare("SELECT projectID AS value, projectName AS label
                            FROM project
                           WHERE projectStatus IN ('Current', 'Potential') OR projectID = %d
                        ORDER BY projectName", $projectID);
        return Page::select_options($query, $projectID, 60);
    }

    public function set_option_tpl_values()
    {
        $p = null;
        // Set template values to provide options for edit selects
        global $TPL;
        $current_user = &singleton("current_user");
        global $isMessage;
        $allocDatabase = new AllocDatabase();
        ($projectID = $_GET["projectID"]) || ($projectID = $this->get_value("projectID"));
        $TPL["personOptions"] = '<select name="personID"><option value="">' . $this->get_personList_dropdown($projectID, "personID") . "</select>";
        $TPL["managerPersonOptions"] = '<select name="managerID"><option value="">' . $this->get_personList_dropdown($projectID, "managerID") . "</select>";
        $TPL["estimatorPersonOptions"] = '<select name="estimatorID"><option value="">' . $this->get_personList_dropdown($projectID, "estimatorID") . "</select>";

        // TaskType Options
        $meta = new Meta("taskType");
        $taskType_array = $meta->get_assoc_array("taskTypeID", "taskTypeID");
        $TPL["taskTypeOptions"] = Page::select_options($taskType_array, $this->get_value("taskTypeID"));

        // Project dropdown
        $TPL["projectOptions"] = (new Task())->get_project_options($projectID);

        // We're building these two with the <select> tags because they will be
        // replaced by an AJAX created dropdown when the projectID changes.
        $TPL["parentTaskOptions"] = $this->get_parent_task_select();
        $TPL["interestedPartyOptions"] = $this->get_task_cc_list_select();

        $allocDatabase->query(unsafe_prepare("SELECT fullName, emailAddress, clientContactPhone, clientContactMobile
                              FROM interestedParty
                         LEFT JOIN clientContact ON interestedParty.clientContactID = clientContact.clientContactID
                             WHERE entity='task'
                               AND entityID = %d
                               AND interestedPartyActive = 1
                          ORDER BY fullName", $this->get_id()));
        while ($allocDatabase->next_record()) {
            $value = InterestedParty::get_encoded_interested_party_identifier($allocDatabase->f("fullName"));
            $phone = [
                "p" => $allocDatabase->f('clientContactPhone'),
                "m" => $allocDatabase->f('clientContactMobile'),
            ];
            $TPL["interestedParties"][] = [
                'key'   => $value,
                'name'  => $allocDatabase->f("fullName"),
                'email' => $allocDatabase->f("emailAddress"),
                'phone' => $phone,
            ];
        }

        $TPL["task_taskStatusLabel"] = $this->get_task_status("label");
        $TPL["task_taskStatusColour"] = $this->get_task_status("colour");
        $TPL["task_taskStatusValue"] = $this->get_value("taskStatus");
        $TPL["task_taskStatusOptions"] = Page::select_options(Task::get_task_statii_array(true), $this->get_value("taskStatus"));

        // Project label
        if (has("project")) {
            $p = new project();
            $p->set_id($this->get_value("projectID"));
            $p->select();
            $TPL["projectName"] = $p->get_display_value();
        }

        ($taskPriorities = config::get_config_item("taskPriorities")) || ($taskPriorities = []);
        ($projectPriorities = config::get_config_item("projectPriorities")) || ($projectPriorities = []);
        ($priority = $this->get_value("priority")) || ($priority = 3);
        $TPL["priorityOptions"] = Page::select_options(array_kv($taskPriorities, null, "label"), $priority);
        $TPL["priorityLabel"] = ' <div style="display:inline; color:' . $taskPriorities[$priority]["colour"] . '">[';

        if (is_object($p)) {
            [$priorityFactor, $daysUntilDue] = $this->get_overall_priority($this->get_value("dateTargetCompletion"), $p->get_value("projectPriority"), $this->get_value("priority"));
            $str = "Task priority: " . $taskPriorities[$this->get_value("priority")]["label"] . "<br>";
            $str .= "Project priority: " . $projectPriorities[$p->get_value("projectPriority")]["label"] . "<br>";
            $str .= "Days until due: " . $daysUntilDue . "<br>";
            $str .= "Calculated priority: " . $priorityFactor;
            $TPL["priorityLabel"] .= Page::help($str, $this->get_priority_label());
        } else {
            $TPL["priorityLabel"] .= $this->get_priority_label();
        }

        $TPL["priorityLabel"] .= "]</div>";

        // If we're viewing the printer friendly view
        if ($_GET["media"] == "print") {
            // Parent Task label
            $task = new Task();
            $task->set_id($this->get_value("parentTaskID"));
            $task->select();
            $TPL["parentTask"] = $task->get_display_value();

            // Task Type label
            $TPL["taskType"] = $this->get_value("taskTypeID");

            // Priority
            $TPL["priority"] = $this->get_value("priority");

            // Assignee label
            $p = new person();
            $p->set_id($this->get_value("personID"));
            $p->select();
            $TPL["person"] = $p->get_display_value();
        }
    }

    public function get_task_comments_array()
    {
        $rows = comment::util_get_comments_array("task", $this->get_id());
        $rows || ($rows = []);
        return $rows;
    }

    public function get_task_link($_FORM = []): string
    {
        $_FORM["return"] || ($_FORM["return"] = "html");
        $rtn = '<a href="' . $this->get_url() . '">';
        $rtn .= $this->get_name($_FORM);
        return $rtn . "</a>";
    }

    public function get_task_image(): string
    {
        global $TPL;
        return '<img class="taskType" alt="' . $this->get_value("taskTypeID") . '" title="' . $this->get_value("taskTypeID") . '" src="' . $TPL["url_alloc_images"] . "taskType_" . strtolower($this->get_value("taskTypeID")) . '.gif">';
    }

    public function get_name($_FORM = [])
    {

        $id = null;
        if (isset($_FORM["prefixTaskID"])) {
            $id = $this->get_id() . " ";
        }

        if ($this->get_value("taskTypeID") == "Parent" && $_FORM["return"] == "html") {
            $rtn = "<strong>" . $id . $this->get_value("taskName", DST_HTML_DISPLAY) . "</strong>";
        } elseif ($_FORM["return"] == "html") {
            $rtn = $id . $this->get_value("taskName", DST_HTML_DISPLAY);
        } else {
            $rtn = $id . $this->get_value("taskName");
        }

        return $rtn;
    }

    public function get_url($absolute = false, $id = false)
    {
        global $sess;
        $sess || ($sess = new Session());
        $id || ($id = $this->get_id());
        $url = "task/task.php?taskID=" . $id;

        if ($sess->Started() && !$absolute) {
            $url = $sess->url(SCRIPT_PATH . $url);

            // This for urls that are emailed
        } else {
            static $prefix;
            $prefix || ($prefix = config::get_config_item("allocURL"));
            $url = $prefix . $url;
        }

        return $url;
    }

    public static function get_task_statii_array($flat = false)
    {
        $taskStatiiArray = [];
        // This gets an array that is useful for building the two types of dropdown lists that taskStatus uses
        $taskStatii = Task::get_task_statii();
        if ($flat) {
            $meta = new Meta("taskStatus");
            $taskStatii = $meta->get_assoc_array();
            foreach ($taskStatii as $status => $arr) {
                $taskStatiiArray[$status] = Task::get_task_status_thing("label", $status);
            }
        } else {
            foreach ($taskStatii as $status => $sub) {
                $taskStatiiArray[$status] = ucwords($status);
                foreach ($sub as $subStatus => $arr) {
                    $taskStatiiArray[$status . "_" . $subStatus] = "&nbsp;&nbsp;&nbsp;&nbsp;" . $arr["label"];
                }
            }
        }

        return $taskStatiiArray;
    }

    public static function get_task_statii()
    {
        $rtn = [];
        // looks like:
        // $arr["open"]["notstarted"] = array("label"=>"Not Started","colour"=>"#ffffff");
        // $arr["open"]["inprogress"] = array("label"=>"In Progress","colour"=>"#ffffff");
        // etc
        static $rows;
        if (!$rows) {
            $meta = new Meta("taskStatus");
            $rows = $meta->get_assoc_array();
        }

        foreach ($rows as $taskStatusID => $arr) {
            [$s, $ss] = explode("_", $taskStatusID);
            $rtn[$s][$ss] = [
                "label"  => $arr["taskStatusLabel"],
                "colour" => $arr["taskStatusColour"],
            ];
        }

        return $rtn;
    }

    public function get_task_status($thing = "")
    {
        return Task::get_task_status_thing($thing, $this->get_value("taskStatus"));
    }

    public static function get_task_status_thing($thing = "", $status = "")
    {
        [$taskStatus, $taskSubStatus] = explode("_", $status);
        $arr = Task::get_task_statii();
        if (!$thing) {
            return;
        }

        if (!$arr[$taskStatus][$taskSubStatus][$thing]) {
            return;
        }

        return $arr[$taskStatus][$taskSubStatus][$thing];
    }

    public static function get_task_status_in_set_sql()
    {
        $commar1 = null;
        $commar2 = null;
        $commar3 = null;
        $sql_clos = null;
        $sql_open = null;
        $sql_pend = null;
        $meta = new Meta("taskStatus");
        $arr = $meta->get_assoc_array();
        foreach ($arr as $taskStatusID => $r) {
            $id = strtolower(substr($taskStatusID, 0, 4));
            if ($id == "open") {
                $sql_open .= $commar1 . '"' . $taskStatusID . '"';
                $commar1 = ",";
            } elseif ($id == "clos") {
                $sql_clos .= $commar2 . '"' . $taskStatusID . '"';
                $commar2 = ",";
            } elseif ($id == "pend") {
                $sql_pend .= $commar3 . '"' . $taskStatusID . '"';
                $commar3 = ",";
            }
        }

        return [$sql_open, $sql_pend, $sql_clos];
    }

    public static function get_taskStatus_sql($status)
    {
        $lengths = [];
        if (!is_array($status)) {
            $status = [$status];
        }

        foreach ((array)$status as $s) {
            $lengths[] = strlen($s);
        }

        return sprintf_implode("SUBSTRING(task.taskStatus,1,%d) = '%s'", $lengths, $status);
    }

    public static function get_list_filter($filter = [])
    {
        $_FORM = [];
        $having = "";
        $projectIDs = null;
        $sql = [];
        $tags = [];

        // If they want starred, load up the taskID filter element
        if (isset($filter["starred"])) {
            $current_user = &singleton("current_user");
            $starredTasks = isset($current_user->prefs["stars"]) ? ($current_user->prefs["stars"]["task"] ?? "") : "";
            if (!empty($starredTasks) && is_array($starredTasks)) {
                foreach (array_keys($starredTasks) as $k) {
                    $filter["taskID"][] = $k;
                }
            }

            if (!is_array($filter["taskID"] ?? "")) {
                $filter["taskID"][] = -1;
            }
        }

        // Filter on taskID
        if (isset($filter["taskID"])) {
            $sql[] = sprintf_implode("task.taskID = %d", $filter["taskID"]);
        }

        // No point continuing if primary key specified, so return
        if (isset($filter["taskID"])) {
            return [$sql, ""];
        }

        // This takes care of projectID singular and plural
        has("project") && ($projectIDs = project::get_projectID_sql($filter));
        if (isset($projectIDs)) {
            $sql["projectIDs"] = $projectIDs;
        }

        // project name or project nick name or project id
        if (isset($filter["projectNameMatches"])) {
            $sql[] = sprintf_implode(
                "project.projectName LIKE '%%%s%%'
          OR project.projectShortName LIKE '%%%s%%'
          OR project.projectID = %d",
                $filter["projectNameMatches"],
                $filter["projectNameMatches"],
                $filter["projectNameMatches"]
            );
        }

        [$ts_open, $ts_pending, $ts_closed] = Task::get_task_status_in_set_sql();

        // New Tasks
        if (isset($_FORM["taskDate"])) {
            if ($filter["taskDate"] == "new") {
                $past = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 2, date("Y"))) . " 00:00:00";
                if (date("D") == "Mon") {
                    $past = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 4, date("Y"))) . " 00:00:00";
                }

                $sql[] = unsafe_prepare("(task.taskStatus NOT IN (" . $ts_closed . ") AND task.dateCreated >= '" . $past . "')");
                // Due Today
            } elseif ($filter["taskDate"] == "due_today") {
                $sql[] = "(task.taskStatus NOT IN (" . $ts_closed . ") AND task.dateTargetCompletion = '" . date("Y-m-d") . "')";
                // Overdue
            } elseif ($filter["taskDate"] == "overdue") {
                $sql[] = "(task.taskStatus NOT IN (" . $ts_closed . ")
                AND
                (task.dateTargetCompletion IS NOT NULL AND task.dateTargetCompletion != '' AND '" . date("Y-m-d") . "' > task.dateTargetCompletion))";
                // Date Created
            } elseif ($filter["taskDate"] == "d_created") {
                $filter["dateOne"] && ($sql[] = unsafe_prepare("(task.dateCreated >= '%s')", $filter["dateOne"]));
                $filter["dateTwo"] && ($sql[] = unsafe_prepare("(task.dateCreated <= '%s 23:59:59')", $filter["dateTwo"]));
                // Date Assigned
            } elseif ($filter["taskDate"] == "d_assigned") {
                $filter["dateOne"] && ($sql[] = unsafe_prepare("(task.dateAssigned >= '%s')", $filter["dateOne"]));
                $filter["dateTwo"] && ($sql[] = unsafe_prepare("(task.dateAssigned <= '%s 23:59:59')", $filter["dateTwo"]));
                // Date Target Start
            } elseif ($filter["taskDate"] == "d_targetStart") {
                $filter["dateOne"] && ($sql[] = unsafe_prepare("(task.dateTargetStart >= '%s')", $filter["dateOne"]));
                $filter["dateTwo"] && ($sql[] = unsafe_prepare("(task.dateTargetStart <= '%s')", $filter["dateTwo"]));
                // Date Target Completion
            } elseif ($filter["taskDate"] == "d_targetCompletion") {
                $filter["dateOne"] && ($sql[] = unsafe_prepare("(task.dateTargetCompletion >= '%s')", $filter["dateOne"]));
                $filter["dateTwo"] && ($sql[] = unsafe_prepare("(task.dateTargetCompletion <= '%s')", $filter["dateTwo"]));
                // Date Actual Start
            } elseif ($filter["taskDate"] == "d_actualStart") {
                $filter["dateOne"] && ($sql[] = unsafe_prepare("(task.dateActualStart >= '%s')", $filter["dateOne"]));
                $filter["dateTwo"] && ($sql[] = unsafe_prepare("(task.dateActualStart <= '%s')", $filter["dateTwo"]));
                // Date Actual Completion
            } elseif ($filter["taskDate"] == "d_actualCompletion") {
                $filter["dateOne"] && ($sql[] = unsafe_prepare("(task.dateActualCompletion >= '%s')", $filter["dateOne"]));
                $filter["dateTwo"] && ($sql[] = unsafe_prepare("(task.dateActualCompletion <= '%s')", $filter["dateTwo"]));
            }
        }

        // Task status filtering
        if (isset($filter["taskStatus"])) {
            $sql[] = Task::get_taskStatus_sql($filter["taskStatus"]);
        }

        if (isset($filter["taskTypeID"])) {
            $sql[] = sprintf_implode("task.taskTypeID = '%s'", $filter["taskTypeID"]);
        }

        // Filter on %taskName%
        if (isset($filter["taskName"])) {
            $sql[] = sprintf_implode("task.taskName LIKE '%%%s%%'", $filter["taskName"]);
        }

        // If personID filter
        if (isset($filter["personID"])) {
            $sql["personID"] = sprintf_implode("IFNULL(task.personID,0) = %d", $filter["personID"]);
        }

        if (isset($filter["creatorID"])) {
            $sql["creatorID"] = sprintf_implode("IFNULL(task.creatorID,0) = %d", $filter["creatorID"]);
        }

        if (isset($filter["managerID"])) {
            $sql["managerID"] = sprintf_implode("IFNULL(task.managerID,0) = %d", $filter["managerID"]);
        }

        // If tags filter
        if (isset($filter["tags"]) && is_array($filter["tags"])) {
            foreach ((array)$filter["tags"] as $k => $tag) {
                $tag && ($tags[] = $tag);
            }

            $tags && ($sql[] = sprintf_implode("seltag.name = '%s'", $tags));
            $having = unsafe_prepare("HAVING count(DISTINCT seltag.name) = %d", count($tags));
        }

        if (isset($filter["taskTimeSheetStatus"])) {
            // These filters are for the time sheet dropdown list
            if ($filter["taskTimeSheetStatus"] == "open") {
                unset($sql["personID"]);
                $sql[] = unsafe_prepare("(task.taskStatus NOT IN (" . $ts_closed . "))");
            } elseif ($filter["taskTimeSheetStatus"] == "mine") {
                $current_user = &singleton("current_user");
                unset($sql["personID"]);
                $sql[] = unsafe_prepare("((task.taskStatus NOT IN (" . $ts_closed . ")) AND task.personID = %d)", $current_user->get_id());
            } elseif ($filter["taskTimeSheetStatus"] == "not_assigned") {
                unset($sql["personID"]);
                $sql[] = unsafe_prepare("((task.taskStatus NOT IN (" . $ts_closed . ")) AND task.personID != %d)", $filter["personID"]);
            } elseif ($filter["taskTimeSheetStatus"] == "recent_closed") {
                unset($sql["personID"]);
                $sql[] = unsafe_prepare("(task.dateActualCompletion >= DATE_SUB(CURDATE(),INTERVAL 14 DAY))");
            } elseif ($filter["taskTimeSheetStatus"] == "all") {
            }
        }

        if (isset($filter["parentTaskID"])) {
            $sql["parentTaskID"] = sprintf_implode("IFNULL(task.parentTaskID,0) = %d", $filter["parentTaskID"]);
        }

        return [$sql, $having];
    }

    public static function get_recursive_child_tasks($taskID_of_parent, $rows = [], $padding = 0)
    {
        $rtn = [];
        $rows || ($rows = []);
        foreach ($rows as $taskID => $v) {
            $parentTaskID = $v["parentTaskID"];
            $row = $v["row"];

            if ($taskID_of_parent == $parentTaskID) {
                $row["padding"] = $padding;
                $rtn[$taskID]["row"] = $row;
                unset($rows[$taskID]);
                ++$padding;
                $children = Task::get_recursive_child_tasks($taskID, $rows, $padding);
                --$padding;

                if ((is_countable($children) ? count($children) : 0) !== 0) {
                    $rtn[$taskID]["children"] = $children;
                }
            }
        }

        return $rtn;
    }

    public static function build_recursive_task_list($t = [], $_FORM = [])
    {
        $done = [];
        $tasks = [];
        foreach ($t as $r) {
            $row = $r["row"];
            $done[$row["taskID"]] = true; // To track orphans
            $tasks += [$row["taskID"] => $row];

            if ($r["children"]) {
                [$t, $d] = Task::build_recursive_task_list($r["children"], $_FORM);
                $t && ($tasks += $t);
                $d && ($done += $d);
            }
        }

        return [$tasks, $done];
    }

    public function get_overall_priority(
        int $dateTargetCompletion,
        int $projectPriority = 0,
        int $taskPriority = 0
    ): array {
        $daysUntilDue = null;
        $spread = sprintf("%d", config::get_config_item("taskPrioritySpread"));
        $scale = sprintf("%d", config::get_config_item("taskPriorityScale"));
        $scale_halved = sprintf("%d", config::get_config_item("taskPriorityScale") / 2);

        if ($dateTargetCompletion !== 0) {
            $daysUntilDue = (format_date("U", $dateTargetCompletion) - time()) / 60 / 60 / 24;
            $mult = atan($daysUntilDue / $spread) / 3.14 * $scale + $scale_halved;
        } else {
            $mult = 8;
        }

        $daysUntilDue && ($daysUntilDue = sprintf("%d", ceil($daysUntilDue)));
        return [sprintf("%0.2f", ($taskPriority * $projectPriority ** 2) * $mult / 10), $daysUntilDue];
    }

    public static function get_list($_FORM)
    {
        $limit = null;
        $f = null;
        $rows = [];
        $tasks = null;
        $current_user = &singleton("current_user");

        // This is the definitive method of getting a list of tasks that need a sophisticated level of filtering

        [$filter, $having] = Task::get_list_filter($_FORM);

        $_FORM["taskView"] ??= 'prioritised';

        // Zero is a valid limit
        if (isset($_FORM["limit"]) && (bool)strlen($_FORM["limit"])) {
            $limit = unsafe_prepare("limit %d", $_FORM["limit"]);
        }

        $_FORM["return"] ??= "html";

        $_FORM["people_cache"] = &get_cached_table("person");
        $_FORM["timeUnit_cache"] = &get_cached_table("timeUnit");
        $_FORM["taskType_cache"] = &get_cached_table("taskType");

        if ($_FORM["taskView"] == "prioritised") {
            unset($filter["parentTaskID"]);
            $order_limit = " " . $limit;
        } else {
            $order_limit = " ORDER BY projectName,taskName " . $limit;
        }

        // Get a hierarchical list of tasks
        if (is_array($filter) && count($filter)) {
            $f = " WHERE " . implode(" AND ", $filter);
        }

        $uid = sprintf("%d", $current_user->get_id());
        $spread = sprintf("%d", config::get_config_item("taskPrioritySpread"));
        $scale = sprintf("%d", config::get_config_item("taskPriorityScale"));
        $scale_halved = sprintf("%d", config::get_config_item("taskPriorityScale") / 2);

        $q = "SELECT task.*
                    ,projectName
                    ,projectShortName
                    ,clientID
                    ,projectPriority
                    ,project.currencyTypeID as currency
                    ,rate
                    ,rateUnitID
                    ,GROUP_CONCAT(pendingTask.pendingTaskID) as pendingTaskIDs
                    ,GROUP_CONCAT(DISTINCT alltag.name SEPARATOR ', ') as tags
                FROM task
           LEFT JOIN project ON project.projectID = task.projectID
           LEFT JOIN projectPerson ON project.projectID = projectPerson.projectID AND projectPerson.personID = '" . $uid . "'
           LEFT JOIN pendingTask ON pendingTask.taskID = task.taskID
           LEFT JOIN tag alltag ON alltag.taskID = task.taskID
           LEFT JOIN tag seltag ON seltag.taskID = task.taskID
                     " . $f . "
            GROUP BY task.taskID
                     " . $having . "
                     " . $order_limit;

        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);
        while ($row = $allocDatabase->next_record()) {
            $task = new Task();
            $task->read_db_record($allocDatabase);
            $row["taskURL"] = $task->get_url();
            $row["taskName"] = $task->get_name($_FORM);
            $row["taskLink"] = $task->get_task_link($_FORM);
            $row["project_name"] = $row["projectShortName"] ?? $row["projectName"] ?? "";
            $row["projectPriority"] = $allocDatabase->f("projectPriority") ?? "";
            has("project") && ($row["projectPriorityLabel"] = project::get_priority_label($allocDatabase->f("projectPriority")));
            has("project") && ([$row["priorityFactor"], $row["daysUntilDue"]] = $task->get_overall_priority((int)$row["dateTargetCompletion"], (int)$row["projectPriority"], (int)$row["priority"]));
            $row["taskTypeImage"] = $task->get_task_image();
            $row["taskTypeSeq"] = $_FORM["taskType_cache"][$row["taskTypeID"]]["taskTypeSeq"] ?? "";
            $row["taskStatusLabel"] = $task->get_task_status("label");
            $row["taskStatusColour"] = $task->get_task_status("colour");
            $row["creator_name"] = $_FORM["people_cache"][$row["creatorID"]]["name"] ?? "";
            $row["manager_name"] = $_FORM["people_cache"][$row["managerID"]]["name"] ?? "";
            $row["assignee_name"] = $_FORM["people_cache"][$row["personID"]]["name"] ?? "";
            $row["closer_name"] = $_FORM["people_cache"][$row["closerID"]]["name"] ?? "";
            $row["estimator_name"] = $_FORM["people_cache"][$row["estimatorID"]]["name"] ?? "";
            $row["creator_username"] = $_FORM["people_cache"][$row["creatorID"]]["username"] ?? "";
            $row["manager_username"] = $_FORM["people_cache"][$row["managerID"]]["username"] ?? "";
            $row["assignee_username"] = $_FORM["people_cache"][$row["personID"]]["username"] ?? "";
            $row["closer_username"] = $_FORM["people_cache"][$row["closerID"]]["username"] ?? "";
            $row["estimator_username"] = $_FORM["people_cache"][$row["estimatorID"]]["username"] ?? "";
            $row["newSubTask"] = $task->get_new_subtask_link();
            if (isset($_FORM["showPercent"])) {
                $row["percentComplete"] = $task->get_percentComplete();
            }

            if (isset($_FORM["showTimes"])) {
                $row["timeActual"] = $task->get_time_billed() / 60 / 60;
            }

            $row["rate"] = Page::money($row["currency"], $row["rate"], "%mo");
            $row["rateUnit"] = $_FORM["timeUnit_cache"][$row["rateUnitID"]]["timeUnitName"] ?? "";
            $row["priorityLabel"] = $task->get_priority_label();
            if (!isset($_FORM["skipObject"]) && $_FORM["return"] == "array") {
                $row["object"] = $task;
            }

            $row["padding"] = $_FORM["padding"] ?? "";
            $row["taskID"] = $task->get_id();
            $row["parentTaskID"] = $task->get_value("parentTaskID");
            $row["parentTaskID_link"] = "<a href='" . $task->get_url(false, $task->get_value("parentTaskID")) . "'>" . $task->get_value("parentTaskID") . "</a>";
            $row["timeLimitLabel"] = $row["timeBestLabel"] = $row["timeWorstLabel"] = $row["timeExpectedLabel"] = $row["timeActualLabel"] = "";
            if (isset($row["timeLimit"])) {
                $row["timeLimitLabel"] = seconds_to_display_format($row["timeLimit"] * 60 * 60);
            }

            if (isset($row["timeBest"])) {
                $row["timeBestLabel"] = seconds_to_display_format($row["timeBest"] * 60 * 60);
            }

            if (isset($row["timeWorst"])) {
                $row["timeWorstLabel"] = seconds_to_display_format($row["timeWorst"] * 60 * 60);
            }

            if (isset($row["timeExpected"])) {
                $row["timeExpectedLabel"] = seconds_to_display_format($row["timeExpected"] * 60 * 60);
            }

            if (isset($row["timeActual"])) {
                $row["timeActualLabel"] = seconds_to_display_format($row["timeActual"] * 60 * 60);
            }

            if (isset($_FORM["showComments"]) && $comments = comment::util_get_comments("task", $row["taskID"])) {
                $row["comments"] = $comments;
            }

            if (isset($_FORM["taskView"])) {
                if ($_FORM["taskView"] == "byProject") {
                    $rows[$task->get_id()] = ["parentTaskID" => $row["parentTaskID"], "row" => $row];
                } elseif ($_FORM["taskView"] == "prioritised") {
                    $rows[$row["taskID"]] = $row;
                    if (is_array($rows) && count($rows)) {
                        uasort($rows, ["Task", "priority_compare"]);
                    }
                }
            }
        }

        if ($_FORM["taskView"] == "byProject") {
            $parentTaskID = $_FORM["parentTaskID"] ?? 0;
            $t = Task::get_recursive_child_tasks($parentTaskID, (array)$rows);
            [$tasks, $done] = Task::build_recursive_task_list($t, $_FORM);
            // This bit appends the orphan tasks onto the end..
            foreach ((array)$rows as $taskID => $r) {
                $row = $r["row"];
                $row["padding"] = 0;
                if (!$done[$taskID]) {
                    $tasks += [$taskID => $row];
                }
            }
        } elseif ($_FORM["taskView"] == "prioritised") {
            $tasks = &$rows;
        }

        return (array)$tasks;
    }

    public static function priority_compare($a, $b)
    {
        return $a["priorityFactor"] > $b["priorityFactor"];
    }

    public static function get_list_html($tasks = [], $ops = [])
    {
        global $TPL;
        $TPL["taskListRows"] = $tasks;
        $TPL["_FORM"] = $ops;
        $TPL["taskPriorities"] = config::get_config_item("taskPriorities");
        $TPL["projectPriorities"] = config::get_config_item("projectPriorities");
        include_template(__DIR__ . "/../templates/taskListS.tpl");
    }

    public function get_task_priority_dropdown($priority = false)
    {
        $tp = [];
        ($taskPriorities = config::get_config_item("taskPriorities")) || ($taskPriorities = []);
        foreach ($taskPriorities as $k => $v) {
            $tp[$k] = $v["label"];
        }

        return Page::select_options($tp, $priority);
    }

    public function get_new_subtask_link()
    {
        global $TPL;
        if (!is_object($this)) {
            return;
        }

        if ($this->get_value("taskTypeID") != "Parent") {
            return;
        }

        return '<a class="noprint" href="' . $TPL["url_alloc_task"] . "projectID=" . $this->get_value("projectID") . "&parentTaskID=" . $this->get_id() . '">New Subtask</a>';
    }

    public function get_time_billed($taskID = "")
    {
        static $results;
        if (is_object($this) && !$taskID) {
            $taskID = $this->get_id();
        }

        if ($results[$taskID]) {
            return $results[$taskID];
        }

        if ($taskID) {
            $allocDatabase = new AllocDatabase();
            // Get tally from timeSheetItem table
            $allocDatabase->query(["SELECT sum(timeSheetItemDuration*timeUnitSeconds) as sum_of_time
                          FROM timeSheetItem
                     LEFT JOIN timeUnit ON timeSheetItemDurationUnitID = timeUnitID
                         WHERE taskID = %d
                      GROUP BY taskID", $taskID]);
            while ($allocDatabase->next_record()) {
                $results[$taskID] = $allocDatabase->f("sum_of_time");
                return $allocDatabase->f("sum_of_time");
            }

            return "";
        }
    }

    public function get_percentComplete($get_num = false)
    {

        $closed_text = null;
        $closed_text_end = null;
        $timeActual = sprintf("%0.2f", $this->get_time_billed());
        $timeExpected = sprintf("%0.2f", $this->get_value("timeLimit") * 60 * 60);

        if ($timeExpected > 0 && is_object($this)) {
            $percent = $timeActual / $timeExpected * 100;
            if ($this->get_value("dateActualCompletion") && ($closed_text = "<del>")) {
                $closed_text_end = "</del> Closed";
            }

            // Return number
            if ($get_num) {
                if ($this->get_value("dateActualCompletion") || $percent > 100) {
                    return 100;
                }

                return $percent;
                // Else if task <= 100%
            } elseif ($percent <= 100) {
                return $closed_text . sprintf("%d%%", $percent) . $closed_text_end;
                // Else if task > 100%
            } elseif ($percent > 100) {
                return "<span class='bad'>" . $closed_text . sprintf("%d%%", $percent) . $closed_text_end . "</span>";
            }
        }
    }

    public function get_priority_label()
    {
        static $taskPriorities;
        $taskPriorities || ($taskPriorities = config::get_config_item("taskPriorities"));
        return $taskPriorities[$this->get_value("priority")]["label"];
    }

    public function get_forecast_completion()
    {
        // Get the date the task is forecast to be completed given an actual start
        // date and percent complete
        $date_actual_start = $this->get_value("dateActualStart");
        $percent_complete = $this->get_percentComplete(true);

        if (!($date_actual_start && $percent_complete)) {
            // Can't calculate forecast date without date_actual_start and % complete
            return 0;
        }

        $date_actual_start = format_date("U", $date_actual_start);
        $time_spent = time() - $date_actual_start;
        $time_per_percent = $time_spent / $percent_complete;
        $percent_left = 100 - $percent_complete;
        $time_left = $percent_left * $time_per_percent;
        return time() + $time_left;
    }

    public static function get_list_vars()
    {
        $pipe = null;
        $taskStatiiStr = null;
        $taskStatii = Task::get_task_statii_array(true);
        foreach ($taskStatii as $k => $v) {
            $taskStatiiStr .= $pipe . $k;
            $pipe = " | ";
        }

        return [
            "taskView"            => "[MANDATORY] eg: byProject | prioritised",
            "return"              => "[MANDATORY] eg: html | array",
            "limit"               => "Appends an SQL limit (only for prioritised and objects views)",
            "projectIDs"          => "An array of projectIDs",
            "projectID"           => "A single projectID",
            "taskStatus"          => $taskStatiiStr,
            "taskDate"            => "new | due_today | overdue | d_created | d_assigned | d_targetStart | d_targetCompletion | d_actualStart | d_actualCompletion (all the d_* options require a dateOne (From Date) or a dateTwo (To Date) to be filled)",
            "dateOne"             => "From Date (must be used with a d_* taskDate option)",
            "dateTwo"             => "To Date (must be used with a d_* taskDate option)",
            "taskTimeSheetStatus" => "my_open | not_assigned | my_closed | my_recently_closed | all",
            "taskTypeID"          => "Task | Parent | Message | Fault | Milestone",
            "current_user"        => "Lets us fake a current_user id for when generating task emails and there is no \$current_user object",
            "taskID"              => "Task ID",
            "starred"             => "Tasks that you have starred",
            "taskName"            => "Task Name (eg: *install*)",
            "tags"                => "Task tags",
            "creatorID"           => "Task creator",
            "managerID"           => "The person managing task",
            "personID"            => "The person assigned to the task",
            "parentTaskID"        => "ID of parent task, all top level tasks have parentTaskID of 0, so this defaults to 0",
            "projectType"         => "mine | pm | tsm | pmORtsm | Current | Potential | Archived | all",
            "applyFilter"         => "Saves this filter as the persons preference",
            "padding"             => "Initial indentation level (useful for byProject lists)",
            "url_form_action"     => "The submit action for the filter form",
            "hide_field_options"  => "Hide the filter's field's panel.",
            "form_name"           => "The name of this form, i.e. a handle for referring to this saved form",
            "dontSave"            => "Specify that the filter preferences should not be saved this time",
            "skipObject"          => "Services coming over SOAP should set this true to minimize the amount of bandwidth",
            "showDates"           => "Show dates 1-4",
            "showDate1"           => "Date Target Start",
            "showDate2"           => "Date Target Completion",
            "showDate3"           => "Date Actual Start",
            "showDate4"           => "Date Actual Completion",
            "showDate5"           => "Date Created",
            "showProject"         => "The tasks Project (has different layout when prioritised vs byProject)",
            "showPriority"        => "The calculated overall priority, then the tasks, then the projects priority",
            "showPriorityFactor"  => "The calculated overall priority",
            "showStatus"          => "A colour coded textual description of the status of the task",
            "showCreator"         => "The tasks creator",
            "showAssigned"        => "The person assigned to the task",
            "showTimes"           => "The original estimate and the time billed and percentage",
            "showHeader"          => "A descriptive html header row",
            "showDescription"     => "The tasks description",
            "showComments"        => "The tasks comments",
            "showTaskID"          => "The task ID",
            "showParentID"        => "The task's parent ID",
            "showManager"         => "Show the tasks manager",
            "showPercent"         => "The percent complete",
            "showTags"            => "The task's tags",
            "showEdit"            => "Display the html edit controls to allow en masse task editing",
            "showTotals"          => "Display the totals of certain columns in the html view",
        ];
    }

    public static function load_form_data($defaults = [])
    {
        $current_user = &singleton("current_user");

        $page_vars = array_keys(Task::get_list_vars());

        $_FORM = get_all_form_data($page_vars, $defaults);

        if (isset($_FORM["projectID"]) && !is_array($_FORM["projectID"])) {
            $p = $_FORM["projectID"];
            unset($_FORM["projectID"]);
            $_FORM["projectID"][] = $p;
        } elseif (!isset($_FORM["projectType"])) {
            $_FORM["projectType"] = "mine";
        }

        if (isset($_FORM["showDates"])) {
            $_FORM["showDate1"] = true;
            $_FORM["showDate2"] = true;
            $_FORM["showDate3"] = true;
            $_FORM["showDate4"] = true;
            $_FORM["showDate5"] = true;
        }

        if (isset($_FORM["applyFilter"]) && is_object($current_user)) {
            // we have a new filter configuration from the user, and must save it
            if (!isset($_FORM["dontSave"])) {
                $url = $_FORM["url_form_action"];
                unset($_FORM["url_form_action"]);
                $current_user->prefs[$_FORM["form_name"]] = $_FORM;
                $_FORM["url_form_action"] = $url;
            }

            // we haven't been given a filter configuration, so load it from user preferences
        } elseif (isset($_FORM['form_name'])) {
            if (isset($_FORM["form_name"]) && isset($current_user->prefs[$_FORM["form_name"]])) {
                $_FORM = $current_user->prefs[$_FORM["form_name"]];
            }

            if (!isset($current_user->prefs[$_FORM["form_name"] ?? ""])) {
                $_FORM["projectType"] = "mine";
                $_FORM["taskStatus"] = "open";
                $_FORM["personID"] = $current_user->get_id();
            }
        }

        // If have check Show Description checkbox then display the Long Description and the Comments
        if (isset($_FORM["showDescription"])) {
            $_FORM["showComments"] = true;
        } else {
            unset($_FORM["showComments"]);
        }

        $_FORM["taskView"] ??= "byProject";
        return $_FORM;
    }

    public static function load_task_filter(array $_FORM): array
    {
        $rtn = [];
        $task = new Task();

        // Load up the forms action url
        $rtn["url_form_action"] = $_FORM["url_form_action"];
        $rtn["hide_field_options"] = $_FORM["hide_field_options"] ?? "";

        // time Load up the filter bits
        has("project") && ($rtn["projectOptions"] = project::get_list_dropdown($_FORM["projectType"], $_FORM["projectID"] ?? ""));

        $_FORM["projectType"] && ($rtn["projectType_checked"][$_FORM["projectType"]] = " checked");
        $ops = ["0" => "Nobody"];
        $rtn["personOptions"] = Page::select_options($ops + person::get_username_list($_FORM["personID"]), $_FORM["personID"]);
        $rtn["managerPersonOptions"] = Page::select_options($ops + person::get_username_list($_FORM["managerID"] ?? ""), $_FORM["managerID"] ?? "");
        $rtn["creatorPersonOptions"] = Page::select_options(person::get_username_list($_FORM["creatorID"] ?? ""), $_FORM["creatorID"] ?? "");
        $rtn["all_tags"] = $task->get_tags(true);
        $rtn["tags"] = $_FORM["tags"] ?? "";

        $meta = new Meta("taskType");
        $taskType_array = $meta->get_assoc_array("taskTypeID", "taskTypeID");
        $rtn["taskTypeOptions"] = Page::select_options($taskType_array, $_FORM["taskTypeID"] ?? "");

        $_FORM["taskView"] && ($rtn["taskView_checked_" . $_FORM["taskView"]] = " checked");

        $taskStatii = Task::get_task_statii_array();
        $rtn["taskStatusOptions"] = Page::select_options($taskStatii, $_FORM["taskStatus"]);

        if (isset($_FORM["showDescription"])) {
            $rtn["showDescription_checked"] = " checked";
        }
        if (isset($_FORM["showDates"])) {
            $rtn["showDates_checked"] = " checked";
        }
        if (isset($_FORM["showCreator"])) {
            $rtn["showCreator_checked"] = " checked";
        }
        if (isset($_FORM["showAssigned"])) {
            $rtn["showAssigned_checked"] = " checked";
        }
        if (isset($_FORM["showTimes"])) {
            $rtn["showTimes_checked"] = " checked";
        }
        if (isset($_FORM["showPercent"])) {
            $rtn["showPercent_checked"] = " checked";
        }
        if (isset($_FORM["showPriority"])) {
            $rtn["showPriority_checked"] = " checked";
        }
        if (isset($_FORM["showTaskID"])) {
            $rtn["showTaskID_checked"] = " checked";
        }
        if (isset($_FORM["showManager"])) {
            $rtn["showManager_checked"] = " checked";
        }
        if (isset($_FORM["showProject"])) {
            $rtn["showProject_checked"] = " checked";
        }
        if (isset($_FORM["showTags"])) {
            $rtn["showTags_checked"] = " checked";
        }
        if (isset($_FORM["showParentID"])) {
            $rtn["showParentID_checked"] = " checked";
        }

        $arrow = " --&gt;";
        $taskDateOps = [
            ""                   => "",
            "new"                => "New Tasks",
            "due_today"          => "Due Today",
            "overdue"            => "Overdue",
            "d_created"          => "Date Created" . $arrow,
            "d_assigned"         => "Date Assigned" . $arrow,
            "d_targetStart"      => "Estimated Start" . $arrow,
            "d_targetCompletion" => "Estimated Completion" . $arrow,
            "d_actualStart"      => "Date Started" . $arrow,
            "d_actualCompletion" => "Date Completed" . $arrow,
        ];
        $rtn["taskDateOptions"] = Page::select_options($taskDateOps, $_FORM["taskDate"] ?? "", 45, false);

        if (isset($_FORM["taskDate"]) && !in_array($_FORM["taskDate"], ["new", "due_today", "overdue"])) {
            $rtn["dateOne"] = $_FORM["dateOne"];
            $rtn["dateTwo"] = $_FORM["dateTwo"];
        }

        $task_num_ops = [
            ""    => "All results",
            1     => "1 result",
            2     => "2 results",
            3     => "3 results",
            4     => "4 results",
            5     => "5 results",
            10    => "10 results",
            15    => "15 results",
            20    => "20 results",
            30    => "30 results",
            40    => "40 results",
            50    => "50 results",
            100   => "100 results",
            150   => "150 results",
            200   => "200 results",
            300   => "300 results",
            400   => "400 results",
            500   => "500 results",
            1000  => "1000 results",
            2000  => "2000 results",
            3000  => "3000 results",
            4000  => "4000 results",
            5000  => "5000 results",
            10000 => "10000 results",
        ];

        $rtn["limitOptions"] = Page::select_options($task_num_ops, $_FORM["limit"] ?? "");

        // unset vars that aren't necessary
        foreach ((array)$_FORM as $k => $v) {
            if (!$v) {
                unset($_FORM[$k]);
            }
        }

        // Get
        $rtn["FORM"] = "FORM=" . urlencode(serialize($_FORM));

        return $rtn;
    }

    public function get_changes_list(): string
    {
        // This function returns HTML rows for the changes that have been made to this task
        $rows = [];

        $people_cache = &get_cached_table("person");

        $options = ["taskID" => $this->get_id()];
        $changes = audit::get_list($options);

        foreach ($changes as $change) {
            $changeDescription = "";
            $newValue = $change['value'];
            switch ($change['field']) {
                case 'created':
                    $changeDescription = $newValue;
                    break;
                case 'dip':
                    $changeDescription = "Default parties set to " . InterestedParty::abbreviate($newValue);
                    break;
                case 'taskName':
                    $changeDescription = sprintf("Task name set to '%s'.", $newValue);
                    break;
                case 'taskDescription':
                    $changeDescription = "Task description set to <a class=\"magic\" href=\"#x\" onclick=\"$('#audit" . $change["auditID"] . "').slideToggle('fast');\">Show</a> <div class=\"hidden\" id=\"audit" . $change["auditID"] . '"><div>' . $newValue . "</div></div>";
                    break;
                case 'priority':
                    $priorities = config::get_config_item("taskPriorities");
                    $changeDescription = sprintf('Task priority set to <span style="color: %s;">%s</span>.', $priorities[$newValue]["colour"], $priorities[$newValue]["label"]);
                    break;
                case 'projectID':
                    (new Task())->load_entity("project", $newValue, $newProject);
                    if (is_object($newProject)) {
                        $newProjectLink = $newProject->get_link();
                    }

                    $newProjectLink || ($newProjectLink = "&lt;empty&gt;");
                    $changeDescription = "Project changed set to " . $newProjectLink . ".";
                    break;
                case 'parentTaskID':
                    (new Task())->load_entity("task", $newValue, $newTask);
                    if ($newValue) {
                        $changeDescription = sprintf("Task set to a child of %d %s.", $newTask->get_id(), $newTask->get_task_link());
                    } else {
                        $changeDescription = "Task no longer a child task.";
                    }

                    break;
                case 'duplicateTaskID':
                    (new Task())->load_entity("task", $newValue, $newTask);
                    if ($newValue) {
                        $changeDescription = "Task set to a duplicate of " . $newTask->get_task_link();
                    } else {
                        $changeDescription = "Task is no longer a duplicate.";
                    }

                    break;
                case 'personID':
                    $changeDescription = "Task assigned to " . $people_cache[$newValue]["name"] . ".";
                    break;
                case 'managerID':
                    $changeDescription = "Task manager set to " . $people_cache[$newValue]["name"] . ".";
                    break;
                case 'estimatorID':
                    $changeDescription = "Task estimator set to " . $people_cache[$newValue]["name"] . ".";
                    break;
                case 'taskTypeID':
                    $changeDescription = "Task type set to " . $newValue . ".";
                    break;
                case 'taskStatus':
                    $changeDescription = sprintf(
                        'Task status set to <span style="background-color:%s">%s</span>.',
                        Task::get_task_status_thing("colour", $newValue),
                        Task::get_task_status_thing("label", $newValue)
                    );
                    break;
                case 'dateActualCompletion':
                case 'dateActualStart':
                case 'dateTargetStart':
                case 'dateTargetCompletion':
                case 'timeLimit':
                case 'timeBest':
                case 'timeWorst':
                case 'timeExpected':
                    // these cases are more or less identical
                    switch ($change['field']) {
                        case 'dateActualCompletion':
                            $fieldDesc = "actual completion date";
                            break;
                        case 'dateActualStart':
                            $fieldDesc = "actual start date";
                            break;
                        case 'dateTargetStart':
                            $fieldDesc = "estimate/target start date";
                            break;
                        case 'dateTargetCompletion':
                            $fieldDesc = "estimate/target completion date";
                            break;
                        case 'timeLimit':
                            $fieldDesc = "hours worked limit";
                            break;
                        case 'timeBest':
                            $fieldDesc = "best estimate";
                            break;
                        case 'timeWorst':
                            $fieldDesc = "worst estimate";
                            break;
                        case 'timeExpected':
                            $fieldDesc = "expected estimate";
                    }

                    if ($newValue) {
                        $changeDescription = sprintf('The %s was set to %s.', $fieldDesc, $newValue);
                    } else {
                        $changeDescription = sprintf('The %s was removed.', $fieldDesc);
                    }

                    break;
            }

            $rows[] = '<tr><td class="nobr">' . $change["dateChanged"] . sprintf('</td><td>%s</td><td>', $changeDescription) . Page::htmlentities($people_cache[$change["personID"]]["name"]) . "</td></tr>";
        }

        return implode("\n", $rows);
    }

    public function load_entity($type, $id, &$entity)
    {
        // helper function to cut down on code duplication in the above function
        if ($id) {
            $entity = new $type;
            $entity->set_id($id);
            $entity->select();
        }
    }

    public function update_search_index_doc(&$index)
    {
        $projectName = null;
        $p = &get_cached_table("person");
        $creatorID = $this->get_value("creatorID");
        $creator_field = $creatorID . " " . $p[$creatorID]["username"] . " " . $p[$creatorID]["name"];
        $closerID = $this->get_value("closerID");
        $closer_field = $closerID . " " . $p[$closerID]["username"] . " " . $p[$closerID]["name"];
        $personID = $this->get_value("personID");
        $person_field = $personID . " " . $p[$personID]["username"] . " " . $p[$personID]["name"];
        $managerID = $this->get_value("managerID");
        $manager_field = $managerID . " " . $p[$managerID]["username"] . " " . $p[$managerID]["name"];
        $taskModifiedUser = $this->get_value("taskModifiedUser");
        $taskModifiedUser_field = $taskModifiedUser . " " . $p[$taskModifiedUser]["username"] . " " . $p[$taskModifiedUser]["name"];
        $status = $this->get_value("taskStatus");

        if ($this->get_value("projectID")) {
            $project = new project();
            $project->set_id($this->get_value("projectID"));
            $project->select();
            $projectName = $project->get_name();
            $projectShortName = $project->get_name(["showShortProjectLink" => true]);
            if ($projectShortName && $projectShortName != $projectName) {
                $projectName .= " " . $projectShortName;
            }
        }

        $zendSearchLuceneDocument = new Document();
        $zendSearchLuceneDocument->addField(Field::Keyword('id', $this->get_id()));
        $zendSearchLuceneDocument->addField(Field::Text('name', $this->get_value("taskName"), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('project', $projectName, "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('pid', $this->get_value("projectID"), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('creator', $creator_field, "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('closer', $closer_field, "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('assignee', $person_field, "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('manager', $manager_field, "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('modifier', $taskModifiedUser_field, "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('desc', $this->get_value("taskDescription"), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('priority', $this->get_value("priority"), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('limit', $this->get_value("timeLimit"), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('best', $this->get_value("timeBest"), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('worst', $this->get_value("timeWorst"), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('expected', $this->get_value("timeExpected"), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('type', $this->get_value("taskTypeID"), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('status', $status, "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('dateCreated', str_replace("-", "", $this->get_value("dateCreated")), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('dateAssigned', str_replace("-", "", $this->get_value("dateAssigned")), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('dateClosed', str_replace("-", "", $this->get_value("dateClosed")), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('dateTargetStart', str_replace("-", "", $this->get_value("dateTargetStart")), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('dateTargetCompletion', str_replace("-", "", $this->get_value("dateTargetCompletion")), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('dateStart', str_replace("-", "", $this->get_value("dateActualStart")), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('dateCompletion', str_replace("-", "", $this->get_value("dateActualCompletion")), "utf-8"));

        $index->addDocument($zendSearchLuceneDocument);
    }

    public function add_comment_from_email($email_receive, $ignorethis)
    {
        return comment::add_comment_from_email($email_receive, $this);
    }

    public function get_project_id()
    {
        return $this->get_value("projectID");
    }

    public function can_be_deleted()
    {
        if (is_object($this) && $this->get_id()) {
            $allocDatabase = new AllocDatabase();
            $q = unsafe_prepare("SELECT can_delete_task(%d) as rtn", $this->get_id());
            $allocDatabase->query($q);
            $row = $allocDatabase->row();
            return $row['rtn'];
        }
    }

    public function moved_from_pending_to_open()
    {
        if (is_object($this) && $this->get_id()) {
            $this->select();
            if (substr($this->get_value("taskStatus"), 0, 4) == 'open') {
                $allocDatabase = new AllocDatabase();
                $q = unsafe_prepare("SELECT *
                                FROM audit
                               WHERE taskID = %d
                                 AND field = 'taskStatus'
                            ORDER BY dateChanged DESC
                               LIMIT 2,1", $this->get_id());
                $row = $allocDatabase->qr($q);
                return substr($row["value"], 0, 7) == "pending";
            }
        }
    }

    public function reopen_pending_task()
    {
        if (is_object($this) && $this->get_id()) {
            $this->select();
            if (substr($this->get_value("taskStatus"), 0, 4) == 'pend') {
                $allocDatabase = new AllocDatabase();
                $allocDatabase->query(["call change_task_status(%d,'%s')", $this->get_id(), "open_inprogress"]);
                return true;
            }
        }
    }

    public function add_notification($tokenActionID, $maxUsed, $name, $desc, $recipients, $datetime = false)
    {
        $current_user = &singleton("current_user");
        $token = new token();
        $token->set_value("tokenEntity", "task");
        $token->set_value("tokenEntityID", $this->get_id());
        $token->set_value("tokenActionID", $tokenActionID);
        $token->set_value("tokenActive", 1);
        $token->set_value("tokenMaxUsed", $maxUsed);
        $token->set_value("tokenCreatedBy", $current_user->get_id());
        $token->set_value("tokenCreatedDate", date("Y-m-d H:i:s"));

        $hash = $token->generate_hash();
        $token->set_value("tokenHash", $hash);
        $token->save();
        if ($token->get_id()) {
            $reminder = new reminder();
            $reminder->set_value("reminderType", "task");
            $reminder->set_value("reminderLinkID", $this->get_id());
            $reminder->set_value("reminderHash", $hash);
            $reminder->set_value("reminderSubject", $name);
            $reminder->set_value("reminderContent", $desc);
            if ($datetime) {
                $reminder->set_value("reminderTime", $datetime);
            }

            $reminder->save();
            if ($reminder->get_id()) {
                foreach ($recipients as $recipient) {
                    $reminderRecipient = new reminderRecipient();
                    $reminderRecipient->set_value("reminderID", $reminder->get_id());
                    $reminderRecipient->set_value($recipient["field"], $recipient["who"]);
                    $reminderRecipient->save();
                }
            }
        }
    }
}
