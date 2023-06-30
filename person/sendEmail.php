<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define('NO_AUTH', true);
define('IS_GOD', true);
require_once __DIR__ . '/../alloc.php';

if ('Sat' == date('D') || 'Sun' == date('D')) {
    alloc_error("IT'S THE WEEKEND - GET OUTTA HERE", true);
}

// Do announcements ONCE up here.
$announcement = person::get_announcements_for_email();
$db = new AllocDatabase();
$db->query("SELECT personID,emailAddress,firstName,surname FROM person WHERE personActive = '1'");
// AND username='alla'"); // or username=\"ashridah\"");

while ($db->next_record()) {
    $person = new person();
    $person->read_db_record($db);
    $person->set_id($db->f('personID'));
    $person->load_prefs();
    if (!$person->prefs['dailyTaskEmail']) {
        continue;
    }

    $msg = '';
    $tasks = '';
    $to = '';

    if ($announcement['heading']) {
        $msg .= $announcement['heading'];
        $msg .= "\n" . $announcement['body'] . "\n";
        $msg .= "\n- - - - - - - - - -\n";
    }

    if ($person->get_value('emailAddress')) {
        $tasks = $person->get_tasks_for_email();
        $msg .= $tasks;

        // FIXME: ???
        $subject = commentTemplate::populate_string(config::get_config_item('emailSubject_dailyDigest'), '');
        $to = $person->get_value('emailAddress');
        if ($person->get_value('firstName') && $person->get_value('surname') && $to) {
            $to = $person->get_value('firstName') . ' ' . $person->get_value('surname') . ' <' . $to . '>';
        }

        if ($tasks && $to) {
            $email = new email_send($to, $subject, $msg, 'daily_digest');
            if ($email->send()) {
                echo "\n<br>Sent email to: " . $to;
            }
        }
    }
}
