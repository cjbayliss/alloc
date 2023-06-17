<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

// -1 used to be ALL, but was made redundant with the multiselect
define("REMINDER_METAPERSON_TASK_ASSIGNEE", 2);
define("REMINDER_METAPERSON_TASK_MANAGER", 3);

class reminder extends DatabaseEntity
{
    public $data_table = "reminder";
    public $display_field_name = "reminderSubject";
    public $key_field = "reminderID";
    public $data_fields = [
        "reminderType",
        "reminderLinkID",
        "reminderTime",
        "reminderHash",
        "reminderRecuringInterval",
        "reminderRecuringValue",
        "reminderAdvNoticeSent",
        "reminderAdvNoticeInterval",
        "reminderAdvNoticeValue",
        "reminderSubject",
        "reminderContent",
        "reminderCreatedTime",
        "reminderCreatedUser",
        "reminderModifiedTime",
        "reminderModifiedUser",
        "reminderActive" => ["empty_to_null" => true],
    ];

    // set the modified time to now
    public function set_modified_time()
    {
        $this->set_value("reminderModifiedTime", date("Y-m-d H:i:s"));
    }

    public function delete()
    {
        $q = unsafe_prepare("DELETE FROM reminderRecipient WHERE reminderID = %d", $this->get_id());
        $dballoc = new db_alloc();
        $dballoc->query($q);
        return parent::delete();
    }

