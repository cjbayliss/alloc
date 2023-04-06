<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class task_module extends module
{
    public $module = "task";
    public $db_entities = ["task"];
    public $home_items = ["task_list_home_item", "task_message_list_home_item"];
}
