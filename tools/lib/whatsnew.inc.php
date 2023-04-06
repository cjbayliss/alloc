<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class whatsnew
{

    public $folders = [];

    public function __construct()
    {
    }

    public function set_id()
    {
        // dummy so can re-use the get_attachment.php script
        return true;
    }

    public function select()
    {
        // dummy so can re-use the get_attachment.php script
        return true;
    }

    public function has_attachment_permission($person)
    {
        return true;
    }
}
