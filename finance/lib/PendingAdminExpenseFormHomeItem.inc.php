<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class PendingAdminExpenseFormHomeItem extends HomeItem
{
    private array $expenseForms;

    public function __construct()
    {
        parent::__construct(
            'pending_admin_expense_form',
            'Expense Forms Pending Admin Approval',
            'finance',
            'narrow',
            42,
        );
    }

    public function visible(): bool
    {
        $current_user = &singleton('current_user');

        return isset($current_user) && $current_user->have_role('admin');
    }

    public function render(): bool
    {
        $this->expenseForms = expenseForm::get_list([
            'status'    => 'pending',
            'finalised' => 1,
        ]);

        return (is_countable($this->expenseForms) ? count($this->expenseForms) : 0) !== 0;
    }

    public function getHTML(): string
    {
        $html = '';
        $page = new Page();
        $allocExpenseFormURL = $page->getURL('url_alloc_expenseForm');
        foreach ($this->expenseForms as $expenseForm) {
            $expenseFormID = $expenseForm['expenseFormID'];
            $expenseFormCreatedUser = $page->escape($expenseForm['expenseFormCreatedUser']);
            $html .= <<<HTML
                <tr>
                    <td><a href="{$allocExpenseFormURL}?expenseFormID={$expenseFormID}&edit=true">{$expenseFormID}</a></td>
                    <td>{$expenseFormCreatedUser}</td>
                    <td align="right" class="obfuscate">&nbsp;{$expenseForm['formTotal']}</td>
                </tr>
                HTML;
        }

        return <<<'HTML'
            <table class="list sortable">
                <tr>
                    <th width="5%" data-sort="num">ID</th>
                    <th>Created By</th>
                    <th class="right">Form Total</th>
                </tr>
                {$html}
            </table>
            HTML;
    }
}
