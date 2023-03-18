<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

$file = $_GET["file"];
$rev = $_GET["rev"];
$pathfile = realpath(wiki_module::get_wiki_path().$file);

if (path_under_path(dirname($pathfile), wiki_module::get_wiki_path())) {
    // Check if we're using a VCS
    $vcs = vcs::get();
    #$vcs->debug = true;
    if (is_object($vcs)) {
        $logs = $vcs->log($pathfile);
        $logs = $vcs->format_log($logs);
        foreach ($logs as $id => $bits) {
            unset($class);
            if (is_file($pathfile)) {
                $rev == $id and $class = "highlighted";
                !$rev && !$done and $done = $class = "highlighted";
            }
            echo "<div class=\"".$class."\" style=\"padding:3px; margin-bottom:10px;\">";
            is_file($pathfile) and print sprintf("<a href='%starget=%s&rev=%s'>", $TPL["url_alloc_wiki"], urlencode($file), urlencode($id));
            echo $bits["author"]." ".$bits["date"]."<br>".$bits["msg"];
            is_file($pathfile) and print "</a>";
            echo "</div>";
        }
    }
}
