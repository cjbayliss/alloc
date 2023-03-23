<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


define("NO_REDIRECT", 1);
require_once("../alloc.php");

$index = new Zend_Search_Lucene(ATTACHMENTS_DIR . 'search/task');
$index->setResultSetLimit(10);
$needle = 'name:(' . $_GET["taskName"] . ') AND pid:' . $_GET["projectID"];
$query = Zend_Search_Lucene_Search_QueryParser::parse($needle);
$hits = $index->find($needle);

foreach ($hits as $hit) {
    $d = $hit->getDocument();
    $str .= "<div style='padding-bottom:3px'>";
    $str .= "<a href=\"" . $TPL["url_alloc_task"] . "taskID=" . $d->getFieldValue('id') . "\">" . $d->getFieldValue('id') . " " . $d->getFieldValue('name') . "</a>";
    $str .= "</div>";
}

if ($str) {
    echo $str;
}
