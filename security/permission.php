<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$permission = new permission();
$permissionID = $_POST["permissionID"] or $permissionID = $_GET["permissionID"];

if ($permissionID) {
    $permission->set_id($permissionID);
    $permission->select();
}

$actions_array = $_POST["actions_array"];
if (is_array($actions_array)) {
    $actions = 0;
    foreach ($actions_array as $k => $a) {
        $actions = $actions | $a;
    }
}

$permission->read_globals();
$permission->set_values();

if (!$permission->get_value("tableName")) {
    global $modules;
    $entities = [];

    foreach($modules as $module_name => $module) {
        $mod_entities = $module->db_entities;
        $entities = array_merge($entities, $mod_entities);
    }

    $table_names = [];
    foreach ($entities as $entity_name) {
        $entity = new $entity_name;
        $table_names[] = $entity->data_table;
    }

    $ops = $table_names;
    asort($ops);
    foreach ($ops as $op) {
        $table_name_options[$op] = $op;
    }
    $TPL["tableNameOptions"] = page::select_options($table_name_options, $permission->get_value("tableName"));
    include_template("templates/permissionTableM.tpl");
    exit();
}

if ($_POST["save"]) {
    $permission->set_value("actions", $actions);
    $permission->set_value("comment", rtrim($permission->get_value("comment")));
    $permission->save();
    alloc_redirect($TPL["url_alloc_permissionList"]);
} else if ($_POST["delete"]) {
    $permission->delete();
    alloc_redirect($TPL["url_alloc_permissionList"]);
}

// necessary
$permission->select();

$TPL["roleNameOptions"] = page::select_options(permission::get_roles(), $permission->get_value("roleName"));

$table_name = $_POST["tableName"] or $table_name = $permission->get_value("tableName");
$entity = new $table_name;

foreach ($entity->permissions as $value => $label) {
    if (($permission->get_value("actions") & $value) == $value) {
        $sel[] = $value;
    }
}

$TPL["actionOptions"] = page::select_options($entity->permissions, $sel);

$TPL["main_alloc_title"] = "Edit Permission - " . APPLICATION_NAME;

include_template("templates/permissionM.tpl");
