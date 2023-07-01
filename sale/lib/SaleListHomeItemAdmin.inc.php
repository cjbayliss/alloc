<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class SaleListHomeItemAdmin extends HomeItem
{
    private array $productSales;

    public function __construct()
    {
        parent::__construct(
            'sale_list_admin',
            'Sales Pending Admin',
            'sale',
            'narrow',
            38,
        );
    }

    public function visible(): bool
    {
        $current_user = &singleton('current_user');

        return (bool) isset($current_user) && $current_user->have_role('admin');
    }

    public function render(): bool
    {
        $this->productSales = (new productSale())->get_list([
            'return' => 'array',
            'status' => ['admin'],
        ]);

        return (bool) $this->productSales;
    }

    public function getHTML(): string
    {
        if (!$this->productSales) {
            return '<b>No Sales Found.</b>';
        }

        $html = '';
        $page = new Page();
        foreach ($this->productSales as $productSale) {
            $clientName = $page->escape($productSale['clientName']);
            $totalPrice = is_numeric($productSale['amounts']['total_sellPrice']) ? $productSale['amounts']['total_sellPrice'] : '';
            $html .= <<<HTML
                <tr>
                    <td>{$productSale['productSaleLink']}</td>
                    <td>{$clientName}</td>
                    <td>{$productSale['status']}</td>
                    <td class="nobr right obfuscate">{$totalPrice}</td>
                </tr>
                HTML;
        }

        return <<<'HTML'
            <table class="list sortable">
              <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Status</th>
                    <th class="right">Amount</th>
              </tr>
              {$html}
            </table>
            HTML;
    }
}
