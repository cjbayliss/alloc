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

require_once("../alloc.php");

$current_user->check_employee();

if ($_POST["owner"]) {
  $TPL["owner_checked"] = " checked";
  $filter[] = sprintf("tfPerson.personID = %d",$current_user->get_id());
} else {
  $TPL["owner_checked"] = "";
}

if ($_POST["showall"]) {
  $TPL["showall_checked"] = " checked";
} else {
  $filter[] = "status = 'active'";
}

$TPL["main_alloc_title"] = "TF List - ".APPLICATION_NAME;

include_template("templates/tfListM.tpl");

function show_tf($template_name) {
  global $TPL, $filter;

  if (is_array($filter) && count($filter)) {
    $f = " WHERE ".implode(" AND ",$filter);
  }

  $db = new db_alloc;
  $q = sprintf("SELECT tf.* FROM tf LEFT JOIN tfPerson ON tf.tfID = tfPerson.tfID %s GROUP BY tf.tfID ORDER BY tf.tfName",$f);  
  $db->query($q);

  while ($db->next_record()) {

    $tf = new tf;
    $tf->read_db_record($db);
    $tf->set_tpl_values();

    if (have_entity_perm("transaction", PERM_READ, $current_user, $tf->is_owner())) {
      $TPL["tfBalance"] = sprintf("%0.2f",$tf->get_balance());
      $grand_total += $tf->get_balance();
    } else {
      $TPL["tfBalance"] = "not available";
    }
    $TPL["odd_even"] = $TPL["odd_even"] == "odd" ? "even" : "odd";

    $nav_links = $tf->get_nav_links();
    $TPL["nav_links"] = implode(" ", $nav_links);

    if ($tf->have_perm(PERM_UPDATE)) {
      $edit_url = $TPL["url_alloc_tf"]."tfID=".$tf->get_id();
      $TPL["tfName"] = "<a href=\"".$edit_url."\">".$tf->get_value("tfName")."</a>";
    }



    include_template($template_name);
  }

  $TPL["grand_total"] = sprintf("%0.2f",$grand_total);

}


?>
