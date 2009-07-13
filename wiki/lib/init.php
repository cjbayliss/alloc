<?php

/*
 * Copyright (C) 2006, 2007, 2008 Alex Lance, Clancy Malcolm, Cybersource
 * Pty. Ltd.
 * 
 * This file is part of the allocPSA application <info@cyber.com.au>.
 * 
 * allocPSA is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at
 * your option) any later version.
 * 
 * allocPSA is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with allocPSA. If not, see <http://www.gnu.org/licenses/>.
*/


require_once(dirname(__FILE__)."/markdown.inc.php");
require_once(dirname(__FILE__)."/vcs.inc.php");
require_once(dirname(__FILE__)."/vcs_darcs.inc.php");
require_once(dirname(__FILE__)."/vcs_mercurial.inc.php");
require_once(dirname(__FILE__)."/vcs_git.inc.php");


class wiki_module extends module {

  function get_wiki_path() {
    return realpath(ATTACHMENTS_DIR."wiki").DIRECTORY_SEPARATOR;
  }

  function file_save($file,$body) {
    if (is_dir(dirname($file)) && path_under_path(dirname($file), wiki_module::get_wiki_path())) {
      // Save the file ...
      $handle = fopen($file,"w+b");
      fputs($handle,$body);
      fclose($handle);
    }
  }

  function nuke_trailing_spaces_from_all_lines($str) {
    // for some reason trailing slashes on a line appear to not get saved by
    // particular vcs's. So when we compare the two files (the one on disk and
    // the one in version control, we need to nuke trailing spaces, from every
    // line.
    $lines or $lines = array();
    $str = str_replace("\r\n","\n",$str);
    $bits = explode("\n",$str);
    foreach($bits as $line) {
      $lines[] = rtrim($line);
    }
    return rtrim(implode("\n",$lines));
  }

  function get_file($file, $rev="") {
    global $TPL;

    // Get the regular revision ...
    $disk_file = file_get_contents(wiki_module::get_wiki_path().$file) or $disk_file = "";

    $vcs = vcs::get();
    //$vcs->debug = true;

    // Get a particular revision
    if ($vcs) {
      $vcs_file = $vcs->cat(urldecode(wiki_module::get_wiki_path().$file), urldecode($rev));
    }

    if ($vcs && wiki_module::nuke_trailing_spaces_from_all_lines($disk_file) != wiki_module::nuke_trailing_spaces_from_all_lines($vcs_file)) {

      if (!$vcs_file) {
        $TPL["msg"] = "<div class='message warn' style='margin-top:0px; margin-bottom:10px; padding:10px;'>
                        Warning: This file may not be under version control.
                       </div>";
      } else {
        $TPL["msg"] = "<div class='message warn' style='margin-top:0px; margin-bottom:10px; padding:10px;'>
                        Warning: This file may not be the latest version.
                       </div>";
      }
    }

    if ($rev && $vcs_file) {
      $TPL["str"] = $vcs_file;
    } else {
      $TPL["str"] = $disk_file;
    }
    $wikiMarkup = config::get_config_item("wikiMarkup");
    $TPL["str_html"] = $wikiMarkup($TPL["str"]);
    $TPL["rev"] = urlencode($rev);
    include_template("templates/fileGetM.tpl");
  }


}




?>