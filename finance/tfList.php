<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$current_user->check_employee();

if ($_REQUEST["owner"]) {
    $TPL["owner_checked"] = " checked";
} else {
    $TPL["owner_checked"] = "";
}

if ($_REQUEST["showall"]) {
    $TPL["showall_checked"] = " checked";
}

$TPL["main_alloc_title"] = "TF List - " . APPLICATION_NAME;

$TPL["tfListRows"] = tf::get_list($_REQUEST);

include_template("templates/tfListM.tpl");
