<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once(__DIR__ . "/../alloc.php");

$defaults = [
    "return"     => "html",
    "showHeader" => true,
    "showName"   => true,
    "showActive" => true,
    "showNos"    => true,
    "showLinks"  => true,
    "form_name"  => "personList_filter",
];

function show_filter()
{
    global $TPL;
    global $defaults;
    $_FORM = person::load_form_data($defaults);
    $arr = person::load_person_filter($_FORM);
    if (is_array($arr)) {
        $TPL = array_merge($TPL, $arr);
    }

    include_template("templates/personListFilterS.tpl");
}

function show_people()
{
    global $defaults;
    $_FORM = person::load_form_data($defaults);
    // echo "<pre>".print_r($_FORM,1)."</pre>";
    echo person::get_list($_FORM);
}

$TPL["main_alloc_title"] = "People - " . APPLICATION_NAME;

include_template("templates/personListM.tpl");
