<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

if ($_GET["projectID"]) {
    usleep(300000);
    $project = new project();
    $project->set_id($_GET["projectID"]);
    $project->select();
    $tf_sel = $project->get_value("cost_centre_tfID") or $tf_sel = config::get_config_item("mainTfID");
    $tf = new tf();
    $options = page::select_options($tf->get_assoc_array("tfID", "tfName"), $tf_sel);
    echo "<select id=\"tfID\" name=\"tfID\">" . $options . "</select>";
}
