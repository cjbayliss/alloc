<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class tokenAction extends db_entity
{
    public $classname = "tokenAction";
    public $data_table = "tokenAction";
    public $key_field = "tokenActionID";
    public $data_fields = [
        "tokenAction",
        "tokenActionType",
        "tokenActionMethod",
    ];
}
