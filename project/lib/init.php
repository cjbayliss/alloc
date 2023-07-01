<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class project_module extends Module
{
    public $module = 'project';

    public $databaseEntities = [
        'project',
        'projectPerson',
        'projectCommissionPerson',
    ];

    public $home_items = ['ProjectListHomeItem'];
}
