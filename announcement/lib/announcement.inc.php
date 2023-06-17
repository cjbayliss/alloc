<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class announcement extends DatabaseEntity
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
        $allocDatabase = new AllocDatabase();
        $allocDatabase->connect();

        $getAnnouncements = $allocDatabase->pdo->query(
            "SELECT * from announcement 
              where displayFromDate <= CURRENT_DATE()
                and displayToDate >= CURRENT_DATE()"
        );
        if ($getAnnouncements->fetch(PDO::FETCH_ASSOC)) {
            return true;
        }

        return false;
    }
}
