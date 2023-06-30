<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class timeSheetStatusHomeItem extends home_item
{
    public function __construct()
    {
        parent::__construct('time_status_list', 'Time Sheet Statistics', 'time', 'timeSheetStatusHomeM.tpl', 'narrow', 29);
    }

    public function visible(): bool
    {
        $current_user = &singleton('current_user');

        if (!isset($current_user->prefs['showTimeSheetStatsHome'])) {
            $current_user->prefs['showTimeSheetStatsHome'] = 1;
        }

        if (!isset($current_user)) {
            return false;
        }

        if (!$current_user->is_employee()) {
            return false;
        }

        return (bool) $current_user->prefs['showTimeSheetStatsHome'];
    }

    public function render(): bool
    {
        $current_user = &singleton('current_user');
        global $TPL;
        // Get averages for hours worked over the past fortnight and year
        $timeSheetItem = new timeSheetItem();
        $day = 60 * 60 * 24;
        // mktime(0,0,0,date("m"),date("d")-1, date("Y"))
        $today = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
        $yestA = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')));
        $yestB = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
        $fortn = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 14, date('Y')));

        [$hours_sum_today, $dollars_sum_today] = $timeSheetItem->get_averages($today, $current_user->get_id());
        [$hours_sum_yesterday, $dollars_sum_yesterday] = $timeSheetItem->get_averages($yestA, $current_user->get_id(), null, $yestB);
        [$hours_sum_fortnight, $dollars_sum_fortnight] = $timeSheetItem->get_averages($fortn, $current_user->get_id());
        [$hours_avg_fortnight, $dollars_avg_fortnight] = $timeSheetItem->get_fortnightly_average($current_user->get_id());

        $TPL['hours_sum_today'] = sprintf('%0.2f', $hours_sum_today[$current_user->get_id()] ?? 0);
        $TPL['dollars_sum_today'] = Page::money_print($dollars_sum_today[$current_user->get_id()] ?? []);

        $TPL['hours_sum_yesterday'] = sprintf('%0.2f', $hours_sum_yesterday[$current_user->get_id()] ?? 0);
        $TPL['dollars_sum_yesterday'] = Page::money_print($dollars_sum_yesterday[$current_user->get_id()] ?? []);

        $TPL['hours_sum_fortnight'] = sprintf('%0.2f', $hours_sum_fortnight[$current_user->get_id()] ?? 0);
        $TPL['dollars_sum_fortnight'] = Page::money_print($dollars_sum_fortnight[$current_user->get_id()] ?? []);

        $TPL['hours_avg_fortnight'] = sprintf('%0.2f', $hours_avg_fortnight[$current_user->get_id()] ?? 0);
        $TPL['dollars_avg_fortnight'] = Page::money(config::get_config_item('currency'), $dollars_avg_fortnight[$current_user->get_id()] ?? 0, '%s%m %c');

        $TPL['dateFrom'] = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 28, date('Y')));
        $TPL['dateTo'] = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')));

        return true;
    }
}
