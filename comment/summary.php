<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once(__DIR__ . "/../alloc.php");

if ($_REQUEST["filter"]) {
    $current_user->prefs["comment_summary_list"] = $_REQUEST;
} else {
    $_REQUEST = $current_user->prefs["comment_summary_list"];
}

$TPL["main_alloc_title"] = "Task Comment Summary";
include_template("templates/summaryM.tpl");
