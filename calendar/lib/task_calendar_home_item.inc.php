<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class task_calendar_home_item extends home_item
{
    public $date;

    function __construct()
    {
        $this->has_config = true;
        parent::__construct("task_calendar_home_item", "Calendar", "calendar", "taskCalendarS.tpl", "standard", 30);
    }

    function visible()
    {
        $current_user = &singleton("current_user");

        if (!isset($current_user->prefs["showCalendarHome"])) {
            $current_user->prefs["showCalendarHome"] = 1;
            $current_user->prefs["tasksGraphPlotHome"] = 4;
            $current_user->prefs["tasksGraphPlotHomeStart"] = 1;
        }

        if ($current_user->prefs["showCalendarHome"]) {
            return true;
        }
    }

    function render()
    {
        return true;
    }

    function show_task_calendar_recursive()
    {
        $current_user = &singleton("current_user");
        $tasksGraphPlotHomeStart = $current_user->prefs["tasksGraphPlotHomeStart"];
        $tasksGraphPlotHome = $current_user->prefs["tasksGraphPlotHome"];
        $calendar = new calendar($tasksGraphPlotHomeStart, $tasksGraphPlotHome);
        $calendar->set_cal_person($current_user->get_id());
        $calendar->set_return_mode("home");
        $calendar->draw($template);
    }
}
