<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


require_once("../alloc.php");

$defaults = [
    "showProjectType" => true,
    "url_form_action" => $TPL["url_alloc_projectList"],
    "form_name"       => "projectList_filter"
];

function show_filter()
{
    global $TPL;
    global $defaults;

    $_FORM = project::load_form_data($defaults);
    $arr = project::load_project_filter($_FORM);
    is_array($arr) and $TPL = array_merge($TPL, $arr);
    include_template("templates/projectListFilterS.tpl");
}


$_FORM = project::load_form_data($defaults);
$TPL["projectListRows"] = project::get_list($_FORM);
$TPL["_FORM"] = $_FORM;


if (!$current_user->prefs["projectList_filter"]) {
    $TPL["message_help"][] = "

allocPSA helps you manage Projects. This page allows you to see a list of
Projects.

<br><br>

Simply adjust the filter settings and click the <b>Filter</b> button to
display a list of previously created Projects.
If you would prefer to create a new Project, click the <b>New Project</b> link
in the top-right hand corner of the box below.";
}





$TPL["main_alloc_title"] = "Project List - " . APPLICATION_NAME;
include_template("templates/projectListM.tpl");
