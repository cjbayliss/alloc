<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


require_once("../alloc.php");

function show_task_calendar_recursive()
{
    $calendar = new calendar(2, 20);
    $calendar->set_cal_person($_GET["personID"]);
    $calendar->set_return_mode("calendar");
    $calendar->draw();
}

$TPL["username"] = person::get_fullname($_GET["personID"]);

include_template("templates/taskCalendarM.tpl");
