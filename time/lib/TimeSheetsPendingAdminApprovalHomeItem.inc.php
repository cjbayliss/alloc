<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class TimeSheetsPendingAdminApprovalHomeItem extends HomeItem
{
    public function __construct()
    {
        parent::__construct(
            'pending_admin_time_list',
            'Time Sheets Pending Admin Approval',
            'time',
            'narrow',
            22,
        );
    }

    public function visible(): bool
    {
        $current_user = &singleton('current_user');
        if (!isset($current_user) || !$current_user->is_employee()) {
            return false;
        }

        if (
            !in_array(
                $current_user->get_id(),
                (new config())->get_config_item('defaultTimeSheetAdminList')
            )
        ) {
            return false;
        }

        return has_pending_admin_timesheet();
    }

    public function render(): bool
    {
        return true;
    }

    private function show_pending_time_sheets($template_name, $doAdmin = false)
    {
        show_time_sheets_list_for_classes($template_name, $doAdmin);
    }

    public function getHTML(): string
    {
        return <<<HTML
                <table class="list sortable">
                    <tr>
                        <th>Time Sheet</th>
                        <th>Person</th>
                        <th class="right">Date</th>
                    </tr>
                    {$this->show_pending_time_sheets('pendingApprovalTimeSheetHomeR.tpl', true)}
                </table>
            HTML;
    }
}
