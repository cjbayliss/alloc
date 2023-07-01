<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class TimeSheetListHomeItem extends HomeItem
{
    private bool $has_config = true;

    private array $timeSheets;

    private array $timeSheetsExtraInfo;

    public function __construct()
    {
        parent::__construct(
            'time_list',
            'Current Time Sheets',
            'time',
            'narrow',
            30,
        );
    }

    public function visible(): bool
    {
        $current_user = &singleton('current_user');

        return isset($current_user) && $current_user->is_employee();
    }

    public function render(): bool
    {
        $current_user = &singleton('current_user');

        $timeSheetList = (new timeSheet())->get_list([
            'showShortProjectLink' => 'true',
            'personID'             => $current_user->get_id(),
            'status'               => [
                'edit',
                'manager',
                'admin',
                'invoiced',
                'rejected',
            ],
        ]);

        $this->timeSheets = $timeSheetList['rows'];
        $this->timeSheetsExtraInfo = $timeSheetList['extra'];

        return (bool) $this->timeSheets;
    }

    public function get_config(): bool
    {
        return $this->has_config;
    }

    public function getHTML(): string
    {
        if ([] === $this->timeSheets) {
            return '<b>No Time Sheets Found.</b>';
        }

        $html = '';
        $page = new Page();
        if ($this->timeSheets) {
            $html .= <<<'HTML'
                <table class="list sortable">
                    <tr>
                        <th>Project</th>
                        <th>Status</th>
                        <th class="right">Amount</th>
                    </tr>
                HTML;
            $rejected = '';
            foreach ($this->timeSheets as $timeSheet) {
                if ('Rejected' == $timeSheet['status']) {
                    $rejected .= '<span class="warn" title="This timesheet needs to be re-submitted.">' . $timeSheet['status'] . '</span>';
                }

                $hoursWarn = $timeSheet['hoursWarn'] ?? '';
                $daysWarn = $timeSheet['daysWarn'] ?? '';

                $html .= <<<HTML
                    <tr>
                    <td>{$hoursWarn}{$daysWarn}{$timeSheet['projectLink']}</td>
                    <td>{$rejected}</td>
                    <td class="nobr right obfuscate">{$page->money($timeSheet['currencyTypeID'], $timeSheet['amount'], '%s%m %c')}</td>
                    </tr>
                    HTML;
            }

            if ([] !== $this->timeSheets) {
                $html .= <<<HTML
                        <tfoot>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="grand_total right nobr obfuscate">{$this->timeSheetsExtraInfo['amount_tallies']}</td>
                            </tr>
                        </tfoot>
                    HTML;
            }

            $html .= '</table>';
        }

        return $html;
    }
}
