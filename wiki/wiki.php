<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$TPL["target"] = $_GET["target"] or $TPL["target"] = $_POST["target"];
$TPL["rev"] = $_GET["rev"];
$TPL["wiki_tree"] = ATTACHMENTS_DIR."wiki";

if ($_REQUEST['op'] == 'new') {
    $TPL['newFile'] = 'true';
} else {
    $TPL['newFile'] = '';
}

include_template("templates/wikiM.tpl");
