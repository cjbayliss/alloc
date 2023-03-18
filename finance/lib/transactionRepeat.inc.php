<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class transactionRepeat extends db_entity
{
    public $data_table = "transactionRepeat";
    public $display_field_name = "product";
    public $key_field = "transactionRepeatID";
    public $data_fields = array("companyDetails" => array("empty_to_null"=>false),
                                "payToName" => array("empty_to_null"=>false),
                                "payToAccount" => array("empty_to_null"=>false),
                                "tfID",
                                "fromTfID",
                                "emailOne",
                                "emailTwo",
                                "transactionStartDate",
                                "transactionFinishDate",
                                "transactionRepeatModifiedUser",
                                "reimbursementRequired" => array("empty_to_null"=>false),
                                "transactionRepeatModifiedTime",
                                "transactionRepeatCreatedTime",
                                "transactionRepeatCreatedUser",
                                "paymentBasis",
                                "amount" => array("type"=>"money"),
                                "currencyTypeID",
                                "product",
                                "status",
                                "transactionType");


    function is_owner()
    {
        $tf = new tf();
        $tf->set_id($this->get_value("tfID"));
        $tf->select();
        return $tf->is_owner();
    }
}
