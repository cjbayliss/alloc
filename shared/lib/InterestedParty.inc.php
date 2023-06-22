<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class InterestedParty extends DatabaseEntity
{
    public $data_table = "interestedParty";

    public $key_field = "interestedPartyID";

    public $data_fields = [
        "entityID",
        "entity",
        "fullName",
        "emailAddress",
        "personID",
        "clientContactID",
        "external",
        "interestedPartyCreatedUser",
        "interestedPartyCreatedTime",
        "interestedPartyActive",
    ];

    public function delete()
    {
        $this->set_value("interestedPartyActive", 0);
        $this->save();
    }

    public function is_owner($ignored = null)
    {
        $current_user = &singleton("current_user");
        return same_email_address($this->get_value("emailAddress"), $current_user->get_value("emailAddress"));
    }

    public function save()
    {
        $this->set_value("emailAddress", str_replace(["<", ">"], "", $this->get_value("emailAddress")));
        return parent::save();
    }

    public static function exists($entity, $entityID, $email)
    {
        $email = str_replace(["<", ">"], "", $email);
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query("SELECT *
                      FROM interestedParty
                     WHERE entityID = %d
                       AND entity = '%s'
                       AND emailAddress = '%s'
                   ", $entityID, $entity, $email);
        return $allocDatabase->row();
    }

    public function active($entity, $entityID, $email)
    {
        [$email, $name] = parse_email_address($email);
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query("SELECT *
                      FROM interestedParty
                     WHERE entityID = %d
                       AND entity = '%s'
                       AND emailAddress = '%s'
                       AND interestedPartyActive = 1
                   ", $entityID, $entity, $email);
        return $allocDatabase->row();
    }

    public static function make_interested_parties($entity, $entityID, $encoded_parties = [])
    {
        $ipIDs = [];
        // Nuke entries from interestedParty
        $allocDatabase = new AllocDatabase();
        $allocDatabase->start_transaction();

        // Add entries to interestedParty
        if (is_array($encoded_parties)) {
            foreach ($encoded_parties as $encoded_party) {
                $info = (new InterestedParty())->get_decoded_interested_party_identifier($encoded_party);
                $info["entity"] = $entity;
                $info["entityID"] = $entityID;
                $info["emailAddress"] ??= $info["email"];
                $ipIDs[] = InterestedParty::add_interested_party($info);
            }
        }

        $q = unsafe_prepare("UPDATE interestedParty
                         SET interestedPartyActive = 0
                       WHERE entity = '%s'
                         AND entityID = %d", $entity, $entityID);
        $ipIDs && ($q .= " AND " . sprintf_implode(" AND ", "interestedPartyID != %d", $ipIDs));
        $allocDatabase->query($q);

        $allocDatabase->commit();
    }

    public static function abbreviate($str)
    {
        $rtn = [];
        $bits = explode(",", $str);
        foreach ((array)$bits as $bit) {
            if ($bit !== '' && $bit !== '0') {
                [$name, $address] = explode("<", $bit);
                $rtn[] = Page::htmlentities(trim($name)) . "<span class='hidden'> " . Page::htmlentities("<" . $address) . "</span>";
            }
        }

        if ($rtn !== []) {
            return "<span>" . implode(", ", $rtn) . "&nbsp;&nbsp;<a href='' onClick='$(this).parent().find(\"span\").slideToggle(\"fast\"); return false;'>Show</a></span>";
        }
    }

    public function get_interested_parties_string($entity, $entityID)
    {
        $q = unsafe_prepare("SELECT get_interested_parties_string('%s',%d) as parties", $entity, $entityID);
        $allocDatabase = new AllocDatabase();
        $row = $allocDatabase->qr($q);
        return $row["parties"];
    }

    public static function sort_interested_parties($a, $b)
    {
        return strtolower($a["name"]) > strtolower($b["name"]);
    }

    public static function get_interested_parties($entity, $entityID = false, $ops = [], $dont_select = false)
    {
        $rtn = [];

        if ($entityID) {
            $allocDatabase = new AllocDatabase();
            $q = unsafe_prepare("SELECT *
                           FROM interestedParty
                          WHERE entity='%s'
                            AND entityID = %d
                         ", $entity, $entityID);
            $allocDatabase->query($q);
            while ($allocDatabase->row()) {
                $ops[$allocDatabase->f("emailAddress")]["name"] = $allocDatabase->f("fullName");
                $ops[$allocDatabase->f("emailAddress")]["role"] = "interested";
                $ops[$allocDatabase->f("emailAddress")]["selected"] = $allocDatabase->f("interestedPartyActive") && !$dont_select;
                $ops[$allocDatabase->f("emailAddress")]["personID"] = $allocDatabase->f("personID");
                $ops[$allocDatabase->f("emailAddress")]["clientContactID"] = $allocDatabase->f("clientContactID");
                $ops[$allocDatabase->f("emailAddress")]["external"] = $allocDatabase->f("external");
            }
        }

        if (is_array($ops)) {
            foreach ($ops as $email => $info) {
                // if there is an @ symbol in email address
                if (stristr($email, "@")) {
                    $info["email"] = $email;
                    $info["identifier"] = InterestedParty::get_encoded_interested_party_identifier($info);
                    $rtn[$email] = $info;
                }
            }

            uasort($rtn, ["InterestedParty", "sort_interested_parties"]);
        }

        return $rtn;
    }

    public static function get_encoded_interested_party_identifier($info = []): string
    {
        return base64_encode(serialize($info));
    }

    public function get_decoded_interested_party_identifier($blob)
    {
        return unserialize(base64_decode($blob));
    }

    public static function get_interested_parties_html($parties = [])
    {
        $str = "";
        $current_user = &singleton("current_user");
        if (is_object($current_user) && $current_user->get_id()) {
            $current_user_email = $current_user->get_value("emailAddress");
        }

        $counter = 0;
        foreach ((array)$parties as $email => $info) {
            $info["name"] || ($info["name"] = $email);
            if ($info["name"]) {
                unset($sel, $c);
                ++$counter;

                if ($current_user_email && same_email_address($current_user_email, $email)) {
                    $sel = " checked";
                }

                $c = "";
                if (isset($info["selected"])) {
                    $sel = " checked";
                }

                if (!isset($info["internal"]) && isset($info["external"])) {
                    $c .= " warn";
                }

                $str .= '<span width="150px" class="nobr ' . $c . '" id="td_ect_' . $counter . '" style="float:left; width:150px; margin-bottom:5px;">';
                $str .= '<input id="ect_' . $counter . '" type="checkbox" name="commentEmailRecipients[]" value="' . $info["identifier"] . '"' . $sel . "> ";
                $str .= '<label for="ect_' . $counter . '" title="' . $info["name"] . " &lt;" . $info["email"] . '&gt;">' . Page::htmlentities($info["name"]) . "</label></span>";
            }
        }

        return $str;
    }

    public static function delete_interested_party($entity, $entityID, $email)
    {
        // Delete existing entries
        [$email, $name] = parse_email_address($email);
        $row = (new InterestedParty())->active($entity, $entityID, $email);
        if ($row) {
            $interestedParty = new InterestedParty();
            $interestedParty->read_row_record($row);
            $interestedParty->delete();
        }
    }

    public static function add_interested_party($data)
    {
        static $people;
        $data["emailAddress"] = str_replace(["<", ">"], "", $data["emailAddress"]);
        // Add new entry

        $interestedParty = new InterestedParty();
        $existing = InterestedParty::exists($data["entity"], $data["entityID"], $data["emailAddress"]);
        if ($existing) {
            $interestedParty->set_id($existing["interestedPartyID"]);
            $interestedParty->select();
        }

        $interestedParty->set_value("entity", $data["entity"]);
        $interestedParty->set_value("entityID", $data["entityID"]);
        $interestedParty->set_value("fullName", $data["name"]);
        $interestedParty->set_value("emailAddress", $data["emailAddress"]);
        $interestedParty->set_value("interestedPartyActive", 1);
        if ($data["personID"]) {
            $interestedParty->set_value("personID", $data["personID"]);
            $interestedParty->set_value("fullName", person::get_fullname($data["personID"]));
        } else {
            $people || ($people = &get_cached_table("person"));
            foreach ($people as $personID => $p) {
                if ($data["emailAddress"] && same_email_address($p["emailAddress"], $data["emailAddress"])) {
                    $interestedParty->set_value("personID", $personID);
                    $interestedParty->set_value("fullName", $p["name"]);
                }
            }
        }

        $extra_interested_parties = config::get_config_item("defaultInterestedParties");
        if (!$interestedParty->get_value("personID") && !in_array($data["emailAddress"], (array)$extra_interested_parties)) {
            $interestedParty->set_value("external", 1);
            $q = unsafe_prepare("SELECT * FROM clientContact WHERE clientContactEmail = '%s'", $data["emailAddress"]);
            $allocDatabase = new AllocDatabase();
            $allocDatabase->query($q);
            if ($row = $allocDatabase->row()) {
                $interestedParty->set_value("clientContactID", $row["clientContactID"]);
                $interestedParty->set_value("fullName", $row["clientContactName"]);
            }
        }

        $interestedParty->save();
        return $interestedParty->get_id();
    }

    public static function adjust_by_email_subject($email_receive, $e)
    {
        $quiet = null;
        $current_user = &singleton("current_user");

        $entity = $e->classname;
        $entityID = $e->get_id();
        $subject = trim($email_receive->mail_headers["subject"]);
        $body = $email_receive->get_converted_encoding();
        $msg_uid = $email_receive->msg_uid;
        [$emailAddress, $fullName] = parse_email_address($email_receive->mail_headers["from"]);
        [$personID, $clientContactID, $fullName] = comment::get_person_and_client($emailAddress, $fullName, $e->get_project_id());

        // Load up the parent object that this comment refers to, be it task or timeSheet etc
        if ($entity == "comment" && $entityID) {
            $comment = new comment();
            $comment->set_id($entityID);
            $comment->select();
            $object = $comment->get_parent_object();
        } elseif (class_exists($entity) && $entityID) {
            $object = new $entity;
            $object->set_id($entityID);
            $object->select();
        }

        // If we're doing subject line magic, then we're only going to do it with
        // subject lines that have a {Key:fdsFFeSD} in them.
        preg_match("/\{Key:[A-Za-z0-9]{8}\}(.*)\s*$/i", $subject, $m);
        $commands = explode(" ", trim($m[1]));

        foreach ((array)$commands as $command) {
            $command = strtolower($command);
            [$command, $command2] = explode(":", $command); // for eg: duplicate:1234

            // If "quiet" in the subject line, then the email/comment won't be re-emailed out again
            if ($command == "quiet") {
                $quiet = true;
                // To unsubscribe from this conversation
            } elseif ($command == "unsub" || $command == "unsubscribe") {
                if ((new InterestedParty())->active($entity, $entityID, $emailAddress)) {
                    InterestedParty::delete_interested_party($entity, $entityID, $emailAddress);
                }

                // To subscribe to this conversation
            } elseif ($command == "sub" || $command == "subscribe") {
                $ip = InterestedParty::exists($entity, $entityID, $emailAddress);
                if (!$ip) {
                    $data = [
                        "entity"          => $entity,
                        "entityID"        => $entityID,
                        "fullName"        => $fullName,
                        "emailAddress"    => $emailAddress,
                        "personID"        => $personID,
                        "clientContactID" => $clientContactID,
                    ];
                    InterestedParty::add_interested_party($data);
                    // Else reactivate existing IP
                } elseif (!(new InterestedParty())->active($entity, $entityID, $emailAddress)) {
                    $interestedParty = new InterestedParty();
                    $interestedParty->set_id($ip["interestedPartyID"]);
                    $interestedParty->select();
                    $interestedParty->set_value("interestedPartyActive", 1);
                    $interestedParty->save();
                }

                // If there's a number/duration then add some time to a time sheet
            } elseif (is_object($current_user) && $current_user->get_id() && preg_match("/([\.\d]+)/i", $command, $m)) {
                $duration = $m[1];
                if (is_numeric($duration) && (is_object($object) && $object->classname == "task" && $object->get_id() && $current_user->get_id())) {
                    $timeSheet = new timeSheet();
                    $tsi_row = $timeSheet->add_timeSheetItem([
                        "taskID"     => $object->get_id(),
                        "duration"   => $duration,
                        "comment"    => $body,
                        "msg_uid"    => $msg_uid,
                        "msg_id"     => $email_receive->mail_headers["message-id"],
                        "multiplier" => 1,
                    ]);
                    $timeUnit = new timeUnit();
                    $units = $timeUnit->get_assoc_array("timeUnitID", "timeUnitLabelA");
                    $unitLabel = $units[$tsi_row["timeSheetItemDurationUnitID"]];
                }

                // Otherwise assume it's a status change
            } elseif (is_object($current_user) && $current_user->get_id() && $command) {
                if (is_object($object) && $object->get_id()) {
                    $object->set_value("taskStatus", $command);
                    if ($command2 && preg_match("/dup/i", $command)) {
                        $object->set_value("duplicateTaskID", $command2);
                    } elseif ($command2 && preg_match("/tasks/i", $command)) {
                        $object->add_pending_tasks($command2);
                    }

                    $object->save();
                }
            }
        }

        return $quiet;
    }

    public function get_list_filter($filter = [])
    {
        $sql = [];
        $filter["emailAddress"] = str_replace(["<", ">"], "", $filter["emailAddress"]);
        $filter["emailAddress"] && ($sql[] = unsafe_prepare("(interestedParty.emailAddress LIKE '%%%s%%')", $filter["emailAddress"]));
        $filter["fullName"] && ($sql[] = unsafe_prepare("(interestedParty.fullName LIKE '%%%s%%')", $filter["fullName"]));
        $filter["personID"] && ($sql[] = unsafe_prepare("(interestedParty.personID = %d)", $filter["personID"]));
        $filter["clientContactID"] && ($sql[] = unsafe_prepare("(interestedParty.clientContactID = %d)", $filter["clientContactID"]));
        $filter["entity"] && ($sql[] = unsafe_prepare("(interestedParty.entity = '%s')", $filter["entity"]));
        $filter["entityID"] && ($sql[] = unsafe_prepare("(interestedParty.entityID = %d)", $filter["entityID"]));
        $filter["active"] && ($sql[] = unsafe_prepare("(interestedParty.interestedPartyActive = %d)", $filter["active"]));
        $filter["taskID"] && ($sql[] = unsafe_prepare("(comment.commentMaster='task' AND comment.commentMasterID=%d)", $filter["taskID"]));
        return $sql;
    }

    public static function get_list($_FORM)
    {

        $join = null;
        $f = null;
        $groupby = null;
        $rows = [];
        if ($_FORM["taskID"]) {
            $join = " LEFT JOIN comment ON ((interestedParty.entity = comment.commentType AND interestedParty.entityID = comment.commentLinkID) OR (interestedParty.entity = 'comment' and interestedParty.entityID = comment.commentID))";
            $groupby = ' GROUP BY interestedPartyID';
        }

        $filter = (new InterestedParty())->get_list_filter($_FORM);
        $_FORM["return"] || ($_FORM["return"] = "html");

        if (is_array($filter) && count($filter)) {
            $f = " WHERE " . implode(" AND ", $filter);
        }

        $allocDatabase = new AllocDatabase();
        $q = "SELECT * FROM interestedParty " . $join . $f . $groupby;

        $allocDatabase->query($q);
        while ($row = $allocDatabase->next_record()) {
            $interestedParty = new InterestedParty();
            $interestedParty->read_db_record($allocDatabase);
            $rows[$interestedParty->get_id()] = $row;
        }

        return (array)$rows;
    }

    public static function is_external($entity, $entityID)
    {
        $ips = InterestedParty::get_interested_parties($entity, $entityID);
        foreach ($ips as $email => $info) {
            if (!$info["external"]) {
                continue;
            }

            if (!$info["selected"]) {
                continue;
            }

            return true;
        }
    }

    public function expand_ip($ip, $projectID = null)
    {

        $people = [];
        // jon               alloc username
        // jon@jon.com       alloc username or client or stranger
        // Jon <jon@jon.com> alloc username or client or stranger
        // Jon Smith         alloc fullname or client fullname

        // username
        $people || ($people = person::get_people_by_username());
        if (preg_match("/^\w+$/i", $ip)) {
            return [$people[$ip]["personID"], $people[$ip]["name"], $people[$ip]["emailAddress"]];
        }

        // email address
        $people = person::get_people_by_username("emailAddress");
        [$email, $name] = parse_email_address($ip);
        if ($people[$email]) {
            return [$people[$email]["personID"], $people[$email]["name"], $people[$email]["emailAddress"]];
        }

        // Jon smith
        if (preg_match("/^[\w\s]+$/i", $ip)) {
            $personID = person::find_by_name($ip, 100);
            if ($personID) {
                $people = person::get_people_by_username("personID");
                return [$personID, $people[$personID]["name"], $people[$personID]["emailAddress"]];
            }

            $ccid = clientContact::find_by_name($ip, $projectID, 100);
            if ($ccid) {
                $clientContact = new clientContact();
                $clientContact->set_id($ccid);
                $clientContact->select();
                $name = $clientContact->get_value("clientContactName");
                $email = $clientContact->get_value("clientContactEmail");
            }
        }

        return [null, $name, $email];
    }

    public static function add_remove_ips($ip, $entity, $entityID, $projectID = null)
    {
        $parties = explode(",", $ip);
        foreach ($parties as $party) {
            $party = trim($party);

            // remove an ip
            if ($party[0] == "%") {
                [$personID, $name, $email] = (new InterestedParty())->expand_ip(implode("", array_slice(str_split($party), 1)), $projectID);
                InterestedParty::delete_interested_party($entity, $entityID, $email);

                // add an ip
            } else {
                [$personID, $name, $email] = (new InterestedParty())->expand_ip($party, $projectID);
                if (!$email || strpos($email, "@") === false) {
                    alloc_error("Unable to add interested party: " . $party);
                } else {
                    InterestedParty::add_interested_party([
                        "entity"       => $entity,
                        "entityID"     => $entityID,
                        "fullName"     => $name,
                        "emailAddress" => $email,
                        "personID"     => $personID,
                    ]);
                }
            }
        }
    }
}
