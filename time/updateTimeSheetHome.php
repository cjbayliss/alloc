<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define('NO_REDIRECT', 1);
require_once __DIR__ . '/../alloc.php';

$parsedTimeString = timeSheetItem::parse_time_string($_REQUEST['time_item']);

$timeUnit = new timeUnit();
$units = $timeUnit->get_assoc_array('timeUnitID', 'timeUnitLabelA');

$timeSheetItemMultiplier = new Meta('timeSheetItemMultiplier');
$tsims = $timeSheetItemMultiplier->get_list();

foreach ($parsedTimeString as $key => $value) {
    if ($value) {
        if ('taskID' == $key) {
            $task = new Task();
            $task->set_id($value);
            $value = $task->select() ? $task->get_id() . ' ' . $task->get_link() : 'Task ' . $value . ' not found.';
        } elseif ('unit' == $key) {
            $value = $units[$value];
        } elseif ('multiplier' == $key) {
            $value = $tsims[sprintf('%0.2f', $value)]['timeSheetItemMultiplierName'];
        }

        $rtn[$key] = $value;
    }
}

// 2010-10-01  1 Days x Double Time
// Task: 102 This is the task
// Comment: This is the comment
$str[] = '<table>';
$str[] = '<tr><td>' . $rtn['date'] . " </td><td class='nobr bold'> " . ($rtn['duration'] ?? '') . ' ' . ($rtn['unit'] ?? '') . "</td><td class='nobr'>&times; " . $rtn['multiplier'] . '</td></tr>';
if (isset($rtn['taskID'])) {
    $str[] = "<tr><td colspan='3'>" . $rtn['taskID'] . '</td></tr>';
}

if (isset($rtn['comment'])) {
    $str[] = "<tr><td colspan='3'>" . $rtn['comment'] . '</td></tr>';
}

$str[] = '</table>';

echo implode("\n", $str);
