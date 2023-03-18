<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

if ($_REQUEST["entity"] && $_REQUEST["entityID"]) {
    $stars = $current_user->prefs["stars"];
    if ($stars[$_REQUEST["entity"]][$_REQUEST["entityID"]]) {
        unset($stars[$_REQUEST["entity"]][$_REQUEST["entityID"]]);
    } else {
        $stars[$_REQUEST["entity"]][$_REQUEST["entityID"]] = true;
    }
    $current_user->prefs["stars"] = $stars;
    $current_user->store_prefs();

    alloc_redirect($TPL["url_alloc_".$_REQUEST["entity"]."List"]);
}
