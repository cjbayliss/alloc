<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once('../alloc.php');
$image = ALLOC_LOGO;
$_GET["type"] == "small" and $image = ALLOC_LOGO_SMALL;
header('Content-type: image/jpg');
echo file_get_contents($image);
