<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

$defaults = [
    "showHeader"      => true,
    "showTaskID"      => true,
    "taskView"        => "prioritised",
    "showStatus"      => "true",
    "url_form_action" => $TPL["url_alloc_settings"],
    "form_name"       => "taskListHome_filter",
];

$_FORM = Task::load_form_data($defaults);
$arr = Task::load_task_filter($_FORM);
is_array($arr) and $TPL = array_merge($TPL, $arr);
$TPL["showCancel"] = true;
include_template("../task/templates/taskFilterS.tpl");
