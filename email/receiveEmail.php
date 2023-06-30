<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define('NO_AUTH', 1);
require_once __DIR__ . '/../alloc.php';
singleton('errors_fatal', false);
singleton('errors_format', 'text');
singleton('errors_logged', false);
singleton('errors_thrown', true);
singleton('errors_haltdb', true);

$nl = "<br>\n";
$info = inbox::get_mail_info();

if (!$info['host']) {
    alloc_error('Email mailbox host not defined, assuming email receive function is inactive.', true);
}

$email_receive = new email_receive($info);
$email_receive->open_mailbox(config::get_config_item('allocEmailFolder'));
$email_receive->check_mail();
$num_new_emails = $email_receive->get_num_new_emails();

if ($num_new_emails > 0) {
    $msg_nums = $email_receive->get_new_email_msg_uids();
    echo $nl . date('Y-m-d H:i:s') . ' Found ' . (is_countable($msg_nums) ? count($msg_nums) : 0) . ' new/unseen emails.' . $nl;
    foreach ($msg_nums as $msg_num) {
        // Errors from previous iterations shouldn't affect processing of the next email
        AllocDatabase::$stop_doing_queries = false;

        $email_receive->set_msg($msg_num);
        $email_receive->get_msg_header();
        $keys = $email_receive->get_hashes();

        try {
            // If no keys
            if (!$keys) {
                // If email sent from a known staff member
                $from_staff = inbox::change_current_user($email_receive->mail_headers['from']);
                if ($from_staff) {
                    inbox::convert_email_to_new_task($email_receive, true);
                } else {
                    $email_receive->mark_seen(); // mark it seen so we don't poll for it again
                    alloc_error('Could not create a task from this email. Email was not sent by a staff member. Email resides in INBOX.');
                }

                // Else if we have a key, append to comment
            } elseif (same_email_address($email_receive->mail_headers['from'], ALLOC_DEFAULT_FROM_ADDRESS)) {
                // Skip over emails that are from alloc. These emails are kept only for
                // posterity and should not be parsed and downloaded and re-emailed etc.
                $email_receive->mark_seen();
                $email_receive->archive();
            } else {
                inbox::process_one_email($email_receive);
            }
        } catch (Exception $e) {
            // There may have been a database error, so let the database know it can run this next bit
            AllocDatabase::$stop_doing_queries = false;

            // Try forwarding the errant email
            try {
                $email_receive->forward(
                    config::get_config_item('allocEmailAdmin'),
                    'Email command failed',
                    "\n" . $e->getMessage() . "\n\n" . $e->getTraceAsString()
                );

                // If that fails, try last-ditch email send
            } catch (Exception $e) {
                mail(config::get_config_item('allocEmailAdmin'), 'Email command failed(2)', "\n" . $e->getMessage() . "\n\n" . $e->getTraceAsString());
            }
        }
    }
}

$email_receive->expunge();
$email_receive->close();
