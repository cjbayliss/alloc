<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

usleep(600000);

$task = new task();
if ($_GET["taskID"]) {
    $task->set_id($_GET["taskID"]);
    $task->select();
}
echo "<select name=\"personID\"><option value=\"\">" . $task->get_personList_dropdown($_GET["projectID"], "personID", $_GET["selected"]) . "</select>";
