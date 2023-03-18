<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");


$defaults = array("url_form_action"=>$TPL["url_alloc_clientList"],
                  "form_name"=>"clientList_filter");


function show_filter()
{
    global $TPL;
    global $defaults;
    $_FORM = client::load_form_data($defaults);
    $arr = client::load_client_filter($_FORM);
    is_array($arr) and $TPL = array_merge($TPL, $arr);
    include_template("templates/clientListFilterS.tpl");
}


$_FORM = client::load_form_data($defaults);
$TPL["clientListRows"] = client::get_list($_FORM);

if (!$current_user->prefs["clientList_filter"]) {
    $TPL["message_help"][] = "

allocPSA allows you to store pertinent information about your Clients and
the organisations that you interact with. This page allows you to see a list of Clients.

<br><br>

Simply adjust the filter settings and click the <b>Filter</b> button to
display a list of previously created Clients.
If you would prefer to create a new Client, click the <b>New Client</b> link
in the top-right hand corner of the box below.";
}


$TPL["main_alloc_title"] = "Client List - ".APPLICATION_NAME;
include_template("templates/clientListM.tpl");
