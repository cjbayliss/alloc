<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class timeSheetGraph
{

    public function __construct()
    {
    }
    public function get_list_vars()
    {
        return [
            // "projectIDs" => "An array of projectIDs"
            "dateFrom"    => "From Date",
            "dateTo"      => "To Date",
            "personID"    => "The person assigned to the task",
            "groupBy"     => "Group the results by day or month",
            "applyFilter" => "Store the filter settings",
        ];
    }

    public static function load_filter($defaults)
    {
        $rtn = [];
        $current_user = &singleton("current_user");

        // display the list of project name.
        $allocDatabase = new AllocDatabase();
        $page_vars = array_keys((new timeSheetGraph())->get_list_vars());
        $_FORM = get_all_form_data($page_vars, $defaults);

        if ($_FORM["applyFilter"] && is_object($current_user)) {
            // we have a new filter configuration from the user, and must save it
            if (!$_FORM["dontSave"]) {
                $url = $_FORM["url_form_action"];
                unset($_FORM["url_form_action"]);
                $current_user->prefs[$_FORM["form_name"]] = $_FORM;
                $_FORM["url_form_action"] = $url;
            }
        } else {
            // we haven't been given a filter configuration, so load it from user preferences
            $_FORM = $current_user->prefs[$_FORM["form_name"]];
        }

        $rtn["personOptions"] = Page::select_options(person::get_username_list($_FORM["personID"]), $_FORM["personID"]);
        $rtn["dateFrom"] = $_FORM["dateFrom"];
        $rtn["dateTo"] = $_FORM["dateTo"];
        $rtn["personID"] = $_FORM["personID"];
        $rtn["groupBy"] = $_FORM["groupBy"];

        // GET
        $rtn["FORM"] = "FORM=" . urlencode(serialize($_FORM));
        return $rtn;
    }
}
