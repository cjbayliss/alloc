<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define('NO_REDIRECT', 1);
require_once __DIR__ . '/../alloc.php';

usleep(400000);

$db = new AllocDatabase();
if ($_GET['current']) {
    $filter = " WHERE projectStatus = 'Current'";
}

$query = unsafe_prepare(sprintf('SELECT projectID AS value, projectName AS label FROM project %s ORDER by projectName', $filter));

echo '<select name="projectID[]" multiple="true" style="width:100%">' . Page::select_options($query, null, 70) . '</select>';
