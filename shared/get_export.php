<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

// Get an exported representation of something (at the moment, only a project)
// Call as: get_export.php?entity=project&id=1&format=planner

require_once("../alloc.php");

if (isset($_GET["id"]) && isset($_GET["entity"])) {
    switch ($_GET["entity"]) {
        case "project":
            switch ($_GET["format"]) {
                case "planner":
                    header('Content-Type: application/xml');
                    header('Content-Disposition: attachment; filename="allocProject.planner"');
                    echo export_gnome_planner(intval($_GET["id"]));
                    break;
                case "csv":
                    header('Content-Type: text/plain');
                    header('Content-Disposition: attachment; filename="allocProject.csv"');
                    echo export_csv(intval($_GET["id"]));
            }
            break;
    }
}
