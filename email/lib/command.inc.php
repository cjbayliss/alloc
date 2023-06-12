<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class command
{

    public $commands;
    public $email_receive;

    public static function get_help($type)
    {
        $message = ucwords($type) . " fields:";
        $fields = command::get_fields($type);
        foreach ((array)$fields as $k => $arr) {
            $message .= "\n      " . $k . ":\t" . $arr[1];
        }
        return $message;
    }

    public function get_fields($type = "all")
    {

        $comment = [
            "key"   => ["", "The comment thread key."],
            "ip"    => ["", "Add to a comment's interested parties. Eg: jon,jane@j.com,Hal Comp <hc@hc.com> ... can also remove eg: -jon"],
            "quiet" => ["", "Don't resend the email back out to anyone."],
        ];

        $item = [
            "tsid"       => ["timeSheetID", "time sheet that this item belongs to"],
            "date"       => ["dateTimeSheetItem", "time sheet item's date"],
            "duration"   => ["timeSheetItemDuration", "time sheet item's duration"],
            "unit"       => ["timeSheetItemDurationUnitID", "time sheet item's unit of duration eg: 1=hours 2=days 3=week 4=month 5=fixed"],
            "task"       => ["taskID", "ID of the time sheet item's task"],
            "rate"       => ["rate", "\$rate of the time sheet item's"],
            "private"    => ["commentPrivate", "privacy setting of the time sheet item's comment eg: 1=private 0=normal"],
            "comment"    => ["comment", "time sheet item comment"],
            "multiplier" => ["multiplier", "time sheet item multiplier eg: 1=standard 1.5=time-and-a-half 2=double-time 3=triple-time 0=no-charge"],
            "delete"     => ["", "set this to 1 to delete the time sheet item"],
            "time"       => ["", "Add some time to a time sheet. Auto-creates time sheet if none exist."],
        ];

        $task = [
            "status"           => ["taskStatus", "inprogress, notstarted, info, client, manager, invalid, duplicate, incomplete, complete; or: open, pending, closed"],
            "name"             => ["taskName", "task's title"],
            "assign"           => ["personID", "username of the person that the task is assigned to"],
            "manage"           => ["managerID", "username of the person that the task is managed by"],
            "desc"             => ["taskDescription", "task's long description"],
            "priority"         => ["priority", "1, 2, 3, 4 or 5; or one of Wishlist, Minor, Normal, Important or Critical"],
            "limit"            => ["timeLimit", "limit in hours for effort spend on this task"],
            "best"             => ["timeBest", "shortest estimate of how many hours of effort this task will take"],
            "likely"           => ["timeExpected", "most likely amount of hours of effort this task will take"],
            "worst"            => ["timeWorst", "longest estimate of how many hours of effort this task will take"],
            "estimator"        => ["estimatorID", "the person who created the estimates on this task"],
            "targetstart"      => ["dateTargetStart", "estimated date for work to start on this task"],
            "targetcompletion" => ["dateTargetCompletion", "estimated date for when this task should be finished"],
            "project"          => ["projectID", "task's project ID"],
            "type"             => ["taskTypeID", "Task, Fault, Message, Milestone or Parent"],
            "dupe"             => ["duplicateTaskID", "If the task status is duplicate, then this should be set to the task ID of the related dupe"],
            "pend"             => ["", "The task ID(s), commar separated, that block this task"],
            "reopen"           => ["", "Reopen the task on this date. To be used with --status=pending."],
            "task"             => ["", "A task ID, or the word 'new' to create a new task."],
            "dip"              => ["", "Add some default interested parties."],
            "tags"             => ["", "Add some tags, comma separated tags"],
        ];

        $reminder = [
            "date"            => ["reminderTime", ""],
            "subject"         => ["reminderSubject", ""],
            "body"            => ["reminderContent", ""],
            "frequency"       => ["reminderRecuringValue", ""],
            "frequency_units" => ["reminderRecuringInterval", ""],
            "active"          => ["reminderActive", ""],
            "notice"          => ["reminderAdvNoticeValue", ""],
            "notice_units"    => ["reminderAdvNoticeInterval", ""],
        ];

        $types = ["all" => array_merge($comment, $item, $task, $reminder), "comment" => $comment, "item" => $item, "task" => $task, "reminder" => $reminder];
        return $types[$type];
    }

    public function run_commands($commands = [], $email_receive = false)
    {

        $s = null;
        $m = null;
        if ($commands["command"] == "edit_timeSheetItem") {
            list($s, $m) = $this->edit_timeSheetItem($commands);
        } else if ($commands["command"] == "edit_task") {
            list($s, $m) = $this->edit_task($commands, $email_receive);
        } else if ($commands["command"] == "add_comment") {
            list($s, $m) = $this->add_comment($commands);
        } else if ($commands["command"] == "edit_reminder") {
            list($s, $m) = $this->edit_reminder($commands);
        }

        if ($commands["key"]) {
            list($s, $m) = $this->add_comment_via_email($commands, $email_receive);
        }

        if ($commands["time"]) {
            list($s, $m) = $this->add_time($commands, $email_receive);
        }

        if ($s && $m) {
            $status = $s;
            $message = $m;
        }

        // Status will be yay, msg, err or die, i.e. mirrored with the alloc-cli messaging system
        return ["status" => $status, "message" => $message];
    }

    public function edit_task($commands, $email_receive)
    {
        $changes = [];
        $rr_bits = [];
        $status = [];
        $message = [];
        $task_fields = $this->get_fields("task");
        // Task commands
        if ($commands["task"]) {
            unset($changes);

            $taskPriorities = config::get_config_item("taskPriorities") or $taskPriorities = [];
            foreach ($taskPriorities as $k => $v) {
                $priorities[strtolower($v["label"])] = $k;
            }

            $people_by_username = person::get_people_by_username();

            // Else edit/create the task ...
            $task = new task();
            if ($commands["task"] && strtolower($commands["task"]) != "new") {
                $task->set_id($commands["task"]);
                if (!$task->select()) {
                    alloc_error("Unable to select task with ID: " . $commands["task"]);
                }
            }

            foreach ($commands as $k => $v) {
                // transform from username to personID
                if ($k == "assign") {
                    $changes[$k] = "personID";
                    $v = $people_by_username[$v]["personID"];
                    $v or alloc_error("Unrecognized username.");
                }

                if ($k == "manage") {
                    $changes[$k] = "managerID";
                    $v = $people_by_username[$v]["personID"];
                    $v or alloc_error("Unrecognized username.");
                }

                if ($k == "estimator") {
                    $changes[$k] = "estimatorID";
                    $v = $people_by_username[$v]["personID"];
                    $v or alloc_error("Unrecognized username.");
                }

                // transform from priority label to priority ID
                if ($k == "priority" && !in_array($v, [1, 2, 3, 4, 5])) {
                    $v = $priorities[strtolower($v)];
                }

                // so that --type parent becomes --type Parent
                // mysql's referential integrity is case-insensitive :(
                if ($k == "type") {
                    $v = ucwords($v);
                }

                // Plug the value in
                if ($task_fields[$k][0]) {
                    $changes[$k] = $task_fields[$k][0];
                    $task->set_value($task_fields[$k][0], sprintf("%s", $v));
                }
            }

            if (isset($commands["pend"])) {
                $changes["pend"] = implode(",", (array)$task->get_pending_tasks());
            }

            if (isset($commands["reopen"])) {
                $reopen_rows = $task->get_reopen_reminders();
                unset($rr_bits);
                foreach ($reopen_rows as $rr) {
                    $rr_bits[] = $rr["reminderTime"];
                }
                $changes["reopen"] = implode(",", (array)$rr_bits);
            }

            if (isset($commands["tags"])) {
                $changes["tags"] = implode(",", $task->get_tags());
            }

            if (strtolower($commands["task"]) == "new") {
                if (!$commands["desc"] && is_object($email_receive)) {
                    $task->set_value("taskDescription", $email_receive->get_converted_encoding());
                }
            }

            $after_label = "After:  ";
            if (strtolower($commands["task"]) != "new") {
                $str = $this->condense_changes($changes, $task->row());
                $str and $status[] = "msg";
                $str and $message[] = "Before: " . $str;
            } else {
                $after_label = "Fields: ";
            }

            // Save task
            $err = $task->validate();
            if (!$err && $task->save()) {
                $task->select();

                if (isset($commands["pend"])) {
                    $task->add_pending_tasks($commands["pend"]);
                    $changes["pend"] = implode(",", (array)$task->get_pending_tasks());
                }
                if (isset($commands["reopen"])) {
                    $task->add_reopen_reminder($commands["reopen"]);
                    $reopen_rows = $task->get_reopen_reminders();
                    unset($rr_bits);
                    foreach ($reopen_rows as $rr) {
                        $rr_bits[] = $rr["reminderTime"];
                    }
                    $changes["reopen"] = implode(",", (array)$rr_bits);
                }

                if (isset($commands["tags"])) {
                    $task->add_tags([$commands["tags"]]);
                    $changes["tags"] = implode(",", $task->get_tags());
                }

                $str = $this->condense_changes($changes, $task->row());
                $str and $status[] = "msg";
                $str and $message[] = $after_label . $str;

                if ($commands["dip"]) {
                    interestedParty::add_remove_ips($commands["dip"], "task", $task->get_id(), $task->get_value("projectID"));
                }

                if (strtolower($commands["task"]) == "new") {
                    $status[] = "yay";
                    $message[] = "Task " . $task->get_id() . " created.";
                } else {
                    $status[] = "yay";
                    $message[] = "Task " . $task->get_id() . " updated.";
                }

                // Problems
            } else {
                alloc_error("Problem updating task: " . implode("\n", (array)$err));
            }
        }
        return [$status, $message];
    }

    public function edit_timeSheetItem($commands)
    {
        $changes = [];
        $status = [];
        $message = [];
        $err = [];
        $item_fields = $this->get_fields("item");

        // Time Sheet Item commands
        if ($commands["item"]) {
            $timeSheetItem = new timeSheetItem();
            if ($commands["item"] && strtolower($commands["item"] != "new")) {
                $timeSheetItem->set_id($commands["item"]);
                if (!$timeSheetItem->select()) {
                    alloc_error("Unable to select time sheet item with ID: " . $commands["item"]);
                }
            }
            $timeSheet = $timeSheetItem->get_foreign_object("timeSheet");
            $timeSheetItem->currency = $timeSheet->get_value("currencyTypeID");
            $timeSheetItem->set_value("rate", $timeSheetItem->get_value("rate", DST_HTML_DISPLAY));

            foreach ($commands as $k => $v) {
                // Validate/coerce the fields
                if ($k == "unit") {
                    $changes[$k] = "timeSheetItemDurationUnitID";
                    in_array($v, [1, 2, 3, 4, 5]) or $err[] = "Invalid unit. Try a number from 1-5.";
                } else if ($k == "task") {
                    $changes[$k] = "taskID";
                    $t = new task();
                    $t->set_id($v);
                    $t->select();
                    is_object($timeSheet) && $timeSheet->get_id() && $t->get_value("projectID") != $timeSheet->get_value("projectID") and $err[] = "Invalid task. Task belongs to different project.";
                }

                // Plug the value in
                if ($item_fields[$k][0]) {
                    $changes[$k] = $item_fields[$k][0];
                    $timeSheetItem->set_value($item_fields[$k][0], sprintf("%s", $v));
                }
            }

            $after_label2 = "After:  ";
            if (strtolower($commands["item"]) != "new") {
                $str = $this->condense_changes($changes, $timeSheetItem->row());
                $str and $status[] = "msg";
                $str and $message[] = "Before: " . $str;
            } else {
                $after_label2 = "Fields: ";
            }

            if ($commands["delete"]) {
                $id = $timeSheetItem->get_id();
                $timeSheetItem->delete();
                $status[] = "yay";
                $message[] = "Time sheet item " . $id . " deleted.";

                // Save timeSheetItem
            } else if (!$err && $commands["item"] && $timeSheetItem->save()) {
                $timeSheetItem->select();
                $str = $this->condense_changes($changes, $timeSheetItem->row());
                $str and $status[] = "msg";
                $str and $message[] = $after_label2 . $str;
                $status[] = "yay";
                if (strtolower($commands["item"]) == "new") {
                    $message[] = "Time sheet item " . $timeSheetItem->get_id() . " created.";
                } else {
                    $message[] = "Time sheet item " . $timeSheetItem->get_id() . " updated.";
                }

                // Problems
            } else if ($err && $commands["item"]) {
                alloc_error("Problem updating time sheet item: " . implode("\n", (array)$err));
            }
        }
        return [$status, $message];
    }

    public function edit_reminder($commands)
    {
        $status = [];
        $message = [];
        $id = $commands["reminder"];
        $options = $commands;
        $reminder = new reminder();
        if ($id and $id != "new") {
            $reminder->set_id($id);
            $reminder->select();
        } else if ($id == "new") {
            // extra sanity checks, partially filled in reminder isn't much good
            if (!$options['date'] || !$options['subject'] || !$options['recipients']) {
                $status[] = "err";
                $message[] = "Missing one of date, subject or recipients.";
                return [$status, $message];
            }

            if ($options['task']) {
                $reminder->set_value('reminderType', 'task');
                $reminder->set_value('reminderLinkID', $options['task']);
            } else if ($options['project']) {
                $reminder->set_value('reminderType', 'project');
                $reminder->set_value('reminderLinkID', $options['project']);
            } else if ($options['client']) {
                $reminder->set_value('reminderLinkID', $options['client']);
                $reminder->set_value('reminderType', 'client');
            }
        }

        $units_of_time = [
            'h' => 'Hour',
            'd' => 'Day',
            'w' => 'Week',
            'm' => 'Month',
            'y' => 'Year',
        ];

        // Tear apart the frequency bits
        if ($options['frequency']) {
            list($freq, $units) = sscanf($options['frequency'], "%d%c");
            $options['frequency'] = $freq;
            $options['frequency_units'] = $units_of_time[strtolower($units)];
        }
        if ($options['notice']) {
            list($freq, $units) = sscanf($options['notice'], "%d%c");
            $options['notice'] = $freq;
            $options['notice_units'] = $units_of_time[strtolower($units)];
        }

        $fields = $this->get_fields("reminder");
        foreach ($fields as $s => $d) {
            if ($options[$s]) {
                $reminder->set_value($d[0], $options[$s]);
            }
        }

        if (!$reminder->get_value("reminderRecuringInterval")) {
            $reminder->set_value("reminderRecuringInterval", "No");
        }

        if (!$reminder->get_value("reminderAdvNoticeInterval")) {
            $reminder->set_value("reminderAdvNoticeInterval", "No");
        }

        $reminder->save();

        // Deal with recipients
        if ($options['recipients']) {
            list($_x, $recipients) = $reminder->get_recipient_options();
            if ($options['recipients']) {
                $recipients = array_unique(array_merge($recipients, $options['recipients']));
            }
            if ($options['recipients_remove']) {
                $recipients = array_diff($recipients, $options['recipients_remove']);
            }
            $reminder->update_recipients($recipients);
        }

        if (is_object($reminder) && $reminder->get_id()) {
            $status[] = "yay";
            $message[] = "Reminder " . $reminder->get_id() . " saved.";
        } else {
            $status[] = "err";
            $message[] = "Reminder not saved.";
        }
        return [$status, $message];
    }

    public function add_time($commands, $email_receive)
    {
        $current_user = &singleton("current_user");
        if ($commands["time"]) {
            // CLI passes time along as a string, email passes time along as an array
            if (!is_array($commands["time"])) {
                $t = $commands["time"];
                unset($commands["time"]);
                $commands["time"][] = $t;
            }

            foreach ((array)$commands["time"] as $time) {
                $t = timeSheetItem::parse_time_string($time);

                if (is_numeric($t["duration"]) && $current_user->get_id()) {
                    $timeSheet = new timeSheet();
                    is_object($email_receive) and $t["msg_uid"] = $email_receive->msg_uid;
                    $tsi_row = $timeSheet->add_timeSheetItem($t);
                    $status[] = $tsi_row["status"];
                    $message[] = $tsi_row["message"];
                }
            }
        }
        return [$status, $message];
    }

    public function add_comment_via_email($commands, $email_receive)
    {
        // If there's Key in the email, then add a comment with the contents of
        // the email.
        $token = new token();
        if ($commands["key"] && $token->set_hash($commands["key"])) {
            list($entity, $method) = $token->execute();
            if (is_object($entity) && $method == "add_comment_from_email") {
                $c = comment::add_comment_from_email($email_receive, $entity);

                if (is_object($c) && $c->get_id()) {
                    $quiet = interestedParty::adjust_by_email_subject($email_receive, $entity);

                    if ($commands["ip"]) {
                        // FIXME: does this do something?
                        $rtn = interestedParty::add_remove_ips($commands["ip"], $entity->classname, $entity->get_id(), $entity->get_project_id());
                    }

                    if (!$quiet) {
                        comment::send_comment($c->get_id(), ["interested"], $email_receive);
                    }
                }
            }
            // Bad or missing key, then error
        } else if ($email_receive) {
            alloc_error("Bad or missing key. Unable to process email.");
        }
    }

    public function add_comment($commands)
    {
        $commentID = comment::add_comment($commands["entity"], $commands["entityID"], $commands["comment_text"]);

        // add interested parties
        foreach ((array)$commands["ip"] as $k => $info) {
            $info["entity"] = "comment";
            $info["entityID"] = $commentID;
            interestedParty::add_interested_party($info);
        }

        $emailRecipients = [];
        $emailRecipients[] = "interested";
        if (defined("ALLOC_DEFAULT_FROM_ADDRESS") && ALLOC_DEFAULT_FROM_ADDRESS) {
            list($from_address, $from_name) = parse_email_address(ALLOC_DEFAULT_FROM_ADDRESS);
            $emailRecipients[] = $from_address;
        }

        // Re-email the comment out
        comment::send_comment($commentID, $emailRecipients);
    }

    public function condense_changes($changes, $fields)
    {
        $str = null;
        $sep = "";
        foreach ((array)$changes as $label => $field) {
            $v = $fields[$field] or $v = $field;
            $str .= $sep . $label . ": " . $v;
            $sep = ", ";
        }
        return $str;
    }
}
