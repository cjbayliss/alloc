<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


require_once("../alloc.php");
include("lib/task_graph.inc.php");

if ($_GET["projectID"]) {
    $options["projectIDs"][] = $_GET["projectID"];
}

$options["personID"] = $_GET["personID"];
$options["taskView"] = "prioritised";
$options["return"] = "array";
$options["taskStatus"] = "open";
$options["showTaskID"] = true;

if ($_GET["graph_type"] == "phases") {
    $options["taskTypeID"] = 'Parent';
}

$task_graph = new task_graph();
$task_graph->set_title($_GET["graphTitle"]);
$task_graph->set_width($_GET["graphWidth"]);
$task_graph->bottom_margin = 20;

$tasks = task::get_list($options) or $tasks = [];

foreach ($tasks as $task) {
    $objects[$task["taskID"]] = $task["object"];
}

$task_graph->init($objects);
$task_graph->draw_grid();

foreach ($tasks as $task) {
    $task_graph->draw_task($task);
}

$task_graph->draw_milestones();
$task_graph->draw_today();
$task_graph->output();
