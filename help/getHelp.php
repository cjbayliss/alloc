<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

if ($_GET['topic']) {
    $topic = $_GET['topic'];
    $TPL['str'] = @file_get_contents($TPL['url_alloc_help'] . $topic . '.html');
} else {
    $TPL['str'] = 'No valid help topic specified.';
}

include_template('templates/getHelpM.tpl');
