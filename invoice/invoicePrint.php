<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

if (!$current_user->is_employee()) {
    alloc_error("You do not have permission to access invoices", true);
}

$invoiceID = $_POST["invoiceID"] or $invoiceID = $_GET["invoiceID"];
$verbose = $_GET["verbose"];

$invoice = new invoice();
$invoice->set_id($invoiceID);
$invoice->select();
$invoice->generate_invoice_file($verbose);
