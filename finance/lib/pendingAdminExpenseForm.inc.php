<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class pendingAdminExpenseForm extends home_item
{

    public function __construct()
    {
        parent::__construct("pending_admin_expense_form", "Expense Forms Pending Admin Approval", "finance", "pendingAdminExpenseFormM.tpl", "narrow", 42);
    }

    public function visible()
    {
        $current_user = &singleton("current_user");
        if (isset($current_user) && $current_user->have_role("admin")) {
            return true;
        }
    }

    public function render()
    {
        $ops = [];
        global $TPL;
        $ops["status"] = "pending";
        $ops["finalised"] = 1;
        $TPL["expenseFormRows"] = expenseForm::get_list($ops);
        if (count($TPL["expenseFormRows"])) {
            return true;
        }
    }
}
