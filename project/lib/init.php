<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


require_once(__DIR__ . "/import_export.inc.php");

class project_module extends module
{
    public $module = "project";
    public $db_entities = [
        "project",
        "projectPerson",
        "projectCommissionPerson"
    ];
    public $home_items = ["project_list_home_item"];
}
