<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

use ZendSearch\Lucene\Index;
use ZendSearch\Lucene\Lucene;
use ZendSearch\Lucene\Search\QueryParser;

define("NO_REDIRECT", 1);
require_once(__DIR__ . "/../alloc.php");

$index = new Index(ATTACHMENTS_DIR . 'search/client');
Lucene::setResultSetLimit(10);
$needle = sprintf('name:%s', $_GET['clientName']);
$query = QueryParser::parse($needle);
$matches = $index->find($needle);

$result = null;
foreach ($matches as $match) {
    $document = $match->getDocument();
    $clientID = $document->getFieldValue('id');
    $clientName = $document->getFieldValue('name');
    $result = <<<HTML
            <div style='padding-bottom:3px'>
            <a href="{$TPL['url_alloc_client']}clientID={$clientID}">{$clientID} {$clientName}</a>
            </div>
        HTML;
}

if ($result === null) {
    return;
}

if ($result === '') {
    return;
}

echo $result;
