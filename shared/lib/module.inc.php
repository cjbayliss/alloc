<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class module
{
    var $module = '';
    var $db_entities = array();   // A list of db_entity class names implemented by this module
    var $home_items = array();    // A list of all the home page items implemented by this module

    public function __construct()
    {
        spl_autoload_register(array($this, 'autoloader'));
    }

    public function autoloader($class)
    {
        $s = DIRECTORY_SEPARATOR;
        $p = dirname(__FILE__).$s.'..'.$s.'..'.$s.$this->module.$s.'lib'.$s.$class.'.inc.php';
        if (file_exists($p)) {
            require_once($p);
        }
    }
}
