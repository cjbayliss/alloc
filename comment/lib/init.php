<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


class comment_module extends module
{
    public $module = "comment";
    public $db_entities = ["comment", "commentTemplate"];
}
