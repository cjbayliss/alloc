<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$TPL["alloc_version"] = get_alloc_version();
$TPL["main_alloc_title"] = "Help - " . APPLICATION_NAME;

include_template("templates/helpM.tpl");
