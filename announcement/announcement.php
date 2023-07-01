<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

$announcement = new announcement();
$page = new Page();

$announcementID = $_POST['announcementID'] ?? $_GET['announcementID'] ?? '';
if ($announcementID) {
    $announcement->set_id($announcementID);
    $announcement->select();
}

// TODO: don't use globals
$announcement->read_globals();

$allocAnnouncementListURL = $page->getURL('url_alloc_announcementList');
if (isset($_POST['save'])) {
    $announcement->set_value('personID', $current_user->get_id());
    $announcement->save();
    alloc_redirect($allocAnnouncementListURL);
} elseif (isset($_POST['delete'])) {
    $announcement->delete();
    alloc_redirect($allocAnnouncementListURL);
    exit;
}

$main_alloc_title = 'Edit Announcement - ' . APPLICATION_NAME;

echo $page->header($main_alloc_title);
echo $page->toolbar();

$allocAnnouncementURL = $page->getURL('url_alloc_announcement');
$heading = $announcement->get_row_value('heading');
$body = $announcement->get_row_value('body');
$displayFromDate = $announcement->get_row_value('displayFromDate');
$displayToDate = $announcement->get_row_value('displayToDate');

echo <<<HTML
    <form action="{$allocAnnouncementURL}" method="post">
    <table class="box"> 
        <tr>
            <th>Announcement</th>
            <th class="right"><a href="{$allocAnnouncementListURL}">Return to Announcement List</a></th>
        </tr>
        <tr>
            <td>Heading</td>
            <td><input type="text" name="heading" size="80" value="{$heading}"></td>
        </tr>
        <tr>
            <td>Display From</td>
            <td>{$page->calendar('displayFromDate', $displayFromDate)}</td>
        </tr>
        <tr>
            <td>Display To</td>
            <td>{$page->calendar('displayToDate', $displayToDate)}</td>
        </tr>
        <tr>
            <td>Body</td>
            <td>{$page->textarea('body', $body, ['height' => 'jumbo'])}</td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <button type="submit" name="delete" value="1" class="delete_button">Delete<i class="icon-trash"></i></button>
                <button type="submit" name="save" value="1" class="save_button default">Save<i class="icon-ok-sign"></i></button>
            </td>
        </tr>
    </table>
    <input type="hidden" name="announcementID" value="{$announcementID}">
    <input type="hidden" name="sessID" value="{$TPL['sessID']}">
    </form>
    HTML;

echo $page->footer();
