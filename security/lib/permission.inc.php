<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class permission extends DatabaseEntity
{
    public $data_table = 'permission';

    public $display_field_name = 'tableName';

    public $key_field = 'permissionID';

    public $data_fields = [
        'tableName',
        'entityID',
        'roleName' => ['empty_to_null' => false],
        'actions',
        'sortKey',
        'comment',
    ];

    public function describe_actions()
    {
        $actions = $this->get_value('actions');
        $description = '';

        $entity_class = $this->get_value('tableName');

        if (isset(Meta::$tables[$entity_class])) {
            $entity = new Meta($entity_class);
            $permissions = $entity->permissions;
        } elseif (class_exists($entity_class)) {
            $entity = new $entity_class();
            $permissions = $entity->permissions;
        } else {
            return '';
        }

        foreach ((array) $permissions as $a => $d) {
            if ((($actions & $a) == $a) && '' != $d) {
                if ('' !== $description && '0' !== $description) {
                    $description .= ',';
                }

                $description .= $d;
            }
        }

        return $description;
    }

    public static function get_roles()
    {
        return [
            'god'      => 'Super User',
            'admin'    => 'Finance Admin',
            'manage'   => 'Project Manager',
            'employee' => 'Employee',
            'client'   => 'Client',
        ];
    }
}
