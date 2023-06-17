<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class expenseForm extends DatabaseEntity
{
    public $data_table = "expenseForm";
    public $key_field = "expenseFormID";
    public $data_fields = [
        "expenseFormModifiedUser",
        "expenseFormModifiedTime",
        "paymentMethod",
        "reimbursementRequired"   => ["empty_to_null" => false],
        "seekClientReimbursement" => ["empty_to_null" => false],
        "transactionRepeatID",
        "clientID",
        "expenseFormCreatedUser",
        "expenseFormCreatedTime",
        "expenseFormFinalised" => ["empty_to_null" => false],
        "expenseFormComment",
    ];

    public function is_owner($person = "")
    {
        $current_user = &singleton("current_user");

        if ($person == "") {
            $person = $current_user;
        }
        // Return true if this user created the expense form
        if ($person->get_id() == $this->get_value("expenseFormCreatedUser", DST_VARIABLE)) {
            return true;
        }

        if ($this->get_id()) {
            // Return true if any of the transactions on the expense form are accessible by the current user
            $current_user_tfIDs = $current_user->get_tfIDs();
            $query = unsafe_prepare("SELECT * FROM transaction WHERE expenseFormID=%d", $this->get_id());
            $allocDatabase = new AllocDatabase();
            $allocDatabase->query($query);
            while ($allocDatabase->next_record()) {
                if (is_array($current_user_tfIDs) && (in_array($allocDatabase->f("tfID"), $current_user_tfIDs) || in_array($allocDatabase->f("fromTfID"), $current_user_tfIDs))) {
                    return true;
                }
            }

            // If no expenseForm ID, then it hasn't been created yet...
        } else {
            return true;
        }

        if ($current_user->have_role("admin")) {
            return true;
        }

        return false;
    }

    public static function get_reimbursementRequired_array()
    {
        return ["0" => "Unpaid", "1" => "Paid by me", "2" => "Paid by company"];
    }

    public function set_status($status)
    {
        // This sets the status of the expense form. Actually, the expense form
        // doesn't have its own status - this sets the status of the transactions on the
        // expense form
        $current_user = &singleton("current_user");
        $transactions = $this->get_foreign_objects("transaction");
        foreach ($transactions as $transaction) {
            $transaction->set_value("status", $status);
            $transaction->save();
        }
    }

    public function get_status()
    {
        $arr = [];
        $return = null;
        $q = unsafe_prepare("SELECT status FROM transaction WHERE expenseFormID = %d", $this->get_id());
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);
        while ($row = $allocDatabase->row()) {
            $arr[$row["status"]] = 1;
        }
        $arr or $arr = [];
        $sp = "";
        foreach ($arr as $s => $v) {
            $return .= $sp . $s;
            $sp = "&nbsp;&amp;&nbsp;";
        }
        return $return;
    }

    public function delete_transactions($transactionID = "")
    {
        $extra_sql = null;
        global $TPL;

        $transactionID and $extra_sql = unsafe_prepare("AND transactionID = %d", $transactionID);

        $allocDatabase = new AllocDatabase();
        if ($this->is_owner()) {
            $allocDatabase->query(unsafe_prepare("DELETE FROM transaction WHERE expenseFormID = %d " . $extra_sql, $this->get_id()));
            $transactionID and $TPL["message_good"][] = "Expense Form Line Item deleted.";
        }
    }

    public function get_invoice_link()
    {
        $str = null;
        $sp = null;
        global $TPL;
        $allocDatabase = new AllocDatabase();
        if ($this->get_id()) {
            $allocDatabase->query("SELECT invoice.* FROM invoiceItem LEFT JOIN invoice on invoice.invoiceID = invoiceItem.invoiceID WHERE expenseFormID = %d", $this->get_id());
            while ($row = $allocDatabase->next_record()) {
                $str .= $sp . "<a href=\"" . $TPL["url_alloc_invoice"] . "invoiceID=" . $row["invoiceID"] . "\">" . $row["invoiceNum"] . "</a>";
                $sp = "&nbsp;&nbsp;";
            }
            return $str;
        }
    }

    public function save_to_invoice($invoiceID = false)
    {

        $extra = null;
        if ($this->get_value("clientID")) {
            $invoiceID and $extra = unsafe_prepare(" AND invoiceID = %d", $invoiceID);
            $client = $this->get_foreign_object("client");
            $allocDatabase = new AllocDatabase();
            $q = unsafe_prepare("SELECT * FROM invoice WHERE clientID = %d AND invoiceStatus = 'edit' " . $extra, $this->get_value("clientID"));
            $allocDatabase->query($q);

            // Create invoice
            if (!$allocDatabase->next_record()) {
                $invoice = new invoice();
                $invoice->set_value("clientID", $this->get_value("clientID"));
                $invoice->set_value("invoiceDateFrom", $this->get_min_date());
                $invoice->set_value("invoiceDateTo", $this->get_max_date());
                $invoice->set_value("invoiceNum", invoice::get_next_invoiceNum());
                $invoice->set_value("invoiceName", $client->get_value("clientName"));
                $invoice->set_value("invoiceStatus", "edit");
                $invoice->save();
                $invoiceID = $invoice->get_id();

                // Use existing invoice
            } else {
                $invoiceID = $allocDatabase->f("invoiceID");
            }

            // Add invoiceItem and add expense form transactions to invoiceItem
            if ($_POST["split_invoice"]) {
                invoiceEntity::save_invoice_expenseFormItems($invoiceID, $this->get_id());
            } else {
                invoiceEntity::save_invoice_expenseForm($invoiceID, $this->get_id());
            }
        }
    }

    public function get_min_date()
    {
        $allocDatabase = new AllocDatabase();
        $q = unsafe_prepare("SELECT min(transactionDate) as date FROM transaction WHERE expenseFormID = %d", $this->get_id());
        $allocDatabase->query($q);
        $allocDatabase->next_record();
        return $allocDatabase->f('date');
    }

    public function get_max_date()
    {
        $allocDatabase = new AllocDatabase();
        $q = unsafe_prepare("SELECT max(transactionDate) as date FROM transaction WHERE expenseFormID = %d", $this->get_id());
        $allocDatabase->query($q);
        $allocDatabase->next_record();
        return $allocDatabase->f('date');
    }

    public function get_url()
    {
        global $sess;
        $sess or $sess = new Session();

        $url = "finance/expenseForm.php?expenseFormID=" . $this->get_id();

        if ($sess->Started()) {
            $url = $sess->url(SCRIPT_PATH . $url);

            // This for urls that are emailed
        } else {
            static $prefix;
            $prefix or $prefix = config::get_config_item("allocURL");
            $url = $prefix . $url;
        }
        return $url;
    }

    public function get_abs_sum_transactions($id = false)
    {
        if (is_object($this)) {
            $id = $this->get_id();
        }
        $allocDatabase = new AllocDatabase();
        $q = unsafe_prepare("SELECT sum(amount * pow(10,-currencyType.numberToBasic) * exchangeRate) AS amount
                        FROM transaction
                   LEFT JOIN currencyType on transaction.currencyTypeID = currencyType.currencyTypeID
                       WHERE expenseFormID = %d", $id);
        $allocDatabase->query($q);
        $row = $allocDatabase->row();
        return $row["amount"];
    }

    public static function get_list_filter($filter = [])
    {
        $sql = [];
        $filter["projectID"] and $sql[] = unsafe_prepare("transaction.projectID = %d", $filter["projectID"]);
        $filter["status"] and $sql[] = unsafe_prepare("transaction.status = '%s'", $filter["status"]);
        isset($filter["finalised"]) and $sql[] = unsafe_prepare("expenseForm.expenseFormFinalised = %d", $filter["finalised"]);
        return $sql;
    }

    public static function get_list($_FORM = [])
    {
        $f = null;
        $amounts = [];
        $sp = [];
        $allrows = [];
        $rows = [];
        global $TPL;
        $filter = expenseForm::get_list_filter($_FORM);
        if (is_array($filter) && count($filter)) {
            $f = " AND " . implode(" AND ", $filter);
        }

        $db = new AllocDatabase();
        $dbTwo = new AllocDatabase();
        $transDB = new AllocDatabase();
        $expenseForm = new expenseForm();
        $transaction = new transaction();
        $rr_options = expenseForm::get_reimbursementRequired_array();

        $q = unsafe_prepare("SELECT expenseForm.*
                            ,SUM(transaction.amount * pow(10,-currencyType.numberToBasic)) as formTotal
                            ,transaction.currencyTypeID
                        FROM expenseForm, transaction
                   LEFT JOIN currencyType on transaction.currencyTypeID = currencyType.currencyTypeID
                       WHERE expenseForm.expenseFormID = transaction.expenseFormID
                             " . $f . "
                    GROUP BY expenseForm.expenseFormID, transaction.currencyTypeID
                    ORDER BY expenseFormID");

        $db->query($q);

        while ($row = $db->row()) {
            $amounts[$row["expenseFormID"]] .= $sp[$row["expenseFormID"]] . Page::money($row["currencyTypeID"], $row["formTotal"], "%s%m");
            $sp[$row["expenseFormID"]] = " + ";
            $allrows[$row["expenseFormID"]] = $row;
        }
        foreach ((array)$allrows as $expenseFormID => $row) {
            $expenseForm = new expenseForm();
            if ($expenseForm->read_row_record($row)) {
                $expenseForm->set_values();
                $row["formTotal"] = $amounts[$expenseFormID];
                $row["expenseFormModifiedUser"] = person::get_fullname($expenseForm->get_value("expenseFormModifiedUser"));
                $row["expenseFormModifiedTime"] = $expenseForm->get_value("expenseFormModifiedTime");
                $row["expenseFormCreatedUser"] = person::get_fullname($expenseForm->get_value("expenseFormCreatedUser"));
                $row["expenseFormCreatedTime"] = $expenseForm->get_value("expenseFormCreatedTime");
                unset($extra);
                $expenseForm->get_value("paymentMethod") and $extra = " (" . $expenseForm->get_value("paymentMethod") . ")";
                $row["rr_label"] = $rr_options[$expenseForm->get_value("reimbursementRequired")] . $extra;
                $rows[] = $row;
            }
        }
        return (array)$rows;
    }

    public static function get_pending_repeat_transaction_list()
    {
        $rows = [];
        global $TPL;
        $transactionTypes = transaction::get_transactionTypes();
        $q = "SELECT * FROM transaction
           LEFT JOIN transactionRepeat on transactionRepeat.transactionRepeatID = transaction.transactionRepeatID
               WHERE transaction.transactionRepeatID IS NOT NULL AND transaction.status = 'pending'";
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);
        while ($row = $allocDatabase->row()) {
            $transaction = new transaction();
            $transaction->read_db_record($allocDatabase);
            $transaction->set_values();
            $transactionRepeat = new transactionRepeat();
            $transactionRepeat->read_db_record($allocDatabase);
            $transactionRepeat->set_values();
            $row["transactionType"] = $transactionTypes[$transaction->get_value("transactionType")];
            $row["formTotal"] = $allocDatabase->f("amount");
            $row["transactionModifiedTime"] = $transaction->get_value("transactionModifiedTime");
            $row["transactionCreatedTime"] = $transaction->get_value("transactionCreatedTime");
            $row["transactionCreatedUser"] = person::get_fullname($transaction->get_value("transactionCreatedUser"));
            $rows[] = $row;
        }
        return (array)$rows;
    }
}
