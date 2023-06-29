<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once(__DIR__ . "/../alloc.php");

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
    "starred"     => true,
];

$timeSheet_defaults = [
    "starred" => true,
    "noextra" => true,
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
    "starred"               => true,
];

$star_entities = [
    "client" => [
        "label" => "Clients",
        "form"  => $client_defaults,
    ],
    "clientContact" => [
        "label" => "Contacts",
        "form"  => $clientContact_defaults,
    ],
    "project" => [
        "label" => "Projects",
        "form"  => $project_defaults,
    ],
    "Task" => [
        "label" => "Tasks",
        "form"  => $task_defaults,
    ],
    "comment" => [
        "label" => "Comments",
        "form"  => $comment_defaults,
    ],
    "timeSheet" => [
        "label" => "Time Sheets",
        "form"  => $timeSheet_defaults,
    ],
    "invoice" => [
        "label" => "Invoices",
        "form"  => $invoice_defaults,
    ],
    "productSale" => [
        "label" => "Sales",
        "form"  => $productSale_defaults,
    ],
];

$page = new Page();
$page->header();
$page->toolbar();

foreach ($star_entities as $entity => $e) {
    $rows = [];
    has($entity) && ($rows = $entity::get_list($e["form"]));
    if ($rows) {
        $total = is_countable($rows) ? count($rows) : 0;
        $printed_something = true;
        $listHTML = $entity::get_list_html($rows, $e["form"]);
        echo <<<HTML
            <table class="box">
                <tr>
                    <th class="header">
                        {$e["label"]}
                        <b> - {$total} records</b>
                    </th>
                </tr>
                <tr>
                    <td>
                        {$listHTML}
                    </td>
                </tr>
            </table>
            HTML;
    }
}

if (!isset($printed_something)) {
    echo <<<HTML
        <br><br>
        No items have been starred yet.
        <br><br>
        Go and click the stars on the task list page (for example), and then use this page to quickly get back to your
        favourites.
        <br><br>
        HTML;
}

$page->footer();
