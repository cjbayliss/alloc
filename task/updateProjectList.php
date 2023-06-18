<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once(__DIR__ . "/../alloc.php");

usleep(400000);
echo project::get_list_dropdown($_GET["projectType"]);
