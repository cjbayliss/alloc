<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


class finance_module extends module
{
    var $module = "finance";
    var $db_entities = array("tf", "transaction", "expenseForm", "tfPerson", "transactionRepeat");
    var $home_items = array("tfList_home_item","pendingAdminExpenseForm");
}
