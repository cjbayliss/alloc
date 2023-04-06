<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class saleListHomeItem extends home_item
{

    public function __construct()
    {
        parent::__construct("sale_list", "Sales", "sale", "saleListHomeM.tpl", "narrow", 39);
    }

    public function visible()
    {
        $current_user = &singleton("current_user");
        return isset($current_user) && $current_user->is_employee();
    }

    public function render()
    {
        $ops = [];
        $current_user = &singleton("current_user");
        global $TPL;
        $ops["return"] = "array";
        $ops["personID"] = $current_user->get_id();
        $ops["status"] = ["admin", "allocate", "edit"];
        $rows = productSale::get_list($ops);
        $TPL["saleListRows"] = $rows;
        if ($TPL["saleListRows"]) {
            return true;
        }
    }
}
