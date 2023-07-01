<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class TaskMessageListHomeItem extends HomeItem
{
    public function __construct()
    {
        parent::__construct(
            'task_message_list_home_item',
            'Messages For You',
            'task',
            'narrow',
            19,
        );
    }

    public function visible(): bool
    {
        $current_user = &singleton('current_user');

        return $current_user->has_messages();
    }

    public function render(): bool
    {
        return true;
    }

    private function showMessageTasksHTML(): string
    {
        $current_user = &singleton('current_user');

        [,, $taskStatusClosed] = (new Task())->get_task_status_in_set_sql();
        $q = unsafe_prepare('SELECT *
                        FROM task
                       WHERE (task.taskStatus NOT IN (' . $taskStatusClosed . ") AND task.taskTypeID = 'Message')
                         AND (personID = %d)
                    ORDER BY priority
                     ", $current_user->get_id());

        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);

        $html = '';
        while ($allocDatabase->next_record()) {
            $task = new Task();
            $task->read_db_record($allocDatabase);
            $html .= ($br ?? '') . $task->get_task_image() . $task->get_task_link(['return' => 'html']);
            $br = '<br>';
        }

        return $html;
    }

    public function getHTML(): string
    {
        return $this->showMessageTasksHTML();
    }
}
