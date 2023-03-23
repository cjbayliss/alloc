<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class reminderRecipient extends db_entity
{
    public $data_table = "reminderRecipient";
    public $display_field_name = "reminderRecipientID";
    public $key_field = "reminderRecipientID";
    public $data_fields = ["reminderID", "personID", "metaPersonID"];
}
