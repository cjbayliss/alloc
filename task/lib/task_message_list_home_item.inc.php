<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class task_message_list_home_item extends home_item
{
    public $date;

    public function __construct()
    {
        parent::__construct('task_message_list_home_item', 'Messages For You', 'task', 'taskMessageListH.tpl', 'narrow', 19);
    }

    public function visible()
    {
        $current_user = &singleton('current_user');

        return $current_user->has_messages();
    }

    public function render(): bool
    {
        return true;
    }

    public function show_tasks()
    {
        $br = null;
        $current_user = &singleton('current_user');
        global $tasks_date;

        [$ts_open, $ts_pending, $ts_closed] = Task::get_task_status_in_set_sql();
        $q = unsafe_prepare('SELECT *
                        FROM task
                       WHERE (task.taskStatus NOT IN (' . $ts_closed . ") AND task.taskTypeID = 'Message')
                         AND (personID = %d)
                    ORDER BY priority
                     ", $current_user->get_id());

        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);

        while ($allocDatabase->next_record()) {
            $task = new Task();
            $task->read_db_record($allocDatabase);
            echo $br . $task->get_task_image() . $task->get_task_link(['return' => 'html']);
            $br = '<br>';
        }
    }
}
