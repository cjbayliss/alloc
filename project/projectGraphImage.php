<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once(__DIR__ . "/../alloc.php");
include(__DIR__ . "/lib/task_graph.inc.php");

$current_user = &singleton("current_user");
global $show_weeks;
global $for_home_item;

$options = unserialize(stripslashes($_GET["FORM"]));
$options["return"] = "array";
$options["padding"] = 0;
$options["debug"] = 0;

($tasks = Task::get_list($options)) || ($tasks = []);

foreach ($tasks as $task) {
    $objects[$task["taskID"]] = $task["object"];
}

$task_graph = new task_graph();
$task_graph->set_title($_GET["graphTitle"]);
$task_graph->set_width($_GET["graphWidth"]);
$task_graph->init($objects);
$task_graph->draw_grid();

foreach ($tasks as $task) {
    $task_graph->draw_task($task);
}

$task_graph->draw_milestones();
$task_graph->draw_today();
$task_graph->draw_legend();
$task_graph->output();
