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

define("NO_AUTH",1);
require_once("../alloc.php");

// Get list of patch files in order
$abc123_files = get_patch_file_list();

// Get the most recently applied patch
$abc123_applied_patches = get_applied_patches();


// Hack to update everyones patch tree
if (!in_array("patch-00053-alla.php",$abc123_applied_patches)) {
  apply_patch(ALLOC_MOD_DIR."patches/patch-00053-alla.php");
}


// Apply all patches
if ($_GET["apply_patches"] || $_POST["apply_patches"]) {
  foreach ($abc123_files as $abc123_file) {
    $abc123_f = ALLOC_MOD_DIR."patches/".$abc123_file;
    if (!in_array($abc123_file,$abc123_applied_patches)) {
      apply_patch($abc123_f);
    }
  }

// Apply a single patch
} else if ($_POST["apply_patch"] && $_POST["patch_file"]) {
  $abc123_f = ALLOC_MOD_DIR."patches/".$_POST["patch_file"];
  if (!in_array($abc123_file,$abc123_applied_patches)) {
    apply_patch($abc123_f);
  }
}



$abc123_applied_patches = get_applied_patches();
foreach ($abc123_files as $abc123_file) {
  if (!in_array($abc123_file,$abc123_applied_patches)) {
    $abc123_incomplete = true;
  }
}


if (!$abc123_incomplete) {
  header("Location: ".$TPL["url_alloc_login"]);
} 


include_template("templates/patch.tpl");

?>
