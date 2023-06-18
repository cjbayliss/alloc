<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once(__DIR__ . "/../alloc.php");

if ($_REQUEST["search_action"]) {
    [$method, $thing] = explode("_", $_REQUEST["search_action"]);

    if ($method == "search") {
        alloc_redirect($TPL["url_alloc_search"] . "needle=" . urlencode($_REQUEST["needle"]) . "&category=" . $_REQUEST["search_action"] . "&search=true");
    } elseif ($method == "create") {
        alloc_redirect($sess->url($thing));
    } elseif ($method == "history") {
        alloc_redirect($TPL["url_alloc_history"] . "historyID=" . $thing);
    }
}
