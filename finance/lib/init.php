<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class finance_module extends module
{
    public $module = "finance";
    public $db_entities = [
        "tf",
        "transaction",
        "expenseForm",
        "tfPerson",
        "transactionRepeat",
    ];
    public $home_items = ["tfList_home_item", "pendingAdminExpenseForm"];
}
