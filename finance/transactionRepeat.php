<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$current_user->check_employee();

$transactionRepeat = new transactionRepeat();
$db = new AllocDatabase();

global $TPL;
global $transactionRepeatID;

$transactionRepeatID = $_POST["transactionRepeatID"] or $transactionRepeatID = $_GET["transactionRepeatID"];

if ($transactionRepeatID) {
    $transactionRepeat->set_id($transactionRepeatID);
    $transactionRepeat->select();
    $transactionRepeat->set_values();
}

if (!isset($_POST["reimbursementRequired"])) {
    $_POST["reimbursementRequired"] = 0;
}

if ($_POST["save"] || $_POST["delete"] || $_POST["pending"] || $_POST["approved"] || $_POST["rejected"]) {
    $transactionRepeat->read_globals();

    if ($current_user->have_role("admin")) {
        if ($_POST["changeTransactionStatus"] == "pending") {
            $transactionRepeat->set_value("status", "pending");
            $TPL["message_good"][] = "Repeating Expense form Pending.";
        } else if ($_POST["changeTransactionStatus"] == "approved") {
            $transactionRepeat->set_value("status", "approved");
            $TPL["message_good"][] = "Repeating Expense form Approved!";
        } else if ($_POST["changeTransactionStatus"] == "rejected") {
            $transactionRepeat->set_value("status", "rejected");
            $TPL["message_good"][] = "Repeating Expense form  Rejected.";
        }
    }

    if ($_POST["delete"] && $transactionRepeatID) {
        $transactionRepeat->set_id($transactionRepeatID);
        $transactionRepeat->delete();
        alloc_redirect($TPL["url_alloc_transactionRepeatList"] . "tfID=" . $_POST["tfID"]);
    }

    $_POST["product"] or alloc_error("Please enter a Product");
    $_POST["amount"] or alloc_error("Please enter an Amount");
    $_POST["fromTfID"] or alloc_error("Please select a Source TF");
    $_POST["tfID"] or alloc_error("Please select a Destination TF");
    $_POST["companyDetails"] or alloc_error("Please provide Company Details");
    $_POST["transactionType"] or alloc_error("Please select a Transaction Type");
    $_POST["transactionStartDate"] or alloc_error("You must enter the Start date in the format yyyy-mm-dd");
    $_POST["transactionFinishDate"] or alloc_error("You must enter the Finish date in the format yyyy-mm-dd");

    if (!$TPL["message"]) {
        !$transactionRepeat->get_value("status") && $transactionRepeat->set_value("status", "pending");
        $transactionRepeat->set_value("companyDetails", rtrim($transactionRepeat->get_value("companyDetails")));
        $transactionRepeat->save();
        alloc_redirect($TPL["url_alloc_transactionRepeat"] . "transactionRepeatID=" . $transactionRepeat->get_id());
    }
    $transactionRepeat->set_values();
}

$TPL["reimbursementRequired_checked"] = $transactionRepeat->get_value("reimbursementRequired") ? " checked" : "";

if ($transactionRepeat->get_value("transactionRepeatModifiedUser")) {
    $db->query("select username from person where personID=%d", $transactionRepeat->get_value("transactionRepeatModifiedUser"));
    $db->next_record();
    $TPL["user"] = $db->f("username");
}

if (have_entity_perm("tf", PERM_READ, $current_user, false)) {
    // Person can access all TF records
    $q = unsafe_prepare(
        "SELECT tfID AS value, tfName AS label
           FROM tf
          WHERE tfActive = 1
             OR tf.tfID = %d
             OR tf.tfID = %d
       ORDER BY tfName",
        $transactionRepeat->get_value("tfID"),
        $transactionRepeat->get_value("fromTfID")
    );
} else if (have_entity_perm("tf", PERM_READ, $current_user, true)) {
    // Person can only read TF records that they own
    $q = unsafe_prepare(
        "SELECT tf.tfID AS value, tf.tfName AS label
                  FROM tf, tfPerson
                 WHERE tfPerson.personID=%d
                   AND tf.tfID=tfPerson.tfID
                   AND (tf.tfActive = 1 OR tf.tfID = %d OR tf.tfID = %d)
              ORDER BY tfName",
        $current_user->get_id(),
        $transactionRepeat->get_value("tfID"),
        $transactionRepeat->get_value("fromTfID")
    );
} else {
    alloc_error("No permissions to generate TF list");
}

// special case for disabled TF. Include it in the list, but also add a warning message.
$tf = new tf();
$tf->set_id($transactionRepeat->get_value("tfID"));
if ($tf->select() && !$tf->get_value("tfActive")) {
    $TPL["message_help"][] = "This expense is allocated to an inactive TF. It will not create transactions.";
}
$tf = new tf();
$tf->set_id($transactionRepeat->get_value("fromTfID"));
if ($tf->select() && !$tf->get_value("tfActive")) {
    $TPL["message_help"][] = "This expense is sourced from an inactive TF. It will not create transactions.";
}

$m = new meta("currencyType");
$currencyOps = $m->get_assoc_array("currencyTypeID", "currencyTypeID");
$TPL["currencyTypeOptions"] = page::select_options($currencyOps, $transactionRepeat->get_value("currencyTypeID"));

$TPL["tfOptions"] = page::select_options($q, $transactionRepeat->get_value("tfID"));
$TPL["fromTfOptions"] = page::select_options($q, $transactionRepeat->get_value("fromTfID"));
$TPL["basisOptions"] = page::select_options(
    ["weekly" => "weekly", "fortnightly" => "fortnightly", "monthly" => "monthly", "quarterly" => "quarterly", "yearly" => "yearly"],
    $transactionRepeat->get_value("paymentBasis")
);

$TPL["transactionTypeOptions"] = page::select_options(transaction::get_transactionTypes(), $transactionRepeat->get_value("transactionType"));

if (is_object($transactionRepeat) && $transactionRepeat->get_id() && $current_user->have_role("admin")) {
    $TPL["adminButtons"] .= '
  <select name="changeTransactionStatus"><option value="">Transaction Status<option value="approved">Approve<option value="rejected">Reject<option value="pending">Pending</select>
  ';
}

if (is_object($transactionRepeat) && $transactionRepeat->get_id() && $transactionRepeat->get_value("status") == "pending") {
    $TPL["message_help"][] = "This Repeating Expense will only create Transactions once its status is Approved.";
} else if (!$transactionRepeat->get_id()) {
    $TPL["message_help"][] = "Complete all the details and click the Save button to create an automatically Repeating Expense";
}

$transactionRepeat->get_value("status") and $TPL["statusLabel"] = " - " . ucwords($transactionRepeat->get_value("status"));

$TPL["taxName"] = config::get_config_item("taxName");

$TPL["main_alloc_title"] = "Create Repeating Expense - " . APPLICATION_NAME;
include_template("templates/transactionRepeatM.tpl");
