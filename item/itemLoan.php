<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$current_user->check_employee();

$TPL["main_alloc_title"] = "Item Loans - " . APPLICATION_NAME;
include_template("templates/itemLoanM.tpl");




function show_overdue($template_name)
{

    $i = null;
    global $db;
    global $TPL;
    $current_user = &singleton("current_user");

    $db = new db_alloc();
    $temp = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    $today = date("Y", $temp) . "-" . date("m", $temp) . "-" . date("d", $temp);

    $q = prepare("SELECT itemName,itemType,item.itemID,dateBorrowed,dateToBeReturned,loan.personID
                    FROM loan,item
                   WHERE dateToBeReturned < '%s'
                     AND dateReturned = '0000-00-00'
                     AND item.itemID = loan.itemID
                 ", $today);

    if (!have_entity_perm("loan", PERM_READ, $current_user, false)) {
        $q .= prepare("AND loan.personID = %d", $current_user->get_id());
    }

    $db->query($q);

    while ($db->next_record()) {
        $i++;

        $item = new item();
        $loan = new loan();
        $item->read_db_record($db);
        $loan->read_db_record($db);
        $item->set_values();
        $loan->set_values();
        $person = new person();
        $person->set_id($loan->get_value("personID"));
        $person->select();
        $TPL["person"] = $person->get_name();
        $TPL["overdue"] = "<a href=\"" . $TPL["url_alloc_item"] . "itemID=" . $item->get_id() . "&return=true\">Overdue!</a>";

        include_template($template_name);
    }
}
