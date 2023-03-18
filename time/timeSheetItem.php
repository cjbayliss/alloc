<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

if (!$current_user->is_employee()) {
    alloc_error("You do not have permission to access time sheets", true);
}

$timeSheetID = $_POST["timeSheetID"];
$timeSheetItemID = $_POST["timeSheetItem_timeSheetItemID"];

if (($_POST["timeSheetItem_save"] || $_POST["timeSheetItem_edit"] || $_POST["timeSheetItem_delete"]) && $timeSheetID) {
    $timeSheet = new timeSheet();
    $timeSheet->set_id($timeSheetID);
    $timeSheet->select();
    $timeSheet->load_pay_info();

    $timeSheetItem = new timeSheetItem();
    if ($timeSheetItemID) {
        $timeSheetItem->set_id($timeSheetItemID);
        $timeSheetItem->select();
    }
    $timeSheetItem->read_globals();
    $timeSheetItem->read_globals("timeSheetItem_");

    if ($_POST["timeSheetItem_save"]) {
        $timeSheetItem->read_globals();
        $timeSheetItem->read_globals("timeSheetItem_");
        $rtn = $timeSheetItem->save();
        $rtn and $TPL["message_good"][] = "Time Sheet Item saved.";
        $_POST["timeSheetItem_taskID"] and $t = "&taskID=".$_POST["timeSheetItem_taskID"];
        alloc_redirect($TPL["url_alloc_timeSheet"]."timeSheetID=".$timeSheetID.$t);
    } else if ($_POST["timeSheetItem_edit"]) {
        alloc_redirect($TPL["url_alloc_timeSheet"]."timeSheetID=".$timeSheetID."&timeSheetItem_edit=true&timeSheetItemID=".$timeSheetItem->get_id());
    } else if ($_POST["timeSheetItem_delete"]) {
        $timeSheetItem->select();
        $timeSheetItem->delete();
        $TPL["message_good"][] = "Time Sheet Item deleted.";
        alloc_redirect($TPL["url_alloc_timeSheet"]."timeSheetID=".$timeSheetID);
    }
}
