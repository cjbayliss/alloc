<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class proficiency extends DatabaseEntity
{
    public $data_table = "proficiency";
    public $display_field_name = "personID";
    public $key_field = "proficiencyID";
    public $data_fields = ["personID", "skillID", "skillProficiency"];
}
