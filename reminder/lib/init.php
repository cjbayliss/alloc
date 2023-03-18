<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


class reminder_module extends module
{
    var $module = "reminder";
    var $db_entities = array("reminder", "reminderRecipient");
}
