<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");


function get_all_source_files($dir = "")
{
    global $TPL;
    $dir or $dir = ALLOC_MOD_DIR;

    if (path_under_path($dir, ALLOC_MOD_DIR) && is_dir($dir)) {
        $dir = realpath($dir);
        $handle = opendir($dir);
        while (false !== ($file = readdir($handle))) {
            clearstatcache();

            if ($file == ".") {
                continue;
            }
            if ($file == ".." && realpath($dir) == realpath(ALLOC_MOD_DIR)) {
                continue;
            }

            if (is_file($dir.DIRECTORY_SEPARATOR.$file)) {
                $image = "<img border=\"0\" alt=\"icon\" src=\"".$TPL["url_alloc_images"]."/fileicons/unknown.gif\">";
                $files[$file] = "<a href=\"".$TPL["url_alloc_sourceCodeView"]."dir=".urlencode($dir)."&file=".urlencode($file)."\">".$image.$file."</a>";
            } else if (is_dir($dir.DIRECTORY_SEPARATOR.$file)) {
                $image = "<img border=\"0\" alt=\"icon\" src=\"".$TPL["url_alloc_images"]."/fileicons/directory.gif\">";
                $dirs[$file] = "<a href=\"".$TPL["url_alloc_sourceCodeList"]."dir=".urlencode($dir.DIRECTORY_SEPARATOR.$file)."\">".$image.$file."</a>";
            } else {
                #echo "<br>wtf: ".$dir.DIRECTORY_SEPARATOR.$file;
            }
        }
    }


    $files or $files = array();
    $dirs or $dirs = array();
    asort($files);
    asort($dirs);
    $rtn = array_merge($dirs, $files);
    return $rtn;
}


$files = get_all_source_files($_GET["dir"]);
if (is_array($files)) {
    foreach ($files as $file => $link) {
        $TPL["results"].= "<p style=\"padding:0px; margin:4px\">".$link."</p>";
    }
}



include_template("templates/sourceCodeListM.tpl");
