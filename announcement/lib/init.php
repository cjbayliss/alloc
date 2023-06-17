<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class announcement_module extends module
{
    public $module = "announcement";
    public $databaseEntities = ["announcement"];
    public $home_items = ["announcements_home_item"];
}
