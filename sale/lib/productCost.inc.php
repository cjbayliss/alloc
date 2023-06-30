<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: GPL-2.0-or-later
 */

class productCost extends DatabaseEntity
{
    public $classname = 'productCost';

    public $data_table = 'productCost';

    public $key_field = 'productCostID';

    public $data_fields = [
        'tfID',
        'productID',
        'amount'       => ['type' => 'money'],
        'isPercentage' => ['empty_to_null' => false],
        'description',
        'currencyTypeID',
        'tax',
        'productCostActive',
    ];

    public function validate($_ = null)
    {
        $err = [];
        $this->get_value('productID') || ($err[] = 'Missing a Product.');
        $this->get_value('tfID') || ($err[] = 'Missing a Destination TF.');
        $this->get_value('amount') || ($err[] = 'Missing an amount.');

        return parent::validate($err);
    }

    public function delete()
    {
        if ($this->get_id()) {
            $this->set_value('productCostActive', 0);

            return $this->save();
        }
    }
}
