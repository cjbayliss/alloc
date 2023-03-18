<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


define("NO_REDIRECT", 1);
require_once("../alloc.php");

usleep(400000);

$db = new db_alloc();
if ($_GET['current']) {
    $filter = " WHERE projectStatus = 'Current'";
}
$query = prepare("SELECT projectID AS value, projectName AS label FROM project $filter ORDER by projectName");

echo '<select name="projectID[]" multiple="true" style="width:100%">'.page::select_options($query, null, 70).'</select>';
