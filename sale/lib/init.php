<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: GPL-2.0-or-later
 */

class sale_module extends Module
{
    public $module = 'sale';

    public $databaseEntities = [
        'product',
        'productCost',
        'productSale',
        'productSaleItem',
    ];

    public $home_items = ['saleListHomeItemAdmin', 'saleListHomeItem'];
}
