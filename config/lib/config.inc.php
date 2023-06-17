<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class config extends DatabaseEntity
{
    public $data_table = "config";
    public $key_field = "configID";
    public $data_fields = ["name", "value", "type"];

    public static function get_config_item($name = '', $anew = false)
    {
        $table = &get_cached_table("config", $anew);
        if ($table[$name]["type"] == "array") {
            $val = unserialize($table[$name]["value"]) or $val = [];
            return $val;
        } else if ($table[$name]["type"] == "text") {
            $val = $table[$name]["value"];
            return $val;
        }
    }

    public static function get_config_item_id($name = '')
    {
        $dballoc = new db_alloc();
        $dballoc->query(unsafe_prepare("SELECT configID FROM config WHERE name = '%s'", $name));
        $dballoc->next_record();
        return $dballoc->f('configID');
    }

    public static function get_config_logo($anew = false)
    {
        global $TPL;
        $table = &get_cached_table("config", $anew);
        $val = '';
        if (file_exists(ALLOC_LOGO)) {
            $val = '<img src="' . $TPL["url_alloc_logo"] . 'type=small" alt="' . $table['companyName']['value'] . '" />';
        } else {
            $val = $table['companyName']['value'];
        }
        return $val;
    }

    public static function for_cyber()
    {
        return config::get_config_item("companyHandle") == "cybersource";
    }
}
