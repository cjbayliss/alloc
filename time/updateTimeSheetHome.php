<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

// usleep(1000);

$t = timeSheetItem::parse_time_string($_REQUEST["time_item"]);

$timeUnit = new timeUnit();
$units = $timeUnit->get_assoc_array("timeUnitID", "timeUnitLabelA");

$timeSheetItemMultiplier = new meta("timeSheetItemMultiplier");
$tsims = $timeSheetItemMultiplier->get_list();

foreach ($t as $k => $v) {
    if ($v) {
        if ($k == "taskID") {
            $task = new task();
            $task->set_id($v);
            if ($task->select()) {
                $v = $task->get_id() . " " . $task->get_link();
            } else {
                $v = "Task " . $v . " not found.";
            }
        } else if ($k == "unit") {
            $v = $units[$v];
        } else if ($k == "multiplier") {
            $v = $tsims[sprintf("%0.2f", $v)]["timeSheetItemMultiplierName"];
        }
        $rtn[$k] = $v;
    }
}

// 2010-10-01  1 Days x Double Time
// Task: 102 This is the task
// Comment: This is the comment
$str[] = "<tr><td>" . $rtn["date"] . " </td><td class='nobr bold'> " . $rtn["duration"] . " " . $rtn["unit"] . "</td><td class='nobr'>&times; " . $rtn["multiplier"] . "</td></tr>";
$rtn["taskID"] and $str[] = "<tr><td colspan='3'>" . $rtn["taskID"] . "</td></tr>";
$rtn["comment"] and $str[] = "<tr><td colspan='3'>" . $rtn["comment"] . "</td></tr>";

if (isset($_REQUEST["save"]) && isset($_REQUEST["time_item"])) {
    $t = timeSheetItem::parse_time_string($_REQUEST["time_item"]);

    if (!is_numeric($t["duration"])) {
        $status = "bad";
        $extra = "Time not added. Duration not found.";
    } else if (!is_numeric($t["taskID"])) {
        $status = "bad";
        $extra = "Time not added. Task not found.";
    }

    if ($status != "bad") {
        $timeSheet = new timeSheet();
        $tsi_row = $timeSheet->add_timeSheetItem($t);

        if ($TPL["message"]) {
            $status = "bad";
            $extra = "Time not added.<br>" . implode("<br>", $TPL["message"]);
        } else {
            $status = "good";
            $tsid = $tsi_row["message"];
            $extra = "Added time to time sheet <a href='" . $TPL["url_alloc_timeSheet"] . "timeSheetID=" . $tsid . "'>#" . $tsid . "</a>";
        }
    }
}

// $extra and array_unshift($str, "<tr><td colspan='3' class='".$status." bold'>".$extra."</td></tr>");
$extra and $str[] = "<tr><td colspan='3' class='" . $status . " bold'>" . $extra . "</td></tr>";
print json_encode(["status" => $status, "table" => "<table class='" . $status . "'>" . implode("\n", $str) . "</table>"]);
