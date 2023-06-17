<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

$db = new AllocDatabase();

$product = $_GET["product"];
$quantity = $_GET["quantity"];

$p = new product();
$p->set_id($product);
$p->select();
$p->set_tpl_values();

// Probably not valid XML, but jQuery will parse it.
echo "<data>\n";
echo "<price>" . page::money($TPL["sellPriceCurrencyTypeID"], $TPL["sellPrice"] * $quantity, "%m") . "</price>\n";
echo "<priceCurrency>" . $TPL["sellPriceCurrencyTypeID"] . "</priceCurrency>\n";
echo "<priceTax>" . ($TPL["sellPriceIncTax"] ? "1" : "") . "</priceTax>\n";
echo "<description>" . $TPL["description"] . "</description>\n";
echo "</data>\n";
