<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

global $TPL;

$db = new db_alloc();
$TPL["tfID"] = $_GET["tfID"];


$TPL["main_alloc_title"] = "Repeating Expenses List - " . APPLICATION_NAME;
include_template("templates/transactionRepeatListM.tpl");

function show_expenseFormList($template_name)
{
    $sql = null;
    $i = null;
    global $db;
    global $TPL;
    global $transactionRepeat;
    $current_user = &singleton("current_user");

    $db = new db_alloc();
    $transactionRepeat = new transactionRepeat();

    if (!$_GET["tfID"] && !$current_user->have_role("admin")) {
        $tfIDs = $current_user->get_tfIDs();
        $tfIDs and $sql = prepare("WHERE tfID in (%s)", $tfIDs);
    } else if ($_GET["tfID"]) {
        $sql = prepare("WHERE tfID = %d", $_GET["tfID"]);
    }

    $db->query("select * FROM transactionRepeat " . $sql);

    while ($db->next_record()) {
        $i++;
        $transactionRepeat->read_db_record($db);
        $transactionRepeat->set_values();
        $TPL["tfName"] = tf::get_name($transactionRepeat->get_value("tfID"));
        $TPL["fromTfName"] = tf::get_name($transactionRepeat->get_value("fromTfID"));
        include_template($template_name);
    }
    $TPL["tfID"] = $tfID;
}
