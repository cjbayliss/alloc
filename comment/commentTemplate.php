<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once(__DIR__ . "/../alloc.php");

// Create an object to hold a commentTemplate
$commentTemplate = new commentTemplate();

// Load the commentTemplate from the database

($commentTemplateID = $_POST["commentTemplateID"]) || ($commentTemplateID = $_GET["commentTemplateID"]);

if ($commentTemplateID) {
    $commentTemplate->set_id($commentTemplateID);
    $commentTemplate->select();
}

// Process submission of the form using the save button
if ($_POST["save"]) {
    $commentTemplate->read_globals();
    $commentTemplate->save();
    alloc_redirect($TPL["url_alloc_commentTemplateList"]);
    // Process submission of the form using the delete button
} elseif ($_POST["delete"]) {
    $commentTemplate->delete();
    alloc_redirect($TPL["url_alloc_commentTemplateList"]);
    exit();
}

// Load data for display in the template
$commentTemplate->set_values();

$ops = [
    ""            => "Comment Template Type",
    "task"        => "Task",
    "timeSheet"   => "Time Sheet",
    "project"     => "Project",
    "client"      => "Client",
    "invoice"     => "Invoice",
    "productSale" => "Sale",
];
$TPL["commentTemplateTypeOptions"] = Page::select_options($ops, $commentTemplate->get_value("commentTemplateType"));

$TPL["main_alloc_title"] = "Edit Comment Template - " . APPLICATION_NAME;
// Invoke the page's main template
include_template("templates/commentTemplateM.tpl");
