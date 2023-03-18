<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$prohibited[] = "alloc_config.php";

if ($_GET["dir"] && $_GET["file"]) {
    $path = realpath($_GET["dir"].DIRECTORY_SEPARATOR.$_GET["file"]);
    $TPL["path"] = $path;
    if (path_under_path($path, ALLOC_MOD_DIR) && is_file($path) && !in_array(basename($path), $prohibited)) {
        $TPL["results"] = page::htmlentities(file_get_contents($path));
    }
}


include_template("templates/sourceCodeView.tpl");
