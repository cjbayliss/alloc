<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: GPL-2.0-or-later
 */

class product extends DatabaseEntity
{
    public $classname = "product";
    public $data_table = "product";
    public $display_field_name = "productName";
    public $key_field = "productID";
    public $data_fields = [
        "productName",
        "sellPrice" => [
            "type"     => "money",
            "currency" => "sellPriceCurrencyTypeID",
        ],
        "sellPriceCurrencyTypeID",
        "sellPriceIncTax" => [],
        "description",
        "comment",
        "productActive",
    ];

    public function delete()
    {
        $this->set_value("productActive", 0);
        $this->save();
    }

    public function get_list_filter($filter)
    {
        $sql = null;
        // stub function for one day when you can filter products
        return $sql;
    }

    public static function get_list($_FORM = [])
    {

        $f = null;
        $rows = [];
        $filter = (new product())->get_list_filter($_FORM);

        $debug = $_FORM["debug"];
        $debug and print "\n<pre>_FORM: " . print_r($_FORM, 1) . "</pre>";
        $debug and print "\n<pre>filter: " . print_r($filter, 1) . "</pre>";

        if (is_array($filter) && count($filter)) {
            $f = " WHERE " . implode(" AND ", $filter);
        }

        // Put the inactive ones down the bottom.
        $f .= " ORDER BY productActive DESC, productName";

        $taxName = config::get_config_item("taxName");

        $query = unsafe_prepare("SELECT * FROM product " . $f);
        $dballoc = new db_alloc();
        $dballoc->query($query);
        while ($row = $dballoc->next_record()) {
            $product = new product();
            $product->read_db_record($dballoc);
            $row["taxName"] = $taxName;
            $rows[] = $row;
        }

        return $rows;
    }

    public function get_link($row = [])
    {
        global $TPL;
        if (is_object($this)) {
            return "<a href=\"" . $TPL["url_alloc_product"] . "productID=" . $this->get_id() . "\">" . $this->get_value("productName", DST_HTML_DISPLAY) . "</a>";
        } else {
            return "<a href=\"" . $TPL["url_alloc_product"] . "productID=" . $row["productID"] . "\">" . page::htmlentities($row["productName"]) . "</a>";
        }
    }

    public function get_list_vars()
    {
        // stub function for one day when you can specify list parameters
        return [];
    }

    public static function get_buy_cost($id = false)
    {
        $amount = null;
        $id or $id = $this->get_id();
        $dballoc = new db_alloc();
        $q = unsafe_prepare("SELECT amount, currencyTypeID, tax
                        FROM productCost
                       WHERE isPercentage != 1
                         AND productID = %d
                         AND productCostActive = true
                     ", $id);
        $dballoc->query($q);
        while ($row = $dballoc->row()) {
            if ($row["tax"]) {
                [$amount_minus_tax, $amount_of_tax] = tax($row["amount"]);
                $row["amount"] = $amount_minus_tax;
            }
            $amount += exchangeRate::convert($row["currencyTypeID"], $row["amount"]);
        }
        return $amount;
    }

    public function get_list_html($rows = [], $_FORM = [])
    {
        global $TPL;
        $TPL["productListRows"] = $rows;
        $_FORM["taxName"] = config::get_config_item("taxName");
        $TPL["_FORM"] = $_FORM;
        include_template(__DIR__ . "/../templates/productListS.tpl");
    }
}
