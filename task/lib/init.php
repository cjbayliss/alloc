<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class task_module extends Module
{
    public $module = 'task';

    public $databaseEntities = ['task'];

    public $home_items = ['TaskListHomeItem', 'TaskMessageListHomeItem'];
}
