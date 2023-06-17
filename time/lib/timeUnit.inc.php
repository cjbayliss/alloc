<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class timeUnit extends db_entity
{
    public $classname = "timeUnit";
    public $data_table = "timeUnit";
    public $display_field_name = "timeUnitLabelA";
    public $key_field = "timeUnitID";
    public $data_fields = [
        "timeUnitName",
        "timeUnitLabelA",
        "timeUnitLabelB",
        "timeUnitSeconds",
        "timeUnitActive",
        "timeUnitSequence",
    ];

    public function seconds_to_display_time_unit($seconds)
    {
        $q = "SELECT * FROM timeUnit";
        $dballoc = new db_alloc();
        $dballoc->query($q);
        while ($dballoc->next_record()) {
            // blag someother time
        }
    }
}
