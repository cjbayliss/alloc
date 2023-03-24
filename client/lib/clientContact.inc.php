<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class clientContact extends db_entity
{
    public $classname = "clientContact";
    public $data_table = "clientContact";
    public $display_field_name = "clientContactName";
    public $key_field = "clientContactID";
    public $data_fields = [
        "clientID", 
        "clientContactName", 
        "clientContactStreetAddress", 
        "clientContactSuburb", 
        "clientContactState", 
        "clientContactPostcode", 
        "clientContactCountry", 
        "clientContactPhone", 
        "clientContactMobile", 
        "clientContactFax", 
        "clientContactEmail", 
        "clientContactOther", 
        "primaryContact",
        "clientContactActive",
    ];

    function save()
    {
        $rtn = parent::save();
        $c = new client();
        $c->set_id($this->get_value("clientID"));
        $c->select();
        $c->save();
        return $rtn;
    }

    function find_by_name($name = false, $projectID = false, $percent = 90)
    {
        $extra = null;
        $stack1 = [];
        $people = [];
        $db = new db_alloc();

        if ($projectID) {
            $db->query("SELECT clientID FROM project WHERE projectID = %d", $projectID);
            $row = $db->qr();
            if ($row["clientID"]) {
                $extra = prepare("AND clientID = %d", $row["clientID"]);
            }
        }
        $extra or $extra = prepare("AND clientContactName = '%s'", $name);

        $q = "SELECT clientContactID, clientContactName FROM clientContact WHERE 1=1 " . $extra;
        $db->query($q);
        while ($row = $db->row()) {
            $people[$db->f("clientContactID")] = $row;
        }

        foreach ($people as $personID => $row) {
            similar_text(strtolower($row["clientContactName"]), strtolower($name), $percent1);
            $stack1[$personID] = $percent1;
        }

        asort($stack1);
        end($stack1);
        $probable1_clientContactID = key($stack1);
        $person_percent1 = current($stack1);

        if ($probable1_clientContactID && $person_percent1 >= $percent) {
            return $probable1_clientContactID;
        }
    }

    function find_by_partial_name($name = false, $projectID = false)
    {
        $extra = null;
        $stack1 = [];
        $people = [];
        $db = new db_alloc();

        if ($projectID) {
            $db->query("SELECT clientID FROM project WHERE projectID = %d", $projectID);
            $row = $db->qr();
            if ($row["clientID"]) {
                $extra = prepare("AND clientID = %d", $row["clientID"]);
            }
        }

        $q = prepare(
            "SELECT clientContactID, clientContactName
               FROM clientContact
              WHERE 1=1
                AND clientContactName like '%s%%'"
                . $extra,
            $name
        );
        $db->query($q);
        while ($row = $db->row()) {
            $people[$db->f("clientContactID")] = $row;
        }

        foreach ($people as $personID => $row) {
            similar_text(strtolower($row["clientContactName"]), strtolower($name), $percent1);
            $stack1[$personID] = $percent1;
        }

        asort($stack1);
        end($stack1);
        $probable1_clientContactID = key($stack1);
        $person_percent1 = current($stack1);

        if ($probable1_clientContactID) {
            return $probable1_clientContactID;
        }
    }

    function find_by_nick($name = false, $clientID = false)
    {
        $q = prepare("SELECT clientContactID
                        FROM clientContact
                       WHERE SUBSTRING(clientContactEmail,1,LOCATE('@',clientContactEmail)-1) = '%s'
                         AND clientID = %d LIMIT 1
                     ", $name, $clientID);
        $db = new db_alloc();
        $db->query($q);
        $row = $db->row();
        return $row["clientContactID"];
    }

    function find_by_email($email = false)
    {
        $email = str_replace(["<", ">"], "", $email);
        if ($email) {
            $q = prepare("SELECT clientContactID
                            FROM clientContact
                           WHERE replace(replace(clientContactEmail,'<',''),'>','') = '%s'
                         ", $email);
            $db = new db_alloc();
            $db->query($q);
            $row = $db->row();
            return $row["clientContactID"];
        }
    }

    function delete()
    {
        // have to null out any records that point to this clientContact first to satisfy the referential integrity constraints
        if ($this->get_id()) {
            $db = new db_alloc();
            $q = prepare("UPDATE interestedParty SET clientContactID = NULL where clientContactID = %d", $this->get_id());
            $db->query($q);
            $q = prepare("UPDATE comment SET commentCreatedUserClientContactID = NULL where commentCreatedUserClientContactID = %d", $this->get_id());
            $db->query($q);
            $q = prepare("UPDATE project SET clientContactID = NULL where clientContactID = %d", $this->get_id());
            $db->query($q);
        }
        return parent::delete();
    }

    function format_contact()
    {
        $str = null;
        $this->get_value("clientContactName")          and $str .= $this->get_value("clientContactName", DST_HTML_DISPLAY) . "<br>";
        $this->get_value("clientContactStreetAddress") and $str .= $this->get_value("clientContactStreetAddress", DST_HTML_DISPLAY) . "<br>";
        $this->get_value("clientContactSuburb")        and $str .= $this->get_value("clientContactSuburb", DST_HTML_DISPLAY) . "<br>";
        $this->get_value("clientContactPostcode")      and $str .= $this->get_value("clientContactPostcode", DST_HTML_DISPLAY) . "<br>";
        $this->get_value("clientContactPhone")         and $str .= $this->get_value("clientContactPhone", DST_HTML_DISPLAY) . "<br>";
        $this->get_value("clientContactMobile")        and $str .= $this->get_value("clientContactMobile", DST_HTML_DISPLAY) . "<br>";
        $this->get_value("clientContactFax")           and $str .= $this->get_value("clientContactFax", DST_HTML_DISPLAY) . "<br>";
        $this->get_value("clientContactEmail")         and $str .= "<a href='mailto:\"" . $this->get_value("clientContactName", DST_HTML_DISPLAY) . "\" <" . $this->get_value("clientContactEmail", DST_HTML_DISPLAY) . ">'>" . $this->get_value("clientContactEmail", DST_HTML_DISPLAY) . "</a><br>";
        return $str;
    }

    function output_vcard()
    {
        //array of mappings from DB field to vcard field
        $fields = [
            //clientContactName is special
            "clientContactPhone" => "TEL;WORK;VOICE",
            "clientContactMobile" => "TEL;CELL",
            "clientContactFax" => "TEL;TYPE=WORK;FAX",
            "clientContactEmail" => "EMAIL;WORK",
        ]; //address fields are handled specially because they're a composite of DB fields

        $vcard = [];

        // This could be templated, but there's not much point
        // Based off the vcard output by Android 2.1
        header("Content-type: text/x-vcard");
        $filename = strtr($this->get_value("clientContactName"), " ", "_") . ".vcf";
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        print("BEGIN:VCARD\nVERSION:2.1\n");

        if ($this->get_value("clientContactName")) {
            //vcard stuff requires N to be last name; first name
            // Assume whatever comes after the last space is the last name
            // cut the string up to get <last name>;<everything else>
            $name = explode(" ", $this->get_value("clientContactName"));
            $lastname = array_slice($name, -1);
            $lastname = $lastname[0];

            $rest = implode(array_slice($name, 0, -1));
            print "N:" . $lastname . ";" . $rest . "\n";
            print "FN:" . $this->get_value("clientContactName") . "\n";
        }

        foreach ($fields as $db => $label) {
            if ($this->get_value($db)) {
                print $label . ":" . $this->get_value($db) . "\n";
            }
        }
        if ($this->get_value("clientContactStreetAddress")) {
            print "ADR;HOME:;;" . $this->get_value("clientContactStreetAddress") . ";" .
                $this->get_value("clientContactSuburb") . ";;" . //county or something
                $this->get_value("clientContactPostcode") . ";" .
                $this->get_value("clientContactCountry") . "\n";
        }
        print("END:VCARD\n");
    }

    function have_role($role = "")
    {
        return in_array($role, ["", "client"]);
    }

    function get_list_filter($filter = [])
    {
        $sql = [];
        $current_user = &singleton("current_user");

        // If they want starred, load up the clientContactID filter element
        if ($filter["starred"]) {
            foreach ((array)$current_user->prefs["stars"]["clientContact"] as $k => $v) {
                $filter["clientContactID"][] = $k;
            }
            is_array($filter["clientContactID"]) or $filter["clientContactID"][] = -1;
        }

        // Filter on clientContactID
        if ($filter["clientContactID"] && is_array($filter["clientContactID"])) {
            $sql[] = prepare("(clientContact.clientContactID in (%s))", $filter["clientContactID"]);
        } else if ($filter["clientContactID"]) {
            $sql[] = prepare("(clientContact.clientContactID = %d)", $filter["clientContactID"]);
        }

        // No point continuing if primary key specified, so return
        if ($filter["clientContactID"] || $filter["starred"]) {
            return $sql;
        }
    }

    public static function get_list($_FORM)
    {
        $rows = [];
        global $TPL;
        $filter = clientContact::get_list_filter($_FORM);
        if (is_array($filter) && count($filter)) {
            $filter = " WHERE " . implode(" AND ", $filter);
        }

        $q = "SELECT clientContact.*, client.*
                FROM clientContact
           LEFT JOIN client ON client.clientID = clientContact.clientID
                     " . $filter . "
            GROUP BY clientContact.clientContactID
            ORDER BY clientContactName,clientContact.primaryContact asc";
        $db = new db_alloc();
        $db->query($q);
        while ($row = $db->next_record()) {
            $c = new client();
            $c->read_db_record($db);
            $row["clientLink"] = $c->get_client_link($_FORM);
            $row["clientContactEmail"] and $row["clientContactEmail"] = "<a href=\"mailto:" . page::htmlentities($row["clientContactName"] . " <" . $row["clientContactEmail"] . ">") . "\">" . page::htmlentities($row["clientContactEmail"]) . "</a>";
            $rows[] = $row;
        }
        return $rows;
    }

    function get_list_html($rows = [], $ops = [])
    {
        global $TPL;
        $TPL["clientContactListRows"] = $rows;
        $TPL["_FORM"] = $ops;
        include_template(__DIR__ . "/../templates/clientContactListS.tpl");
    }
}
