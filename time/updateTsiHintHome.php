<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define('NO_REDIRECT', 1);
require_once __DIR__ . '/../alloc.php';

// usleep(1000);

$t = tsiHint::parse_tsiHint_string($_REQUEST['tsiHint_item']);

$people = person::get_people_by_username();

foreach ($t as $k => $v) {
    if ($v) {
        if ('taskID' == $k) {
            $task = new Task();
            $task->set_id($v);
            $v = $task->select() ? $task->get_id() . ' ' . $task->get_link() : 'Task ' . $v . ' not found.';
        } elseif ('username' == $k) {
            ($name = $people[$v]['name']) || ($name = $people[$v]['username']);
        }

        $rtn[$k] = $v;
    }
}

// 2010-10-01  1 Days x Double Time
// Task: 102 This is the task
// Comment: This is the comment

$str[] = '<table>';
$str[] = '<tr><td>' . $name . ' ' . $rtn['date'] . " </td><td class='nobr bold'> " . $rtn['duration'] . " Hours</td><td class='nobr'></td></tr>";
$rtn['taskID'] && ($str[] = "<tr><td colspan='3'>" . $rtn['taskID'] . '</td></tr>');
$rtn['comment'] && ($str[] = "<tr><td colspan='3'>" . $rtn['comment'] . '</td></tr>');
$str[] = '</table>';

echo implode("\n", $str);
