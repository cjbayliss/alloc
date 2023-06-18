<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once(__DIR__ . "/../alloc.php");

global $sess;
global $TPL;

($historyID = $_POST["historyID"]) || ($historyID = $_GET["historyID"]);

if ($historyID && is_numeric($historyID)) {
    $db = new AllocDatabase();
    $query = unsafe_prepare("SELECT * FROM history WHERE historyID = %d", $historyID);
    $db->query($query);
    $db->next_record();
    alloc_redirect($sess->url($TPL[$db->f("the_place")] . "historyID=" . $historyID) . $db->f("the_args"));
}
