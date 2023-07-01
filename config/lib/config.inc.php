<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class config extends DatabaseEntity
{
    public $data_table = 'config';

    public $key_field = 'configID';

    public $data_fields = ['name', 'value', 'type'];

    public static function get_config_item($name = '', $anew = false)
    {
        $table = &get_cached_table('config', $anew);

        if (empty($table) || empty($table[$name]) || empty($table[$name]['type'])) {
            return '';
        }

        if ('array' == $table[$name]['type']) {
            ($val = unserialize($table[$name]['value'])) || ($val = []);

            return $val;
        }

        if ('text' == $table[$name]['type']) {
            return $table[$name]['value'];
        }
    }

    public static function get_config_item_id($name = '')
    {
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query(unsafe_prepare("SELECT configID FROM config WHERE name = '%s'", $name));
        $allocDatabase->next_record();

        return $allocDatabase->f('configID');
    }

    public static function get_config_logo($anew = false)
    {
        global $TPL;
        $table = &get_cached_table('config', $anew);
        $val = '';
        if (file_exists(ALLOC_LOGO)) {
            return '<img src="' . $TPL['url_alloc_logo'] . 'type=small" alt="' . $table['companyName']['value'] . '" />';
        }

        return $table['companyName']['value'];
    }

    public static function for_cyber()
    {
        return 'cybersource' == config::get_config_item('companyHandle');
    }
}
