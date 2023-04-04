<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


require_once("../alloc.php");

function show_people($template_name)
{
    global $person_query;
    global $project;
    global $TPL;

    $db = new db_alloc();
    $db->query($person_query);
    while ($db->next_record()) {
        $person = new person();
        $person->read_db_record($db);
        $person->set_values("person_");
        $TPL["graphTitle"] = urlencode($person->get_name());
        include_template($template_name);
    }
}

$projectID = $_POST["projectID"] or $projectID = $_GET["projectID"];

if ($projectID) {
    $project = new project();
    $project->set_id($projectID);
    $project->select();
    $TPL["navigation_links"] = $project->get_navigation_links();
    $person_query = unsafe_prepare("SELECT person.*
                               FROM person, projectPerson
                              WHERE person.personID = projectPerson.personID
                                AND projectPerson.projectID=%d", $project->get_id());
} else if ($_GET["personID"]) {
    $person_query = unsafe_prepare("SELECT * FROM person WHERE personID = %d ORDER BY username", $_GET["personID"]);
} else {
    $person_query = unsafe_prepare("SELECT * FROM person ORDER BY username");
}

$TPL["projectID"] = $projectID;
$TPL["main_alloc_title"] = "Allocation Graph - " . APPLICATION_NAME;
include_template("templates/personGraphM.tpl");
