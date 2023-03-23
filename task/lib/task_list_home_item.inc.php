<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class task_list_home_item extends home_item
{
    public $date;

    function __construct()
    {
        $this->has_config = true;
        parent::__construct("top_ten_tasks", "Tasks", "task", "taskListH.tpl", "standard", 20);
    }

    function visible()
    {
        $current_user = &singleton("current_user");

        if (!isset($current_user->prefs["showTaskListHome"])) {
            $current_user->prefs["showTaskListHome"] = 1;
        }

        if ($current_user->prefs["showTaskListHome"]) {
            return true;
        }
    }

    function render()
    {
        global $TPL;

        $defaults = [
            "showHeader"      => true,
            "showTaskID"      => true,
            "taskView"        => "prioritised",
            "showStatus"      => "true",
            "url_form_action" => $TPL["url_alloc_home"],
            "form_name"       => "taskListHome_filter"
        ];

        $current_user = &singleton("current_user");
        if (!$current_user->prefs["taskListHome_filter"]) {
            $defaults["taskStatus"] = "open";
            $defaults["personID"] = $current_user->get_id();
            $defaults["showStatus"] = true;
            $defaults["showProject"] = true;
            $defaults["limit"] = 10;
            $defaults["applyFilter"] = true;
        }

        $_FORM = task::load_form_data($defaults);
        $TPL["taskListRows"] = task::get_list($_FORM);
        $TPL["_FORM"] = $_FORM;

        return true;
    }
}
