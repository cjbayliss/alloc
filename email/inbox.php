<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';
singleton('errors_thrown', true);

if (!have_entity_perm('inbox', PERM_READ, $current_user)) {
    alloc_error('Permission denied.', true);
}

$info = inbox::get_mail_info();

if (!$info['host']) {
    alloc_error('Email mailbox host not defined, assuming email function is inactive.', true);
}

if ($_REQUEST['id'] && $_REQUEST['hash'] && !inbox::verify_hash($_REQUEST['id'], $_REQUEST['hash'])) {
    alloc_error('The IMAP ID for that email is no longer valid. Refresh the list and try again.');
} elseif ($_REQUEST['id'] && $_REQUEST['hash']) {
    $_REQUEST['archive'] && inbox::archive_email($_REQUEST);
    // archive the email by moving it to another folder
    $_REQUEST['download'] && inbox::download_email($_REQUEST);
    // download it to a mbox file
    $_REQUEST['process'] && inbox::process_email($_REQUEST);
    // attach it to a task etc
    $_REQUEST['readmail'] && inbox::read_email($_REQUEST);
    // mark the email as read
    $_REQUEST['unreadmail'] && inbox::unread_email($_REQUEST);
    // mark the email as unread
    $_REQUEST['newtask'] && inbox::process_email_to_task($_REQUEST);
    // use this email to create a new task
    $_REQUEST['taskID'] && inbox::attach_email_to_existing_task($_REQUEST);
    // attach email as new comment thread onto existing task
    alloc_redirect($TPL['url_alloc_inbox']);
}

$TPL['rows'] = inbox::get_list();

include_template('templates/inboxM.tpl');
