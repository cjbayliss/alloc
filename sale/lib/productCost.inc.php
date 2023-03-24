<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: GPL-2.0-or-later
 */

class productCost extends db_entity
{
    public $classname = "productCost";
    public $data_table = "productCost";
    public $key_field = "productCostID";
    public $data_fields = [
        "tfID",
        "productID",
        "amount" => ["type" => "money"],
        "isPercentage" => ["empty_to_null" => false],
        "description",
        "currencyTypeID",
        "tax",
        "productCostActive"
    ];

    function validate()
    {
        $err = [];
        $this->get_value("productID")    or $err[] = "Missing a Product.";
        $this->get_value("tfID")         or $err[] = "Missing a Destination TF.";
        $this->get_value("amount")       or $err[] = "Missing an amount.";
        return parent::validate($err);
    }

    function delete()
    {
        if ($this->get_id()) {
            $this->set_value("productCostActive", 0);
            return $this->save();
        }
    }
}
