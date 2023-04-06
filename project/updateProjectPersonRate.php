<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

if ($_GET["project"] && $_GET["person"]) {
    $rate = projectPerson::get_rate($_GET["project"], $_GET["person"]);

    $project = new project($_GET["project"]);
    $currency = $project->get_value("currencyTypeID") or $currency = config::get_config_item("currency");
    $rate['rate'] = page::money($currency, $rate['rate'], '%mo');
    echo alloc_json_encode($rate);
}
