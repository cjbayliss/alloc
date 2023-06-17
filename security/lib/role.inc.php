<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class role extends db_entity
{
    public $data_table = "role";
    public $key_field = "roleID";
    public $data_fields = ["roleHandle", "roleName", "roleLevel", "roleSequence"];

    public static function get_roles_array($level = "person")
    {
        $rows = [];
        $dballoc = new db_alloc();
        $q = unsafe_prepare("SELECT * FROM role WHERE roleLevel = '%s' ORDER BY roleSequence", $level);
        $dballoc->query($q);
        while ($row = $dballoc->row()) {
            $rows[$row["roleHandle"]] = $row["roleName"];
        }
        return $rows;
    }
}
