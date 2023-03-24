<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class tfList_home_item extends home_item
{
    function __construct()
    {
        parent::__construct("", "Tagged Funds", "finance", "tfListH.tpl", "narrow", 20);
    }

    function visible()
    {
        return true;
    }

    function render()
    {
        $ops = [];
        global $TPL;
        $ops["owner"] = 1;
        $TPL["tfListRows"] = tf::get_list($ops);
        if ($TPL["tfListRows"]) {
            return true;
        }
    }
}
