<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class wiki
{

    public static function get_list($_FORM)
    {
        global $TPL;
        $current_user = &singleton("current_user");

        $wiki_path = wiki_module::get_wiki_path();
        $files = search::get_recursive_dir_list($wiki_path);

        foreach ($files as $row) {
            $file = str_replace($wiki_path, "", $row);
            if ($_FORM["starred"] && $current_user->prefs["stars"]["wiki"][base64_encode($file)]) {
                $rows[] = ["filename" => $file];
            }
        }
        return (array)$rows;
    }

    function get_list_html($rows = [], $ops = [])
    {
        global $TPL;
        $TPL["wikiListRows"] = $rows;
        $TPL["_FORM"] = $ops;
        include_template(__DIR__ . "/../templates/wikiListS.tpl");
    }
}
