<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


require_once("../alloc.php");

$absenceID = $_POST["absenceID"] or $absenceID = $_GET["absenceID"];
$returnToParent = $_GET["returnToParent"] or $returnToParent = $_POST["returnToParent"];
$TPL["returnToParent"] = $returnToParent;
$urls["home"] = $TPL["url_alloc_home"];
$urls["calendar"] = $TPL["url_alloc_taskCalendar"] . "personID=" . $personID;

$absence = new absence();
if ($absenceID) {
    $absence->set_id($absenceID);
    $absence->select();
    $absence->set_values();
    $personID = $absence->get_value("personID");
}

$person = new person();
$personID = $personID or $personID = $_POST["personID"] or $personID = $_GET["personID"];
if ($personID) {
    $person->set_id($personID);
    $person->select();
}

$db = new db_alloc();

if ($_POST["save"]) {
    // Saving a record
    $absence->read_globals();
    $absence->set_value("contactDetails", rtrim($absence->get_value("contactDetails")));
    $success = $absence->save();
    if ($success && !$TPL["message"]) {
        $url = $TPL["url_alloc_person"] . "personID=" . $personID;
        $urls[$returnToParent] and $url = $urls[$returnToParent];
        alloc_redirect($url);
    }
} else if ($_POST["delete"]) {
    // Deleting a record
    $absence->read_globals();
    $absence->delete();
    $url = $TPL["url_alloc_person"] . "personID=" . $personID;
    $urls[$returnToParent] and $url = $urls[$returnToParent];
    alloc_redirect($url);
}

// create a new record
$absence->read_globals();
$absence->set_value("personID", $person->get_id());
$absence->set_values("absence_");
$_GET["date"] and $TPL["absence_dateFrom"] = $_GET["date"];
$TPL["personName"] = $person->get_name();

// Set up the options for the absence type.
$absenceType_array = [
    'Annual Leave' => 'Annual Leave',
    'Holiday'      => 'Holiday',
    'Illness'      => 'Illness',
    'Other'        => 'Other'
];

$TPL["absenceType_options"] = page::select_options($absenceType_array, $absence->get_value("absenceType"));
$TPL["main_alloc_title"] = "Absence Form - " . APPLICATION_NAME;
include_template("templates/absenceFormM.tpl");
