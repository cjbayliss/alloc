<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class timeUnit extends DatabaseEntity
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
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);
        while ($allocDatabase->next_record()) {
            // blag someother time
        }
    }
}
