<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

usleep(500000);

$task = new Task();
if ($_GET["taskID"]) {
    $task->set_id($_GET["taskID"]);
    $task->select();
    echo $task->get_task_cc_list_select($_GET["projectID"]);
} else {
    echo $task->get_task_cc_list_select($_GET["projectID"]);
}
