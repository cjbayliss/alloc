<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

function show_commentTemplate($template_name)
{
    global $TPL;

    // Run query and loop through the records
    $db = new db_alloc();
    $query = "SELECT * FROM commentTemplate ORDER BY commentTemplateType, commentTemplateName";
    $db->query($query);
    while ($db->next_record()) {
        $commentTemplate = new commentTemplate();
        $commentTemplate->read_db_record($db);
        $commentTemplate->set_values();
        $TPL["odd_even"] = $TPL["odd_even"] == "even" ? "odd" : "even";
        include_template($template_name);
    }
}

$TPL["main_alloc_title"] = "Comment Template List - " . APPLICATION_NAME;
include_template("templates/commentTemplateListM.tpl");
