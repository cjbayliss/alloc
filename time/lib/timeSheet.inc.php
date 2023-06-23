<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Document\Field;

define("PERM_TIME_APPROVE_TIMESHEETS", 256);
define("PERM_TIME_INVOICE_TIMESHEETS", 512);

class timeSheet extends DatabaseEntity
{
    public $classname = "timeSheet";

    public $data_table = "timeSheet";

    public $display_field_name = "projectID";

    public $key_field = "timeSheetID";

    public $pay_info = null;

    public $fromTfID;

    public $data_fields = [
        "projectID",
        "dateFrom",
        "dateTo",
        "status",
        "personID",
        "approvedByManagerPersonID",
        "approvedByAdminPersonID",
        "dateSubmittedToManager",
        "dateSubmittedToAdmin",
        "dateRejected" => ["empty_to_null" => true],
        "billingNote",
        "recipient_tfID",
        "customerBilledDollars" => ["type" => "money"],
        "currencyTypeID",
    ];

    public $permissions = [PERM_TIME_APPROVE_TIMESHEETS => "approve", PERM_TIME_INVOICE_TIMESHEETS => "invoice"];

    public function is_owner($ignored = null)
    {
        $current_user = &singleton("current_user");

        if (!$this->get_id()) {
            return true;
        }

        if ($this->get_value("personID") == $current_user->get_id()) {
            return true;
        }

        $project = $this->get_foreign_object("project");
        ($managers = $project->get_timeSheetRecipients()) || ($managers = []);
        if (in_array($current_user->get_id(), $managers)) {
            return true;
        }

        if ($current_user->have_role("admin")) {
            return true;
        }

        // This allows people with transactions on this time sheet who may not
        // actually be this time sheets owner to view this time sheet.
        if ($this->get_value("status") != "edit") {
            $current_user_tfIDs = $current_user->get_tfIDs();
            $q = unsafe_prepare("SELECT * FROM transaction WHERE timeSheetID = %d", $this->get_id());
            $allocDatabase = new AllocDatabase();
            $allocDatabase->query($q);
            while ($allocDatabase->next_record()) {
                if (is_array($current_user_tfIDs) && (in_array($allocDatabase->f("tfID"), $current_user_tfIDs) || in_array($allocDatabase->f("fromTfID"), $current_user_tfIDs))) {
                    return true;
                }
            }
        }
    }

    public function save()
    {
        $rtn = parent::save();
        $this->update_related_invoices();
        return $rtn;
    }

    public function update_related_invoices()
    {
        $invoiceEntity = new invoiceEntity();
        if ($rows = $invoiceEntity->get("timeSheet", $this->get_id())) {
            foreach ($rows as $row) {
                if ($row["useItems"]) {
                    $invoiceEntity->save_invoice_timeSheetItems($row["invoiceID"], $this->get_id());
                } else {
                    $invoiceEntity->save_invoice_timeSheet($row["invoiceID"], $this->get_id());
                }
            }
        }
    }

    public static function get_timeSheet_statii()
    {
        return [
            "edit"     => "Add Time",
            "manager"  => "Manager",
            "admin"    => "Administrator",
            "invoiced" => "Invoice",
            "finished" => "Completed",
            "rejected" => "Rejected",
        ];
    }

    public function get_timeSheet_status()
    {
        $statii = timeSheet::get_timeSheet_statii();
        return $statii[$this->get_value("status")];
    }

