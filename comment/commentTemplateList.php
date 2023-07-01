<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

$page = new Page();
$url_alloc_commentTemplate = $page->getURL('url_alloc_commentTemplate');
global $TPL;

$commentTemplates = (new commentTemplate())->getCommentTemplates();
$commentTemplateHTML = '';
foreach ($commentTemplates as $commentTemplate) {
    $commentTemplateID = $commentTemplate['commentTemplateID'];
    $commentTemplateName = $page->escape($commentTemplate['commentTemplateName']);
    $commentTemplateHTML = <<<HTML
            <tr>
                <td><a href="{$url_alloc_commentTemplate}?commentTemplateID={$commentTemplateID}">{$commentTemplateID}</a></td>
                <td>{$commentTemplateName}</td>
                <td>{$commentTemplate['commentTemplateType']}</td>
            </tr>
        HTML;
}

$main_alloc_title = 'Comment Template List - ' . APPLICATION_NAME;

$page->header($main_alloc_title);
$page->toolbar();

echo <<<HTML
    <table class="box">
      <tr>
        <th class="header">Comment Templates
          <span>
            <a href="{$url_alloc_commentTemplate}">New Comment Template</a>
          </span>
        </th>
      </tr>
      <tr>
        <td>
          <table class="list sortable">
            <tr>
              <th width="1%" data-sort="num">ID</th>
              <th>Template</th>
              <th>Type</th>
            </tr>
            {$commentTemplateHTML}
          </table>
        </td>
      </tr>
    </table>
    HTML;
$page->footer();
