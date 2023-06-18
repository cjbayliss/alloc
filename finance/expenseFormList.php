<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once(__DIR__ . "/../alloc.php");

$ops["status"] = "pending";
$TPL["expenseFormRows"] = expenseForm::get_list($ops);
$TPL["transactionRows"] = expenseForm::get_pending_repeat_transaction_list();
$TPL["main_alloc_title"] = "Expense Form List - " . APPLICATION_NAME;
include_template("templates/expenseFormListM.tpl");
