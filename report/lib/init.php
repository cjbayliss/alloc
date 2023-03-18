<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class report_module extends module
{
    var $module = "report";
}

function has_report_perm()
{
    $current_user = &singleton("current_user");
    if (is_object($current_user)) {
        return $current_user->have_role("admin") || $current_user->have_role("manage");
    }
    return false;
}
