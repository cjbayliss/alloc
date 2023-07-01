<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class TaskCalendarHomeItem extends HomeItem
{
    private bool $has_config = true;

    public function __construct()
    {
        parent::__construct(
            'task_calendar_home_item',
            'Calendar',
            'calendar',
            'standard',
            30,
        );
    }

    public function visible(): bool
    {
        $current_user = &singleton('current_user');

        if (!isset($current_user->prefs['showCalendarHome'])) {
            $current_user->prefs['showCalendarHome'] = 1;
            $current_user->prefs['tasksGraphPlotHome'] = 4;
            $current_user->prefs['tasksGraphPlotHomeStart'] = 1;
        }

        return (bool) $current_user->prefs['showCalendarHome'];
    }

    public function render(): bool
    {
        return true;
    }

    private function show_task_calendar_recursive(): string
    {
        $current_user = &singleton('current_user');
        $tasksGraphPlotHomeStart = $current_user->prefs['tasksGraphPlotHomeStart'];
        $tasksGraphPlotHome = $current_user->prefs['tasksGraphPlotHome'];
        $calendar = new calendar($tasksGraphPlotHomeStart, $tasksGraphPlotHome);
        $calendar->setPerson($current_user->get_id());
        $calendar->setReturnMode('home');

        return $calendar->draw();
    }

    public function get_config(): bool
    {
        return $this->has_config;
    }

    public function getHTML(): string
    {
        return $this->show_task_calendar_recursive();
    }
}
