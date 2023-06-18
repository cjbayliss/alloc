<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class timeSheetHomeItem extends home_item
{

    public function __construct()
    {
        parent::__construct("time_edit", "New Time Sheet Item", "time", "timeSheetH.tpl", "narrow", 24);
    }

    public function visible()
    {
        $current_user = &singleton("current_user");

        if (!isset($current_user->prefs["showTimeSheetItemHome"])) {
            $current_user->prefs["showTimeSheetItemHome"] = 1;
        }

        if ($current_user->prefs["showTimeSheetItemHome"]) {
            return true;
        }
    }

    public function render(): bool
    {
        $current_user = &singleton("current_user");
        global $TPL;
        return true;
    }
}
