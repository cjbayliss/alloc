<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$_FORM = $_GET;

// Add requested fields to pdf
$_FORM["showEdit"] = false;
$fields["taskID"] = "ID";
$fields["taskName"] = "Task";
$_FORM["showProject"] and $fields["projectName"] = "Project";
$_FORM["showPriority"] || $_FORM["showPriorityFactor"] and $fields["priorityFactor"] = "Pri";
$_FORM["showPriority"] and $fields["taskPriority"] = "Task Pri";
$_FORM["showPriority"] and $fields["projectPriority"] = "Proj Pri";
$_FORM["showCreator"] and $fields["creator_name"] = "Creator";
$_FORM["showManager"] and $fields["manager_name"] = "Manager";
$_FORM["showAssigned"] and $fields["assignee_name"] = "Assigned To";
$_FORM["showDate1"] and $fields["dateTargetStart"] = "Targ Start";
$_FORM["showDate2"] and $fields["dateTargetCompletion"] = "Targ Compl";
$_FORM["showDate3"] and $fields["dateActualStart"] = "Start";
$_FORM["showDate4"] and $fields["dateActualCompletion"] = "Compl";
$_FORM["showDate5"] and $fields["dateCreated"] = "Created";
$_FORM["showTimes"] and $fields["timeBestLabel"] = "Best";
$_FORM["showTimes"] and $fields["timeExpectedLabel"] = "Likely";
$_FORM["showTimes"] and $fields["timeWorstLabel"] = "Worst";
$_FORM["showTimes"] and $fields["timeActualLabel"] = "Actual";
$_FORM["showTimes"] and $fields["timeLimitLabel"] = "Limit";
$_FORM["showPercent"] and $fields["percentComplete"] = "%";
$_FORM["showStatus"] and $fields["taskStatusLabel"] = "Status";

$taskPriorities = config::get_config_item("taskPriorities");
$projectPriorities = config::get_config_item("projectPriorities");

$rows = task::get_list($_FORM);
$taskListRows = [];
foreach ((array)$rows as $row) {
    $row["taskPriority"] = $taskPriorities[$row["priority"]]["label"];
    $row["projectPriority"] = $projectPriorities[$row["projectPriority"]]["label"];
    $row["taskDateStatus"] = strip_tags($row["taskDateStatus"]);
    $row["percentComplete"] = strip_tags($row["percentComplete"]);
    $taskListRows[] = $row;
}

if ($taskListRows) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=tasklist' . time() . '.csv');
    $fp = fopen('php://output', 'w');

    // header row
    fputcsv($fp, array_keys(current($taskListRows)));

    foreach ($taskListRows as $taskListRow) {
        fputcsv($fp, $taskListRow);
    }

    fclose($fp);
}
