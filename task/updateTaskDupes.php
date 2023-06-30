<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

use ZendSearch\Lucene\Index;
use ZendSearch\Lucene\Lucene;
use ZendSearch\Lucene\Search\QueryParser;

define('NO_REDIRECT', 1);
require_once __DIR__ . '/../alloc.php';

$index = new Index(ATTACHMENTS_DIR . 'search/task');
Lucene::setResultSetLimit(10);
$needle = 'name:(' . $_GET['taskName'] . ') AND pid:' . $_GET['projectID'];
$query = QueryParser::parse($needle);
$hits = $index->find($needle);

foreach ($hits as $hit) {
    $d = $hit->getDocument();
    $str .= "<div style='padding-bottom:3px'>";
    $str .= '<a href="' . $TPL['url_alloc_task'] . 'taskID=' . $d->getFieldValue('id') . '">' . $d->getFieldValue('id') . ' ' . $d->getFieldValue('name') . '</a>';
    $str .= '</div>';
}

if (!empty($str)) {
    echo $str;
}
