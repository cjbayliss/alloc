<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once(__DIR__ . "/../alloc.php");

usleep(50000);
$task = new Task();
echo $task->get_parent_task_select($_GET["projectID"]);
