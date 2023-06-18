<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once(__DIR__ . "/../alloc.php");

$misc_options = [
    [
        "url"    => "reminderList",
        "text"   => "Reminders",
        "entity" => "",
        "action" => true,
    ],
    [
        "url"    => "announcementList",
        "text"   => "Announcements",
        "entity" => "announcement",
        "action" => PERM_READ_WRITE,
    ],
    [
        "url"    => "commentSummary",
        "text"   => "Task Comment Summary",
        "entity" => "",
        "action" => true,
    ],
    [
        "url"    => "permissionList",
        "text"   => "Security",
        "entity" => "permission",
        "action" => PERM_READ_WRITE,
    ],
    [
        "url"    => "search",
        "text"   => "Search",
        "entity" => "",
        "action" => true,
    ],
    [
        "url"    => "personSkillMatrix",
        "text"   => "Company Skill Matrix",
        "entity" => "person",
        "action" => true,
    ],
    [
        "url"    => "personSkillAdd",
        "text"   => "Edit Skill Items",
        "entity" => "person",
        "action" => PERM_PERSON_READ_MANAGEMENT,
    ],
    [
        "url"    => "commentTemplateList",
        "text"   => "Comment Templates",
        "entity" => "commentTemplate",
        "action" => PERM_READ_WRITE,
    ],
    [
        "url"    => "loans",
        "text"   => "Item Loans",
        "entity" => "loan",
        "action" => true,
    ],
    [
        "url"      => "report",
        "text"     => "Reports",
        "entity"   => "",
        "action"   => true,
        "function" => "has_report_perm",
    ],
    [
        "url"    => "inbox",
        "text"   => "Manage Inbox",
        "entity" => "config",
        "action" => PERM_UPDATE,
    ],
];

function user_is_admin()
{
    $current_user = &singleton("current_user");
    return $current_user->have_role("admin");
}

$finance_options = [
    [
        "url"    => "tf",
        "text"   => "New Tagged Fund",
        "entity" => "tf",
        "action" => PERM_CREATE,
    ],
    [
        "url"    => "tfList",
        "text"   => "List of Tagged Funds",
        "entity" => "tf",
        "action" => PERM_READ,
        "br"     => true,
    ],
    [
        "url"      => "transaction",
        "text"     => "New Transaction",
        "entity"   => "",
        "function" => "user_is_admin",
    ],
    [
        "url"      => "transactionGroup",
        "text"     => "New Transaction Group",
        "entity"   => "",
        "function" => "user_is_admin",
    ],
    [
        "url"    => "searchTransaction",
        "text"   => "Search Transactions",
        "entity" => "transaction",
        "action" => PERM_READ,
        "br"     => true,
    ],
    [
        "url"    => "expenseForm",
        "text"   => "New Expense Form",
        "entity" => "expenseForm",
        "action" => PERM_CREATE,
    ],
    [
        "url"    => "expenseFormList",
        "text"   => "View Pending Expenses",
        "entity" => "expenseForm",
        "action" => PERM_READ,
        "br"     => true,
    ],
    [
        "url"      => "wagesUpload",
        "text"     => "Upload Wages File",
        "entity"   => "",
        "function" => "user_is_admin",
        "br"       => true,
    ],
    [
        "url"      => "transactionRepeat",
        "text"     => "New Repeating Expense",
        "entity"   => "",
        "function" => "user_is_admin",
    ],
    [
        "url"    => "transactionRepeatList",
        "text"   => "Repeating Expense List",
        "entity" => "transaction",
        "action" => PERM_READ,
    ],
    [
        "url"      => "checkRepeat",
        "text"     => "Execute Repeating Expenses",
        "entity"   => "",
        "function" => "user_is_admin",
    ],
];

function has_whatsnew_files()
{
    $rows = get_attachments("whatsnew", 0);
    if ((is_countable($rows) ? count($rows) : 0) !== 0) {
        return true;
    }
}

function show_misc_options($template)
{
    $current_user = &singleton("current_user");
    global $misc_options;
    global $TPL;

    $TPL["br"] = "<br>\n";
    foreach ($misc_options as $misc_option) {
        if ($misc_option["entity"] != "") {
            if (have_entity_perm($misc_option["entity"], $misc_option["action"], $current_user, true)) {
                $TPL["url"] = $TPL["url_alloc_" . $misc_option["url"]];
                $TPL["params"] = $misc_option["params"];
                $TPL["text"] = $misc_option["text"];
                include_template($template);
            }
        } elseif ($misc_option["function"]) {
            $f = $misc_option["function"];
            if ($f()) {
                $TPL["url"] = $TPL["url_alloc_" . $misc_option["url"]];
                $TPL["params"] = $misc_option["params"];
                $TPL["text"] = $misc_option["text"];
                include_template($template);
            }
        } else {
            $TPL["url"] = $TPL["url_alloc_" . $misc_option["url"]];
            $TPL["params"] = $misc_option["params"];
            $TPL["text"] = $misc_option["text"];
            include_template($template);
        }
    }
}

function show_finance_options($template)
{
    $current_user = &singleton("current_user");
    global $finance_options;
    global $TPL;
    foreach ($finance_options as $finance_option) {
        if ($finance_option["entity"] != "") {
            if (have_entity_perm($finance_option["entity"], $finance_option["action"], $current_user, true)) {
                $TPL["url"] = $TPL["url_alloc_" . $finance_option["url"]];
                $TPL["params"] = $finance_option["params"];
                $TPL["text"] = $finance_option["text"];
                $TPL["br"] = "<br>\n";
                $finance_option["br"] && ($TPL["br"] = "<br><br>\n");

                include_template($template);
            }
        } elseif ($finance_option["function"]) {
            $f = $finance_option["function"];
            if ($f()) {
                $TPL["url"] = $TPL["url_alloc_" . $finance_option["url"]];
                $TPL["params"] = $finance_option["params"];
                $TPL["text"] = $finance_option["text"];
                $TPL["br"] = "<br>\n";
                $finance_option["br"] && ($TPL["br"] = "<br><br>\n");
                include_template($template);
            }
        }
    }
}

$TPL["main_alloc_title"] = "Tools - " . APPLICATION_NAME;

include_template("templates/menuM.tpl");
