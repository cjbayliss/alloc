<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


define("NO_REDIRECT", 1);
require_once("../alloc.php");

if ($_GET["clientID"]) {
    usleep(400000);
    echo client::get_client_contact_select($_GET["clientID"], $_GET["clientContactID"]);
}
