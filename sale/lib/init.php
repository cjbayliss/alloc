<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: GPL-2.0-or-later
 */

class sale_module extends module
{
    public $module = "sale";
    public $db_entities = [
        "product",
        "productCost",
        "productSale",
        "productSaleItem",
    ];
    public $home_items = ["saleListHomeItemAdmin", "saleListHomeItem"];
}
