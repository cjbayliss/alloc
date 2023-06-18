<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once(__DIR__ . "/../alloc.php");

$info["host"] = config::get_config_item("allocEmailHost");
$info["port"] = config::get_config_item("allocEmailPort");
$info["username"] = config::get_config_item("allocEmailUsername");
$info["password"] = config::get_config_item("allocEmailPassword");
$info["protocol"] = config::get_config_item("allocEmailProtocol");

if (!$info["host"]) {
    alloc_error("Email mailbox host not defined, assuming email function is inactive.", true);
}

$email_receive = new email_receive($info);
$email_receive->open_mailbox(config::get_config_item("allocEmailFolder"), OP_HALFOPEN | OP_READONLY);
$email_receive->set_msg($_REQUEST["id"]);
$new_nums = $email_receive->get_new_email_msg_uids();
if (in_array($_REQUEST["id"], (array)$new_nums)) {
    $new = true;
}

$mail_text = $email_receive->fetch_mail_text();
$new && $email_receive->set_unread(); // might have to "unread" the email, if it was new, i.e. set it back to new
$email_receive->close();

echo nl2br(trim(Page::htmlentities($mail_text)));
