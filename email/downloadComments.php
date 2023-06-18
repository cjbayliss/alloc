<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once(__DIR__ . "/../alloc.php");

if ($_REQUEST["entity"] && $_REQUEST["entityID"]) {
    $s = new services();
    $emails = $s->get_task_emails($_REQUEST["entityID"], $_REQUEST["entity"]);
    if ($_REQUEST["entity"] == "task") {
        $emails .= "\n\n" . $s->get_timeSheetItem_comments($_REQUEST["entityID"]);
    }

    header('Content-Type: text/plain; charset=utf-8');
    echo ltrim($emails);
}
