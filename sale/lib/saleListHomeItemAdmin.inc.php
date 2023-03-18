<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class saleListHomeItemAdmin extends home_item
{

    function __construct()
    {
        parent::__construct("sale_list_admin", "Sales Pending Admin", "sale", "saleListHomeM.tpl", "narrow", 38);
    }

    function visible()
    {
        $current_user = &singleton("current_user");
        return isset($current_user) && $current_user->have_role("admin");
    }

    function render()
    {
        $current_user = &singleton("current_user");
        global $TPL;
        $ops["return"] = "array";
        $ops["status"] = array("admin");
        $rows = productSale::get_list($ops);
        $TPL["saleListRows"] = $rows;
        if ($TPL["saleListRows"]) {
            return true;
        }
    }
}
