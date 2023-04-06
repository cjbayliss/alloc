<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class time_module extends module
{
    public $module = "time";
    public $db_entities = ["timeSheet", "timeSheetItem", "timeUnit"];
    public $home_items = [
        "timeSheetHomeItem",
        "tsiHintHomeItem",
        "timeSheetListHomeItem",
        "pendingApprovalTimeSheetListHomeItem",
        "timeSheetStatusHomeItem",
        "pendingAdminApprovalTimeSheetListHomeItem",
    ];
}
