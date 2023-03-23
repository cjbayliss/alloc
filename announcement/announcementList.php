<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

function show_announcements($template_name)
{
    global $TPL;

    $people = &get_cached_table("person");
    $query = "SELECT announcement.*
                FROM announcement
            ORDER BY displayFromDate DESC";
    $db = new db_alloc();
    $db->query($query);
    while ($db->next_record()) {
        $announcement = new announcement();
        $announcement->read_db_record($db);
        $announcement->set_values();
        $TPL["personName"] = $people[$announcement->get_value("personID")]["name"];
        $TPL["odd_even"] = $TPL["odd_even"] == "odd" ? "even" : "odd";
        include_template($template_name);
    }
}

$TPL["main_alloc_title"] = "Announcement List - " . APPLICATION_NAME;

include_template("templates/announcementListM.tpl");
