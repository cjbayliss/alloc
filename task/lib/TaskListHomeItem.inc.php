<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class TaskListHomeItem extends HomeItem
{
    private array $defaults;

    private array $taskListRows;

    private bool $has_config = true;

    public $date;

    public function __construct()
    {
        parent::__construct(
            'top_ten_tasks',
            'Tasks',
            'task',
            'standard',
            20,
        );
    }

    public function visible(): bool
    {
        $current_user = &singleton('current_user');

        if (!isset($current_user->prefs['showTaskListHome'])) {
            $current_user->prefs['showTaskListHome'] = 1;
        }

        return (bool) $current_user->prefs['showTaskListHome'];
    }

    public function render(): bool
    {
        $task = new Task();
        $current_user = &singleton('current_user');

        $defaults = [
            'showHeader'      => true,
            'showTaskID'      => true,
            'taskView'        => 'prioritised',
            'showStatus'      => 'true',
            'url_form_action' => (new Page())->getURL('url_alloc_home'),
            'form_name'       => 'taskListHome_filter',
        ];

        if (!isset($current_user->prefs['taskListHome_filter'])) {
            $defaults['taskStatus'] = 'open';
            $defaults['personID'] = $current_user->get_id();
            $defaults['showStatus'] = true;
            $defaults['showProject'] = true;
            $defaults['limit'] = 10;
            $defaults['applyFilter'] = true;
        }

        $this->defaults = $task->load_form_data($defaults);
        $this->taskListRows = $task->get_list($this->defaults);

        return true;
    }

    public function get_config()
    {
        return $this->has_config;
    }

    public function getHTML(): string
    {
        return (new Task())->listHTML($this->taskListRows, $this->defaults);
    }
}
