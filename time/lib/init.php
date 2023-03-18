<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


class time_module extends module
{
    var $module = "time";
    var $db_entities = array("timeSheet", "timeSheetItem","timeUnit");
    var $home_items = array("timeSheetHomeItem",
                            "tsiHintHomeItem",
                            "timeSheetListHomeItem",
                            "pendingApprovalTimeSheetListHomeItem",
                            "timeSheetStatusHomeItem",
                            "pendingAdminApprovalTimeSheetListHomeItem");
}
