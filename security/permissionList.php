<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

function show_permission_list($template_name)
{
    global $TPL;

    $roles = permission::get_roles();

    if ($_REQUEST["submit"] || $_REQUEST["filter"] != "") {
        $where = " where tableName like '%".db_esc($_REQUEST["filter"])."%' ";   // TODO: Add filtering to permission list
    }
    $db = new db_alloc();
    $db->query("SELECT * FROM permission $where ORDER BY tableName, sortKey");
    while ($db->next_record()) {
        $permission = new permission();
        $permission->read_db_record($db);
        $permission->set_values();
        $TPL["actions"] = $permission->describe_actions();
        $TPL["odd_even"] = $TPL["odd_even"] == "odd" ? "even" : "odd";
        $TPL["roleName"] = $roles[$TPL["roleName"]];
        include_template($template_name);
    }
}

$TPL["main_alloc_title"] = "Permissions List - ".APPLICATION_NAME;

include_template("templates/permissionListM.tpl");
