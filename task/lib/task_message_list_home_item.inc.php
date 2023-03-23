<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class task_message_list_home_item extends home_item
{
    public $date;

    function __construct()
    {
        parent::__construct("task_message_list_home_item", "Messages For You", "task", "taskMessageListH.tpl", "narrow", 19);
    }

    function visible()
    {
        $current_user = &singleton("current_user");
        return $current_user->has_messages();
    }

    function render()
    {
        return true;
    }

    function show_tasks()
    {
        $current_user = &singleton("current_user");
        global $tasks_date;

        list($ts_open, $ts_pending, $ts_closed) = task::get_task_status_in_set_sql();
        $q = prepare("SELECT *
                        FROM task
                       WHERE (task.taskStatus NOT IN (" . $ts_closed . ") AND task.taskTypeID = 'Message')
                         AND (personID = %d)
                    ORDER BY priority
                     ", $current_user->get_id());

        $db = new db_alloc();
        $db->query($q);

        while ($db->next_record()) {
            $task = new task();
            $task->read_db_record($db);
            echo $br . $task->get_task_image() . $task->get_task_link(["return" => "html"]);
            $br = "<br>";
        }
    }
}
