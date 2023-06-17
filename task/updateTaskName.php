<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

if ($_REQUEST["taskID"]) {
    $q = unsafe_prepare("SELECT taskID, taskName FROM task WHERE taskID = %d", $_REQUEST["taskID"]);
    $db = new AllocDatabase();
    $row = $db->qr($q);
    echo page::htmlentities($row["taskID"] . " " . $row["taskName"]);
}
