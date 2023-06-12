<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

if ($_GET["task_type"] && $_GET["timeSheetID"]) {
    usleep(400000);
    $timeSheet = new timeSheet();
    echo $timeSheet->get_task_list_dropdown($_GET["task_type"], $_GET["timeSheetID"], $_GET["taskID"]);
}
