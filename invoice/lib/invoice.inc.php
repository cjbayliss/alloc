<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("DEFAULT_SEP", "\n");
class invoice extends DatabaseEntity
{
    public $classname = "invoice";

    public $data_table = "invoice";

    public $display_field_name = "invoiceName";

    public $key_field = "invoiceID";

    public $data_fields = [
        "invoiceName",
        "clientID",
        "projectID",
        "tfID",
        "invoiceDateFrom",
        "invoiceDateTo",
        "invoiceNum",
        "invoiceName",
        "invoiceStatus",
        "currencyTypeID",
        "maxAmount" => ["type" => "money"],
        "invoiceRepeatID",
        "invoiceRepeatDate",
        "invoiceCreatedTime",
        "invoiceCreatedUser",
        "invoiceModifiedTime",
        "invoiceModifiedUser",
    ];

    public function save()
    {
        $currencyTypeID = null;
        if (!$this->get_value("currencyTypeID")) {
            if ($this->get_value("projectID")) {
                $project = $this->get_foreign_object("project");
                $currencyTypeID = $project->get_value("currencyTypeID");
            } elseif (config::get_config_item("currency")) {
                $currencyTypeID = config::get_config_item("currency");
            }

            if (!($this->get_value("maxAmount") !== null && (bool)strlen($this->get_value("maxAmount")))) {
                $this->set_value("maxAmount", '');
            }

            if ($currencyTypeID) {
                $this->set_value("currencyTypeID", $currencyTypeID);
            } else {
                alloc_error("Unable to save invoice. No currency is able to be determined. Either attach this invoice to a project, or set a Main Currency on the Setup -> Finance screen.");
            }
        }

        return parent::save();
    }

    public function delete()
    {
        $allocDatabase = new AllocDatabase();
        $q = unsafe_prepare("DELETE FROM invoiceEntity WHERE invoiceID = %d", $this->get_id());
        $allocDatabase->query($q);
        return parent::delete();
    }

    public static function get_invoice_statii()
    {
        return [
            "create"    => "Create",
            "edit"      => "Add Items",
            "reconcile" => "Approve/Reject",
            "finished"  => "Completed",
        ];
    }

    public function get_invoice_statii_payment()
    {
        return [
            "pending" => "Not Paid In Full",
            // "partly_paid"=>"Waiting to be Paid"
            "rejected"   => "Has Rejected Transactions",
            "fully_paid" => "Paid In Full",
            "over_paid"  => "Overpaid/Pre-Paid",
        ];
    }

    public function get_invoice_statii_payment_image($payment_status = false)
    {
        global $TPL;
        if ($payment_status) {
            $payment_statii = (new invoice())->get_invoice_statii_payment();
            return '<img src="' . $TPL["url_alloc_images"] . "invoice_" . $payment_status . '.png" alt="' . $payment_statii[$payment_status] . '" title="' . $payment_statii[$payment_status] . '">';
        }
    }

    public function is_owner($person = ""): bool
    {
        $current_user = &singleton("current_user");

        if ($person == "") {
            $person = $current_user;
        }

        $allocDatabase = new AllocDatabase();
        $allocDatabase->query(["SELECT * FROM invoiceItem WHERE invoiceID=%d", $this->get_id()]);
        while ($allocDatabase->next_record()) {
            $invoice_item = new invoiceItem();
            if ($invoice_item->read_db_record($allocDatabase) && $invoice_item->is_owner($person)) {
                return true;
            }
        }

        return false;
    }

    public function get_invoiceItems($invoiceID = "")
    {
        $invoiceItemIDs = [];
        ($id = $invoiceID) || ($id = $this->get_id());
        $q = unsafe_prepare("SELECT invoiceItemID FROM invoiceItem WHERE invoiceID = %d", $id);
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);
        while ($row = $allocDatabase->row()) {
            $invoiceItemIDs[] = $row["invoiceItemID"];
        }

