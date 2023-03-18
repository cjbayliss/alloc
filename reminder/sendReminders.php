<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_AUTH", true);
require_once("../alloc.php");

$db = new db_alloc();

// do advanced notice emails
$query = prepare("SELECT *
                    FROM reminder
                   WHERE reminderActive = 1
                     AND reminderAdvNoticeSent = 0
                     AND NOW() >
                         CASE
                           WHEN reminderAdvNoticeInterval = 'Minute' THEN DATE_SUB(reminderTime, INTERVAL reminderAdvNoticeValue MINUTE)
                           WHEN reminderAdvNoticeInterval = 'Hour'   THEN DATE_SUB(reminderTime, INTERVAL reminderAdvNoticeValue HOUR)
                           WHEN reminderAdvNoticeInterval = 'Day'    THEN DATE_SUB(reminderTime, INTERVAL reminderAdvNoticeValue DAY)
                           WHEN reminderAdvNoticeInterval = 'Week'   THEN DATE_SUB(reminderTime, INTERVAL reminderAdvNoticeValue WEEK)
                           WHEN reminderAdvNoticeInterval = 'No'     THEN NULL
                         END
                 ");

$db->query($query);
while ($db->next_record()) {
    $reminder = new reminder();
    $reminder->read_db_record($db);
    //echo "<br>Adv: ".$reminder->get_id();
    $current_user = new person();
    $current_user->load_current_user($db->f('reminderCreatedUser'));
    singleton("current_user", $current_user);
    if (!$reminder->is_alive()) {
        $reminder->deactivate();
    } else {
        $reminder->mail_advnotice();
    }
}


// do reminders
$query = prepare("SELECT *
                    FROM reminder
                   WHERE reminderActive = 1
                     AND (reminderTime IS NULL OR NOW() > reminderTime)
                 ");

$db->query($query);
while ($db->next_record()) {
    $reminder = new reminder();
    $reminder->read_db_record($db);
    //echo "<br>Rem: ".$reminder->get_id();
    $current_user = new person();
    $current_user->load_current_user($db->f('reminderCreatedUser'));
    singleton("current_user", $current_user);
    if (!$reminder->is_alive()) {
        $reminder->deactivate();
    } else {
        $reminder->mail_reminder();
    }
}
