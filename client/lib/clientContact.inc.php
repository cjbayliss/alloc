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

    public function save()
    {
        $rtn = parent::save();
        $client = new client();
        $client->set_id($this->get_value("clientID"));
        $client->select();
        $client->save();
        return $rtn;
    }

    /**
     * Get a list of people from the database
     *
     * @param int $projectID The project ID to look in
     * @param string $extra Extra SQL to be appended to the client contact query
     * @return array a list of people
     */
    private function get_people($projectID = false, $extra = '')
    {
        $database = new db_alloc();
        $database->connect();

        if ($projectID) {
            $getProjectClientID = $database->pdo->prepare(
                "SELECT clientID FROM project WHERE projectID = :projectID"
            );
            $getProjectClientID->bindValue(':projectID', $projectID, PDO::PARAM_INT);
            $getProjectClientID->execute();
            $projectClientID = $getProjectClientID->fetch(PDO::FETCH_ASSOC);
            if ($projectClientID["clientID"]) {
                $extra = "AND clientID = :clientID";
            }
        }

        $clientContactIDsAndNames = $database->pdo->prepare(
            "SELECT clientContactID, clientContactName
               FROM clientContact WHERE 1=1 " . $extra
        );

        if ($projectID && isset($projectClientID["clientID"])) {
            $clientContactIDsAndNames->bindValue(
                ":clientID",
                $projectClientID["clientID"],
                PDO::PARAM_INT
            );
        }

        $clientContactIDsAndNames->execute();
        $peopleArray = [];
        while ($row = $clientContactIDsAndNames->fetch(PDO::FETCH_ASSOC)) {
            $peopleArray[$row["clientContactID"]] = $row;
        }

        return (array)$peopleArray;
    }

    /**
     * Find the closest matching person
     *
     * @param array $people List of people to check
     * @param string $name Name of person to find
     * @param int $percent Required similarity (e.g.: 90)
     * @return string
     */
    private function get_closest_matching_person($people, $name, $percent)
    {
        $similarityScores = [];

        foreach ($people as $personID => $row) {
            similar_text(
                strtolower($row["clientContactName"]),
                strtolower($name),
                $score
            );
            $similarityScores[$personID] = $score;
        }

        asort($similarityScores);
        $personWithHighestSimilarity = array_key_last($similarityScores);
        $highestSimilarityScore = current($similarityScores);

        if ($percent === 0) {
            return $personWithHighestSimilarity;
        } else if ($personWithHighestSimilarity && $highestSimilarityScore >= $percent) {
            return $personWithHighestSimilarity;
        }
    }

    public static function find_by_name($name = false, $projectID = false, $percent = 90)
    {
        $extra = $name ? unsafe_prepare("AND clientContactName = '%s'", $name) : null;
        $people = self::get_people($projectID, $extra);
        return self::get_closest_matching_person($people, $name, $percent);
    }

    public static function find_by_partial_name($name = false, $projectID = false)
    {
        return self::find_by_name($name, $projectID, 0);
    }

    public static function find_by_nick($name = false, $clientID = false)
    {
        $q = unsafe_prepare("SELECT clientContactID
                        FROM clientContact
                       WHERE SUBSTRING(clientContactEmail,1,LOCATE('@',clientContactEmail)-1) = '%s'
                         AND clientID = %d LIMIT 1
                     ", $name, $clientID);
        $db = new db_alloc();
        $db->query($q);
        $row = $db->row();
        return $row["clientContactID"];
    }

    public static function find_by_email($email = false)
    {
        $email = str_replace(["<", ">"], "", $email);
        if ($email) {
            $q = unsafe_prepare("SELECT clientContactID
                            FROM clientContact
                           WHERE replace(replace(clientContactEmail,'<',''),'>','') = '%s'
                         ", $email);
            $db = new db_alloc();
            $db->query($q);
            $row = $db->row();
            return $row["clientContactID"];
        }
    }

    public function delete()
    {
        // have to null out any records that point to this clientContact first
        // to satisfy the referential integrity constraints
        $currentClientID = $this->get_id();
        if (!empty($currentClientID)) {
            $database = new db_alloc();
            $nullifyClientContactIDQuery = unsafe_prepare(
                "UPDATE interestedParty 
                    SET clientContactID = NULL where clientContactID = %d",
                $currentClientID
            );
            $database->query($nullifyClientContactIDQuery);

            $nullifyCommentCreatedUserClientContactIDQuery = unsafe_prepare(
                "UPDATE comment 
                    SET commentCreatedUserClientContactID = NULL 
                  where commentCreatedUserClientContactID = %d",
                $currentClientID
            );
            $database->query($nullifyCommentCreatedUserClientContactIDQuery);

            $nullifyProjectClientContactIDQuery = unsafe_prepare(
                "UPDATE project 
                    SET clientContactID = NULL where clientContactID = %d",
                $currentClientID
            );
            $database->query($nullifyProjectClientContactIDQuery);
        }
        return parent::delete();
    }

    public function format_contact()
    {
        $str = null;

        $fields = [
            "clientContactName",
            "clientContactStreetAddress",
            "clientContactSuburb",
            "clientContactPostcode",
            "clientContactPhone",
            "clientContactMobile",
            "clientContactFax",
        ];

        foreach ($fields as $field) {
            if ($this->get_value($field)) {
                $str .= $this->get_value($field, DST_HTML_DISPLAY) . "<br>";
            }
        }

        if ($email = $this->get_value("clientContactEmail")) {
            $name = $this->get_value("clientContactName", DST_HTML_DISPLAY);
            $str .= "<a href='mailto:\"{$name}\" <{$email}>'>{$email}</a><br>";
        }

        return $str;
    }

    public function output_vcard()
    {
        // array of mappings from DB field to vcard field
        $fields = [
            // clientContactName is special
            "clientContactPhone"  => "TEL;WORK;VOICE",
            "clientContactMobile" => "TEL;CELL",
            "clientContactFax"    => "TEL;TYPE=WORK;FAX",
            "clientContactEmail"  => "EMAIL;WORK",
        ]; // address fields are handled specially because they're a composite of DB fields

        $vcard = [];

        // This could be templated, but there's not much point
        // Based off the vcard output by Android 2.1
        header("Content-type: text/x-vcard");
        $filename = strtr($this->get_value("clientContactName"), " ", "_") . ".vcf";
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        print("BEGIN:VCARD\nVERSION:2.1\n");

        if ($this->get_value("clientContactName")) {
            // vcard stuff requires N to be last name; first name
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
                $this->get_value("clientContactSuburb") . ";;" . // county or something
                $this->get_value("clientContactPostcode") . ";" .
                $this->get_value("clientContactCountry") . "\n";
        }
        print("END:VCARD\n");
    }

    // FIXME: ??
    public function have_role($role = "")
    {
        return in_array($role, ["", "client"]);
    }

    public function get_list_filter($filter = [])
    {
        $sql = [];

        // If they want starred, load up the clientContactID filter element
        if ($filter["starred"]) {
            $current_user = &singleton("current_user");
            $starredContacts = array_keys((array)$current_user->prefs["stars"]["clientContact"]);
            foreach ($starredContacts as $clientContactId) {
                $filter["clientContactID"][] = $clientContactId;
            }

            if (!is_array($filter["clientContactID"])) {
                $filter["clientContactID"][] = -1;
            }
        }

        // Filter on clientContactID
        if ($filter["clientContactID"] && is_array($filter["clientContactID"])) {
            $sql[] = unsafe_prepare(
                "(clientContact.clientContactID in (%s))",
                $filter["clientContactID"]
            );
        } else if ($filter["clientContactID"]) {
            $sql[] = unsafe_prepare(
                "(clientContact.clientContactID = %d)",
                $filter["clientContactID"]
            );
        }

        // No point continuing if primary key specified, so return
        if ($filter["clientContactID"] || $filter["starred"]) {
            return $sql;
        }
    }

    public static function get_list($_FORM)
    {
        global $TPL;
        $filter = (new clientContact())->get_list_filter($_FORM);
        if (is_array($filter) && count($filter)) {
            $filter = " WHERE " . implode(" AND ", $filter);
        }

        $clientContactQuery =
            "SELECT clientContact.*, client.*
               FROM clientContact
          LEFT JOIN client ON client.clientID = clientContact.clientID
                    " . $filter . "
           GROUP BY clientContact.clientContactID
           ORDER BY clientContactName,clientContact.primaryContact asc";
        $database = new db_alloc();
        $database->query($clientContactQuery);

        $rows = [];
        while ($row = $database->next_record()) {
            $client = new client();
            $client->read_db_record($database);
            $row["clientLink"] = $client->get_client_link($_FORM);
            if ($row["clientContactEmail"]) {
                $email = page::htmlentities($row["clientContactEmail"]);
                $name = page::htmlentities($row["clientContactName"]);
                $row["clientContactEmail"] = "<a href=\"mailto:{$name} &lt;{$email}&gt;\">{$email}</a>";
            }
            $rows[] = $row;
        }

        return $rows;
    }

    public function get_list_html($rows = [], $ops = [])
    {
        global $TPL;
        $TPL["clientContactListRows"] = $rows;
        $TPL["_FORM"] = $ops;
        include_template(__DIR__ . "/../templates/clientContactListS.tpl");
    }
}
