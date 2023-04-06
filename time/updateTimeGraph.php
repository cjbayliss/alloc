<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

$current_user = &singleton("current_user");
$num_days_back = 28;
$start = date("Y-m-d", mktime() - (60 * 60 * 24 * $num_days_back));
$points = timeSheetItem::get_total_hours_worked_per_day($current_user->get_id(), $start);
print alloc_json_encode(["status" => "good", "points" => $points]);
