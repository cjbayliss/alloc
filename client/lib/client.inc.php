<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class client extends db_entity
{
    public $classname = "client";
    public $data_table = "client";
    public $display_field_name = "clientName";
    public $key_field = "clientID";
    public $data_fields = [
        "clientName",
        "clientStreetAddressOne",
        "clientStreetAddressTwo",
        "clientSuburbOne",
        "clientSuburbTwo",
        "clientStateOne",
        "clientStateTwo",
        "clientPostcodeOne",
        "clientPostcodeTwo",
        "clientPhoneOne",
        "clientFaxOne",
        "clientCountryOne",
        "clientCountryTwo",
        "clientCreatedTime",
        "clientModifiedTime",
        "clientModifiedUser",
        "clientStatus",
        "clientCategory",
        "clientURL"
    ];


    function delete()
    {
        // delete all contacts and comments linked with this client as well
        $database = new db_alloc();

        $clientContactQuery = prepare(
            "SELECT * FROM clientContact WHERE clientID=%d",
            $this->get_id()
        );
        $database->query($clientContactQuery);
        while ($database->next_record()) {
            $clientContact = new clientContact();
            $clientContact->read_db_record($database);
            $clientContact->delete();
        }

        $commentQuery = prepare(
            "SELECT * FROM comment WHERE commentType = 'client' and commentLinkID=%d",
            $this->get_id()
        );
        $database->query($commentQuery);
        while ($database->next_record()) {
            $comment = new comment();
            $comment->read_db_record($database);
            $comment->delete();
        }

        return parent::delete();
    }

    function is_owner($ignored = null)
    {
        $current_user = &singleton("current_user");
        return $current_user->is_employee();
    }

    function has_attachment_permission($person)
    {
        // Placeholder for security check in shared/get_attchment.php
        return true;
    }

    function has_attachment_permission_delete($person)
    {
        // Placeholder for security check in shared/get_attchment.php
        return true;
    }

    function get_client_select($clientStatus = "", $clientID = "")
    {
        $options = null;
        $clientNamesQuery = null;
        global $TPL;
        $db = new db_alloc(); // FIXME: is this doing magic or can it be deleted?

        if ($clientStatus) {
            $clientNamesQuery = prepare(
                "SELECT clientID as value, clientName as label
                   FROM client
                  WHERE clientStatus = '%s'
                     OR clientID = %d
               ORDER BY clientName",
                $clientStatus,
                $clientID
            );
        }

        $options .= page::select_options($clientNamesQuery, $clientID, 100);
        $str = "<select id=\"clientID\" name=\"clientID\" style=\"width:100%;\">";
        $str .= "<option value=\"\">";
        $str .= $options;
        $str .= "</select>";
        return $str;
    }

    function get_client_contact_select($clientID = "", $clientContactID = "")
    {
        $clientID or $clientID = $_GET["clientID"];
        $db = new db_alloc(); // FIXME: is this doing magic or can it be deleted?
        $clientContactQuery = prepare(
            "SELECT clientContactName as label, clientContactID as value 
               FROM clientContact 
              WHERE clientID = %d",
            $clientID
        );
        $options = page::select_options($clientContactQuery, $clientContactID, 100);
        return "<select id=\"clientContactID\" name=\"clientContactID\" style=\"width:100%\"><option value=\"\">"
            . $options
            . "</select>";
    }

    function get_name($_FORM = [])
    {
        if ($_FORM["return"] == "html") {
            return $this->get_value("clientName", DST_HTML_DISPLAY);
        } else {
            return $this->get_value("clientName");
        }
    }

    function get_client_link($_FORM = [])
    {
        global $TPL;
        return "<a href=\""
            . $TPL["url_alloc_client"]
            . "clientID="
            . $this->get_id()
            . "\">"
            . $this->get_name($_FORM)
            . "</a>";
    }

    function get_list_filter($filter = [])
    {
        $sql = [];
        $current_user = &singleton("current_user");

        // If they want starred, load up the clientID filter element
        if ($filter["starred"]) {
            foreach ((array)$current_user->prefs["stars"]["client"] as $k => $v) {
                $filter["clientID"][] = $k;
            }
            is_array($filter["clientID"]) or $filter["clientID"][] = -1;
        }

        // Filter on clientID
        if (!empty($filter["clientID"])) {
            $sql[] = sprintf_implode("client.clientID = %d", $filter["clientID"]);
        }

        // No point continuing if primary key specified, so return
        if ($filter["clientID"] || $filter["starred"]) {
            return $sql;
        }

        if (!empty($filter["clientStatus"])) {
            $sql[] = sprintf_implode("client.clientStatus = '%s'", $filter["clientStatus"]);
        }

        if (!empty($filter["clientCategory"])) {
            $sql[] = sprintf_implode(
                "IFNULL(client.clientCategory,'') = '%s'",
                $filter["clientCategory"]
            );
        }

        if (!empty($filter["clientName"])) {
            $sql[] = sprintf_implode(
                "IFNULL(clientName,'') LIKE '%%%s%%'",
                $filter["clientName"]
            );
        }

        if (!empty($filter["contactName"])) {
            $sql[] = sprintf_implode(
                "IFNULL(clientContactName,'') LIKE '%%%s%%'",
                $filter["contactName"]
            );
        }

        if ($filter["clientLetter"] && $filter["clientLetter"] == "A") {
            $sql[] = "(clientName like 'A%' or clientName REGEXP '^[^[:alpha:]]')";
        } else if ($filter["clientLetter"] && $filter["clientLetter"] != "ALL") {
            $sql[] = sprintf_implode("clientName LIKE '%s%%'", $filter["clientLetter"]);
        }

        return $sql;
    }

    public static function get_list($_FORM)
    {
        // This is the definitive method of getting a list of clients that need
        // a sophisticated level of filtering

        $rows = [];
        global $TPL;

        $filter = client::get_list_filter($_FORM);

        if (!isset($_FORM["return"])) {
            $_FORM["return"] = "html";
        }

        if (is_array($filter) && count($filter)) {
            $filter = " WHERE " . implode(" AND ", $filter);
        }

        $clientCategories = [];
        $categoryDataArray = config::get_config_item("clientCategories");
        foreach ($categoryDataArray as $client => $category) {
            $clientCategories[$category["value"]] = $category["label"];
        }

        // IMPORTANT: passing empty PHP arrays to SQL queries will put the type
        // keyword 'Array' in the query
        if (empty($filter)) {
            $filter = null;
        }

        $clientInfoQuery =
            "SELECT client.*,
                    clientContactName,
                    clientContactEmail,
                    clientContactPhone,
                    clientContactMobile
               FROM client
          LEFT JOIN clientContact
                 ON client.clientID = clientContact.clientID
                AND clientContact.clientContactActive = 1
                    {$filter}
           GROUP BY client.clientID
           ORDER BY clientName,clientContact.primaryContact asc";

        $database = new db_alloc();
        $database->query($clientInfoQuery);
        while ($row = $database->next_record()) {
            $currentClient = new client();
            $currentClient->read_db_record($database);

            $row["clientCategoryLabel"] =
                $clientCategories[$currentClient->get_value("clientCategory")];
            $row["clientLink"] = $currentClient->get_client_link($_FORM);

            if (!empty($row["clientContactEmail"])) {
                $clientContactName = page::htmlentities($row["clientContactName"]);
                $clientContactEmail = page::htmlentities($$row["clientContactEmail"]);
                $row["clientContactEmail"] =
                    "<a href=\"mailto:{$clientContactName} <{$clientContactEmail}>\">{$clientContactEmail}</a>";
            }

            $rows[$currentClient->get_id()] = $row;
        }
        return (array)$rows;
    }

    function get_list_vars()
    {
        return [
            "clientStatus"     => "Client status eg: Current | Potential | Archived",
            "clientCategory"   => "Client category eg: 1-7",
            "clientName"       => "Client name like *something*",
            "contactName"      => "Client Contact name like *something*",
            "clientLetter"     => "Client name starts with this letter",
            "url_form_action"  => "The submit action for the filter form",
            "form_name"        => "The name of this form, i.e. a handle for referring to this saved form",
            "dontSave"         => "Specify that the filter preferences should not be saved this time",
            "applyFilter"      => "Saves this filter as the persons preference"
        ];
    }

    public static function load_form_data($defaults = [])
    {
        $current_user = &singleton("current_user");

        $page_vars = array_keys(client::get_list_vars());

        $_FORM = get_all_form_data($page_vars, $defaults);

        if (!$_FORM["applyFilter"]) {
            $_FORM = $current_user->prefs[$_FORM["form_name"]];
            if (!isset($current_user->prefs[$_FORM["form_name"]])) {
                $_FORM["clientLetter"] = "A";
                $_FORM["clientStatus"] = "Current";
            }
        } else if ($_FORM["applyFilter"] && is_object($current_user) && !$_FORM["dontSave"]) {
            $url = $_FORM["url_form_action"];
            unset($_FORM["url_form_action"]);
            $current_user->prefs[$_FORM["form_name"]] = $_FORM;
            $_FORM["url_form_action"] = $url;
        }

        return $_FORM;
    }

    public static function load_client_filter($_FORM)
    {
        $rtn = [];
        global $TPL;

        $db = new db_alloc();

        // Load up the forms action url
        $rtn["url_form_action"] = $_FORM["url_form_action"];

        $m = new meta("clientStatus");
        $clientStatus_array = $m->get_assoc_array("clientStatusID", "clientStatusID");
        $rtn["clientStatusOptions"] = page::select_options($clientStatus_array, $_FORM["clientStatus"]);
        $rtn["clientName"] = $_FORM["clientName"];
        $rtn["contactName"] = $_FORM["contactName"];
        $letters = range('A', 'Z');
        $letters[] = 'ALL'; // append 'ALL' for filtering by all

        foreach ($letters as $letter) {
            if ($_FORM["clientLetter"] == $letter) {
                $rtn["alphabet_filter"] .= "&nbsp;&nbsp;{$letter}";
            } else {
                $rtn["alphabet_filter"] .=
                    "&nbsp;&nbsp;<a href=\"{$TPL["url_alloc_clientList"]}clientLetter={$letter}&clientStatus=Current&applyFilter=1\">{$letter}</a>";
            }
        }

        $clientCategories = [];
        $clientCategory = $_FORM["clientCategory"];
        $clientDataArray = config::get_config_item("clientCategories") or $clientDataArray = [];
        foreach ($clientDataArray as $client => $category) {
            $clientCategories[$category["value"]] = $category["label"];
        }
        $rtn["clientCategoryOptions"] = page::select_options(
            $clientCategories,
            $clientCategory
        );

        // Get
        $rtn["FORM"] = "FORM=" . urlencode(serialize($_FORM));

        return $rtn;
    }

    function get_url()
    {
        global $TPL;
        global $sess; // FIXME: can this be deleted?
        $url = "client/client.php?&clientID=" . $this->get_id();
        return $TPL["url_alloc_client"] . $url;
    }

    function get_clientID_from_name($name)
    {
        static $clients;
        if (!$clients) {
            $database = new db_alloc();
            $clientInfoQuery = prepare("SELECT * FROM client");
            $database->query($clientInfoQuery);
            while ($database->next_record()) {
                $clients[$database->f("clientID")] = $database->f("clientName");
            }
        }

        $stack = [];
        foreach ($clients as $clientID => $clientName) {
            similar_text(strtolower($name), strtolower($clientName), $percent);
            $stack[$clientID] = $percent;
        }
        asort($stack);
        end($stack);
        $probable_clientID = key($stack);
        $client_percent = current($stack);
        return [$probable_clientID, $client_percent];
    }

    function get_client_and_project_dropdowns_and_links(
        $clientID = false,
        $projectID = false,
        $onlymine = false
    ) {
        // This function returns dropdown lists and links for both client and
        // project. The two dropdown lists are linked, in that if you change the
        // client, then the project dropdown dynamically updates
        global $TPL;

        $project = new project();
        $project->set_id($projectID);
        $project->select();
        if (!$clientID) {
            $clientID = $project->get_value("clientID");
        }

        $client = new client();
        $client->set_id($clientID);
        $client->select();

        $options = client::get_list([["clientStatus"] => "Current"]);
        $options = array_kv($options, "clientID", "clientName");
        $client->get_id() and $options[$client->get_id()] = $client->get_value("clientName");
        $client_select = "<select id=\"clientID\" name=\"clientID\" onChange=\"makeAjaxRequest('"
            . $TPL["url_alloc_updateProjectListByClient"]
            . "clientID='+$('#clientID').attr('value')+'&onlymine="
            . sprintf("%d", $onlymine)
            . "','projectDropdown')\"><option></option>";
        $client_select .= page::select_options($options, $clientID, 100) . "</select>";

        $client_link = $client->get_link();

        $project_select = '<div id="projectDropdown" style="display:inline">'
            . $project->get_dropdown_by_client($clientID, $onlymine)
            . '</div>';
        $project_link = $project->get_link();

        return [$client_select, $client_link, $project_select, $project_link];
    }

    // FIXME: this function is scary, fix that -- cjb 2023-03
    function update_search_index_doc(&$index)
    {
        $postal = [];
        $street = [];
        $ph = null;
        $fx = null;
        $c = null;
        $nl = null;
        $contacts = null;
        $person = &get_cached_table("person");
        $clientModifiedUser = $this->get_value("clientModifiedUser");
        $clientModifiedUser_field = $clientModifiedUser . " " . $person[$clientModifiedUser]["username"] . " " . $person[$clientModifiedUser]["name"];

        $this->get_value("clientStreetAddressOne") and $postal[] = $this->get_value("clientStreetAddressOne");
        $this->get_value("clientSuburbOne")        and $postal[] = $this->get_value("clientSuburbOne");
        $this->get_value("clientStateOne")         and $postal[] = $this->get_value("clientStateOne");
        $this->get_value("clientPostcodeOne")      and $postal[] = $this->get_value("clientPostcodeOne");
        $this->get_value("clientCountryOne")       and $postal[] = $this->get_value("clientCountryOne");
        $p = implode("\n", (array)$postal);
        $p and $p = "Postal Address:\n" . $p;

        $this->get_value("clientStreetAddressTwo") and $street[] = $this->get_value("clientStreetAddressTwo");
        $this->get_value("clientSuburbTwo")        and $street[] = $this->get_value("clientSuburbTwo");
        $this->get_value("clientStateTwo")         and $street[] = $this->get_value("clientStateTwo");
        $this->get_value("clientPostcodeTwo")      and $street[] = $this->get_value("clientPostcodeTwo");
        $this->get_value("clientCountryTwo")       and $street[] = $this->get_value("clientCountryTwo");
        $s = implode("\n", (array)$street);
        $s and $s = "Street Address:\n" . $s;

        $p && $s and $p .= "\n\n";
        $addresses = $p . $s;

        $this->get_value("clientPhoneOne") and $ph = "Ph: " . $this->get_value("clientPhoneOne");
        $this->get_value("clientFaxOne")   and $fx = "Fax: " . $this->get_value("clientFaxOne");

        $ph and $ph = " " . $ph;
        $fx and $fx = " " . $fx;
        $name = $this->get_name() . $ph . $fx;

        $q = prepare("SELECT * FROM clientContact WHERE clientID = %d", $this->get_id());
        $db = new db_alloc();
        $db->query($q);
        while ($row = $db->row()) {
            $c .= $nl . $row["clientContactName"];
            $row["clientContactEmail"]         and $c .= " <" . $row["clientContactEmail"] . ">";
            $c .= " | ";
            $row["clientContactStreetAddress"] and $c .= " " . $row["clientContactStreetAddress"];
            $row["clientContactSuburb"]        and $c .= " " . $row["clientContactSuburb"];
            $row["clientContactState"]         and $c .= " " . $row["clientContactState"];
            $row["clientContactPostcode"]      and $c .= " " . $row["clientContactPostcode"];
            $row["clientContactCountry"]       and $c .= " " . $row["clientContactCountry"];
            $c .= " | ";
            $row["clientContactPhone"]         and $c .= " Ph: " . $row["clientContactPhone"];
            $row["clientContactMobile"]        and $c .= " Mob: " . $row["clientContactMobile"];
            $row["clientContactFax"]           and $c .= " Fax: " . $row["clientContactFax"];
            $row["primaryContact"]             and $c .= " Primary contact";
            $c .= " | ";
            $row["clientContactOther"]         and $c .= " " . $row["clientContactOther"];
            $nl = "|+|=|";
        }
        $c and $contacts = $c;

        $doc = new Zend_Search_Lucene_Document();
        $doc->addField(Zend_Search_Lucene_Field::Keyword('id', $this->get_id()));
        $doc->addField(Zend_Search_Lucene_Field::Text('name', $name, "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('desc', $addresses, "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('contact', $contacts, "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('status', $this->get_value("clientStatus"), "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('modifier', $clientModifiedUser_field, "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('dateModified', str_replace("-", "", $this->get_value("clientModifiedTime")), "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('category', $this->get_value("clientCategory"), "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('dateCreated', str_replace("-", "", $this->get_value("clientCreatedTime")), "utf-8"));
        $index->addDocument($doc);
    }

    function format_address($type = "street", $map_link = true)
    {
        $stateOrRegion = null;
        $country = null;

        if ($type == "postal") {
            $postalOrStreetAddress = $this->get_value("clientStreetAddressOne", DST_HTML_DISPLAY);
            $suburb = $this->get_value("clientSuburbOne", DST_HTML_DISPLAY);
            $stateOrRegion = $this->get_value("clientStateOne", DST_HTML_DISPLAY);
            $postCode = $this->get_value("clientPostcodeOne", DST_HTML_DISPLAY);
            $country = $this->get_value("clientCountryOne", DST_HTML_DISPLAY);
        } else if ($type == "street") {
            $postalOrStreetAddress = $this->get_value("clientStreetAddressTwo", DST_HTML_DISPLAY);
            $suburb = $this->get_value("clientSuburbTwo", DST_HTML_DISPLAY);
            $stateOrRegion = $this->get_value("clientStateTwo", DST_HTML_DISPLAY);
            $postCode = $this->get_value("clientPostcodeTwo", DST_HTML_DISPLAY);
            $country = $this->get_value("clientCountryTwo", DST_HTML_DISPLAY);
        }

        // Create a map link, this will give you a link even if you only have the
        // street address and the suburb OR post code. -- cjbayliss 2015-01

        // FIXME: shouldn't "!empty($suburb) || !empty($postCode)" be
        // encapsulated by parenthesis? -- cjb 2023-03
        if ($map_link && !empty($postalOrStreetAddress) && !empty($suburb) || !empty($postCode)) {
            $map_base = config::get_config_item('mapURL');
            $address = str_replace(
                "%ad",
                urlencode(implode(", ", [
                    $postalOrStreetAddress,
                    $suburb,
                    $stateOrRegion,
                    $postCode,
                    $country
                ])),
                $map_base
            );
            $str = "<a href=\"{$address}\">{$postalOrStreetAddress}<br/>{$suburb} {$stateOrRegion} {$postCode}<br/>{$country}</a>";
        } else if ($postalOrStreetAddress != "") {
            $str = $postalOrStreetAddress;
            $suburb and $str .= "<br>" . $suburb;
            $stateOrRegion and $str .= " " . $stateOrRegion;
            $postCode and $str .= " " . $postCode;
            $country and $str .= "<br>" . $country;
        }

        return $str;
    }

    function get_all_parties($clientID = false)
    {
        $interestedPartyOptions = [];
        if (!$clientID && is_object($this)) {
            $clientID = $this->get_id();
        }
        if ($clientID) {
            // Get all client contacts
            $database = new db_alloc();
            $clientPartiesQuery = prepare(
                "SELECT clientContactName, clientContactEmail, clientContactID
                   FROM clientContact
                  WHERE clientID = %d
                    AND clientContactActive = 1",
                $clientID
            );
            $database->query($clientPartiesQuery);
            while ($database->next_record()) {
                $interestedPartyOptions[$database->f("clientContactEmail")] = [
                    "name" => $database->f("clientContactName"),
                    "external" => "1",
                    "clientContactID" => $database->f("clientContactID")
                ];
            }
        }

        // return an aggregation of the current task/proj/client parties + the
        // existing interested parties
        $interestedPartyOptions = interestedParty::get_interested_parties(
            "client",
            $clientID,
            $interestedPartyOptions
        );
        return (array)$interestedPartyOptions;
    }

    function get_list_html($rows = [], $ops = [])
    {
        global $TPL;
        $TPL["clientListRows"] = $rows;
        $TPL["_FORM"] = $ops;
        include_template(__DIR__ . "/../templates/clientListS.tpl");
    }
}
