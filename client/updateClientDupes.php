<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

$index = new Zend_Search_Lucene(ATTACHMENTS_DIR . 'search/client');
$index->setResultSetLimit(10);
$needle = "name:{$_GET['clientName']}";
$query = Zend_Search_Lucene_Search_QueryParser::parse($needle);
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

    // FIXME: this comment prevents inteliphese breaking HEREDOC on format,
    // remove once PHP 7.3 is supported
}

if (!empty($result)) {
    echo $result;
}
