<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once(__DIR__ . "/../alloc.php");

usleep(500000);

$task = new Task();
if ($_GET["taskID"]) {
    $task->set_id($_GET["taskID"]);
    $task->select();
}

echo '<select name="estimatorID"><option value="">' . $task->get_personList_dropdown($_GET["projectID"], "estimatorID", $_GET["selected"]) . "</select>";
