<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


class sentEmailLog extends db_entity
{
    public $classname = "sentEmailLog";
    public $data_table = "sentEmailLog";
    public $key_field = "sentEmailLogID";
    public $data_fields = [
        "sentEmailTo",
        "sentEmailSubject",
        "sentEmailBody",
        "sentEmailHeader",
        "sentEmailType",
        "sentEmailLogCreatedTime",
        "sentEmailLogCreatedUser"
    ];
}