    public function load_pay_info()
    {

        $extra_sql = [];
        $timeUnitRows = [];
        $sql = null;
        /***************************************************************************
         *                                                                         *
         * load_pay_info() loads these vars:                                       *
         * $this->pay_info["project_rate"];     according to projectPerson table   *
         * $this->pay_info["project_rate_orig"];before the currency transform      *
         * $this->pay_info["timeSheetItem_rate"];according to timeSheetItem table  *
         * $this->pay_info["customerBilledDollars"];                               *
         * $this->pay_info["project_rateUnitID"];   according to projectPerson table *
         * $this->pay_info["duration"][time sheet ITEM ID];                        *
         * $this->pay_info["total_duration"]; of a timesheet                       *
         * $this->pay_info["total_dollars"];  of a timesheet                       *
         * $this->pay_info["total_customerBilledDollars"]                          *
         * $this->pay_info["total_dollars_minus_gst"]                              *
         * $this->pay_info["total_customerBilledDollars_minus_gst"]                *
         * $this->pay_info["unit"]                                                 *
         * $this->pay_info["summary_unit_totals"]                                  *
         * $this->pay_info["total_dollars_not_null"] tot_custbilled/tot_dollars    *
         * $this->pay_info["currency"]; according to timeSheet table               *
         *                                                                         *
         ***************************************************************************/

        static $rates;
        unset($this->pay_info);
        $allocDatabase = new AllocDatabase();

        if (!$this->get_value("projectID") || !$this->get_value("personID")) {
            return false;
        }

        $currency = $this->get_value("currencyTypeID");

        // The unit labels
        $timeUnit = new timeUnit();
        $units = array_reverse($timeUnit->get_assoc_array("timeUnitID", "timeUnitLabelA"), true);

        if ($rates[$this->get_value("projectID")][$this->get_value("personID")]) {
            [$this->pay_info["project_rate"], $this->pay_info["project_rateUnitID"]] = $rates[$this->get_value("projectID")][$this->get_value("personID")];
        } else {
            // Get rate for person for this particular project
            $allocDatabase->query("SELECT rate, rateUnitID, project.currencyTypeID
                   FROM projectPerson
              LEFT JOIN project on projectPerson.projectID = project.projectID
                  WHERE projectPerson.projectID = %d
                    AND projectPerson.personID = %d", $this->get_value("projectID"), $this->get_value("personID"));

            $allocDatabase->next_record();
            $this->pay_info["project_rate"] = Page::money($allocDatabase->f("currencyTypeID"), $allocDatabase->f("rate"), "%mo");
            $this->pay_info["project_rateUnitID"] = $allocDatabase->f("rateUnitID");
            $rates[$this->get_value("projectID")][$this->get_value("personID")] = [$this->pay_info["project_rate"], $this->pay_info["project_rateUnitID"]];
        }

        // Get external rate, only load up customerBilledDollars if the field is actually set
        if ($this->get_value("customerBilledDollars") !== null && (bool)strlen($this->get_value("customerBilledDollars"))) {
            $this->pay_info["customerBilledDollars"] = Page::money($currency, $this->get_value("customerBilledDollars"), "%mo");
        }

        $q = "SELECT * FROM timeUnit ORDER BY timeUnitSequence DESC";
        $allocDatabase->query($q);
        while ($row = $allocDatabase->row()) {
            if ($row["timeUnitSeconds"]) {
                $extra_sql[] = "SUM(IF(timeUnit.timeUnitLabelA = '" . $row["timeUnitLabelA"] . "',multiplier * timeSheetItemDuration * timeUnit.timeUnitSeconds,0)) /" . $row["timeUnitSeconds"] . " as " . $row["timeUnitLabelA"];
            }

            $timeUnitRows[] = $row;
        }

        $extra_sql && ($sql = "," . implode("\n,", $extra_sql));

        // Get duration for this timesheet/timeSheetItems
        $allocDatabase->query(unsafe_prepare(
            "SELECT SUM(timeSheetItemDuration) AS total_duration,
                    SUM((timeSheetItemDuration * timeUnit.timeUnitSeconds) / 3600) AS total_duration_hours,
                    SUM((rate * pow(10,-currencyType.numberToBasic)) * timeSheetItemDuration * multiplier) AS total_dollars,
                    SUM((IFNULL(timeSheet.customerBilledDollars,0) * pow(10,-currencyType.numberToBasic)) * timeSheetItemDuration * multiplier) AS total_customerBilledDollars
                    " . $sql . "
               FROM timeSheetItem
          LEFT JOIN timeUnit ON timeUnit.timeUnitID = timeSheetItem.timeSheetItemDurationUnitID
          LEFT JOIN timeSheet on timeSheet.timeSheetID = timeSheetItem.timeSheetID
          LEFT JOIN currencyType on currencyType.currencyTypeID = timeSheet.currencyTypeID
              WHERE timeSheetItem.timeSheetID = %d",
            $this->get_id()
        ));

        $row = $allocDatabase->row();
        $this->pay_info = array_merge((array)$this->pay_info, (array)$row);
        $this->pay_info["total_customerBilledDollars"] = Page::money($currency, $this->pay_info["total_customerBilledDollars"], "%m");
        $this->pay_info["total_dollars"] = Page::money($currency, $this->pay_info["total_dollars"], "%m");

        $commar = "";
        foreach ((array)$timeUnitRows as $r) {
            if ($row[$r["timeUnitLabelA"]] != 0) {
                $this->pay_info["summary_unit_totals"] .= $commar . ($row[$r["timeUnitLabelA"]] + 0) . " " . $r["timeUnitLabelA"];
                $commar = ", ";
            }
        }

        if (!isset($this->pay_info["total_dollars"])) {
            $this->pay_info["total_dollars"] = 0;
        }

        if (!isset($this->pay_info["total_duration"])) {
            $this->pay_info["total_duration"] = 0;
        }

        if (!isset($this->pay_info["total_duration_hours"])) {
            $this->pay_info["total_duration_hours"] = 0;
        }

        $taxPercent = config::get_config_item("taxPercent");
        $taxPercentDivisor = ($taxPercent / 100) + 1;
        $this->pay_info["total_dollars_minus_gst"] = Page::money($currency, $this->pay_info["total_dollars"] / $taxPercentDivisor, "%m");
        $this->pay_info["total_customerBilledDollars_minus_gst"] = Page::money($currency, $this->pay_info["total_customerBilledDollars"] / $taxPercentDivisor, "%m");
        ($this->pay_info["total_dollars_not_null"] = $this->pay_info["total_customerBilledDollars"]) || ($this->pay_info["total_dollars_not_null"] = $this->pay_info["total_dollars"]);
        $this->pay_info["currency"] = Page::money($currency, '', "%S");
    }

    public function destroyTransactions()
    {
        $allocDatabase = new AllocDatabase();
        $query = unsafe_prepare("DELETE FROM transaction WHERE timeSheetID = %d AND transactionType != 'invoice'", $this->get_id());
        $allocDatabase->query($query);
    }

    public function createTransactions($status = "pending")
    {

        $errmsg = null;
        $rtnmsg = null;
        // So this will only create transaction if:
        // - The timesheet status is admin
        // - There is a recipient_tfID - that is the money is going to a TF
        $allocDatabase = new AllocDatabase();
        $project = $this->get_foreign_object("project");
        $projectName = $project->get_value("projectName");
        $personName = person::get_fullname($this->get_value("personID"));
        $company_tfID = config::get_config_item("mainTfID");
        ($cost_centre = $project->get_value("cost_centre_tfID")) || ($cost_centre = $company_tfID);
        $this->fromTfID = $cost_centre;
        $this->load_pay_info();

        if ($this->get_value("status") != "invoiced") {
            return "ERROR: Status of the timesheet must be 'invoiced' to Create Transactions.
              The status is currently: " . $this->get_value("status");
        }

        if ($this->get_value("recipient_tfID") == "") {
            return "ERROR: There is no recipient Tagged Fund to credit for this timesheet.
              Go to Tools -> New Tagged Fund, add a new TF and add the owner. Then go
              to People -> Select the user and set their Preferred Payment TF.";
        }

        if (!$cost_centre || $cost_centre == 0) {
            return "ERROR: There is no cost centre associated with the project.";
        }

        $taxName = config::get_config_item("taxName");
        $taxPercent = config::get_config_item("taxPercent");
        $taxTfID = config::get_config_item("taxTfID");
        $taxPercentDivisor = ($taxPercent / 100) + 1;
        $recipient_tfID = $this->get_value("recipient_tfID");
        $timeSheetRecipients = $project->get_timeSheetRecipients();

        $rtn = [];

        // This is just for internal transactions
        if ($_POST["create_transactions_default"] && $this->pay_info["total_customerBilledDollars"] == 0) {
            $this->pay_info["total_customerBilledDollars_minus_gst"] = $this->pay_info["total_dollars"];
            // 1. Credit Employee TF
            $product = "Timesheet #" . $this->get_id() . " for " . $projectName . " (" . $this->pay_info["summary_unit_totals"] . ")";
            $rtn[$product] = $this->createTransaction($product, $this->pay_info["total_dollars"], $recipient_tfID, "timesheet", $status);
            // 2. Payment Insurance
            // removed
        } elseif ($_POST["create_transactions_default"]) {
            /*  This was previously named "Simple" transactions. Ho ho.
                            On the Project page we care about these following variables:
                            - Client Billed At $amount eg: $121
                            - The projectPersons rate for this project eg: $50;

                            $121 after gst == $110
                            cyber get 28.5% of $110
                            djk get $50
                            commissions
                            whatever is left of the $110 goes to the 0% commissions
                        */
            // 1. Credit TAX/GST Cost Centre
            $product = $taxName . " " . $taxPercent . "% for timesheet #" . $this->get_id();
            $rtn[$product] = $this->createTransaction($product, ($this->pay_info["total_customerBilledDollars"] - $this->pay_info["total_customerBilledDollars_minus_gst"]), $taxTfID, "tax", $status);
            // 3. Credit Employee TF
            $product = "Timesheet #" . $this->get_id() . " for " . $projectName . " (" . $this->pay_info["summary_unit_totals"] . ")";
            $rtn[$product] = $this->createTransaction($product, $this->pay_info["total_dollars"], $recipient_tfID, "timesheet", $status);
            // 4. Credit Project Commissions
            $allocDatabase->query("SELECT * FROM projectCommissionPerson where projectID = %d ORDER BY commissionPercent DESC", $this->get_value("projectID"));
            while ($allocDatabase->next_record()) {
                if ($allocDatabase->f("commissionPercent") > 0) {
                    $product = "Commission " . $allocDatabase->f("commissionPercent") . "% of " . $this->pay_info["currency"] . $this->pay_info["total_customerBilledDollars_minus_gst"];
                    $product .= " from timesheet #" . $this->get_id() . ".  Project: " . $projectName;
                    $amount = $this->pay_info["total_customerBilledDollars_minus_gst"] * ($allocDatabase->f("commissionPercent") / 100);
                    $rtn[$product] = $this->createTransaction($product, $amount, $allocDatabase->f("tfID"), "commission", $status);
                    // Suck up the rest of funds if it is a special zero % commission
                } elseif ($allocDatabase->f("commissionPercent") == 0) {
                    $amount = $this->pay_info["total_customerBilledDollars_minus_gst"] - $this->get_amount_so_far();
                    if ($amount < 0) {
                        $amount = 0;
                    }

                    // If the 0% commission is for the company tf, dump it in the company tf
                    if ($allocDatabase->f("tfID") == $company_tfID) {
                        $product = "Commission Remaining from timesheet #" . $this->get_id() . ".  Project: " . $projectName;
                        $rtn[$product] = $this->createTransaction($product, $amount, $allocDatabase->f("tfID"), "commission");
                    } elseif (config::for_cyber()) {
                        // If it's cyber do a 50/50 split with the commission tf and the company
                        $amount /= 2;
                        $product = "Commission Remaining from timesheet #" . $this->get_id() . ".  Project: " . $projectName;
                        $rtn[$product] = $this->createTransaction($product, $amount, $allocDatabase->f("tfID"), "commission");
                        $rtn[$product] = $this->createTransaction($product, $amount, $company_tfID, "commission", $status);
                        // 50/50
                    } else {
                        $product = "Commission Remaining from timesheet #" . $this->get_id() . ".  Project: " . $projectName;
                        $rtn[$product] = $this->createTransaction($product, $amount, $allocDatabase->f("tfID"), "commission");
                    }
                }
            }
        }

        foreach ($rtn as $error => $v) {
            if ($v != 1) {
                $errmsg .= "<br>FAILED: " . $error;
            }
        }

        if ($errmsg !== null) {
            $this->destroyTransactions();
            $rtnmsg .= "<br>Failed to create transactions... " . $errmsg;
        }

        return $rtnmsg;
    }

    public function get_amount_so_far($include_tax = false)
    {
        $q = unsafe_prepare("SELECT SUM(amount * pow(10,-currencyType.numberToBasic) * exchangeRate) AS balance
                        FROM transaction
                   LEFT JOIN currencyType ON currencyType.currencyTypeID = transaction.currencyTypeID
                       WHERE timeSheetID = %d AND transactionType != 'invoice'
                     ", $this->get_id());
        $include_tax || ($q .= "AND transactionType != 'tax'");
        $allocDatabase = new AllocDatabase();
        $r = $allocDatabase->qr($q);
        return $r['balance'];
    }

    public function createTransaction($product, $amount, $tfID, $transactionType, $status = "", $fromTfID = false)
    {

        $transaction = null;
        if ($amount == 0) {
            return 1;
        }

        $status || ($status = "pending");
        $fromTfID || ($fromTfID = $this->fromTfID);

        if ($tfID == 0 || !$tfID || !is_numeric($tfID) || !is_numeric($amount)) {
            return "Error -> \$tfID: " . $tfID . "  and  \$amount: " . $amount;
        }

        $transaction = new transaction();
        $transaction->set_value("product", $product);
        $transaction->set_value("amount", $amount);
        $transaction->set_value("status", $status);
        $transaction->set_value("fromTfID", $fromTfID);
        $transaction->set_value("tfID", $tfID);
        $transaction->set_value("transactionDate", date("Y-m-d"));
        $transaction->set_value("transactionType", $transactionType);
        $transaction->set_value("timeSheetID", $this->get_id());
        $transaction->set_value("currencyTypeID", $this->get_value("currencyTypeID"));
        $transaction->save();
        return 1;
    }

    public function shootEmail($email): string
    {

        $addr = $email["to"];
        $msg = $email["body"];
        $sub = $email["subject"];
        $type = $email["type"];
        $dummy = $_POST["dont_send_email"];

        // New email object wrapper takes care of logging etc.
        $email = new email_send($addr, $sub, $msg, $type);

        // REMOVE ME!!
        // $email->ignore_no_email_urls = true;

        if ($dummy) {
            return "Elected not to send email.";
        }

        if (!$email->is_valid_url()) {
            return "Almost sent email to: " . $email->to_address;
        }

        if (!$email->to_address) {
            return "Could not send email, invalid email address: " . $email->to_address;
        }

        if ($email->send()) {
            return "Sent email to: " . $email->to_address;
        }

        return "Problem sending email to: " . $email->to_address;
    }

    public function get_task_list_dropdown($status, $timeSheetID, $taskID = ""): string
    {

        $options = [];
        $tasks = [];
        if (is_object($this)) {
            $personID = $this->get_value('personID');
            $projectID = $this->get_value('projectID');
        } elseif ($timeSheetID) {
            $t = new timeSheet();
            $t->set_id($timeSheetID);
            $t->select();
            $personID = $t->get_value('personID');
            $projectID = $t->get_value('projectID');
        }

        $options["projectID"] = $projectID;
        $options["personID"] = $personID;
        $options["taskView"] = "byProject";
        $options["return"] = "array";
        $options["taskTimeSheetStatus"] = $status;
        $taskrows = Task::get_list($options);
        foreach ((array)$taskrows as $tid => $row) {
            $tasks[$tid] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $row["padding"]) . $tid . " " . $row["taskName"];
        }

        if ($taskID) {
            $t = new Task();
            $t->set_id($taskID);
            $t->select();
            $tasks[$taskID] = $t->get_id() . " " . $t->get_name();
        }

        $dropdown_options = Page::select_options((array)$tasks, $taskID, 100);
        return '<select name="timeSheetItem_taskID" style="width:400px"><option value="">' . $dropdown_options . "</select>";
    }

    public static function get_list_filter($filter = [])
    {
        $sql = [];
        $current_user = &singleton("current_user");

        // If they want starred, load up the timeSheetID filter element
        if (isset($filter["starred"])) {
            $starredTimeSheets = isset($current_user->prefs["stars"]) ?
                ($current_user->prefs["stars"]["timeSheet"] ?? "") : "";
            if (!empty($starredTimeSheets) && is_array($starredTimeSheets)) {
                foreach (array_keys($starredTimeSheets) as $k) {
                    $filter["timeSheetID"][] = $k;
                }
            }

            if (!is_array($filter["timeSheetID"] ?? "")) {
                $filter["timeSheetID"][] = -1;
            }
        }

        // Filter timeSheetID
        if (isset($filter["timeSheetID"])) {
            $sql[] = sprintf_implode("timeSheet.timeSheetID = %d", $filter["timeSheetID"]);
        }

        // No point continuing if primary key specified, so return
        if (isset($filter["timeSheetID"]) || isset($filter["starred"])) {
            return $sql;
        }

        if (isset($filter["tfID"])) {
            $sql[] = sprintf_implode("timeSheet.recipient_tfID = %d", $filter["tfID"]);
        }

        if (isset($filter["projectID"])) {
            $sql[] = sprintf_implode("timeSheet.projectID = %d", $filter["projectID"]);
        }

        if (isset($filter["taskID"])) {
            $sql[] = sprintf_implode("timeSheetItem.taskID = %d", $filter["taskID"]);
        }

        if (isset($filter["personID"])) {
            $sql[] = sprintf_implode("timeSheet.personID = %d", $filter["personID"]);
        }

        if (isset($filter["status"])) {
            $sql[] = sprintf_implode("timeSheet.status = '%s'", $filter["status"]);
        }

        if (isset($filter["dateFrom"])) {
            if (!in_array($filter["dateFromComparator"], ["=", "!=", ">", ">=", "<", "<="])) {
                $filter["dateFromComparator"] = '=';
            }

            $sql[] = unsafe_prepare("(timeSheet.dateFrom " . $filter['dateFromComparator'] . " '%s')", $filter["dateFrom"]);
        }

        if (isset($filter["dateTo"])) {
            if (!in_array($filter["dateToComparator"], ["=", "!=", ">", ">=", "<", "<="])) {
                $filter["dateToComparator"] = '=';
            }

            $sql[] = unsafe_prepare("(timeSheet.dateTo " . $filter['dateToComparator'] . " '%s')", $filter["dateTo"]);
        }

        return $sql;
    }

    public static function get_list($_FORM)
    {
        $extra = [];
        $amount_tallies = [];
        $billed_tallies = [];
        $rows = [];
        $pos_tallies = [];
        $neg_tallies = [];
        // This is the definitive method of getting a list of timeSheets that need a sophisticated level of filtering

        global $TPL;
        $current_user = &singleton("current_user");
        if (isset($_FORM["showShortProjectLink"])) {
            $_FORM["showProjectLink"] = true;
        }

        $filter = timeSheet::get_list_filter($_FORM);

        // Used in timeSheetListS.tpl
        $extra["showFinances"] = $_FORM["showFinances"] ?? "";
        $_FORM["return"] ??= "html";

        $filter = is_array($filter) && count($filter) ? " WHERE " . implode(" AND ", $filter) : "";

        $q = "SELECT timeSheet.*, person.personID, projectName, projectShortName
                FROM timeSheet
           LEFT JOIN person ON timeSheet.personID = person.personID
           LEFT JOIN project ON timeSheet.projectID = project.projectID
           LEFT JOIN timeSheetItem ON timeSheet.timeSheetID = timeSheetItem.timeSheetID
                     " . $filter . "
            GROUP BY timeSheet.timeSheetID
            ORDER BY dateFrom,projectName,timeSheet.status,surname";

        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);

        $status_array = timeSheet::get_timeSheet_statii();
        $people_array = &get_cached_table("person");

        while ($row = $allocDatabase->next_record()) {
            $t = new timeSheet();
            if (!$t->read_db_record($allocDatabase)) {
                continue;
            }

            $t->load_pay_info();

            if ($_FORM["timeSheetItemHours"] && ($_FORM["timeSheetItemHours"] != $t->pay_info["total_duration_hours"])) {
                continue;
            }

            $row["currencyTypeID"] = $t->get_value("currencyTypeID");
            $row["amount"] = $t->pay_info["total_dollars"];
            $amount_tallies[] = ["amount" => $row["amount"], "currency" => $row["currencyTypeID"]];
            $extra["amountTotal"] += exchangeRate::convert($row["currencyTypeID"], $row["amount"]);
            $extra["totalHours"] += $t->pay_info["total_duration_hours"];
            $row["totalHours"] += $t->pay_info["total_duration_hours"];
            $row["duration"] = $t->pay_info["summary_unit_totals"];

            if (
                $t->get_value("status") == "edit" && (isset($current_user->prefs["timeSheetHoursWarn"]) && (bool)strlen($current_user->prefs["timeSheetHoursWarn"]))
                && $t->pay_info["total_duration_hours"] >= $current_user->prefs["timeSheetHoursWarn"]
            ) {
                $row["hoursWarn"] = Page::help("This time sheet has gone over " . $current_user->prefs["timeSheetHoursWarn"] . " hours.", Page::warn());
            }

            if (
                $t->get_value("status") == "edit" && (isset($current_user->prefs["timeSheetDaysWarn"]) && (bool)strlen($current_user->prefs["timeSheetDaysWarn"]))
                && (time() - format_date("U", $t->get_value("dateFrom"))) / 60 / 60 / 24 >= $current_user->prefs["timeSheetDaysWarn"]
            ) {
                $row["daysWarn"] = Page::help("This time sheet is over " . $current_user->prefs["timeSheetDaysWarn"] . " days old.", Page::warn());
            }

            $row["person"] = $people_array[$row["personID"]]["name"];
            $row["status"] = $status_array[$row["status"]];
            $row["customerBilledDollars"] = $t->pay_info["total_customerBilledDollars"];
            $extra["customerBilledDollarsTotal"] += exchangeRate::convert($row["currencyTypeID"], $t->pay_info["total_customerBilledDollars"]);
            $billed_tallies[] = ["amount" => $row["customerBilledDollars"], "currency" => $row["currencyTypeID"]];

            if ($_FORM["showFinances"]) {
                [$pos, $neg] = $t->get_transaction_totals();
                $row["transactionsPos"] = Page::money_print($pos);
                $row["transactionsNeg"] = Page::money_print($neg);
                foreach ((array)$pos as $v) {
                    $pos_tallies[] = $v;
                }

                foreach ((array)$neg as $v) {
                    $neg_tallies[] = $v;
                }
            }

            $p = new project();
            $p->read_db_record($allocDatabase);
            $row["projectLink"] = $t->get_link($p->get_name($_FORM));
            $rows[$row["timeSheetID"]] = $row;
        }

        $extra["amount_tallies"] = Page::money_print($amount_tallies);
        $extra["billed_tallies"] = Page::money_print($billed_tallies);
        $extra["positive_tallies"] = Page::money_print($pos_tallies);
        $extra["negative_tallies"] = Page::money_print($neg_tallies);

        if (!isset($_FORM["noextra"])) {
            return ["rows" => (array)$rows, "extra" => $extra];
        }

        return (array)$rows;
    }

