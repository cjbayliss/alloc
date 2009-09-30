<?php

/*
 * Copyright (C) 2006, 2007, 2008 Alex Lance, Clancy Malcolm, Cybersource
 * Pty. Ltd.
 * 
 * This file is part of the allocPSA application <info@cyber.com.au>.
 * 
 * allocPSA is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at
 * your option) any later version.
 * 
 * allocPSA is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with allocPSA. If not, see <http://www.gnu.org/licenses/>.
*/

class task_message_list_home_item extends home_item {
  var $date;

  function task_message_list_home_item() {
    home_item::home_item("task_message_list_home_item", "Messages For You", "task", "taskMessageListH.tpl", "narrow", 19);
  }

  function show_tasks() {
    global $current_user, $tasks_date;
    $q = sprintf("SELECT * 
                  FROM task 
                  WHERE (task.taskStatus != 'closed' AND task.taskTypeID = %d) 
                  AND (personID = %d) 
                  ORDER BY priority
                 ",TT_MESSAGE,$current_user->get_id());

    $db = new db_alloc;
    $db->query($q);

    while ($db->next_record()) {
      $task = new task;
      $task->read_db_record($db);
      echo $br.$task->get_task_image().$task->get_task_link();
      $br = "<br>";
    }
  }
}



?>
