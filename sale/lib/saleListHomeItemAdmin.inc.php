<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class saleListHomeItemAdmin extends home_item
{

    public function __construct()
    {
        parent::__construct("sale_list_admin", "Sales Pending Admin", "sale", "saleListHomeM.tpl", "narrow", 38);
    }

    public function visible()
    {
        $current_user = &singleton("current_user");
        return isset($current_user) && $current_user->have_role("admin");
    }

    public function render()
    {
        $ops = [];
        $current_user = &singleton("current_user");
        global $TPL;
        $ops["return"] = "array";
        $ops["status"] = ["admin"];
        $rows = productSale::get_list($ops);
        $TPL["saleListRows"] = $rows;
        if ($TPL["saleListRows"]) {
            return true;
        }
    }
}
