<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$defaults = [
    "return"       => "html",
    "showHeader"   => true,
    "showName"     => true,
    "showActive"   => true,
    "showNos"      => true,
    "showLinks"    => true,
    "form_name"    => "personList_filter"
];

function show_filter()
{
    global $TPL;
    global $defaults;
    $_FORM = person::load_form_data($defaults);
    $arr = person::load_person_filter($_FORM);
    is_array($arr) and $TPL = array_merge($TPL, $arr);
    include_template("templates/personListFilterS.tpl");
}

function show_people()
{
    global $defaults;
    $_FORM = person::load_form_data($defaults);
    #echo "<pre>".print_r($_FORM,1)."</pre>";
    echo person::get_list($_FORM);
}

$TPL["main_alloc_title"] = "People - " . APPLICATION_NAME;

$max_alloc_users = get_max_alloc_users();
$num_alloc_users = get_num_alloc_users();
if ($max_alloc_users && $num_alloc_users > $max_alloc_users) {
    alloc_error("Maximum number of active user accounts: " . $max_alloc_users);
    alloc_error("Current number of active user accounts: " . $num_alloc_users . "<br>");
    alloc_error(get_max_alloc_users_message());
} else if ($max_alloc_users) {
    $TPL["message_help"][] = "Maximum number of active user accounts: " . $max_alloc_users;
    $TPL["message_help"][] = "Current number of active user accounts: " . $num_alloc_users;
}


include_template("templates/personListM.tpl");
