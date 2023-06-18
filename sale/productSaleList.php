<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once(__DIR__ . "/../alloc.php");

function show_filter()
{
    global $TPL;
    global $defaults;
    $_FORM = productSale::load_form_data($defaults);
    $arr = productSale::load_productSale_filter($_FORM);
    if (is_array($arr)) {
        $TPL = array_merge($TPL, $arr);
    }

    include_template("templates/productSaleListFilterS.tpl");
}

$defaults = [
    "url_form_action" => $TPL["url_alloc_productSaleList"],
    "form_name"       => "productSaleList_filter",
    "return"          => "array",
];

$_FORM = productSale::load_form_data($defaults);
$TPL["productSaleListRows"] = productSale::get_list($_FORM);

if (!$current_user->prefs["productSaleList_filter"]) {
    $TPL["message_help"][] = "

allocPSA allows you to create Sales and Products and allocate the funds from
Sales. This page allows you to view a list of Sales.

<br><br>

Simply adjust the filter settings and click the <b>Filter</b> button to
display a list of previously created Sales.
If you would prefer to create a new Sale, click the <b>New Sale</b> link
in the top-right hand corner of the box below.";
}

$TPL["main_alloc_title"] = "Sales List - " . APPLICATION_NAME;
include_template("templates/productSaleListM.tpl");
