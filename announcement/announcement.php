<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

// initialise the request
require_once __DIR__ . '/../alloc.php';

// create an object to hold an announcement
$announcement = new announcement();

// load the announcement from the database
$announcementID = $_POST['announcementID'] ?? $_GET['announcementID'] ?? '';
if ($announcementID) {
    $announcement->set_id($announcementID);
    $announcement->select();
}

// read announcement variables set by the request
$announcement->read_globals();

// process submission of the form using the save button
if (isset($_POST['save'])) {
    $announcement->set_value('personID', $current_user->get_id());
    $announcement->save();
    alloc_redirect($TPL['url_alloc_announcementList']);
    // process submission of the form using the delete button
} elseif (isset($_POST['delete'])) {
    $announcement->delete();
    alloc_redirect($TPL['url_alloc_announcementList']);
    exit;
}

// load data for display in the template
$announcement->set_values();

$TPL['main_alloc_title'] = 'Edit Announcement - ' . APPLICATION_NAME;

// invoke the page's main template
include_template('templates/announcementM.tpl');
