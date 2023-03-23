<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


define("NO_REDIRECT", 1);
require_once("../alloc.php");

$dont_print_these_dirs = [".", "..", "CVS", ".hg", ".bzr", "_darcs", ".git"];


// relative path
$DIR = urldecode($_POST['dir']);

// full path
$PATH = realpath(wiki_module::get_wiki_path() . $DIR) . DIRECTORY_SEPARATOR;

if (path_under_path($PATH, wiki_module::get_wiki_path()) && is_dir($PATH)) {
    $files = scandir($PATH);
    natcasesort($files);
    $str .= "\n<ul class=\"jqueryFileTree\" style=\"display: none;\">";
    // All dirs
    foreach ($files as $file) {
        if (!in_array($file, $dont_print_these_dirs) && is_dir($PATH . $file)) {
            $str .= "\n  <li class=\"directory collapsed\"><a class=\"file\" href=\"#\" rel=\"" . page::htmlentities($DIR . $file . DIRECTORY_SEPARATOR) . "\">" . page::htmlentities($file) . "</a></li>";
        }
    }

    // All files
    foreach ($files as $file) {
        if (file_exists($PATH . $file) && $file != '.' && $file != '..' && !is_dir($PATH . $file) && is_readable($PATH . $file)) {
            unset($extra);
            !is_writable($PATH . $file) and $extra = "(ro) ";
            $ext = strtolower(preg_replace('/^.*\./', '', $file));
            $str .= "\n  <li class=\"file ext_$ext nobr\">";
            $str .= "\n    <a style=\"position:relative;\" class=\"file nobr\" href=\"#x\" rel=\"" . page::htmlentities($DIR . $file) . "\">" . page::htmlentities($file);
            $str .= "<div class='faint nobr' style='top:0px; position:absolute;'>" . $extra . get_filesize_label($PATH . $file) . "</div></a>";
            $str .= "\n  </li>";
        }
    }
    $str .= "\n</ul>";

    #echo "<pre>".page::htmlentities($str)."</pre>";
    echo $str;
}