    public static function get_list_html($rows = [], $extra = [])
    {
        global $TPL;
        $TPL["timeSheetListRows"] = $rows;
        $TPL["extra"] = $extra;
        include_template(__DIR__ . "/../templates/timeSheetListS.tpl");
    }

    public function get_transaction_totals()
    {

        $pos = [];
        $neg = [];
        $allocDatabase = new AllocDatabase();
        $q = unsafe_prepare("SELECT amount * pow(10,-currencyType.numberToBasic) AS amount,
                             transaction.currencyTypeID as currency
                        FROM transaction
                   LEFT JOIN currencyType on transaction.currencyTypeID = currencyType.currencyTypeID
                       WHERE status = 'approved'
                         AND timeSheetID = %d
                     ", $this->get_id());
        $allocDatabase->query($q);
        while ($row = $allocDatabase->row()) {
            if ($row["amount"] > 0) {
                $pos[] = $row;
            } else {
                $neg[] = $row;
            }
        }

        return [$pos, $neg];
    }

    public function get_url()
    {
        global $sess;
        $sess || ($sess = new Session());

        $url = "time/timeSheet.php?timeSheetID=" . $this->get_id();

        if ($sess->Started()) {
            $url = $sess->url(SCRIPT_PATH . $url);

            // This for urls that are emailed
        } else {
            static $prefix;
            $prefix || ($prefix = config::get_config_item("allocURL"));
            $url = $prefix . $url;
        }

        return $url;
    }

    public function get_link($text = false): string
    {
        $text || ($text = $this->get_id());
        return '<a href="' . $this->get_url() . '">' . $text . "</a>";
    }

    public static function get_list_vars()
    {
        return [
            "return"               => "[MANDATORY] eg: array | html",
            "timeSheetID"          => "Time Sheet that has this ID",
            "starred"              => "Time Sheet that have been starred",
            "projectID"            => "Time Sheets that belong to this Project",
            "taskID"               => "Time Sheets that use this task",
            "personID"             => "Time Sheets for this person",
            "status"               => "Time Sheet status eg: edit | manager | admin | invoiced | finished",
            "dateFrom"             => "Time Sheets from a particular date",
            "dateFromComparator"   => "The comparison operator: >, >=, <, <=, =, !=",
            "dateTo"               => "Time Sheets to a particular date",
            "dateToComparator"     => "The comparison operator: >, >=, <, <=, =, !=",
            "timeSheetItemHours"   => "Time Sheets that have a certain amount of hours billed eg: '>7 AND <10 OR =4 AND !=8'",
            "url_form_action"      => "The submit action for the filter form",
            "form_name"            => "The name of this form, i.e. a handle for referring to this saved form",
            "dontSave"             => "Specify that the filter preferences should not be saved this time",
            "applyFilter"          => "Saves this filter as the persons preference",
            "showShortProjectLink" => "Show short Project link",
            "showFinances"         => "Shortcut for displaying the transactions and the totals",
            "tfID"                 => "Time sheets that belong to this TF",
            "showAllProjects"      => "Show archived and potential projects",
        ];
    }

    public static function load_form_data($defaults = [])
    {
        $current_user = &singleton("current_user");

        $page_vars = array_keys(timeSheet::get_list_vars());

        $_FORM = get_all_form_data($page_vars, $defaults);

        if (!isset($_FORM["applyFilter"])) {
            if (isset($_FORM["form_name"]) && isset($current_user->prefs[$_FORM["form_name"]])) {
                $_FORM = $current_user->prefs[$_FORM["form_name"]];
            }

            if (!isset($current_user->prefs[$_FORM["form_name"] ?? ""])) {
                $_FORM["status"] = "edit";
                $_FORM["personID"] = $current_user->get_id();
            }
        } elseif (isset($_FORM["applyFilter"]) && is_object($current_user) && !isset($_FORM["dontSave"])) {
            $url = $_FORM["url_form_action"];
            unset($_FORM["url_form_action"]);
            $current_user->prefs[$_FORM["form_name"]] = $_FORM;
            $_FORM["url_form_action"] = $url;
        }

        return $_FORM;
    }

    public static function load_timeSheet_filter($_FORM)
    {
        $filter = null;
        $rtn = [];
        $current_user = &singleton("current_user");

        // display the list of project name.
        $allocDatabase = new AllocDatabase();
        if (!isset($_FORM['showAllProjects'])) {
            $filter = "WHERE projectStatus = 'Current' ";
        }

        $query = unsafe_prepare(sprintf('SELECT projectID AS value, projectName AS label FROM project %s ORDER by projectName', $filter));
        $rtn["show_project_options"] = Page::select_options($query, $_FORM["projectID"] ?? "", 70);

        // display the list of user name.
        if (have_entity_perm("timeSheet", PERM_READ, $current_user, false)) {
            $rtn["show_userID_options"] = Page::select_options(person::get_username_list(), $_FORM["personID"] ?? "gp stop");
        } else {
            $person = new person();
            $person->set_id($current_user->get_id());
            $person->select();
            $person_array = [$current_user->get_id() => $person->get_name()];
            $rtn["show_userID_options"] = Page::select_options($person_array, $_FORM["personID"]);
        }

        // display a list of status
        $status_array = timeSheet::get_timeSheet_statii();
        unset($status_array["create"]);

        if (!$_FORM["status"]) {
            $_FORM["status"][] = 'edit';
        }

        $rtn["show_status_options"] = Page::select_options($status_array, $_FORM["status"]);

        // display the date from filter value
        $rtn["dateFrom"] = $_FORM["dateFrom"] ?? "";
        $rtn["dateTo"] = $_FORM["dateTo"] ?? "";
        $rtn["userID"] = $current_user->get_id();
        $rtn["showFinances"] = $_FORM["showFinances"] ?? "";
        $rtn["showAllProjects"] = $_FORM["showAllProjects"] ?? "";

        // Get
        $rtn["FORM"] = "FORM=" . urlencode(serialize($_FORM));

        return $rtn;
    }

    public function get_invoice_link()
    {
        global $TPL;
        $str = "";
        $sp = "";
        $invoiceEntity = new invoiceEntity();

        $rows = $invoiceEntity->get("timeSheet", $this->get_id());
        foreach ($rows as $row) {
            $str .= $sp . '<a href="' . $TPL["url_alloc_invoice"] . "invoiceID=" . $row["invoiceID"] . '">' . $row["invoiceNum"] . "</a>";
            $sp = "&nbsp;&nbsp;";
        }

        return $str;
    }

    public function change_status($direction)
    {
        $steps = [];
        // access controls are partially disabled for timesheets. Make sure time sheet is really accessible by checking
        // user ID - it's restricted to being NOT NULL in the DB. Not doing this check allows a user to overwrite
        // an existing timesheet with a new one assigned to themself.

        if (!$this->get_value("personID")) {
            alloc_error("You do not have access to this timesheet.");
        }

        $info = $this->get_email_vars();
        if (is_array($info["projectManagers"]) && count($info["projectManagers"])) {
            $steps["forwards"]["edit"] = "manager";
            $steps["forwards"]["rejected"] = "manager";
            $steps["backwards"]["admin"] = "manager";
        } else {
            $steps["forwards"]["edit"] = "admin";
            $steps["forwards"]["rejected"] = "admin";
            $steps["backwards"]["admin"] = "edit";
        }

        $steps["forwards"][""] = "edit";
        $steps["forwards"]["manager"] = "admin";
        $steps["forwards"]["admin"] = "invoiced";
        $steps["forwards"]["invoiced"] = "finished";
        $steps["forwards"]["finished"] = "";
        $steps["backwards"]["finished"] = "invoiced";
        $steps["backwards"]["invoiced"] = "admin";
        $steps["backwards"]["manager"] = "edit";
        $status = $this->get_value("status");
        $newstatus = $steps[$direction][$status];
        if ($newstatus !== '' && $newstatus !== '0') {
            $m = $this->{"email_move_status_to_" . $newstatus}($direction, $info);
            // $this->save();
            if (is_array($m)) {
                return implode("<br>", $m);
            }
        }
    }

    public function email_move_status_to_edit($direction, $info)
    {
        // is possible to move backwards to "edit", from both "manager" and "admin"
        // requires manager or APPROVE_TIMESHEET permission
        $msg = [];
        $current_user = &singleton("current_user");
        $project = $this->get_foreign_object("project");
        $projectManagers = $project->get_timeSheetRecipients();
        $commentTemplate = new commentTemplate();

        if ($direction == "backwards") {
            if (
                !in_array($current_user->get_id(), $projectManagers) &&
                !$this->have_perm(PERM_TIME_APPROVE_TIMESHEETS)
            ) {
                // error, go away
                alloc_error("You do not have permission to change this timesheet.");
            }

            $email = [];
            $email["type"] = "timesheet_reject";
            $email["to"] = $info["timeSheet_personID_email"];
            $email["subject"] = $commentTemplate->populate_string(
                config::get_config_item("emailSubject_timeSheetFromManager"),
                "timeSheet",
                $this->get_id()
            );
            $email["body"] = <<<EOD
                         To: {$info["timeSheet_personID_name"]}
                 Time Sheet: {$info["url"]}
                For Project: {$info["projectName"]}
                Rejected By: {$info["people_cache"][$current_user->get_id()]["name"]}

                EOD;
            $this->get_value("billingNote") && ($email["body"] .= "Billing Note: " . $this->get_value("billingNote"));
            $msg[] = $this->shootEmail($email);

            $this->set_value("dateSubmittedToAdmin", "");
            $this->set_value("approvedByAdminPersonID", "");
            $this->set_value("dateSubmittedToManager", "");
            $this->set_value("approvedByManagerPersonID", "");
            $this->set_value("dateRejected", date("Y-m-d"));
        }

        $this->set_value("status", "rejected");
        return $msg;
    }

    public function email_move_status_to_manager($direction, $info)
    {
        $hasItems = null;
        $msg = [];
        $current_user = &singleton("current_user");
        $commentTemplate = new commentTemplate();

        // Can get forwards to "manager" only from "edit" or "rejected"
        if ($direction == "forwards") {
            // forward to manager requires the timesheet to be owned by the current
            // user or TIME_INVOICE_TIMESHEETS
            // project managers may not do this
            if ($this->get_value("personID") != $current_user->get_id() && !$this->have_perm(PERM_TIME_INVOICE_TIMESHEETS)) {
                alloc_error("You do not have permission to change this timesheet.");
            }

            $this->set_value("dateSubmittedToManager", date("Y-m-d"));
            $this->set_value("dateRejected", "");
            // Check for time overrun
            $overrun_tasks = [];
            $allocDatabase = new AllocDatabase();
            $task_id_query = unsafe_prepare("SELECT DISTINCT taskID FROM timeSheetItem WHERE timeSheetID=%d ORDER BY dateTimeSheetItem, timeSheetItemID", $this->get_id());
            $allocDatabase->query($task_id_query);
            while ($allocDatabase->next_record()) {
                $task = new Task();
                $task->read_db_record($allocDatabase);
                $task->select();
                if ($task->get_value('timeLimit') > 0) {
                    $total_billed_time = ($task->get_time_billed(false)) / 3600;
                    if ($total_billed_time > $task->get_value('timeLimit')) {
                        $overrun_tasks[] = sprintf(" * %d %s (limit: %.02f hours, billed so far: %.02f hours)", $task->get_id(), $task->get_value('taskName'), $task->get_value('timeLimit'), $total_billed_time);
                    }
                }

                $hasItems = true;
            }

            if ($hasItems === null) {
                return alloc_error('Unable to submit time sheet, no items have been added.');
            }

            if ($overrun_tasks !== []) {
                $overrun_notice = "\n\nThe following tasks billed on this timesheet have exceeded their time estimates:\n";
                $overrun_notice .= implode("\n", $overrun_tasks);
            }

            foreach ($info["projectManagers"] as $pm) {
                $email = [];
                $email["type"] = "timesheet_submit";
                $email["to"] = $info["people_cache"][$pm]["emailAddress"];
                $email["subject"] = $commentTemplate->populate_string(
                    config::get_config_item("emailSubject_timeSheetToManager"),
                    "timeSheet",
                    $this->get_id()
                );
                $email["body"] = <<<EOD
                      To Manager: {$info["people_cache"][$pm]["name"]}
                      Time Sheet: {$info["url"]}
                    Submitted By: {$info["timeSheet_personID_name"]}
                     For Project: {$info["projectName"]}

                    A timesheet has been submitted for your approval. If it is satisfactory,
                    submit the timesheet to the Administrator. If not, make it editable again for
                    re-submission.{$overrun_notice}

                    EOD;
                $this->get_value("billingNote") && ($email["body"] .= "\n\nBilling Note: " . $this->get_value("billingNote"));
                $msg[] = $this->shootEmail($email);
            }

            // Can get backwards to "manager" only from "admin"
        } elseif ($direction == "backwards") {
            // admin->manager requires APPROVE_TIMESHEETS
            if (!$this->have_perm(PERM_TIME_INVOICE_TIMESHEETS)) {
                // no permission, go away
                alloc_error("You do not have permission to change this timesheet.");
            }

            $email = [];
            $email["type"] = "timesheet_reject";
            $email["to"] = $info["approvedByManagerPersonID_email"];
            $email["subject"] = $commentTemplate->populate_string(
                config::get_config_item("emailSubject_timeSheetFromAdministrator"),
                "timeSheet",
                $this->get_id()
            );
            $email["body"] = <<<EOD
                  To Manager: {$info["approvedByManagerPersonID_name"]}
                  Time Sheet: {$info["url"]}
                Submitted By: {$info["timeSheet_personID_name"]}
                 For Project: {$info["projectName"]}
                 Rejected By: {$info["people_cache"][$current_user->get_id()]["name"]}

                EOD;
            $this->get_value("billingNote") && ($email["body"] .= "Billing Note: " . $this->get_value("billingNote"));
            $msg[] = $this->shootEmail($email);
            $this->set_value("dateRejected", date("Y-m-d"));
        }

        $this->set_value("status", "manager");
        $this->set_value("dateSubmittedToAdmin", "");
        $this->set_value("approvedByAdminPersonID", "");
        return $msg;
    }

    public function email_move_status_to_admin($direction, $info)
    {
        $msg = [];
        $current_user = &singleton("current_user");
        $project = $this->get_foreign_object("project");
        $projectManagers = $project->get_timeSheetRecipients();
        $commentTemplate = new commentTemplate();

        // Can get forwards to "admin" from "edit" and "manager"
        if ($direction == "forwards") {
            // 3 ways to have permission to do this
            // project manager for the timesheet
            // no project manager and owner of the timesheet
            // the permission flag
            if (!(in_array($current_user->get_id(), $projectManagers) ||
                (empty($projectManagers) && $this->get_value("personID") == $current_user->get_id()) ||
                $this->have_perm(PERM_TIME_APPROVE_TIMESHEETS))) {
                // error, go away
                alloc_error("You do not have permission to change this timesheet.");
            }

            $allocDatabase = new AllocDatabase();
            $hasItems = $allocDatabase->qr("SELECT * FROM timeSheetItem WHERE timeSheetID = %d", $this->get_id());
            if (!$hasItems) {
                return alloc_error('Unable to submit time sheet, no items have been added.');
            }

            if ($this->get_value("status") == "manager") {
                $this->set_value("approvedByManagerPersonID", $current_user->get_id());
                $extra = " Approved By: " . person::get_fullname($current_user->get_id());
            }

            $this->set_value("status", "admin");
            $this->set_value("dateSubmittedToAdmin", date("Y-m-d"));
            $this->set_value("dateRejected", "");
            foreach ($info["timeSheetAdministrators"] as $adminID) {
                $email = [];
                $email["type"] = "timesheet_submit";
                $email["to"] = $info["people_cache"][$adminID]["emailAddress"];
                $email["subject"] = $commentTemplate->populate_string(
                    config::get_config_item("emailSubject_timeSheetToAdministrator"),
                    "timeSheet",
                    $this->get_id()
                );
                $email["body"] = <<<EOD
                        To Admin: {$info["admin_name"]}
                      Time Sheet: {$info["url"]}
                    Submitted By: {$info["timeSheet_personID_name"]}
                     For Project: {$info["projectName"]}
                    {$extra}

                    A timesheet has been submitted for your approval. If it is not
                    satisfactory, make it editable again for re-submission.

                    EOD;
                $this->get_value("billingNote") && ($email["body"] .= "Billing Note: " . $this->get_value("billingNote"));
                $msg[] = $this->shootEmail($email);
            }

            // Can get backwards to "admin" from "invoiced"
        } else {
            // requires INVOICE_TIMESHEETS
            if (!$this->have_perm(PERM_TIME_INVOICE_TIMESHEETS)) {
                // no permission, go away
                alloc_error("You do not have permission to change this timesheet.");
            }

            $this->set_value("approvedByAdminPersonID", "");
        }

        $this->set_value("status", "admin");
        return $msg;
    }

    public function email_move_status_to_invoiced($direction, $info)
    {
        $current_user = &singleton("current_user");
        // Can get forwards to "invoiced" from "admin"
        // requires INVOICE_TIMESHEETS
        if (!$this->have_perm(PERM_TIME_INVOICE_TIMESHEETS)) {
            // no permission, go away
            alloc_error("You do not have permission to change this timesheet.");
        }

        if (
            $info["projectManagers"]
            && !$this->get_value("approvedByManagerPersonID")
        ) {
            $this->set_value("approvedByManagerPersonID", $current_user->get_id());
        }

        $this->set_value("approvedByAdminPersonID", $current_user->get_id());
        $this->set_value("status", "invoiced");
    }

    public function email_move_status_to_finished($direction, $info)
    {
        $msg = [];
        if ($direction == "forwards") {
            // requires INVOICE_TIMESHEETS
            if (!$this->have_perm(PERM_TIME_INVOICE_TIMESHEETS)) {
                // no permission, go away
                alloc_error("You do not have permission to change this timesheet.");
            }

            // transactions
            $q = unsafe_prepare("SELECT DISTINCT transaction.transactionDate, transaction.product, transaction.status
                            FROM transaction
                            JOIN tf ON tf.tfID = transaction.tfID OR tf.tfID = transaction.fromTfID
                      RIGHT JOIN tfPerson ON tfPerson.personID = %d AND tfPerson.tfID = tf.tfID
                           WHERE transaction.timeSheetID = %d
                         ", $this->get_value('personID'), $this->get_id());
            $allocDatabase = new AllocDatabase();
            $allocDatabase->query($q);

            // the email itself
            $email = [];
            $email["type"] = "timesheet_finished";
            $email["to"] = $info["timeSheet_personID_email"];
            $email["subject"] = commentTemplate::populate_string(config::get_config_item("emailSubject_timeSheetCompleted"), "timeSheet", $this->get_id());
            $email["body"] = <<<EOD
                         To: {$info["timeSheet_personID_name"]}
                 Time Sheet: {$info["url"]}
                For Project: {$info["projectName"]}

                Your timesheet has been completed by {$info["current_user_name"]}.

                EOD;

            if ($allocDatabase->num_rows() > 0) {
                $email["body"] .= "Transaction summary:\n";
                $status_ops = [
                    "pending"  => "Pending",
                    "approved" => "Approved",
                    "rejected" => "Rejected",
                ];
                while ($allocDatabase->next_record()) {
                    $email["body"] .= $allocDatabase->f("transactionDate") . " for " . $allocDatabase->f("product") . ": " . $status_ops[$allocDatabase->f("status")] . "\n";
                }
            }

            $msg[] = $this->shootEmail($email);
            $this->set_value("status", "finished");
            return $msg;
        }
    }

    public function pending_transactions_to_approved()
    {
        if (!$this->have_perm(PERM_TIME_APPROVE_TIMESHEETS)) {
            // no permission, die
            alloc_error("You do not have permission to approve transactions for this timesheet.");
        }

        $allocDatabase = new AllocDatabase();
        $q = unsafe_prepare("UPDATE transaction SET status = 'approved' WHERE timeSheetID = %d AND status = 'pending'", $this->get_id());
        $allocDatabase->query($q);
    }

    public function get_email_vars()
    {
        $current_user = &singleton("current_user");
        static $rtn;
        if ($rtn) {
            return $rtn;
        }

        // Get vars for the emails below
        $rtn["people_cache"] = $people_cache = &get_cached_table("person");
        $project = $this->get_foreign_object("project");
        $rtn["projectManagers"] = $project->get_timeSheetRecipients();
        $rtn["projectName"] = $project->get_value("projectName");
        $rtn["timeSheet_personID_email"] = $people_cache[$this->get_value("personID")]["emailAddress"];
        $rtn["timeSheet_personID_name"] = $people_cache[$this->get_value("personID")]["name"];
        $rtn["url"] = config::get_config_item("allocURL") . "time/timeSheet.php?timeSheetID=" . $this->get_id();
        $rtn["timeSheetAdministrators"] = config::get_config_item('defaultTimeSheetAdminList');
        $rtn["approvedByManagerPersonID_email"] = $people_cache[$this->get_value("approvedByManagerPersonID")]["emailAddress"];
        $rtn["approvedByManagerPersonID_name"] = $people_cache[$this->get_value("approvedByManagerPersonID")]["name"];
        $rtn["approvedByAdminPersonID_name"] = $people_cache[$this->get_value("approvedByAdminPersonID")]["name"];
        $rtn["current_user_name"] = $people_cache[$current_user->get_id()]["name"];
        return $rtn;
    }

    public static function add_timeSheetItem($stuff)
    {
        $extra = null;
        $task = null;
        $ID = null;
        $current_user = &singleton("current_user");

        $errstr = "Failed to record new time sheet item. ";
        $taskID = $stuff["taskID"] ?? 0;
        $projectID = $stuff["projectID"] ?? 0;
        $duration = $stuff["duration"] ?? 0;
        $comment = $stuff["comment"] ?? "";
        $emailUID = $stuff["msg_uid"] ?? "";
        $emailMessageID = $stuff["msg_id"] ?? "";
        $date = $stuff["date"] ?? "";
        $unit = $stuff["unit"] ?? "";
        $multiplier = $stuff["multiplier"] ?? "";

        if (!empty($taskID)) {
            $task = new Task();
            $task->set_id($taskID);
            $task->select();
            $projectID = $task->get_value("projectID");
            $extra = " for task " . $taskID;
        }

        !empty($projectID) || alloc_error(sprintf($errstr . "No project found%s.", $extra));

        $row_projectPerson = projectPerson::get_projectPerson_row($projectID, $current_user->get_id());
        $row_projectPerson || alloc_error($errstr . "The person(" . $current_user->get_id() . ") has not been added to the project(" . $projectID . ").");

        if ($row_projectPerson && $projectID) {
            if ($stuff["timeSheetID"]) {
                $q = unsafe_prepare("SELECT *
                                FROM timeSheet
                               WHERE status = 'edit'
                                 AND personID = %d
                                 AND timeSheetID = %d
                            ORDER BY dateFrom
                               LIMIT 1
                             ", $current_user->get_id(), $stuff["timeSheetID"]);
                $db = new AllocDatabase();
                $db->query($q);
                $row = $db->row();
                $row || alloc_error("Couldn't find an editable time sheet with that ID.");
            } else {
                $q = unsafe_prepare("SELECT *
                                FROM timeSheet
                               WHERE status = 'edit'
                                 AND projectID = %d
                                 AND personID = %d
                            ORDER BY dateFrom
                               LIMIT 1
                             ", $projectID, $current_user->get_id());
                $db = new AllocDatabase();
                $db->query($q);
                $row = $db->row();
            }

            // If no timeSheets add a new one
            if (!$row) {
                $project = new project();
                $project->set_id($projectID);
                $project->select();

                $timeSheet = new timeSheet();
                $timeSheet->set_value("projectID", $projectID);
                $timeSheet->set_value("status", "edit");
                $timeSheet->set_value("personID", $current_user->get_id());
                $timeSheet->set_value("recipient_tfID", $current_user->get_value("preferred_tfID"));
                $timeSheet->set_value("customerBilledDollars", Page::money($project->get_value("currencyTypeID"), $project->get_value("customerBilledDollars"), "%mo"));
                $timeSheet->set_value("currencyTypeID", $project->get_value("currencyTypeID"));
                $timeSheet->save();
                $timeSheetID = $timeSheet->get_id();

                // Else use the first timesheet we found
            } else {
                $timeSheetID = $row["timeSheetID"];
            }

            $timeSheetID || alloc_error($errstr . "Couldn't locate an existing, or create a new Time Sheet.");

            // Add new time sheet item
            if ($timeSheetID) {
                $timeSheet = new timeSheet();
                $timeSheet->set_id($timeSheetID);
                $timeSheet->select();

                $tsi = new timeSheetItem();
                $tsi->currency = $timeSheet->get_value("currencyTypeID");
                $tsi->set_value("timeSheetID", $timeSheetID);
                ($d = $date) || ($d = date("Y-m-d"));
                $tsi->set_value("dateTimeSheetItem", $d);
                $tsi->set_value("timeSheetItemDuration", $duration);
                $tsi->set_value("timeSheetItemDurationUnitID", $unit);
                if (is_object($task)) {
                    $tsi->set_value("description", $task->get_name());
                    $tsi->set_value("taskID", sprintf("%d", $taskID));
                    $_POST["timeSheetItem_taskID"] = sprintf("%d", $taskID); // this gets used in timeSheetItem->save();
                }

                $tsi->set_value("personID", $current_user->get_id());
                $tsi->set_value("rate", Page::money($timeSheet->get_value("currencyTypeID"), $row_projectPerson["rate"], "%mo"));
                $tsi->set_value("multiplier", $multiplier);
                $tsi->set_value("comment", $comment);
                $tsi->set_value("emailUID", $emailUID);
                $tsi->set_value("emailMessageID", $emailMessageID);
                $tsi->save();
                $id = $tsi->get_id();

                $tsi = new timeSheetItem();
                $tsi->set_id($id);
                $tsi->select();
                $ID = $tsi->get_value("timeSheetID");
            }
        }

        if ($ID) {
            return ["status" => "yay", "message" => $ID];
        }

        alloc_error($errstr . "Time not added.");
    }

    public function get_all_parties($projectID = "")
    {
        $allocDatabase = new AllocDatabase();
        $interestedPartyOptions = [];

        if (!$projectID && is_object($this)) {
            $projectID = $this->get_value("projectID");
        }

        if ($projectID) {
            $project = new project($projectID);
            $interestedPartyOptions = $project->get_all_parties();
        }

        ($extra_interested_parties = config::get_config_item("defaultInterestedParties")) || ($extra_interested_parties = []);
        foreach ($extra_interested_parties as $name => $email) {
            $interestedPartyOptions[$email] = ["name" => $name];
        }

        if (is_object($this)) {
            if ($this->get_value("personID")) {
                $p = new person();
                $p->set_id($this->get_value("personID"));
                $p->select();
                $p->get_value("emailAddress") && ($interestedPartyOptions[$p->get_value("emailAddress")] = [
                    "name"     => $p->get_value("firstName") . " " . $p->get_value("surname"),
                    "role"     => "assignee",
                    "selected" => false,
                    "personID" => $this->get_value("personID"),
                ]);
            }

            if ($this->get_value("approvedByManagerPersonID")) {
                $p = new person();
                $p->set_id($this->get_value("approvedByManagerPersonID"));
                $p->select();
                $p->get_value("emailAddress") && ($interestedPartyOptions[$p->get_value("emailAddress")] = [
                    "name"     => $p->get_value("firstName") . " " . $p->get_value("surname"),
                    "role"     => "manager",
                    "selected" => true,
                    "personID" => $this->get_value("approvedByManagerPersonID"),
                ]);
            }

            $this_id = $this->get_id();
        }

        // return an aggregation of the current task/proj/client parties + the existing interested parties
        $interestedPartyOptions = InterestedParty::get_interested_parties("timeSheet", $this_id, $interestedPartyOptions);
        return $interestedPartyOptions;
    }

    public function get_amount_allocated($fmt = "%s%mo")
    {
        // Return total amount used and total amount allocated
        if (is_object($this) && $this->get_id()) {
            $allocDatabase = new AllocDatabase();
            // Get most recent invoiceItem that this time sheet belongs to.
            $q = unsafe_prepare("SELECT invoiceID
                            FROM invoiceItem
                           WHERE invoiceItem.timeSheetID = %d
                        ORDER BY invoiceItem.iiDate DESC
                           LIMIT 1
                         ", $this->get_id());
            $allocDatabase->query($q);
            $row = $allocDatabase->row();
            $invoiceID = $row["invoiceID"];
            if ($invoiceID) {
                $invoice = new invoice();
                $invoice->set_id($invoiceID);
                $invoice->select();
                $maxAmount = Page::money($invoice->get_value("currencyTypeID"), $invoice->get_value("maxAmount"), $fmt);

                // Loop through all the other invoice items on that invoice
                $q = unsafe_prepare("SELECT sum(iiAmount) AS totalUsed FROM invoiceItem WHERE invoiceID = %d", $invoiceID);
                $allocDatabase->query($q);
                $row2 = $allocDatabase->row();

                return [Page::money($invoice->get_value("currencyTypeID"), $row2["totalUsed"], $fmt), $maxAmount];
            }
        }
    }

    public function has_attachment_permission()
    {
        return $this->is_owner();
    }

    public function get_name($_FORM = []): string
    {
        $project = new project();
        $project->set_id($this->get_value("projectID"));
        $project->select();
        $p = &get_cached_table("person");
        return "Time Sheet for " . $project->get_name($_FORM) . " by " . $p[$this->get_value("personID")]["name"];
    }

    public function update_search_index_doc(&$index)
    {
        $tf = new tf();
        $desc = null;
        $br = null;
        $projectName = null;
        $p = &get_cached_table("person");
        $personID = $this->get_value("personID");
        $person_field = $personID . " " . $p[$personID]["username"] . " " . $p[$personID]["name"];
        $managerID = $this->get_value("approvedByManagerPersonID");
        $manager_field = $managerID . " " . $p[$managerID]["username"] . " " . $p[$managerID]["name"];
        $adminID = $this->get_value("approvedByAdminPersonID");
        $admin_field = $adminID . " " . $p[$adminID]["username"] . " " . $p[$adminID]["name"];
        $tf_field = $this->get_value("recipient_tfID") . " " . $tf->get_name($this->get_value("recipient_tfID"));

        if ($this->get_value("projectID")) {
            $project = new project();
            $project->set_id($this->get_value("projectID"));
            $project->select();
            $projectName = $project->get_name();
            $projectShortName = $project->get_name(["showShortProjectLink" => true]);
            if ($projectShortName && $projectShortName != $projectName) {
                $projectName .= " " . $projectShortName;
            }
        }

        $q = unsafe_prepare("SELECT dateTimeSheetItem, taskID, description, comment, commentPrivate
                        FROM timeSheetItem
                       WHERE timeSheetID = %d
                    ORDER BY dateTimeSheetItem ASC", $this->get_id());
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);
        while ($r = $allocDatabase->row()) {
            $desc .= $br . $r["dateTimeSheetItem"] . " " . $r["taskID"] . " " . $r["description"] . "\n";
            if (!($r["comment"] && $r["commentPrivate"])) {
                $desc .= $r["comment"] . "\n";
            }

            $br = "\n";
        }

        // ZendSearch
        $zendSearchLuceneDocument = new Document();
        $zendSearchLuceneDocument->addField(Field::Keyword('id', $this->get_id()));
        $zendSearchLuceneDocument->addField(Field::Text('project', $projectName, "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('pid', $this->get_value("projectID"), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('creator', $person_field, "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('desc', $desc, "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('status', $this->get_value("status"), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('tf', $tf_field, "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('manager', $manager_field, "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('admin', $admin_field, "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('dateManager', str_replace("-", "", $this->get_value("dateSubmittedToManager")), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('dateAdmin', str_replace("-", "", $this->get_value("dateSubmittedToAdmin")), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('dateFrom', str_replace("-", "", $this->get_value("dateFrom")), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('dateTo', str_replace("-", "", $this->get_value("dateTo")), "utf-8"));

        $index->addDocument($zendSearchLuceneDocument);
    }

    public function can_edit_rate()
    {
        $current_user = &singleton("current_user");
        $allocDatabase = new AllocDatabase();
        $row = $allocDatabase->qr("SELECT can_edit_rate(%d,%d) as allow", $current_user->get_id(), $this->get_value("projectID"));
        return $row["allow"];
    }

    public function get_project_id()
    {
        return $this->get_value("projectID");
    }
}
