<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class timeSheetListHomeItem extends home_item
{

    function __construct()
    {
        $this->has_config = true;
        parent::__construct("time_list", "Current Time Sheets", "time", "timeSheetListH.tpl", "narrow", 30);
    }

    function visible()
    {
        $current_user = &singleton("current_user");
        return isset($current_user) && $current_user->is_employee();
    }

    function render()
    {
        $current_user = &singleton("current_user");
        global $TPL;
        $ops["showShortProjectLink"] = "true";
        $ops["personID"] = $current_user->get_id();
        $ops["status"] = ['edit', 'manager', 'admin', 'invoiced', 'rejected'];

        $rtn = timeSheet::get_list($ops);
        $TPL["timeSheetListRows"] = $rtn["rows"];
        $TPL["timeSheetListExtra"] = $rtn["extra"];
        if ($TPL["timeSheetListRows"]) {
            return true;
        }
    }
}
