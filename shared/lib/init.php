<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


require_once(dirname(__FILE__)."/module.inc.php");
require_once(dirname(__FILE__)."/template.inc.php");

class shared_module extends module
{
    var $module = "shared";
    var $db_entities = array("sentEmailLog", "interestedParty");
}
