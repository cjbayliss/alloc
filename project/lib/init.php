<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


require_once(dirname(__FILE__)."/import_export.inc.php");

class project_module extends module
{
    var $module = "project";
    var $db_entities = array("project",
                             "projectPerson",
                             "projectCommissionPerson");
    var $home_items = array("project_list_home_item");
}
