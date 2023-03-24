<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$misc_options = [
    [
        "url" => "reminderList",
        "text" => "Reminders",
        "entity" => "",
        "action" => true
    ],
    [
        "url" => "announcementList",
        "text" => "Announcements",
        "entity" => "announcement",
        "action" => PERM_READ_WRITE
    ],
    [
        "url" => "commentSummary",
        "text" => "Task Comment Summary",
        "entity" => "",
        "action" => true
    ],
    [
        "url" => "permissionList",
        "text" => "Security",
        "entity" => "permission",
        "action" => PERM_READ_WRITE
    ],
    [
        "url" => "search",
        "text" => "Search",
        "entity" => "",
        "action" => true
    ],
    [
        "url" => "personSkillMatrix",
        "text" => "Company Skill Matrix",
        "entity" => "person",
        "action" => true
    ],
    [
        "url" => "personSkillAdd",
        "text" => "Edit Skill Items",
        "entity" => "person",
        "action" => PERM_PERSON_READ_MANAGEMENT
    ],
    [
        "url" => "commentTemplateList",
        "text" => "Comment Templates",
        "entity" => "commentTemplate",
        "action" => PERM_READ_WRITE
    ],
    [
        "url" => "loans",
        "text" => "Item Loans",
        "entity" => "loan",
        "action" => true
    ],
    [
        "url" => "report",
        "text" => "Reports",
        "entity" => "",
        "action" => true,
        "function" => "has_report_perm"
    ],
    [
        "url" => "backup",
        "text" => "Database & File Backup",
        "entity" => "",
        "function" => "has_backup_perm"
    ],
    [
        "url" => "sourceCodeList",
        "text" => "allocPSA Source Code",
        "entity" => ""
    ],
    [
        "url" => "whatsnew",
        "text" => "Deployment Changelog",
        "entity" => "",
        "function" => "has_whatsnew_files"
    ],
    [
        "url" => "inbox",
        "text" => "Manage Inbox",
        "entity" => "config",
        "action" => PERM_UPDATE
    ]
];


function user_is_admin()
{
    $current_user = &singleton("current_user");
    return $current_user->have_role("admin");
}


$finance_options = [
    [
        "url" => "tf",
        "text" => "New Tagged Fund",
        "entity" => "tf",
        "action" => PERM_CREATE
    ],
    [
        "url" => "tfList",
        "text" => "List of Tagged Funds",
        "entity" => "tf",
        "action" => PERM_READ,
        "br" => true
    ],
    [
        "url" => "transaction",
        "text" => "New Transaction",
        "entity" => "",
        "function" => "user_is_admin"
    ],
    [
        "url" => "transactionGroup",
        "text" => "New Transaction Group",
        "entity" => "",
        "function" => "user_is_admin"
    ],
    [
        "url" => "searchTransaction",
        "text" => "Search Transactions",
        "entity" => "transaction",
        "action" => PERM_READ,
        "br" => true
    ],
    [
        "url" => "expenseForm",
        "text" => "New Expense Form",
        "entity" => "expenseForm",
        "action" => PERM_CREATE
    ],
    [
        "url" => "expenseFormList",
        "text" => "View Pending Expenses",
        "entity" => "expenseForm",
        "action" => PERM_READ,
        "br" => true
    ],
    [
        "url" => "wagesUpload",
        "text" => "Upload Wages File",
        "entity" => "",
        "function" => "user_is_admin",
        "br" => true
    ],
    [
        "url" => "transactionRepeat",
        "text" => "New Repeating Expense",
        "entity" => "",
        "function" => "user_is_admin"
    ],
    [
        "url" => "transactionRepeatList",
        "text" => "Repeating Expense List",
        "entity" => "transaction",
        "action" => PERM_READ
    ],
    [
        "url" => "checkRepeat",
        "text" => "Execute Repeating Expenses",
        "entity" => "",
        "function" => "user_is_admin"
    ]
];

function has_whatsnew_files()
{
    $rows = get_attachments("whatsnew", 0);
    if (count($rows)) {
        return true;
    }
}


function show_misc_options($template)
{
    $current_user = null;
    global $misc_options;
    global $TPL;

    $TPL["br"] = "<br>\n";
    reset($misc_options);
    while (list(, $option) = each($misc_options)) {
        if ($option["entity"] != "") {
            if (have_entity_perm($option["entity"], $option["action"], $current_user, true)) {
                $TPL["url"] = $TPL["url_alloc_" . $option["url"]];
                $TPL["params"] = $option["params"];
                $TPL["text"] = $option["text"];
                include_template($template);
            }
        } else if ($option["function"]) {
            $f = $option["function"];
            if ($f()) {
                $TPL["url"] = $TPL["url_alloc_" . $option["url"]];
                $TPL["params"] = $option["params"];
                $TPL["text"] = $option["text"];
                include_template($template);
            }
        } else {
            $TPL["url"] = $TPL["url_alloc_" . $option["url"]];
            $TPL["params"] = $option["params"];
            $TPL["text"] = $option["text"];
            include_template($template);
        }
    }
}

function show_finance_options($template)
{
    global $finance_options;
    global $TPL;
    foreach ($finance_options as $option) {
        if ($option["entity"] != "") {
            if (have_entity_perm($option["entity"], $option["action"], $current_user, true)) {
                $TPL["url"] = $TPL["url_alloc_" . $option["url"]];
                $TPL["params"] = $option["params"];
                $TPL["text"] = $option["text"];
                $TPL["br"] = "<br>\n";
                $option["br"] and $TPL["br"] = "<br><br>\n";

                include_template($template);
            }
        } else if ($option["function"]) {
            $f = $option["function"];
            if ($f()) {
                $TPL["url"] = $TPL["url_alloc_" . $option["url"]];
                $TPL["params"] = $option["params"];
                $TPL["text"] = $option["text"];
                $TPL["br"] = "<br>\n";
                $option["br"] and $TPL["br"] = "<br><br>\n";
                include_template($template);
            }
        }
    }
}

$TPL["main_alloc_title"] = "Tools - " . APPLICATION_NAME;

include_template("templates/menuM.tpl");
