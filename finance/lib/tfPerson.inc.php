<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class tfPerson extends DatabaseEntity
{
    public $data_table = "tfPerson";

    public $display_field_name = "personID";

    public $key_field = "tfPersonID";

    public $data_fields = ["tfID", "personID"];
}