    public function get_recipients()
    {
        $recipients = [];
        $dballoc = new db_alloc();
        $type = $this->get_value('reminderType');
        if ($type == "project") {
            $query = unsafe_prepare("SELECT *
                                FROM projectPerson
                           LEFT JOIN person ON projectPerson.personID=person.personID
                               WHERE projectPerson.projectID = %d
                            ORDER BY person.username", $this->get_value('reminderLinkID'));
        } else if ($type == "task") {
            // Modified query option: to send to all people on the project that this task is from.
            $recipients = ["-3" => "Task Manager", "-2" => "Task Assignee"];

            $dballoc->query("SELECT projectID FROM task WHERE taskID = %d", $this->get_value('reminderLinkID'));
            $dballoc->next_record();

            if ($dballoc->f('projectID')) {
                $query = unsafe_prepare("SELECT *
                                    FROM projectPerson
                               LEFT JOIN person ON projectPerson.personID=person.personID
                                   WHERE projectPerson.projectID = %d
                                ORDER BY person.username", $dballoc->f('projectID'));
            } else {
                $query = "SELECT * FROM person WHERE personActive = 1 ORDER BY username";
            }
        } else {
            $query = "SELECT * FROM person WHERE personActive = 1 ORDER BY username";
        }
        $dballoc->query($query);
        while ($dballoc->next_record()) {
            $person = new person();
            $person->read_db_record($dballoc);
            $recipients[$person->get_id()] = $person->get_name();
        }

        return $recipients;
    }

    public function get_recipient_options()
    {
        $current_user = &singleton("current_user");

        $recipients = $this->get_recipients();
        $type = $this->get_value('reminderType');

        $selected = [];
        $dballoc = new db_alloc();
        $query = "SELECT * from reminderRecipient WHERE reminderID = %d";
        $dballoc->query($query, $this->get_id());
        while ($dballoc->next_record()) {
            if ($dballoc->f('metaPersonID')) {
                $selected[] = $dballoc->f('metaPersonID');
            } else {
                $selected[] = $dballoc->f('personID');
            }
        }

        if (!$selected && $_GET["personID"]) {
            $selected[] = $_GET["personID"];
        }
        if (!$this->get_id()) {
            $selected[] = $current_user->get_id();
        }
        return [$recipients, $selected];
    }

    public function get_hour_options()
    {
        $hours = [
            "1"  => "1",
            "2"  => "2",
            "3"  => "3",
            "4"  => "4",
            "5"  => "5",
            "6"  => "6",
            "7"  => "7",
            "8"  => "8",
            "9"  => "9",
            "10" => "10",
            "11" => "11",
            "12" => "12",
        ];
        if ($this->get_value('reminderTime') != "") {
            $date = strtotime($this->get_value('reminderTime'));
            $hour = date("h", $date);
        } else {
            $hour = date("h", mktime(date("H"), date("i") + 5 - (date("i") % 5), 0, date("m"), date("d"), date("Y")));
        }
        return page::select_options($hours, $hour);
    }

    public function get_minute_options()
    {
        $minutes = [
            "0"  => "00",
            "10" => "10",
            "20" => "20",
            "30" => "30",
            "40" => "40",
            "50" => "50",
        ];
        if ($this->get_value('reminderTime') != "") {
            $date = strtotime($this->get_value('reminderTime'));
            $minute = date("i", $date);
        } else {
            $minute = date("i", mktime(date("H"), date("i") + 5 - (date("i") % 5), 0, date("m"), date("d"), date("Y")));
        }
        return page::select_options($minutes, $minute);
    }

    public function get_meridian_options()
    {
        $meridians = [
            "am" => "AM",
            "pm" => "PM",
        ];
        if ($this->get_value('reminderTime') != "") {
            $date = strtotime($this->get_value('reminderTime'));
            $meridian = date("a", $date);
        } else {
            $meridian = date("a", mktime(date("H"), date("i") + 5 - (date("i") % 5), 0, date("m"), date("d"), date("Y")));
        }
        return page::select_options($meridians, $meridian);
    }

    public function get_recuring_interval_options()
    {
        $recuring_interval_options = [
            "Hour"  => "Hour(s)",
            "Day"   => "Day(s)",
            "Week"  => "Week(s)",
            "Month" => "Month(s)",
            "Year"  => "Year(s)",
        ];
        $recuring_interval = $this->get_value('reminderRecuringInterval');
        if ($recuring_interval == "") {
            $recuring_interval = "Week";
        }
        return page::select_options($recuring_interval_options, $recuring_interval);
    }

    public function get_advnotice_interval_options()
    {
        $advnotice_interval_options = [
            "Minute" => "Minute(s)",
            "Hour"   => "Hour(s)",
            "Day"    => "Day(s)",
            "Week"   => "Week(s)",
            "Month"  => "Month(s)",
            "Year"   => "Year(s)",
        ];
        $advnotice_interval = $this->get_value('reminderAdvNoticeInterval');
        if ($advnotice_interval == "") {
            $advnotice_interval = "Hour";
        }
        return page::select_options($advnotice_interval_options, $advnotice_interval);
    }

    public function is_alive()
    {
        $type = $this->get_value('reminderType');
        if ($type == "project") {
            $project = new project();
            $project->set_id($this->get_value('reminderLinkID'));
            if ($project->select() == false || $project->get_value('projectStatus') == "Archived") {
                return false;
            }
        } else if ($type == "task") {
            $task = new task();
            $task->set_id($this->get_value('reminderLinkID'));
            if ($task->select() == false || substr($task->get_value("taskStatus"), 0, 6) == 'closed') {
                return false;
            }
        } else if ($type == "client") {
            $client = new client();
            $client->set_id($this->get_value('reminderLinkID'));
            if ($client->select() == false || $client->get_value('clientStatus') == "Archived") {
                return false;
            }
        }
        return true;
    }

    public function deactivate()
    {
        $this->set_value("reminderActive", 0);
        return $this->save();
    }

    // mail out reminder and update to next date if repeating or remove if onceoff
    // checks to make sure that it is the right time to send reminder should be
    // dome before calling this function
    public function mail_reminder()
    {
        // check for a reminder.reminderHash that links off to a token.tokenHash
        // this lets us trigger reminders on complex actions, for example create
        // a reminder that sends when a task status changes from pending to open

        // Note this->reminderTime is going to always be null for the token that
        // link to task->moved_from_pending_to_open().
        // Whereas the task->reopen_pending_task() will have a reminderTime set.

        $ok = true;
        if ($this->get_value("reminderHash")) {
            $token = new token();
            if ($token->set_hash($this->get_value("reminderHash"))) {
                [$entity, $method] = $token->execute();
                if (is_object($entity) && $entity->get_id()) {
                    if (!$entity->$method()) {
                        $token->decrement_tokenUsed(); // next time, gadget
                        $ok = false;
                    }
                }
            }
        }

        if ($ok) {
            $recipients = $this->get_all_recipients();
            // Reminders can be clients, tasks, projects or "general" - comment threads don't exist for general
            if ($this->get_value('reminderType') != 'general') {
                // Nowhere to put the subject?
                $commentID = comment::add_comment(
                    $this->get_value('reminderType'),
                    $this->get_value('reminderLinkID'),
                    $this->get_value('reminderContent'),
                    $this->get_value('reminderType'),
                    $this->get_value('reminderLinkID')
                );
                // Repackage the recipients to become IPs of the new comment
                $ips = [];
                foreach ((array)$recipients as $id => $person) {
                    $ip = [];
                    $ip['name'] = $person['name'];
                    $ip['addIP'] = true;
                    $ip['addContact'] = false;
                    $ip['internal'] = true;

                    $ips[$person['emailAddress']] = $ip;
                }

                comment::add_interested_parties($commentID, false, $ips);
                // email_receive false or true? false for now... maybe true is better?
                comment::send_comment($commentID, ["interested"]);
            } else {
                foreach ((array)$recipients as $person) {
                    if ($person['emailAddress']) {
                        $email = sprintf("%s %s <%s>", $person['firstName'], $person['surname'], $person['emailAddress']);
                        $subject = $this->get_value('reminderSubject');
                        $content = $this->get_value('reminderContent');
                        $e = new email_send($email, $subject, $content, "reminder");
                        $e->send();
                    }
                }
            }

            // Update reminder (reminderTime can be blank for task->moved_from_pending_to_open)
            if ($this->get_value('reminderRecuringInterval') == "No") {
                $this->deactivate();
            } else if ($this->get_value('reminderRecuringValue') != 0) {
                $interval = $this->get_value('reminderRecuringValue');
                $intervalUnit = $this->get_value('reminderRecuringInterval');
                $newtime = $this->get_next_reminder_time(strtotime($this->get_value('reminderTime')), $interval, $intervalUnit);
                $this->set_value('reminderTime', date("Y-m-d H:i:s", $newtime));
                $this->set_value('reminderAdvNoticeSent', 0);
                $this->save();
            }
        }
    }

    public function get_next_reminder_time($reminderTime, $interval, $intervalUnit)
    {
        $date_H = date("H", $reminderTime);
        $date_i = date("i", $reminderTime);
        $date_s = date("s", $reminderTime);
        $date_m = date("m", $reminderTime);
        $date_d = date("d", $reminderTime);
        $date_Y = date("Y", $reminderTime);

        switch ($intervalUnit) {
            case "Minute":
                $date_i = date("i", $reminderTime) + $interval;
                break;
            case "Hour":
                $date_H = date("H", $reminderTime) + $interval;
                break;
            case "Day":
                $date_d = date("d", $reminderTime) + $interval;
                break;
            case "Week":
                $date_d = date("d", $reminderTime) + (7 * $interval);
                break;
            case "Month":
                $date_m = date("m", $reminderTime) + $interval;
                break;
            case "Year":
                $date_Y = date("Y", $reminderTime) + $interval;
                break;
        }

        return mktime($date_H, $date_i, $date_s, $date_m, $date_d, $date_Y);
    }

    // checks advanced notice time if any and mails advanced notice if it is time
    public function mail_advnotice()
    {
        $date = strtotime($this->get_value('reminderTime'));
        // if no advanced notice needs to be sent then dont bother
        if (
            $this->get_value('reminderAdvNoticeInterval') != "No"
            && $this->get_value('reminderAdvNoticeSent') == 0 && !$this->get_value("reminderHash")
        ) {
            $date = strtotime($this->get_value('reminderTime'));
            $interval = -$this->get_value('reminderAdvNoticeValue');
            $intervalUnit = $this->get_value('reminderAdvNoticeInterval');
            $advnotice_time = $this->get_next_reminder_time($date, $interval, $intervalUnit);

            // only sent advanced notice if it is time to send it
            if (date("YmdHis", $advnotice_time) <= date("YmdHis")) {
                $recipients = $this->get_all_recipients();

                $subject = sprintf(
                    "Adv Notice: %s",
                    $this->get_value('reminderSubject')
                );
                $content = $this->get_value('reminderContent');

                foreach ($recipients as $recipient) {
                    if ($recipient['emailAddress']) {
                        $email = sprintf(
                            "%s %s <%s>",
                            $recipient['firstName'],
                            $recipient['surname'],
                            $recipient['emailAddress']
                        );
                        $e = new email_send($email, $subject, $content, "reminder_advnotice");
                        $e->send();
                    }
                }
                $this->set_value('reminderAdvNoticeSent', 1);
                $this->save();
            }
        }
    }

    // get the personID of the person who'll actually recieve this reminder
    // (i.e., convert "Task Assignee" into "Bob")
    public function get_effective_person_id($recipient = null)
    {
        if ($recipient->get_value('personID') == null) { // nulls don't come through correctly?
            // OK, slightly more complicated, we need to get the relevant link entity
            $metaperson = -$recipient->get_value('metaPersonID');
            $type = $this->get_value("reminderType");
            if ($type == "task") {
                $task = new task();
                $task->set_id($this->get_value('reminderLinkID'));
                $task->select();

                switch ($metaperson) {
                    case REMINDER_METAPERSON_TASK_ASSIGNEE:
                        return $task->get_value('personID');
                        break;
                    case REMINDER_METAPERSON_TASK_MANAGER:
                        return $task->get_value('managerID');
                        break;
                }
            } else {
                // we should never actually get here...
                alloc_error("Unknown metaperson.");
            }
        } else {
            return $recipient->get_value('personID');
        }
    }

    // gets a human-friendly description of the recipient, either the recipient name or in the form Task Manager (Bob)
    public function get_recipient_description()
    {
        $people = &get_cached_table("person");
        $name = $people[$this->get_effective_person_id()]["name"];
        if ($this->get_value("metaPerson") === null) {
            return $name;
        } else {
            return sprintf("%s (%s)", (new reminder())->get_metaperson_name($this->get_value("metaPerson")), $name);
        }
    }

    // gets the human-friendly name of the meta person (e.g. R_MP_TASK_ASSIGNEE to "Task assignee")
    public function get_metaperson_name($metaperson)
    {
        switch ($metaperson) {
            case REMINDER_METAPERSON_TASK_ASSIGNEE:
                return "Task Assignee";
                break;
            case REMINDER_METAPERSON_TASK_MANAGER:
                return "Task Manager";
                break;
        }
    }

    public function get_all_recipients()
    {
        $dballoc = new db_alloc();
        $query = "SELECT * FROM reminderRecipient WHERE reminderID = %d";
        $dballoc->query($query, $this->get_id());
        $people = &get_cached_table("person");
        $recipients = [];
        $reminderRecipient = new reminderRecipient();
        while ($dballoc->next_record()) {
            $reminderRecipient->read_db_record($dballoc);
            $id = $this->get_effective_person_id($reminderRecipient);
            // hash on person ID prevents multiple emails to the same person
            $recipients[$id] = $people[$id];
        }
        return $recipients;
    }

    public function update_recipients($recipients)
    {
        $dballoc = new db_alloc();
        $query = "DELETE FROM reminderRecipient WHERE reminderID = %d";
        $dballoc->query($query, $this->get_id());
        foreach ((array)$recipients as $r) {
            $recipient = new reminderRecipient();
            $recipient->set_value('reminderID', $this->get_id());
            if ($r < 0) {
                $recipient->set_value('metaPersonID', $r);
                $recipient->set_value('personID', null);
            } else {
                $recipient->set_value('personID', $r);
            }
            $recipient->save();
        }
        return;
    }

    public static function get_list_filter($filter = [])
    {
        $sql = [];
        $filter["type"] and $sql[] = unsafe_prepare("reminderType='%s'", $filter["type"]);
        $filter["id"] and $sql[] = unsafe_prepare("reminderLinkID=%d", $filter["id"]);
        $filter["reminderID"] and $sql[] = unsafe_prepare("reminder.reminderID=%d", $filter["reminderID"]);
        $filter["filter_recipient"] and $sql[] = unsafe_prepare("personID = %d", $filter["filter_recipient"]);
        (isset($filter["filter_reminderActive"]) && (bool)strlen($filter["filter_reminderActive"])) and $sql[] = unsafe_prepare("reminderActive = %d", $filter["filter_reminderActive"]);

        return $sql;
    }

    public static function get_list($_FORM)
    {
        $f = null;
        $rows = [];
        $filter = reminder::get_list_filter($_FORM);
        if (is_array($filter) && count($filter)) {
            $f = " WHERE " . implode(" AND ", $filter);
        }
        $dballoc = new db_alloc();
        $q = "SELECT reminder.*,reminderRecipient.*,token.*,tokenAction.*, reminder.reminderID as rID
                FROM reminder
           LEFT JOIN reminderRecipient ON reminder.reminderID = reminderRecipient.reminderID
           LEFT JOIN token ON reminder.reminderHash = token.tokenHash
           LEFT JOIN tokenAction ON token.tokenActionID = tokenAction.tokenActionID
             " . $f . "
            GROUP BY reminder.reminderID
            ORDER BY reminderTime,reminderType";
        $dballoc->query($q);
        while ($row = $dballoc->row()) {
            $reminder = new reminder();
            $reminder->read_db_record($dballoc);
            $rows[$row['reminderID']] = $row;
        }
        return $rows;
    }

    public static function get_list_html($type = null, $id = null)
    {
        global $TPL;
        $_REQUEST["type"] = $type;
        $_REQUEST["id"] = $id;
        $TPL["reminderRows"] = reminder::get_list($_REQUEST);
        $type and $TPL["returnToParent"] = $type;
        $type or $TPL["returnToParent"] = "list";
        include_template(__DIR__ . "/../templates/reminderListS.tpl");
    }
}
