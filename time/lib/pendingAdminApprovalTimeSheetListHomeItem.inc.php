<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class pendingAdminApprovalTimeSheetListHomeItem extends home_item
{
    public function __construct()
    {
        parent::__construct(
            'pending_admin_time_list',
            'Time Sheets Pending Admin Approval',
            'time',
            'pendingAdminApprovalTimeSheetHomeM.tpl',
            'narrow',
            22
        );
    }

    public function visible()
    {
        $current_user = &singleton('current_user');
        if (isset($current_user) && $current_user->is_employee()) {
            $timeSheetAdminPersonIDs = config::get_config_item('defaultTimeSheetAdminList');
            if (in_array($current_user->get_id(), $timeSheetAdminPersonIDs) && has_pending_admin_timesheet()) {
                return true;
            }
        }
    }

    public function render(): bool
    {
        return true;
    }

    public function show_pending_time_sheets($template_name, $doAdmin = false)
    {
        show_time_sheets_list_for_classes($template_name, $doAdmin);
    }
}
