<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

if (!$current_user->is_employee()) {
    alloc_error("You do not have permission to access time sheets", true);
}

$timeSheetID = $_POST["timeSheetID"] or $timeSheetID = $_GET["timeSheetID"];
$timeSheetPrintMode = $_GET["timeSheetPrintMode"];
$printDesc = $_GET["printDesc"];
$format = $_GET["format"];

$t = new timeSheetPrint();
$t->get_printable_timeSheet_file($timeSheetID, $timeSheetPrintMode, $printDesc, $format);
