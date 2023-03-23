<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

global $sess;
global $TPL;

$historyID = $_POST["historyID"] or $historyID = $_GET["historyID"];

if ($historyID) {
    if (is_numeric($historyID)) {
        $db = new db_alloc();
        $query = prepare("SELECT * FROM history WHERE historyID = %d", $historyID);
        $db->query($query);
        $db->next_record();
        alloc_redirect($sess->url($TPL[$db->f("the_place")] . "historyID=" . $historyID) . $db->f("the_args"));
    }
}
