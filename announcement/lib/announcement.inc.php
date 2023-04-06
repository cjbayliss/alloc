<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class announcement extends db_entity
{
    public $data_table = "announcement";
    public $display_field_name = "heading";
    public $key_field = "announcementID";
    public $data_fields = [
        "heading",
        "body",
        "personID",
        "displayFromDate",
        "displayToDate",
    ];

    public function has_announcements()
    {
        $db = new db_alloc();
        $today = date("Y-m-d");
        $query = unsafe_prepare("select * from announcement where displayFromDate <= '%s' and displayToDate >= '%s'", $today, $today);
        $db->query($query);
        if ($db->next_record()) {
            return true;
        }
        return false;
    }
}
