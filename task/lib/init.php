<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


class task_module extends module
{
    var $module = "task";
    var $db_entities = array("task");
    var $home_items = array("task_list_home_item","task_message_list_home_item");
}
