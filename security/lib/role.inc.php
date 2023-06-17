<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class role extends DatabaseEntity
{
    public $data_table = "role";
    public $key_field = "roleID";
    public $data_fields = ["roleHandle", "roleName", "roleLevel", "roleSequence"];

    public static function get_roles_array($level = "person")
    {
        $rows = [];
        $allocDatabase = new AllocDatabase();
        $q = unsafe_prepare("SELECT * FROM role WHERE roleLevel = '%s' ORDER BY roleSequence", $level);
        $allocDatabase->query($q);
        while ($row = $allocDatabase->row()) {
            $rows[$row["roleHandle"]] = $row["roleName"];
        }
        return $rows;
    }
}
