<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

if ($_GET["commentTemplateID"] && $_GET["commentTemplateID"] != "undefined" && $_GET["entity"] && $_GET["entityID"]) {
    $commentTemplate = new commentTemplate();
    $commentTemplate->set_id($_GET["commentTemplateID"]);
    $commentTemplate->select();
    $val = $commentTemplate->get_populated_template($_GET["entity"], $_GET["entityID"]);
    echo page::textarea("comment", $val, ["height" => "medium", "width" => "100%"]);
} else {
    echo page::textarea("comment", $val, ["height" => "medium", "width" => "100%"]);
}

echo "<script>$('textarea:not(.processed)').TextAreaResizer();</script>";
