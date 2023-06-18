<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class project_list_home_item extends home_item
{

    /**
     * @var bool
     */
    public $has_config = true;

    public function __construct()
    {
        parent::__construct(
            "project_list",
            "Project List",
            "project",
            "projectListH.tpl",
            "standard",
            40
        );
    }

    public function visible()
    {
        $current_user = &singleton("current_user");

        if (!isset($current_user->prefs["showProjectHome"])) {
            $current_user->prefs["showProjectHome"] = 1;
            $current_user->prefs["projectListNum"] = "10";
        }

        if ($current_user->prefs["showProjectHome"]) {
            return true;
        }
    }

    public function render(): bool
    {
        $options = [];
        $current_user = &singleton("current_user");
        global $TPL;
        if (isset($current_user->prefs["projectListNum"]) && $current_user->prefs["projectListNum"] != "all") {
            $options["limit"] = sprintf("%d", $current_user->prefs["projectListNum"]);
        }

        $options["projectStatus"] = "Current";
        $options["personID"] = $current_user->get_id();
        $TPL["projectListRows"] = project::getFilteredProjectList($options);
        return true;
    }
}
