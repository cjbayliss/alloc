<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

$page = new Page();
$announcements = (new announcement())->getAnnouncements();

$allocAnnouncementURL = $page->getURL('url_alloc_announcement');
$announcementListHTML = '';

foreach ($announcements as $announcement) {
    $postedBy = $page->escape((new person($announcement['personID']))->get_name());
    $heading = $page->escape($announcement['heading']);

    $announcementListHTML .= <<<HTML
            <tr>
                <td>{$heading}</td>
                <td>{$postedBy}</td>
                <td>{$announcement['displayFromDate']}</td>
                <td>{$announcement['displayToDate']}</td>
                <td><a href="{$allocAnnouncementURL}?announcementID={$announcement['announcementID']}">Edit</a></td>
            </tr>
        HTML;
}

$main_alloc_title = 'Announcement List - ' . APPLICATION_NAME;

echo $page->header($main_alloc_title);
echo $page->toolbar();

echo <<<HTML
        <table class="box">
              <tr>
                  <th class="header">Announcements
                      <span>
                          <a href="{$allocAnnouncementURL}">New Announcement</a>
                      </span>
                  </th>
              </tr>
              <tr>
                  <td>
                      <table class="list sortable"> 
                          <tr>
                              <th>Heading</th>
                              <th>Posted By</th>
                              <th>Display From</th>
                              <th>Display To</th>
                              <th>Action</th>
                          </tr>
                          {$announcementListHTML}
                      </table>
                  </td>
              </tr>
        </table>
    HTML;

echo $page->footer();
