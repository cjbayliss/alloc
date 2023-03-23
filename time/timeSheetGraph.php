<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$current_user = &singleton("current_user");

function show_filter()
{
    global $TPL;
    global $defaults;
    $arr = timeSheetGraph::load_filter($defaults);
    is_array($arr) and $TPL = array_merge($TPL, $arr);
    include_template("templates/timeSheetGraphFilterS.tpl");
}

$defaults = [
    "url_form_action" => $TPL["url_alloc_timeSheetGraph"],
    "form_name"       => "timeSheetGraph_filter",
    "groupBy"         => "day",
    "personID"        => $current_user->get_id()
];


$_FORM = timeSheetGraph::load_filter($defaults);

if ($_FORM["groupBy"] == "day") {
    $TPL["chart1"] = timeSheetItem::get_total_hours_worked_per_day($_FORM["personID"], $_FORM["dateFrom"], $_FORM["dateTo"]);
} else if ($_FORM["groupBy"] == "month") {
    $TPL["chart1"] = timeSheetItem::get_total_hours_worked_per_month($_FORM["personID"], $_FORM["dateFrom"], $_FORM["dateTo"]);
}

$TPL["dateFrom"] = $_FORM["dateFrom"];
$TPL["dateTo"] = $_FORM["dateTo"];
$TPL["groupBy"] = $_FORM["groupBy"];

include_template("templates/timeSheetGraphM.tpl");
