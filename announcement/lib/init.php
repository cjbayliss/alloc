<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


class announcement_module extends module
{
    var $module = "announcement";
    var $db_entities = array("announcement");
    var $home_items = array("announcements_home_item");
}
