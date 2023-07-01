<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class TaggedFundsListHomeItem extends HomeItem
{
    private array $taggedFunds;

    public function __construct()
    {
        parent::__construct(
            '',
            'Tagged Funds',
            'finance',
            'narrow',
            20,
        );
    }

    public function visible(): bool
    {
        return true;
    }

    public function render(): bool
    {
        // FIXME: what is the significance of 'owner = 1'? straight up no
        // permisions?
        $this->taggedFunds = (new tf())->get_list(['owner' => 1]);

        return (bool) $this->taggedFunds;
    }

    public function getHTML(): string
    {
        if ([] === $this->taggedFunds) {
            return '<b>No Accounts Found.</b>';
        }

        $page = new Page();
        $config = new config();

        $allocTransationListURL = $page->getURL('url_alloc_transactionList');

        // FIXME: if this is doing maths for tagged funds, it should be
        // seperated into its own method so that it is clear *what* is being
        // done.
        $grand_total = 0;
        $grand_total_pending = 0;
        $taggedFundsListHTML = '';
        foreach ($this->taggedFunds as $taggedFund) {
            $taggedFundName = $page->escape($taggedFund['tfName']);
            $taggedFundsListHTML .= <<<HTML
                    <tr>
                        <td><a href="{$allocTransationListURL}tfID={$taggedFund['tfID']}">{$taggedFundName}</a></td>
                        <td class="right nobr transaction-pending obfuscate">{$taggedFund['tfBalancePending']}</td>
                        <td class="right nobr transaction-approved obfuscate">{$taggedFund['tfBalance']}</td>
                    </tr>
                HTML;
            $grand_total += $taggedFund['total'];
            $grand_total_pending += $taggedFund['pending_total'];
        }

        $tableFooterHTML = '';
        if (count($this->taggedFunds) > 1) {
            $tableFooterHTML = <<<HTML
                    <tfoot>
                    <tr>
                        <td>&nbsp;</td>
                        <td class="grand_total right transaction-pending obfuscate">
                            {$page->money($config->get_config_item('currency'), $grand_total_pending, '%s%m %c')}
                        </td>
                        <td class="grand_total right transaction-approved obfuscate">
                            {$page->money($config->get_config_item('currency'), $grand_total, '%s%m %c')}
                        </td>
                    </tr>
                    </tfoot>
                HTML;
        }

        return <<<HTML
                <table class="list sortable">
                    <tr>
                        <th>Account</th>
                        <th class="right">Pending</th>
                        <th class="right">Balance</th>
                    </tr>
                    {$taggedFundsListHTML}
                    {$tableFooterHTML}
                </table>
            HTML;
    }
}
