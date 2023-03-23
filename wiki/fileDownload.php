<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

$file = realpath(wiki_module::get_wiki_path() . $_GET["file"]);

if (path_under_path(dirname($file), wiki_module::get_wiki_path())) {
    $fp = fopen($file, "rb");
    $mimetype = get_mimetype($file);
    $disposition = "attachment";
    preg_match("/jpe?g|gif|png/i", basename($file)) and $disposition = "inline";
    header('Content-Type: ' . $mimetype);
    header("Content-Length: " . filesize($file));
    header('Content-Disposition: ' . $disposition . '; filename="' . basename($file) . '"');
    fpassthru($fp);
    exit;
}
