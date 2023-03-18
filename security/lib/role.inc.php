<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class role extends db_entity
{
    public $data_table = "role";
    public $key_field = "roleID";
    public $data_fields = array("roleHandle",
                                "roleName",
                                "roleLevel",
                                "roleSequence");

    function get_roles_array($level = "person")
    {
        $rows = array();
        $db = new db_alloc();
        $q = prepare("SELECT * FROM role WHERE roleLevel = '%s' ORDER BY roleSequence", $level);
        $db->query($q);
        while ($row = $db->row()) {
            $rows[$row["roleHandle"]] = $row["roleName"];
        }
        return $rows;
    }
}
