<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


require_once("../alloc.php");

function show_reminder_filter($template)
{
    $recipientOptions = [];
    $current_user = &singleton("current_user");
    global $TPL;
    if ($current_user->have_role("admin") || $current_user->have_role("manage")) {
        $TPL["reminderActiveOptions"] = page::select_options(["1" => "Active", "0" => "Inactive"], $_REQUEST["filter_reminderActive"]);

        $db = new db_alloc();
        $db->query("SELECT username,personID FROM person WHERE personActive = 1 ORDER BY username");
        while ($db->next_record()) {
            $recipientOptions[$db->f("personID")] = $db->f("username");
        }
        $TPL["recipientOptions"] = page::select_options($recipientOptions, $_REQUEST["filter_recipient"]);
        include_template($template);
    }
}


include_template("templates/reminderListM.tpl");
