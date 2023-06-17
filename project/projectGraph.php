<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$defaults = [
    "showHeader"      => true,
    "showProject"     => true,
    "padding"         => 1,
    "url_form_action" => $TPL["url_alloc_projectGraph"],
    "form_name"       => "projectSummary_filter",
];

function show_filter()
{
    global $TPL;
    global $defaults;

    $_FORM = Task::load_form_data($defaults);
    $arr = Task::load_task_filter($_FORM);
    is_array($arr) and $TPL = array_merge($TPL, $arr);
    include_template("../task/templates/taskFilterS.tpl");
}

function show_projects($template_name)
{
    $defaults = null;
    global $TPL;
    global $default;
    $_FORM = Task::load_form_data($defaults);
    $arr = Task::load_task_filter($_FORM);
    is_array($arr) and $TPL = array_merge($TPL, $arr);

    if (is_array($_FORM["projectID"])) {
        $projectIDs = $_FORM["projectID"];
        foreach ($projectIDs as $projectID) {
            $project = new project();
            $project->set_id($projectID);
            $project->select();
            $_FORM["projectID"] = [$projectID];
            $TPL["graphTitle"] = urlencode($project->get_value("projectName"));
            $arr = Task::load_task_filter($_FORM);
            is_array($arr) and $TPL = array_merge($TPL, $arr);
            include_template($template_name);
        }
    }
}

$TPL["main_alloc_title"] = "Project Graph - " . APPLICATION_NAME;

include_template("templates/projectGraphM.tpl");
