<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


class absence extends db_entity
{
    public $data_table = "absence";
    public $display_field_name = "personID";
    public $key_field = "absenceID";
    public $data_fields = [
        "dateFrom",
        "dateTo",
        "personID",
        "absenceType",
        "contactDetails"
    ];
}
