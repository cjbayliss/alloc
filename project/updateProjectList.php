<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

if ($_GET["projectStatus"]) {
    usleep(400000);
    $options = project::get_list_dropdown_options($_GET["projectStatus"]);
    echo "<select name=\"copy_projectID\">" . $options . "</select>";
}
