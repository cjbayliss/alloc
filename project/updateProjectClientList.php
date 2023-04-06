<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

if ($_GET["clientStatus"]) {
    usleep(400000);
    echo client::get_client_select($_GET["clientStatus"], $_GET["clientID"]);
}
