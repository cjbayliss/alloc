<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class transactionRepeat extends DatabaseEntity
{
    public $data_table = "transactionRepeat";
    public $display_field_name = "product";
    public $key_field = "transactionRepeatID";
    public $data_fields = [
        "companyDetails" => ["empty_to_null" => false],
        "payToName"      => ["empty_to_null" => false],
        "payToAccount"   => ["empty_to_null" => false],
        "tfID",
        "fromTfID",
        "emailOne",
        "emailTwo",
        "transactionStartDate",
        "transactionFinishDate",
        "transactionRepeatModifiedUser",
        "reimbursementRequired" => ["empty_to_null" => false],
        "transactionRepeatModifiedTime",
        "transactionRepeatCreatedTime",
        "transactionRepeatCreatedUser",
        "paymentBasis",
        "amount" => ["type" => "money"],
        "currencyTypeID",
        "product",
        "status",
        "transactionType",
    ];

    public function is_owner($ignored = null)
    {
        $tf = new tf();
        $tf->set_id($this->get_value("tfID"));
        $tf->select();
        return $tf->is_owner();
    }
}
