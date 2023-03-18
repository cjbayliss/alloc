<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$current_user = &singleton("current_user");
$db = new db_alloc();
$invoiceRepeat = new invoiceRepeat($_REQUEST["invoiceRepeatID"]);

if ($_POST["save"]) {
    $invoiceRepeat->set_value("invoiceID", $_POST["invoiceID"]);
    $invoiceRepeat->set_value("message", $_POST["message"]);
    $invoiceRepeat->set_value("active", 1);
    $invoiceRepeat->set_value("personID", $current_user->get_id());
    $invoiceRepeat->save($_POST["frequency"]);
    interestedParty::make_interested_parties("invoiceRepeat", $invoiceRepeat->get_id(), $_POST["commentEmailRecipients"]);
}

alloc_redirect($TPL["url_alloc_invoice"]."invoiceID=".$_POST["invoiceID"]);
