<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class invoiceItem extends db_entity
{
    public $data_table = "invoiceItem";
    public $display_field_name = "iiMemo";
    public $key_field = "invoiceItemID";
    public $data_fields = [
        "invoiceID",
        "timeSheetID",
        "timeSheetItemID",
        "expenseFormID",
        "transactionID",
        "productSaleID",
        "productSaleItemID",
        "iiMemo",
        "iiQuantity",
        "iiUnitPrice" => ["type" => "money"],
        "iiAmount" => ["type" => "money"],
        "iiTax",
        "iiDate"
    ];

    function is_owner($person = "")
    {
        $current_user = &singleton("current_user");

        if ($person == "") {
            $person = $current_user;
        }

        $db = new db_alloc();
        $q = unsafe_prepare("SELECT * FROM transaction WHERE invoiceItemID = %d OR transactionID = %d", $this->get_id(), $this->get_value("transactionID"));
        $db->query($q);
        while ($db->next_record()) {
            $transaction = new transaction();
            $transaction->read_db_record($db);
            if ($transaction->is_owner($person)) {
                return true;
            }
        }

        if ($this->get_value("timeSheetID")) {
            $q = unsafe_prepare("SELECT * FROM timeSheet WHERE timeSheetID = %d", $this->get_value("timeSheetID"));
            $db->query($q);
            while ($db->next_record()) {
                $timeSheet = new timeSheet();
                $timeSheet->read_db_record($db);
                if ($timeSheet->is_owner($person)) {
                    return true;
                }
            }
        }

        if ($this->get_value("expenseFormID")) {
            $q = unsafe_prepare("SELECT * FROM expenseForm WHERE expenseFormID = %d", $this->get_value("expenseFormID"));
            $db->query($q);
            while ($db->next_record()) {
                $expenseForm = new expenseForm();
                $expenseForm->read_db_record($db);
                if ($expenseForm->is_owner($person)) {
                    return true;
                }
            }
        }

        return false;
    }

    function delete()
    {

        $db = new db_alloc();
        $q = unsafe_prepare("DELETE FROM transaction WHERE invoiceItemID = %d", $this->get_id());
        $db->query($q);

        $invoiceID = $this->get_value("invoiceID");
        $status = parent::delete();
        $status2 = invoice::update_invoice_dates($invoiceID);
        return $status && $status2;
    }

    function save()
    {

        if (!imp($this->get_value("iiAmount"))) {
            $this->set_value("iiAmount", $this->get_value("iiQuantity") * $this->get_value("iiUnitPrice"));
        }

        $status = parent::save();
        $status2 = invoice::update_invoice_dates($this->get_value("invoiceID"));
        return $status && $status2;
    }

    function close_related_entity()
    {
        global $TPL;

        // It checks for approved transactions and only approves the timesheets
        // or expenseforms that are completely paid for by an invoice item.
        $db = new db_alloc();
        $q = unsafe_prepare("SELECT amount, currencyTypeID, status
                        FROM transaction
                       WHERE invoiceItemID = %d
                    ORDER BY transactionCreatedTime DESC
                       LIMIT 1
                     ", $this->get_id());
        $db->query($q);
        $row = $db->row();
        $total = $row["amount"];
        $currency = $row["currencyTypeID"];
        $status = $row["status"];

        $timeSheetID = $this->get_value("timeSheetID");
        $expenseFormID = $this->get_value("expenseFormID");

        if ($timeSheetID) {
            $timeSheet = new timeSheet();
            $timeSheet->set_id($timeSheetID);
            $timeSheet->select();

            $db = new db_alloc();

            if ($timeSheet->get_value("status") == "invoiced") {
                // If the time sheet doesn't have any transactions and it is in
                // status invoiced, then we'll simulate the "Create Default Transactions"
                // button being pressed.
                $q = unsafe_prepare("SELECT count(*) as num_transactions
                                FROM transaction
                               WHERE timeSheetID = %d
                                 AND invoiceItemID IS NULL
                             ", $timeSheet->get_id());
                $db->query($q);
                $row = $db->row();
                if ($row["num_transactions"] == 0) {
                    $_POST["create_transactions_default"] = true;
                    $timeSheet->createTransactions($status);
                    $TPL["message_good"][] = "Automatically created time sheet transactions.";
                }

                // Get total of all time sheet transactions.
                $q = unsafe_prepare("SELECT SUM(amount) AS total
                                FROM transaction
                               WHERE timeSheetID = %d
                                 AND status != 'rejected'
                                 AND invoiceItemID IS NULL
                             ", $timeSheet->get_id());
                $db->query($q);
                $row = $db->row();
                $total_timeSheet = $row["total"];

                if ($total >= $total_timeSheet) {
                    $timeSheet->pending_transactions_to_approved();
                    $timeSheet->change_status("forwards");
                    $TPL["message_good"][] = "Closed Time Sheet #" . $timeSheet->get_id() . " and marked its Transactions: " . $status;
                } else {
                    $TPL["message_help"][] = "Unable to close Time Sheet #" . $timeSheet->get_id() . " the sum of the Time Sheet's *Transactions* ("
                        . page::money($timeSheet->get_value("currencyTypeID"), $total_timeSheet, "%s%mo %c")
                        . ") is greater than the Invoice Item Transaction ("
                        . page::money($currency, $total, "%s%mo %c") . ")";
                }
            }
        } else if ($expenseFormID) {
            $expenseForm = new expenseForm();
            $expenseForm->set_id($expenseFormID);
            $expenseForm->select();
            $total_expenseForm = $expenseForm->get_abs_sum_transactions();

            if ($total == $total_expenseForm) {
                $expenseForm->set_status("approved");
                $TPL["message_good"][] = "Approved Expense Form #" . $expenseForm->get_id() . ".";
            } else {
                $TPL["message_help"][] = "Unable to approve Expense Form #" . $expenseForm->get_id() . " the sum of Expense Form Transactions does not equal the Invoice Item Transaction.";
            }
        }
    }

    function create_transaction($amount, $tfID, $status)
    {
        $transaction = new transaction();
        $invoice = $this->get_foreign_object("invoice");
        $this->currency = $invoice->get_value("currencyTypeID");
        $db = new db_alloc();

        // If there already a transaction for this invoiceItem, use it instead of creating a new one
        $q = unsafe_prepare("SELECT * FROM transaction WHERE invoiceItemID = %d ORDER BY transactionCreatedTime DESC LIMIT 1", $this->get_id());
        $db->query($q);
        if ($db->row()) {
            $transaction->set_id($db->f("transactionID"));
            $transaction->select();
        }

        // If there already a transaction for this timeSheet, use it instead of creating a new one
        if ($this->get_value("timeSheetID")) {
            $q = unsafe_prepare(
                "SELECT *
                   FROM transaction
                  WHERE timeSheetID = %d
                    AND fromTfID = %d
                    AND tfID = %d
                    AND amount = %d
                    AND (invoiceItemID = %d or invoiceItemID IS NULL)
               ORDER BY transactionCreatedTime DESC LIMIT 1
                ",
                $this->get_value("timeSheetID"),
                config::get_config_item("inTfID"),
                $tfID,
                page::money($this->currency, $amount, "%mi"),
                $this->get_id()
            );
            $db->query($q);
            if ($db->row()) {
                $transaction->set_id($db->f("transactionID"));
                $transaction->select();
            }
        }

        $transaction->set_value("amount", $amount);
        $transaction->set_value("currencyTypeID", $this->currency);
        $transaction->set_value("fromTfID", config::get_config_item("inTfID"));
        $transaction->set_value("tfID", $tfID);
        $transaction->set_value("status", $status);
        $transaction->set_value("invoiceID", $this->get_value("invoiceID"));
        $transaction->set_value("invoiceItemID", $this->get_id());
        $transaction->set_value("transactionDate", $this->get_value("iiDate"));
        $transaction->set_value("transactionType", "invoice");
        $transaction->set_value("product", sprintf("%s", $this->get_value("iiMemo")));
        $this->get_value("timeSheetID") && $transaction->set_value("timeSheetID", $this->get_value("timeSheetID"));
        $transaction->save();
    }

    function get_list_filter($filter = [])
    {
        $sql = [];
        // Filter on invoiceID
        if ($filter["invoiceID"] && is_array($filter["invoiceID"])) {
            $sql[] = unsafe_prepare("(invoice.invoiceID in (%s))", $filter["invoiceID"]);
        } else if ($filter["invoiceID"]) {
            $sql[] = unsafe_prepare("(invoice.invoiceID = %d)", $filter["invoiceID"]);
        }
        return $sql;
    }

    public static function get_list($_FORM)
    {
        $f = null;
        $rows = [];
        $filter = invoiceItem::get_list_filter($_FORM);
        if (is_array($filter) && count($filter)) {
            $f = " WHERE " . implode(" AND ", $filter);
        }
        $q = unsafe_prepare("SELECT * FROM invoiceItem
                   LEFT JOIN invoice ON invoice.invoiceID = invoiceItem.invoiceID
                   LEFT JOIN client ON client.clientID = invoice.clientID
                     " . $f);
        $db = new db_alloc();
        $db->query($q);
        while ($row = $db->row()) {
            $row["iiAmount"] = page::money($row["currencyTypeID"], $row["iiAmount"], "%mo");
            $row["iiUnitPrice"] = page::money($row["currencyTypeID"], $row["iiUnitPrice"], "%mo");
            $rows[$row["invoiceItemID"]] = $row;
        }
        return (array)$rows;
    }
}