        return $invoiceItemIDs;
    }

    public function get_transactions($invoiceID = "")
    {
        $transactionIDs = [];
        ($id = $invoiceID) || ($id = $this->get_id());
        $q = unsafe_prepare("SELECT transactionID FROM transaction WHERE invoiceID = %d", $id);
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);
        while ($row = $allocDatabase->row()) {
            $transactionIDs[] = $row["transactionID"];
        }

        return $transactionIDs;
    }

    public static function get_next_invoiceNum()
    {
        $q = "SELECT coalesce(max(invoiceNum)+1,1) as newNum FROM invoice";
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);
        $allocDatabase->row();
        return $allocDatabase->f("newNum");
    }

    public function get_invoiceItem_list_for_file($verbose = false)
    {
        $rows = [];
        $info = [];
        $str = [];
        $task_info = [];
        $sep = null;
        $currency = $this->get_value("currencyTypeID");

        $q = unsafe_prepare("SELECT * from invoiceItem WHERE invoiceID=%d ", $this->get_id());
        $q .= unsafe_prepare("ORDER BY iiDate,invoiceItemID");
        $db = new AllocDatabase();
        $db->query($q);

        while ($db->next_record()) {
            $invoiceItem = new invoiceItem();
            $invoiceItem->read_db_record($db);

            $taxPercent = $invoiceItem->get_value("iiTax");
            $taxPercentDivisor = ($taxPercent / 100) + 1;

            $num = Page::money($currency, $invoiceItem->get_value("iiAmount"), "%mo");

            if ($taxPercent) {
                $num_minus_gst = $num / $taxPercentDivisor;
                $gst = $num - $num_minus_gst;

                if ($num_minus_gst + $gst != $num) {
                    $num_minus_gst += $num - ($num_minus_gst + $gst); // round it up.
                }

                $rows[$invoiceItem->get_id()]["quantity"] = $invoiceItem->get_value("iiQuantity");
                $rows[$invoiceItem->get_id()]["unit"] = Page::money($currency, $invoiceItem->get_value("iiUnitPrice"), "%mo");
                $rows[$invoiceItem->get_id()]["money"] = Page::money($currency, $num_minus_gst, "%m");
                $rows[$invoiceItem->get_id()]["gst"] = Page::money($currency, $gst, "%m");
                $info["total_gst"] += $gst;
                $info["total"] += $num_minus_gst;
            } else {
                $taxPercent = config::get_config_item("taxPercent");
                $taxPercentDivisor = ($taxPercent / 100) + 1;

                $num_plus_gst = $num * $taxPercentDivisor;
                $gst = $num_plus_gst - $num;

                $rows[$invoiceItem->get_id()]["quantity"] = $invoiceItem->get_value("iiQuantity");
                $rows[$invoiceItem->get_id()]["unit"] = Page::money($currency, $invoiceItem->get_value("iiUnitPrice"), "%mo");
                $rows[$invoiceItem->get_id()]["money"] = Page::money($currency, $num, "%m");
                $rows[$invoiceItem->get_id()]["gst"] = Page::money($currency, $gst, "%m");
                $info["total_gst"] += $gst;
                $info["total"] += $num;
            }

            unset($str);
            $d = $invoiceItem->get_value('iiMemo');
            $str[] = $d;

            // Get task description
            if ($invoiceItem->get_value("timeSheetID") && $verbose) {
                $q = unsafe_prepare("SELECT * FROM timeSheetItem WHERE timeSheetID = %d", $invoiceItem->get_value("timeSheetID"));
                $db2 = new AllocDatabase();
                $db2->query($q);
                unset($sep);
                unset($task_info);
                while ($db2->next_record()) {
                    if ($db2->f("taskID") && !$task_info[$db2->f("taskID")] && $db2->f("description")) {
                        $task_info[$db2->f("taskID")] = $db2->f("description");
                        $sep = DEFAULT_SEP;
                    }

                    if (!$db2->f("commentPrivate") && $db2->f("comment")) {
                        $task_info[$db2->f("taskID")] .= $sep . "  <i>- " . $db2->f("comment") . "</i>";
                    }

                    $sep = DEFAULT_SEP;
                }

                if (is_array($task_info)) {
                    $str[$invoiceItem->get_id()] .= "* " . implode(DEFAULT_SEP . "* ", $task_info);
                }
            }

            if (is_array($str)) {
                $rows[$invoiceItem->get_id()]["desc"] .= trim(implode(DEFAULT_SEP, $str));
            }
        }

        $info["total_inc_gst"] = Page::money($currency, $info["total"] + $info["total_gst"], "%s%m");

        // If we are in dollar mode, then prefix the total with a dollar sign
        $info["total"] = Page::money($currency, $info["total"], "%s%m");
        $info["total_gst"] = Page::money($currency, $info["total_gst"], "%s%m");
        $rows || ($rows = []);
        $info || ($info = []);
        return [$rows, $info];
    }

    public function generate_invoice_file($verbose = false, $getfile = false)
    {
        $cols_settings = [];
        $cols_settings2 = [];
        $contact_info = [];
        $companyContactEmail = null;
        $companyContactHomePage = null;
        $ts_info = [];
        $totals = [];
        // Build PDF document
        $font1 = ALLOC_MOD_DIR . "util/fonts/Helvetica.afm";
        $font2 = ALLOC_MOD_DIR . "util/fonts/Helvetica-Oblique.afm";

        $allocDatabase = new AllocDatabase();

        // Get client name
        $client = $this->get_foreign_object("client");
        $clientName = $client->get_value("clientName");

        // Get cyber info
        $companyName = config::get_config_item("companyName");
        $companyNos1 = config::get_config_item("companyACN");
        $companyNos2 = config::get_config_item("companyABN");
        $phone = config::get_config_item("companyContactPhone");
        $fax = config::get_config_item("companyContactFax");
        $phone && ($phone = "Ph: " . $phone);
        $fax && ($fax = "Fax: " . $fax);
        $img = config::get_config_item("companyImage");
        $companyContactAddress = config::get_config_item("companyContactAddress");
        $companyContactAddress2 = config::get_config_item("companyContactAddress2");
        $companyContactAddress3 = config::get_config_item("companyContactAddress3");
        $email = config::get_config_item("companyContactEmail");
        $email && ($companyContactEmail = "Email: " . $email);
        $web = config::get_config_item("companyContactHomePage");
        $web && ($companyContactHomePage = "Web: " . $web);
        $footer = config::get_config_item("timeSheetPrintFooter");
        $taxName = config::get_config_item("taxName");

        if (
            $this->get_value("invoiceDateFrom") && $this->get_value("invoiceDateTo")
            && $this->get_value("invoiceDateFrom") != $this->get_value("invoiceDateTo")
        ) {
            $period = format_date(DATE_FORMAT, $this->get_value("invoiceDateFrom")) . " to " . format_date(DATE_FORMAT, $this->get_value("invoiceDateTo"));
        } else {
            $period = format_date(DATE_FORMAT, $this->get_value("invoiceDateTo"));
        }

        $default_header = "Tax Invoice";
        $default_id_label = "Invoice Number";

        $pdf_table_options = [
            "showLines"    => 0,
            "shaded"       => 0,
            "showHeadings" => 0,
            "xPos"         => "left",
            "xOrientation" => "right",
            "fontSize"     => 10,
            "rowGap"       => 0,
            "fontSize"     => 10,
        ];

        $cols = ["one" => "", "two" => "", "three" => "", "four" => ""];
        $cols3 = ["one" => "", "two" => ""];
        $cols_settings["one"] = ["justification" => "right"];
        $cols_settings["three"] = ["justification" => "right"];
        $pdf_table_options2 = [
            "showLines"    => 0,
            "shaded"       => 0,
            "showHeadings" => 0,
            "width"        => 400,
            "fontSize"     => 10,
            "xPos"         => "center",
            "xOrientation" => "center",
            "cols"         => $cols_settings,
        ];
        $cols_settings2["gst"] = ["justification" => "right"];
        $cols_settings2["money"] = ["justification" => "right"];
        $cols_settings2["unit"] = ["justification" => "right"];
        $pdf_table_options3 = [
            "showLines"   => 2,
            "shaded"      => 0,
            "width"       => 400,
            "xPos"        => "center",
            "fontSize"    => 10,
            "cols"        => $cols_settings2,
            "lineCol"     => [0.8, 0.8, 0.8],
            "splitRows"   => 1,
            "protectRows" => 0,
        ];
        $cols_settings["two"] = ["justification" => "right", "width" => 80];
        $pdf_table_options4 = [
            "showLines"    => 2,
            "shaded"       => 0,
            "width"        => 400,
            "showHeadings" => 0,
            "fontSize"     => 10,
            "xPos"         => "center",
            "cols"         => $cols_settings,
            "lineCol"      => [0.8, 0.8, 0.8],
        ];

        $cezpdf = new Cezpdf();
        $cezpdf->ezSetMargins(90, 90, 90, 90);

        $cezpdf->selectFont($font1);
        $cezpdf->ezStartPageNumbers(436, 80, 10, 'right', 'Page {PAGENUM} of {TOTALPAGENUM}');
        $cezpdf->ezStartPageNumbers(200, 80, 10, 'left', '<b>' . $default_id_label . ': </b>' . $this->get_value("invoiceNum"));
        $cezpdf->ezSetY(775);

        $companyName && ($contact_info[] = [$companyName]);
        $companyContactAddress && ($contact_info[] = [$companyContactAddress]);
        $companyContactAddress2 && ($contact_info[] = [$companyContactAddress2]);
        $companyContactAddress3 && ($contact_info[] = [$companyContactAddress3]);
        $companyContactEmail && ($contact_info[] = [$companyContactEmail]);
        $companyContactHomePage && ($contact_info[] = [$companyContactHomePage]);
        $phone && ($contact_info[] = [$phone]);
        $fax && ($contact_info[] = [$fax]);

        $cezpdf->selectFont($font2);
        $y = $cezpdf->ezTable($contact_info, false, "", $pdf_table_options);
        $cezpdf->selectFont($font1);

        $line_y = $y - 10;
        $cezpdf->setLineStyle(1, "round");
        $cezpdf->line(90, $line_y, 510, $line_y);

        $cezpdf->ezSetY(782);

        $image_jpg = ALLOC_LOGO;
        if (file_exists($image_jpg)) {
            $cezpdf->ezImage($image_jpg, 0, sprintf("%d", config::get_config_item("logoScaleX")), 'none');
            $y = 700;
        } else {
            $y = $cezpdf->ezText($companyName, 27, ["justification" => "right"]);
        }

        $nos_y = $line_y + 22;
        $companyNos2 && ($nos_y = $line_y + 34);
        $cezpdf->ezSetY($nos_y);
        $companyNos1 && ($y = $cezpdf->ezText($companyNos1, 10, ["justification" => "right"]));
        $companyNos2 && ($y = $cezpdf->ezText($companyNos2, 10, ["justification" => "right"]));

        $cezpdf->ezSetY($line_y - 20);
        $y = $cezpdf->ezText($default_header, 20, ["justification" => "center"]);
        $cezpdf->ezSetY($y - 20);

        $ts_info[] = [
            "one"   => "<b>" . $default_id_label . ":</b>",
            "two"   => $this->get_value("invoiceNum"),
            "three" => "<b>Date Issued:</b>",
            "four"  => date("d/m/Y"),
        ];
        $ts_info[] = [
            "one"   => "<b>Client:</b>",
            "two"   => $clientName,
            "three" => "<b>Billing Period:</b>",
            "four"  => $period,
        ];
        $y = $cezpdf->ezTable($ts_info, $cols, "", $pdf_table_options2);

        $cezpdf->ezSetY($y - 20);

        [$rows, $info] = $this->get_invoiceItem_list_for_file($verbose);
        $cols2 = [
            "desc"     => "Description",
            "quantity" => "Qty",
            "unit"     => "Unit Price",
            "money"    => "Charges",
            "gst"      => $taxName,
        ];
        $taxPercent = config::get_config_item("taxPercent");
        if ($taxPercent === '') {
            unset($cols2["gst"]);
        }

        $rows[] = [
            "desc"  => "<b>TOTAL</b>",
            "money" => $info["total"],
            "gst"   => $info["total_gst"],
        ];
        $y = $cezpdf->ezTable($rows, $cols2, "", $pdf_table_options3);
        $cezpdf->ezSetY($y - 20);
        if ($taxPercent !== '') {
            $totals[] = ["one" => "TOTAL " . $taxName, "two" => $info["total_gst"]];
        }

        $totals[] = ["one" => "TOTAL CHARGES", "two" => $info["total"]];
        $totals[] = [
            "one" => "<b>TOTAL AMOUNT PAYABLE</b>",
            "two" => "<b>" . $info["total_inc_gst"] . "</b>",
        ];
        $y = $cezpdf->ezTable($totals, $cols3, "", $pdf_table_options4);

        $cezpdf->ezSetY($y - 20);
        $cezpdf->ezText(str_replace(["<br>", "<br/>", "<br />"], "\n", $footer), 10);

        // Add footer
        // $all = $pdf->openObject();
        // $pdf->saveState();
        // $pdf->addText(415,80,12,"<b>".$default_id_label.":</b>".$this->get_value("invoiceNum"));
        // $pdf->restoreState();
        // $pdf->closeObject();
        // $pdf->addObject($all,'all');

        if ($getfile) {
            return $cezpdf->ezOutput();
        }

        $cezpdf->ezStream(["Content-Disposition" => "invoice_" . $this->get_id() . ".pdf"]);
    }

    public function has_attachment_permission($person)
    {
        return $person->have_role("admin");
    }

    public function has_attachment_permission_delete($person)
    {
        return $person->have_role("admin");
    }

    public function get_url()
    {
        global $sess;
        $sess || ($sess = new Session());

        $url = "invoice/invoice.php?invoiceID=" . $this->get_id();

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

    public function get_name($_FORM = [])
    {
        return $this->get_value("invoiceNum");
    }

    public function get_invoice_link($_FORM = []): string
    {
        global $TPL;
        return '<a href="' . $TPL["url_alloc_invoice"] . "invoiceID=" . $this->get_id() . '">' . $this->get_name($_FORM) . "</a>";
    }

    public static function get_list_filter($filter = [])
    {
        $valid_clientIDs = [];
        $approved_clientIDs = [];
        $current_user = &singleton("current_user");
        $sql = [];

        // If they want starred, load up the invoiceID filter element
        if ($filter["starred"]) {
            foreach (array_keys((array)$current_user->prefs["stars"]["invoice"]) as $k) {
                $filter["invoiceID"][] = $k;
            }

            if (!is_array($filter["invoiceID"])) {
                $filter["invoiceID"][] = -1;
            }
        }

        // Filter invoiceID
        $filter["invoiceID"] && ($sql[] = sprintf_implode("invoice.invoiceID = %d", $filter["invoiceID"]));

        // No point continuing if primary key specified, so return
        if ($filter["invoiceID"] || $filter["starred"]) {
            return $sql;
        }

        if ($filter["personID"]) {
            $q = "SELECT DISTINCT project.clientID
                    FROM projectPerson LEFT JOIN project ON projectPerson.projectID = project.projectID
                   WHERE " . sprintf_implode("projectPerson.personID = %d", $filter["personID"]) . "
                     AND project.clientID IS NOT NULL";
            $allocDatabase = new AllocDatabase();
            $allocDatabase->query($q);
            while ($row = $allocDatabase->row()) {
                $valid_clientIDs[] = $row["clientID"];
            }

            if ($filter["clientID"] && !is_array($filter["clientID"])) {
                $filter["clientID"] = [$filter["clientID"]];
            }

            foreach ((array)$filter["clientID"] as $clientID) {
                if (in_array($clientID, (array)$valid_clientIDs)) {
                    $approved_clientIDs[] = $clientID;
                }
            }

            $approved_clientIDs || ($approved_clientIDs = (array)$valid_clientIDs);
            $filter["clientID"] = $approved_clientIDs ?: [0];
        }

        $filter["invoiceNum"] && ($sql[] = sprintf_implode("invoice.invoiceNum = %d", $filter["invoiceNum"]));
        $filter["dateOne"] && ($sql[] = sprintf_implode("invoice.invoiceDateFrom>='%s'", $filter["dateOne"]));
        $filter["dateTwo"] && ($sql[] = sprintf_implode("invoice.invoiceDateTo<='%s'", $filter["dateTwo"]));
        $filter["invoiceName"] && ($sql[] = sprintf_implode("invoice.invoiceName like '%%%s%%'", $filter["invoiceName"]));
        $filter["invoiceStatus"] && ($sql[] = sprintf_implode("invoice.invoiceStatus = '%s'", $filter["invoiceStatus"]));
        $filter["clientID"] && ($sql[] = sprintf_implode("invoice.clientID = %d", $filter["clientID"]));
        $filter["projectID"] && ($sql[] = sprintf_implode("invoice.projectID = %d", $filter["projectID"]));
        return $sql;
    }

    public static function get_list_filter2($filter = [])
    {
        // Filter for the HAVING clause
        $sql = [];
        if ($filter["invoiceStatusPayment"] == "pending") {
            $sql[] = "(COALESCE(amountPaidApproved,0) < iiAmountSum)";
            // if ($filter["invoiceStatusPayment"] == "partly_paid") {
            // $sql[] = "(amountPaidApproved < iiAmountSum)";
        } elseif ($filter["invoiceStatusPayment"] == "rejected") {
            $sql[] = "(COALESCE(amountPaidRejected,0) > 0)";
        } elseif ($filter["invoiceStatusPayment"] == "fully_paid") {
            $sql[] = "(COALESCE(amountPaidApproved,0) = iiAmountSum)";
        } elseif ($filter["invoiceStatusPayment"] == "over_paid") {
            $sql[] = "(COALESCE(amountPaidApproved,0) > iiAmountSum)";
        }

        return $sql;
    }

    public static function get_list($_FORM)
    {
        $f1_where = null;
        $f2_having = null;
        $rows = [];
        // This is the definitive method of getting a list of invoices that need a sophisticated level of filtering

        global $TPL;
        $filter1_where = invoice::get_list_filter($_FORM);
        $filter2_having = invoice::get_list_filter2($_FORM);

        $debug = $_FORM["debug"];
        $debug && (print "<pre>_FORM: " . print_r($_FORM, 1) . "</pre>");
        $debug && (print "<pre>filter1_where: " . print_r($filter1_where, 1) . "</pre>");
        $debug && (print "<pre>filter2_having: " . print_r($filter2_having, 1) . "</pre>");

        $_FORM["return"] || ($_FORM["return"] = "html");

        if (is_array($filter1_where) && count($filter1_where)) {
            $f1_where = " WHERE " . implode(" AND ", $filter1_where);
        }

        if (is_array($filter2_having) && count($filter2_having)) {
            $f2_having = " HAVING " . implode(" AND ", $filter2_having);
        }

        $q1 = "CREATE TEMPORARY TABLE invoice_details
              SELECT SUM(invoiceItem.iiAmount * pow(10,-currencyType.numberToBasic)) as iiAmountSum
                   , invoice.*
                   , client.clientName
                FROM invoice
           LEFT JOIN invoiceItem on invoiceItem.invoiceID = invoice.invoiceID
           LEFT JOIN client ON invoice.clientID = client.clientID
           LEFT JOIN currencyType on invoice.currencyTypeID = currencyType.currencyTypeID
             {$f1_where}
            GROUP BY invoice.invoiceID
            ORDER BY invoiceDateFrom";

        $allocDatabase = new AllocDatabase();
        // $db->query("DROP TABLE IF EXISTS invoice_details");
        $allocDatabase->query($q1);

        $q2 = "SELECT invoice_details.*
                   , SUM(transaction_approved.amount) as amountPaidApproved
                   , SUM(transaction_pending.amount) as amountPaidPending
                   , SUM(transaction_rejected.amount) as amountPaidRejected
                FROM invoice_details
           LEFT JOIN invoiceItem on invoiceItem.invoiceID = invoice_details.invoiceID
           LEFT JOIN transaction transaction_approved on invoiceItem.invoiceItemID = transaction_approved.invoiceItemID AND transaction_approved.status='approved'
           LEFT JOIN transaction transaction_pending on invoiceItem.invoiceItemID = transaction_pending.invoiceItemID AND transaction_pending.status='pending'
           LEFT JOIN transaction transaction_rejected on invoiceItem.invoiceItemID = transaction_rejected.invoiceItemID AND transaction_rejected.status='rejected'
            GROUP BY invoice_details.invoiceID
             {$f2_having}
            ORDER BY invoiceDateFrom";
        // Don't do this! It doubles the totals!
        // LEFT JOIN tfPerson ON tfPerson.tfID = transaction_approved.tfID OR tfPerson.tfID = transaction_pending.tfID OR tfPerson.tfID = transaction_rejected.tfID

        $debug && (print "<pre>Query1: " . $q1 . "</pre>");
        $debug && (print "<pre>Query2: " . $q2 . "</pre>");
        $allocDatabase->query($q2);

        while ($row = $allocDatabase->next_record()) {
            $print = true;
            $i = new invoice();
            $i->read_db_record($allocDatabase);
            $row["amountPaidApproved"] = Page::money($row["currencyTypeID"], $row["amountPaidApproved"], "%mo");
            $row["amountPaidPending"] = Page::money($row["currencyTypeID"], $row["amountPaidPending"], "%mo");
            $row["amountPaidRejected"] = Page::money($row["currencyTypeID"], $row["amountPaidRejected"], "%mo");
            $row["invoiceLink"] = $i->get_invoice_link();

            $payment_status = [];
            $row["statii"] = invoice::get_invoice_statii();
            $row["payment_statii"] = (new invoice())->get_invoice_statii_payment();
            if ($row["amountPaidApproved"] == $row["iiAmountSum"]) {
                $payment_status[] = "fully_paid";
            }

            if ($row["amountPaidApproved"] > $row["iiAmountSum"]) {
                $payment_status[] = "over_paid";
            }

            if ($row["amountPaidRejected"] > 0) {
                $payment_status[] = "rejected";
            }

            // $row["amountPaidApproved"] > 0 && $row["amountPaidApproved"] < $row["iiAmountSum"] and $payment_status[] = "partly_paid";
            if ($row["amountPaidApproved"] < $row["iiAmountSum"]) {
                $payment_status[] = "pending";
            }

            foreach ((array)$payment_status as $ps) {
                $row["image"] .= (new invoice())->get_invoice_statii_payment_image($ps);
                $row["status_label"] .= $ps;
            }

            $row["_FORM"] = $_FORM;
            $row = array_merge($TPL, (array)$row);

            $rows[$row["invoiceID"]] = $row;
        }

        return $rows;
    }

    public function get_list_vars()
    {

        return [
            "return"                   => "[MANDATORY] eg: array | html | dropdown_options",
            "invoiceID"                => "Invoice by ID",
            "clientID"                 => "Invoices for a particular Client",
            "invoiceNum"               => "Invoice by invoice number",
            "dateOne"                  => "Where invoice date from is >= a particular date",
            "dateTwo"                  => "Where invoice date to is <= a particular date",
            "invoiceName"              => "Invoice by name",
            "invoiceStatus"            => "Invoice status eg: edit | reconcile | finished",
            "invoiceStatusPayment"     => "Invoice payment status eg: pending | rejected | fully_paid | over_paid",
            "personID"                 => "Invoices that are for this persons TF's",
            "tfIDs"                    => "Invoices that are for these TF's",
            "url_form_action"          => "The submit action for the filter form",
            "form_name"                => "The name of this form, i.e. a handle for referring to this saved form",
            "dontSave"                 => "Specify that the filter preferences should not be saved this time",
            "applyFilter"              => "Saves this filter as the persons preference",
            "showHeader"               => "A descriptive html header row",
            "showInvoiceNumber"        => "Shows the invoice number",
            "showInvoiceClient"        => "Shows the invoices client",
            "showInvoiceName"          => "Shows the invoices name",
            "showInvoiceAmount"        => "Shows the total amount for each invoice",
            "showInvoiceAmountPaid"    => "Shows the total amount paid for each invoice",
            "showInvoiceDate"          => "Shows the invoices date",
            "showInvoiceStatus"        => "Shows the invoices status",
            "showInvoiceStatusPayment" => "Shows the invoices payment status",
        ];
    }

    public function load_form_data($defaults = [])
    {
        $current_user = &singleton("current_user");

        $page_vars = array_keys((new invoice())->get_list_vars());

        $_FORM = get_all_form_data($page_vars, $defaults);

        if (!$_FORM["applyFilter"]) {
            $_FORM = $current_user->prefs[$_FORM["form_name"]];
            if (!isset($current_user->prefs[$_FORM["form_name"]])) {
                // defaults go here
                $_FORM["invoiceStatus"] = "edit";
            }
        } elseif ($_FORM["applyFilter"] && is_object($current_user) && !$_FORM["dontSave"]) {
            $url = $_FORM["url_form_action"];
            unset($_FORM["url_form_action"]);
            $current_user->prefs[$_FORM["form_name"]] = $_FORM;
            $_FORM["url_form_action"] = $url;
        }

        return $_FORM;
    }

    public function load_invoice_filter($_FORM)
    {
        $rtn = [];
        $options = [];
        global $TPL;

        // Load up the forms action url
        $rtn["url_form_action"] = $_FORM["url_form_action"];

        $statii = invoice::get_invoice_statii();
        unset($statii["create"]);
        $rtn["statusOptions"] = Page::select_options($statii, $_FORM["invoiceStatus"]);
        $statii_payment = (new invoice())->get_invoice_statii_payment();
        $rtn["statusPaymentOptions"] = Page::select_options($statii_payment, $_FORM["invoiceStatusPayment"]);
        $rtn["status"] = $_FORM["status"];
        $rtn["dateOne"] = $_FORM["dateOne"];
        $rtn["dateTwo"] = $_FORM["dateTwo"];
        $rtn["invoiceID"] = $_FORM["invoiceID"];
        $rtn["invoiceName"] = $_FORM["invoiceName"];
        $rtn["invoiceNum"] = $_FORM["invoiceNum"];
        $rtn["invoiceItemID"] = $_FORM["invoiceItemID"];

        $options["clientStatus"] = "Current";
        $options["return"] = "dropdown_options";
        $ops = client::get_list($options);
        $ops = array_kv($ops, "clientID", "clientName");
        $rtn["clientOptions"] = Page::select_options($ops, $_FORM["clientID"]);

        // Get
        $rtn["FORM"] = "FORM=" . urlencode(serialize($_FORM));

        return $rtn;
    }

    public static function update_invoice_dates($invoiceID)
    {
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query(unsafe_prepare(
            "SELECT max(iiDate) AS maxDate, min(iiDate) AS minDate
               FROM invoiceItem
              WHERE invoiceID=%d",
            $invoiceID
        ));
        $allocDatabase->next_record();

        $invoice = new invoice();
        $invoice->set_id($invoiceID);
        $invoice->select();
        $invoice->set_value("invoiceDateFrom", $allocDatabase->f("minDate"));
        $invoice->set_value("invoiceDateTo", $allocDatabase->f("maxDate"));
        return $invoice->save();
    }

    public function close_related_entities()
    {
        $allocDatabase = new AllocDatabase();
        $invoiceItemIDs = $this->get_invoiceItems();
        foreach ($invoiceItemIDs as $invoiceItemID) {
            $q = unsafe_prepare(
                "SELECT *
                   FROM transaction
                  WHERE invoiceItemID = %d
                    AND status = 'pending'",
                $invoiceItemID
            );
            $allocDatabase->query($q);
            if (!$allocDatabase->next_record()) {
                $invoiceItem = new invoiceItem();
                $invoiceItem->set_id($invoiceItemID);
                $invoiceItem->select();
                $invoiceItem->close_related_entity();
            }
        }
    }

    public function next_status($direction)
    {

        $steps = [];
        $steps["forwards"][""] = "edit";
        $steps["forwards"]["edit"] = "reconcile";
        $steps["forwards"]["reconcile"] = "finished";

        $steps["backwards"]["finished"] = "reconcile";
        $steps["backwards"]["reconcile"] = "edit";
        $steps["backwards"]["edit"] = "";

        $status = $this->get_value("invoiceStatus");

        return $steps[$direction][$status];
    }

    public function change_status($direction)
    {
        $m = null;
        $newstatus = $this->next_status($direction);
        if ($newstatus) {
            if ($this->can_move($direction)) {
                $m = $this->{"move_status_to_" . $newstatus}($direction);
            }

            if (is_array($m)) {
                return implode("<br>", $m);
            }
        }
    }

    public function move_status_to_edit($direction)
    {
        $this->set_value("invoiceStatus", "edit");
    }

    public function move_status_to_reconcile($direction)
    {
        $this->set_value("invoiceStatus", "reconcile");
    }

    public function move_status_to_finished($direction)
    {
        if ($direction == "forwards") {
            $this->close_related_entities();
        }

        $this->set_value("invoiceStatus", "finished");
    }

    public function can_move($direction): bool
    {
        $newstatus = $this->next_status($direction);
        if ($direction == "forwards" && $newstatus == "finished" && $this->has_pending_transactions()) {
            alloc_error("There are still Invoice Items pending. This Invoice cannot be marked completed.");
            return false;
        }

        if ($direction == "forwards" && $newstatus == "reconcile") {
            $allocDatabase = new AllocDatabase();
            $hasItems = $allocDatabase->qr(["SELECT * FROM invoiceItem WHERE invoiceID = %d", $this->get_id()]);
            if (!$hasItems) {
                alloc_error("Unable to submit invoice, no items have been added.");
                return false;
            }
        }

        return true;
    }

    public function has_pending_transactions()
    {
        $q = unsafe_prepare("SELECT *
                        FROM transaction
                   LEFT JOIN invoiceItem on transaction.invoiceItemID = invoiceItem.invoiceItemID
                       WHERE invoiceItem.invoiceID = %d AND transaction.status = 'pending'
                   ", $this->get_id());
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);
        return $allocDatabase->next_record();
    }

    public function add_timeSheet($timeSheetID = false)
    {
        if ($timeSheetID) {
            $q = unsafe_prepare(
                "SELECT *
                   FROM invoiceItem
                  WHERE invoiceID = %d
                    AND timeSheetID = %d",
                $this->get_id(),
                $timeSheetID
            );
            $allocDatabase = new AllocDatabase();
            $allocDatabase->query($q);
            // Add this time sheet to the invoice if the timeSheet hasn't already
            // been added to this invoice
            if (!$allocDatabase->row()) {
                invoiceEntity::save_invoice_timeSheet($this->get_id(), $timeSheetID);
            }
        }
    }

    public function get_all_parties($projectID = "", $clientID = "")
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

        if ($clientID) {
            $client = new client($clientID);
            $interestedPartyOptions = array_merge((array)$interestedPartyOptions, (array)$client->get_all_parties());
        }

        ($extra_interested_parties = config::get_config_item("defaultInterestedParties")) || ($extra_interested_parties = []);
        foreach ($extra_interested_parties as $name => $email) {
            $interestedPartyOptions[$email] = ["name" => $name];
        }

        // return an aggregation of the current task/proj/client parties + the existing interested parties
        $interestedPartyOptions = InterestedParty::get_interested_parties("invoice", $this->get_id(), $interestedPartyOptions);
        return $interestedPartyOptions;
    }

    public static function get_list_html($rows = [], $ops = [])
    {
        global $TPL;
        $TPL["invoiceListRows"] = $rows;
        $TPL["_FORM"] = $ops;
        include_template(__DIR__ . "/../templates/invoiceListS.tpl");
    }
}
