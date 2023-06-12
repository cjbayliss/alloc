<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$current_user->check_employee();

$defaults = [
    "showHeader"            => true,
    "showInvoiceNumber"     => true,
    "showInvoiceClient"     => true,
    "showInvoiceName"       => true,
    "showInvoiceAmount"     => true,
    "showInvoiceAmountPaid" => true,
    "showInvoiceDate"       => true,
    "showInvoiceStatus"     => true,
    "url_form_action"       => $TPL["url_alloc_invoiceList"],
    "form_name"             => "invoiceList_filter",
];

function show_filter()
{
    global $TPL;
    global $defaults;
    $invoice = new invoice();
    $_FORM = $invoice->load_form_data($defaults);
    $arr = $invoice->load_invoice_filter($_FORM);
    is_array($arr) and $TPL = array_merge($TPL, $arr);

    $payment_statii = $invoice->get_invoice_statii_payment();
    $summary = "";
    $nbsp = "";
    foreach ($payment_statii as $payment_status => $label) {
        $summary .= "\n" . $nbsp . $invoice->get_invoice_statii_payment_image($payment_status) . " " . $label;
        $nbsp = "&nbsp;&nbsp;";
    }
    $TPL["status_legend"] = $summary;

    include_template("templates/invoiceListFilterS.tpl");
}

$invoice = new invoice();
$_FORM = $invoice->load_form_data($defaults);

// Restrict non-admin users records
if (!$current_user->have_role("admin")) {
    $_FORM["personID"] = $current_user->get_id();
}
$TPL["invoiceListRows"] = invoice::get_list($_FORM);
$TPL["_FORM"] = $_FORM;

if (!$current_user->prefs["invoiceList_filter"]) {
    $TPL["message_help"][] = "

allocPSA allows you to create Invoices for your Clients and record the
payment status of those Invoices. This page allows you to view a list of
Invoices.

<br><br>

Simply adjust the filter settings and click the <b>Filter</b> button to
display a list of previously created Invoices.
If you would prefer to create a new Invoice, click the <b>New Invoice</b> link
in the top-right hand corner of the box below.";
}

$TPL["main_alloc_title"] = "Invoice List - " . APPLICATION_NAME;
include_template("templates/invoiceListM.tpl");
