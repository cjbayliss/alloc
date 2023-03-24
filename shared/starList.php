<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$client_defaults = ["starred" => true];
$clientContact_defaults = ["starred" => true];
$project_defaults = ["starred" => true];
$comment_defaults = ["starred" => true];
$productSale_defaults = ["starred" => true];

$task_defaults = [
    "showHeader"  => true,
    "showTaskID"  => true,
    "showStarred" => true,
    "showStatus"  => true,
    "showProject" => true,
    "starred"     => true
];

$timeSheet_defaults = [
    "starred" => true,
    "noextra" => true
];

$invoice_defaults = [
    "showHeader"            => true,
    "showInvoiceNumber"     => true,
    "showInvoiceClient"     => true,
    "showInvoiceName"       => true,
    "showInvoiceAmount"     => true,
    "showInvoiceAmountPaid" => true,
    "showInvoiceDate"       => true,
    "showInvoiceStatus"     => true,
    "starred"               => true
];

$star_entities = [
    "client"        => [
        "label" => "Clients",
        "form" => $client_defaults
    ],
    "clientContact" => [
        "label" => "Contacts",
        "form" => $clientContact_defaults
    ],
    "project"       => [
        "label" => "Projects",
        "form" => $project_defaults
    ],
    "task"          => [
        "label" => "Tasks",
        "form" => $task_defaults
    ],
    "comment"       => [
        "label" => "Comments",
        "form" => $comment_defaults
    ],
    "timeSheet"     => [
        "label" => "Time Sheets",
        "form" => $timeSheet_defaults
    ],
    "invoice"       => [
        "label" => "Invoices",
        "form" => $invoice_defaults
    ],
    "productSale"   => [
        "label" => "Sales",
        "form" => $productSale_defaults
    ],
];

$TPL["star_entities"] = $star_entities;


include_template("templates/starListM.tpl");
