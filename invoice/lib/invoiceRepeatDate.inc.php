<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class invoiceRepeatDate extends db_entity
{
    public $classname = "invoiceRepeatDate";
    public $data_table = "invoiceRepeatDate";
    public $key_field = "invoiceRepeatDateID";
    public $data_fields = ["invoiceRepeatID", "invoiceDate"];
}
