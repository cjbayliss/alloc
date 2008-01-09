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

class announcement_module extends module {
  var $db_entities = array("announcement");

  function register_home_items() {
    include(ALLOC_MOD_DIR."announcement/lib/announcements_home_item.inc.php");
    $announcement = new announcement;
    if ($announcement->has_announcements()) {
      register_home_item(new announcements_home_item());
    } 
  }

}

include(ALLOC_MOD_DIR."announcement/lib/announcement.inc.php");



?>
