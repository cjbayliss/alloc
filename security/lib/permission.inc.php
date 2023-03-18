<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class permission extends db_entity
{
    public $data_table = "permission";
    public $display_field_name = "tableName";
    public $key_field = "permissionID";
    public $data_fields = array("tableName",
                                "entityID",
                                "roleName"=>array("empty_to_null"=>false),
                                "actions",
                                "sortKey",
                                "comment");

    function describe_actions()
    {
        $actions = $this->get_value("actions");
        $description = "";

        $entity_class = $this->get_value("tableName");

        if (meta::$tables[$entity_class]) {
            $entity = new meta($entity_class);
            $permissions = $entity->permissions;
        } else if (class_exists($entity_class)) {
            $entity = new $entity_class();
            $permissions = $entity->permissions;
        }

        foreach ((array)$permissions as $a => $d) {
            if ((($actions & $a) == $a) && $d != "") {
                if ($description) {
                    $description.= ",";
                }
                $description.= $d;
            }
        }

        return $description;
    }

    function get_roles()
    {
        return array("god"      => "Super User",
                     "admin"    => "Finance Admin",
                     "manage"   => "Project Manager",
                     "employee" => "Employee",
                     "client"   => "Client");
    }
}
