<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

function show_filter()
{
    global $TPL;
    global $defaults;
    $_FORM = timeSheet::load_form_data($defaults);
    $arr = timeSheet::load_timeSheet_filter($_FORM);
    is_array($arr) and $TPL = array_merge($TPL, $arr);
    include_template("templates/timeSheetListFilterS.tpl");
}

$defaults = [
    "url_form_action"    => $TPL["url_alloc_timeSheetList"],
    "form_name"          => "timeSheetList_filter",
    "showFinances"       => $_REQUEST["showFinances"],
    "dateFromComparator" => ">=",
    "dateToComparator"   => "<="
];

$_FORM = timeSheet::load_form_data($defaults);
$rtn = timeSheet::get_list($_FORM);
$TPL["timeSheetListRows"] = $rtn["rows"];
$TPL["timeSheetListExtra"] = $rtn["extra"];

if (!$current_user->prefs["timeSheetList_filter"]) {
    $TPL["message_help"][] = "

allocPSA allows you to record the time that you've worked on various
Projects using Time Sheets. This page allows you to view a list of Time Sheets.

<br><br>

Simply adjust the filter settings and click the <b>Filter</b> button to
display a list of previously created Time Sheets.
If you would prefer to create a new Time Sheet, click the <b>New Time Sheet</b> link
in the top-right hand corner of the box below.";
}




$TPL["main_alloc_title"] = "Timesheet List - " . APPLICATION_NAME;
include_template("templates/timeSheetListM.tpl");
