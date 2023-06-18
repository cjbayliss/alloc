<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class announcements_home_item extends home_item
{
    public function __construct()
    {
        parent::__construct(
            "announcements",
            "Announcements",
            "announcement",
            "announcementsH.tpl",
            "standard",
            10
        );
    }

    public function visible()
    {
        $announcement = new announcement();
        return $announcement->has_announcements();
    }

    public function render()
    {
        return true;
    }

    public function show_announcements($template_name)
    {
        global $TPL;

        $allocDatabase = new AllocDatabase();
        $allocDatabase->connect();

        $getAnnoucements = $allocDatabase->pdo->query(
            "SELECT *
               FROM announcement
              WHERE displayFromDate <= CURRENT_DATE()
                AND displayToDate >= CURRENT_DATE()
              ORDER BY displayFromDate desc"
        );

        while ($annoucmentRow = $getAnnoucements->fetch(PDO::FETCH_ASSOC)) {
            $announcement = new announcement();
            $announcement->read_row_record($annoucmentRow);
            $announcement->set_tpl_values();
            $person = $announcement->get_foreign_object("person");
            $TPL["personName"] = $person->get_name();
            include_template($this->get_template_dir() . $template_name);
        }
    }
}
