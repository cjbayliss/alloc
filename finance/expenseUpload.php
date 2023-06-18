<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

if (!config::get_config_item("mainTfID")) {
    alloc_error("This functionality will not work until you set a Finance TF on the Setup -> Finance screen.");
}

$field_map = [
    "date"        => 0,
    "account"     => 1,
    "num"         => 2,
    "description" => 3,
    "memo"        => 4,
    "category"    => 5,
    "clr"         => 6,
    "amount"      => 7,
];

if ($_POST["upload"]) {
    $db = new AllocDatabase();
    is_uploaded_file($_FILES["expenses_file"]["tmp_name"]) || alloc_error("File referred to was not an uploaded file", true); // Prevent attacks by setting $expenses_file in URL
    $lines = file($_FILES["expenses_file"]["tmp_name"]);

    foreach ($lines as $line) {
        // Ignore blank lines
        if (trim($line) == "") {
            continue;
        }

        // Read field values from the line
        $fields = explode("\t", $line);

        $date = trim($fields[$field_map["date"]]);
        $account = trim($fields[$field_map["account"]]);
        $num = trim($fields[$field_map["num"]]);
        $description = trim($fields[$field_map["description"]]);
        $memo = trim($fields[$field_map["memo"]]);
        $category = trim($fields[$field_map["category"]]);
        $clr = trim($fields[$field_map["clr"]]);
        $amount = trim($fields[$field_map["amount"]]);
        // Idenitify lines containing totals as the date field will contain the text TOTAL
        // Identify the column headings as the date field will be "Date"
        // Ignore ignore these lines
        if (stripos("total", $date) !== false) {
            continue;
        }

        if ($date == "Date") {
            continue;
        }

        // Convert the date to yyyy-mm-dd
        if (!preg_match("|^([0-9]{1,2})/([0-9]{1,2})'([0-9])$|i", $date, $matches)) {
            $msg .= sprintf("<b>Warning: Could not convert date '%s'</b><br>", $date);
            continue;
        }

        $date = sprintf("200%d-%02d-%02d", $matches[3], $matches[2], $matches[1]);

        // Strip $ and , from amount
        $amount = str_replace(['$', ','], [], $amount);
        if (!preg_match("/^-?[0-9]+(\\.[0-9]+)?$/", $amount)) {
            $msg .= sprintf("<b>Warning: Could not convert amount '%s'</b><br>", $amount);
            continue;
        }

        // Ignore positive amounts
        if ($amount > 0) {
            $msg .= sprintf("<b>Warning: Ignored positive '%s' for %s on %s</b><br>", $amount, $memo, $date);
            continue;
        }

        // Find the TF ID for the expense
        $query = unsafe_prepare("SELECT * FROM tf WHERE tfActive = 1 AND quickenAccount='%s'", $account);
        echo $query;
        $db->query($query);
        if ($db->next_record()) {
            $fromTfID = $db->f("tfID");
        } else {
            $msg .= sprintf("<b>Warning: Could not find active TF for account '%s'</b><br>", $account);
            continue;
        }

        // Check for an existing transaction
        $query = unsafe_prepare("SELECT * FROM transaction WHERE transactionType='expense' AND transactionDate='%s' AND product='%s' AND amount > %0.3f and amount < %0.3f", $date, $memo, $amount - 0.004, $amount + 0.004);
        $db->query($query);
        if ($db->next_record()) {
            $msg .= sprintf("Warning: Expense '%s' on %s already exixsts.<br>", $memo, $date);
            continue;
        }

        // Create a transaction object and then save it
        $transaction = new transaction();
        $transaction->set_value("companyDetails", $description);
        $transaction->set_value("product", $memo);
        $transaction->set_value("amount", $amount);
        $transaction->set_value("status", "pending");
        $transaction->set_value("expenseFormID", "0");
        $transaction->set_value("fromTfID", $fromTfID);
        $transaction->set_value("tfID", config::get_config_item("mainTfID"));
        $transaction->set_value("quantity", 1);
        $transaction->set_value("invoiceItemID", "0");
        $transaction->set_value("transactionType", "expense");
        $transaction->set_value("transactionDate", $date);
        $transaction->save();

        $msg .= sprintf("Expense '%s' on %s saved.<br>", $memo, $date);
    }

    $TPL["msg"] = $msg;
}

include_template("templates/expenseUploadM.tpl");
