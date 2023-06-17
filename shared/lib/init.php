<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once(__DIR__ . "/Module.inc.php");
require_once(__DIR__ . "/template.inc.php");

class shared_module extends Module
{
    public $module = "shared";
    public $databaseEntities = ["sentEmailLog", "interestedParty"];
}
