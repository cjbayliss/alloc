<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class module
{
    public $module = '';
    public $databaseEntities = [];   // A list of db_entity class names implemented by this module
    public $home_items = [];    // A list of all the home page items implemented by this module

    public function __construct()
    {
        spl_autoload_register([$this, 'autoloader']);
    }

    public function autoloader($class)
    {
        $s = DIRECTORY_SEPARATOR;
        $p = __DIR__ . $s . '..' . $s . '..' . $s . $this->module . $s . 'lib' . $s . $class . '.inc.php';
        if (file_exists($p)) {
            require_once($p);
        }
    }
}
