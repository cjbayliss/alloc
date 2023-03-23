<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

$wikiMarkup = config::get_config_item("wikiMarkup");
$str = '<div class="wikidoc" style="margin:10px 0px; padding:10px 30px 20px 30px;"><h1 style="text-align:center">[ Preview ]</h1>';
$str .= $wikiMarkup($_REQUEST["data"]);
$str .= '</div>';
echo $str;
