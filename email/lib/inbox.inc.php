<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class inbox extends DatabaseEntity
{

    public static function change_current_user($from)
    {
        [$from_address, $from_name] = parse_email_address($from);
        $person = new person();
        $personID = $person->find_by_email($from_address);
        $personID or $personID = $person->find_by_name($from_name);

        // If we've determined a personID from the $from_address
        if ($personID) {
            $current_user = new person();
            $current_user->load_current_user($personID);
            singleton("current_user", $current_user);
            return true;
        }
        return false;
    }

    public static function verify_hash($id, $hash)
    {
        $info = inbox::get_mail_info();
        $emailreceive = new email_receive($info);
        $emailreceive->open_mailbox($info["folder"], OP_HALFOPEN | OP_READONLY);
        $emailreceive->set_msg($id);
        $emailreceive->get_msg_header();
        $rtn = ($hash == md5($emailreceive->mail_headers["date"]
            . $emailreceive->get_printable_from_address()
            . $emailreceive->mail_headers["subject"]));
        $emailreceive->close();
        return $rtn;
    }

    public static function archive_email($req = [])
    {
        global $TPL;
        $info = inbox::get_mail_info();
        $emailreceive = new email_receive($info);
        $emailreceive->open_mailbox($info["folder"]);
        $mailbox = "INBOX/archive" . date("Y");
        $emailreceive->create_mailbox($mailbox) and $TPL["message_good"][] = "Created mailbox: " . $mailbox;
        $emailreceive->move_mail($req["id"], $mailbox) and $TPL["message_good"][] = "Moved email " . $req["id"] . " to " . $mailbox;
        $emailreceive->close();
    }

    public static function download_email($req = [])
    {
        $new = null;
        global $TPL;
        $info = inbox::get_mail_info();
        $emailreceive = new email_receive($info);
        $emailreceive->open_mailbox($info["folder"], OP_HALFOPEN | OP_READONLY);
        $emailreceive->set_msg($req["id"]);
        $new_nums = $emailreceive->get_new_email_msg_uids();
        in_array($req["id"], (array)$new_nums) and $new = true;
        [$h, $b] = $emailreceive->get_raw_header_and_body();
        $new and $emailreceive->set_unread(); // might have to "unread" the email, if it was new, i.e. set it back to new
        $emailreceive->close();
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="email' . $req["id"] . '.txt"');
        echo $h . $b;
        exit();
    }

    public static function process_email($req = [])
    {
        global $TPL;
        $info = inbox::get_mail_info();
        $emailreceive = new email_receive($info);
        $emailreceive->open_mailbox($info["folder"]);
        $emailreceive->set_msg($req["id"]);
        $emailreceive->get_msg_header();
        inbox::process_one_email($emailreceive);
        $emailreceive->expunge();
        $emailreceive->close();
    }

    public static function process_email_to_task($req = [])
    {
        global $TPL;
        $info = inbox::get_mail_info();
        $emailreceive = new email_receive($info);
        $emailreceive->open_mailbox($info["folder"]);
        $emailreceive->set_msg($req["id"]);
        $emailreceive->get_msg_header();
        inbox::convert_email_to_new_task($emailreceive);
        $emailreceive->expunge();
        $emailreceive->close();
    }

    public static function process_one_email($email_receive)
    {
        $failed = null;
        $TPL = [];
        $current_user = &singleton("current_user");
        $orig_current_user = &$current_user;

        // wrap db queries in a transaction
        $allocDatabase = new AllocDatabase();
        $allocDatabase->start_transaction();

        inbox::change_current_user($email_receive->mail_headers["from"]);
        $current_user = &singleton("current_user");
        $email_receive->save_email();

        // Run any commands that have been embedded in the email
        $command = new command();
        $fields = $command->get_fields();
        $commands = $email_receive->get_commands($fields);

        try {
            $command->run_commands($commands, $email_receive);
        } catch (Exception $e) {
            $current_user = &$orig_current_user;
            singleton("current_user", $current_user);
            $allocDatabase->query("ROLLBACK");
            $failed = true;
            throw new Exception($e);
        }

        // Commit the db, and move the email into its storage location eg: INBOX.task1234
        if (!$failed && !$TPL["message"]) {
            $allocDatabase->commit();
            $email_receive->mark_seen();
            $email_receive->archive();
        }

        // Put current_user back to normal
        $current_user = &$orig_current_user;
        singleton("current_user", $current_user);
    }

    public static function convert_email_to_new_task($email_receive, $change_user = false)
    {
        $personID = null;
        $ip = [];
        global $TPL;
        $current_user = &singleton("current_user");
        $orig_current_user = &$current_user;

        if ($change_user) {
            inbox::change_current_user($email_receive->mail_headers["from"]);
            $current_user = &singleton("current_user");
            if (is_object($current_user) && method_exists($current_user, "get_id") && $current_user->get_id()) {
                $personID = $current_user->get_id();
            }
        }

        $email_receive->save_email();

        // Subject line is name, email body is body
        $task = new Task();
        $task->set_value("taskName", $email_receive->mail_headers["subject"]);
        $task->set_value("taskDescription", $email_receive->mail_text);
        $task->set_value("priority", "3");
        $task->set_value("taskTypeID", "Task");
        $task->save();

        if (!$TPL["message"] && $task->get_id()) {
            $dir = ATTACHMENTS_DIR . DIRECTORY_SEPARATOR . "task" . DIRECTORY_SEPARATOR . $task->get_id();
            if (!is_dir($dir)) {
                mkdir($dir);
                foreach ((array)$email_receive->mimebits as $file) {
                    $fh = fopen($dir . DIRECTORY_SEPARATOR . $file["name"], "wb");
                    fputs($fh, $file["blob"]);
                    fclose($fh);
                }
            }
            rmdir_if_empty(ATTACHMENTS_DIR . DIRECTORY_SEPARATOR . "task" . DIRECTORY_SEPARATOR . $task->get_id());

            $msg = "Created task " . $task->get_task_link(["prefixTaskID" => true]) . " and moved the email to the task's mail folder.";
            $mailbox = "INBOX/task" . $task->get_id();
            $email_receive->create_mailbox($mailbox) and $msg .= "\nCreated mailbox: " . $mailbox;
            $email_receive->archive($mailbox) and $msg .= "\nMoved email to " . $mailbox;
            $msg and $TPL["message_good_no_esc"][] = $msg;

            [$from_address, $from_name] = parse_email_address($email_receive->mail_headers["from"]);
            $ip["emailAddress"] = $from_address;
            $ip["name"] = $from_name;
            $ip["personID"] = $personID;
            $ip["entity"] = "task";
            $ip["entityID"] = $task->get_id();
            InterestedParty::add_interested_party($ip);
        }
        // Put current_user back to normal
        $current_user = &$orig_current_user;
        singleton("current_user", $current_user);
    }

    public static function attach_email_to_existing_task($req = [])
    {
        $recipients = [];
        global $TPL;
        $info = inbox::get_mail_info();
        $current_user = &singleton("current_user");
        $orig_current_user = &$current_user;
        $req["taskID"] = sprintf("%d", $req["taskID"]);

        $task = new Task();
        $task->set_id($req["taskID"]);
        if ($task->select()) {
            $emailreceive = new email_receive($info);
            $emailreceive->open_mailbox($info["folder"]);
            $emailreceive->set_msg($req["id"]);
            $emailreceive->get_msg_header();
            $emailreceive->save_email();

            $c = comment::add_comment_from_email($emailreceive, $task);
            $commentID = $c->get_id();
            $commentID and $TPL["message_good_no_esc"][] = "Created comment " . $commentID . " on task " . $task->get_task_link(["prefixTaskID" => true]);

            // Possibly change the identity of current_user
            [$from_address, $from_name] = parse_email_address($emailreceive->mail_headers["from"]);
            $person = new person();
            $personID = $person->find_by_email($from_address);
            $personID or $personID = $person->find_by_name($from_name);
            if ($personID) {
                $current_user = new person();
                $current_user->load_current_user($personID);
                singleton("current_user", $current_user);
            }

            // swap back to normal user
            $current_user = &$orig_current_user;
            singleton("current_user", $current_user);

            // manually add task manager and assignee to ip list
            $extraips = [];
            if ($task->get_value("personID")) {
                $p = new person($task->get_value("personID"));
                if ($p->get_value("emailAddress")) {
                    $extraips[$p->get_value("emailAddress")]["name"] = $p->get_name();
                    $extraips[$p->get_value("emailAddress")]["role"] = "assignee";
                    $extraips[$p->get_value("emailAddress")]["personID"] = $task->get_value("personID");
                    $extraips[$p->get_value("emailAddress")]["selected"] = 1;
                }
            }
            if ($task->get_value("managerID")) {
                $p = new person($task->get_value("managerID"));
                if ($p->get_value("emailAddress")) {
                    $extraips[$p->get_value("emailAddress")]["name"] = $p->get_name();
                    $extraips[$p->get_value("emailAddress")]["role"] = "manager";
                    $extraips[$p->get_value("emailAddress")]["personID"] = $task->get_value("managerID");
                    $extraips[$p->get_value("emailAddress")]["selected"] = 1;
                }
            }

            // add all the other interested parties
            $ips = InterestedParty::get_interested_parties("task", $req["taskID"], $extraips);
            foreach ((array)$ips as $k => $inf) {
                $inf["entity"] = "comment";
                $inf["entityID"] = $commentID;
                $inf["email"] and $inf["emailAddress"] = $inf["email"];
                if ($req["emailto"] == "internal" && !$inf["external"] && !$inf["clientContactID"]) {
                    $id = InterestedParty::add_interested_party($inf);
                    $recipients[] = $inf["name"] . " " . add_brackets($k);
                } else if ($req["emailto"] == "default") {
                    $id = InterestedParty::add_interested_party($inf);
                    $recipients[] = $inf["name"] . " " . add_brackets($k);
                }
            }

            $recipients and $recipients = implode(", ", (array)$recipients);
            $recipients and $TPL["message_good"][] = "Sent email to " . $recipients;

            // Re-email the comment out
            comment::send_comment($commentID, ["interested"], $emailreceive);

            // File email away in the task's mail folder
            $mailbox = "INBOX/task" . $task->get_id();
            $emailreceive->create_mailbox($mailbox) and $TPL["message_good"][] = "Created mailbox: " . $mailbox;
            $emailreceive->move_mail($req["id"], $mailbox) and $TPL["message_good"][] = "Moved email " . $req["id"] . " to " . $mailbox;
            $emailreceive->close();
        }
    }

    public static function unread_email($req = [])
    {
        global $TPL;
        $info = inbox::get_mail_info();
        $emailreceive = new email_receive($info);
        $emailreceive->open_mailbox($info["folder"]);
        $emailreceive->set_msg($req["id"]);
        $emailreceive->set_unread();
        $emailreceive->close();
    }

    public static function read_email($req = [])
    {
        global $TPL;
        $info = inbox::get_mail_info();
        $emailreceive = new email_receive($info);
        $emailreceive->open_mailbox($info["folder"]);
        $emailreceive->set_msg($req["id"]);
        [$h, $b] = $emailreceive->get_raw_header_and_body();
        $emailreceive->close();
    }

    public static function get_mail_info()
    {
        $info = [];
        $info["host"] = config::get_config_item("allocEmailHost");
        $info["port"] = config::get_config_item("allocEmailPort");
        $info["username"] = config::get_config_item("allocEmailUsername");
        $info["password"] = config::get_config_item("allocEmailPassword");
        $info["protocol"] = config::get_config_item("allocEmailProtocol");
        $info["folder"] = config::get_config_item("allocEmailFolder");
        return $info;
    }

    public static function get_list()
    {
        $rows = [];
        // Get list of emails
        $info = inbox::get_mail_info();
        $emailreceive = new email_receive($info);
        $emailreceive->open_mailbox($info["folder"], OP_HALFOPEN | OP_READONLY);
        $emailreceive->check_mail();
        $new_nums = $emailreceive->get_new_email_msg_uids();
        $msg_nums = $emailreceive->get_all_email_msg_uids();

        if ($msg_nums) {
            foreach ($msg_nums as $msg_num) {
                $row = [];
                $emailreceive->set_msg($msg_num);
                $emailreceive->get_msg_header();
                $row["from"] = $emailreceive->get_printable_from_address();
                in_array($msg_num, (array)$new_nums) and $row["new"] = true;

                $row["id"] = $msg_num;
                $row["date"] = $emailreceive->mail_headers["date"];
                $row["subject"] = $emailreceive->mail_headers["subject"];
                $rows[] = $row;
            }
        }
        $emailreceive->close();
        return $rows;
    }
}
