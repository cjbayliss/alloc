<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

$_FORM = $_GET;

// Add requested fields to pdf
$_FORM['showEdit'] = false;
$fields['taskID'] = 'ID';
$fields['taskName'] = 'Task';
$_FORM['showProject'] && ($fields['projectName'] = 'Project');
if ($_FORM['showPriority'] || $_FORM['showPriorityFactor']) {
    $fields['priorityFactor'] = 'Pri';
}

$_FORM['showPriority'] && ($fields['taskPriority'] = 'Task Pri');
$_FORM['showPriority'] && ($fields['projectPriority'] = 'Proj Pri');
$_FORM['showCreator'] && ($fields['creator_name'] = 'Creator');
$_FORM['showManager'] && ($fields['manager_name'] = 'Manager');
$_FORM['showAssigned'] && ($fields['assignee_name'] = 'Assigned To');
$_FORM['showDate1'] && ($fields['dateTargetStart'] = 'Targ Start');
$_FORM['showDate2'] && ($fields['dateTargetCompletion'] = 'Targ Compl');
$_FORM['showDate3'] && ($fields['dateActualStart'] = 'Start');
$_FORM['showDate4'] && ($fields['dateActualCompletion'] = 'Compl');
$_FORM['showDate5'] && ($fields['dateCreated'] = 'Created');
$_FORM['showTimes'] && ($fields['timeBestLabel'] = 'Best');
$_FORM['showTimes'] && ($fields['timeExpectedLabel'] = 'Likely');
$_FORM['showTimes'] && ($fields['timeWorstLabel'] = 'Worst');
$_FORM['showTimes'] && ($fields['timeActualLabel'] = 'Actual');
$_FORM['showTimes'] && ($fields['timeLimitLabel'] = 'Limit');
$_FORM['showPercent'] && ($fields['percentComplete'] = '%');
$_FORM['showStatus'] && ($fields['taskStatusLabel'] = 'Status');

$taskPriorities = config::get_config_item('taskPriorities');
$projectPriorities = config::get_config_item('projectPriorities');

$rows = Task::get_list($_FORM);
$taskListRows = [];
foreach ((array) $rows as $row) {
    $row['taskPriority'] = $taskPriorities[$row['priority']]['label'];
    $row['projectPriority'] = $projectPriorities[$row['projectPriority']]['label'];
    $row['taskDateStatus'] = strip_tags($row['taskDateStatus']);
    $row['percentComplete'] = strip_tags($row['percentComplete']);
    $taskListRows[] = $row;
}

if ([] !== $taskListRows) {
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
