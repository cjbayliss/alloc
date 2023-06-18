<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("DEFAULT_SEP", "\n");

class timeSheetPrint
{

    public function get_timeSheetItem_vars($timeSheetID)
    {

        $timeSheet = new timeSheet();
        $timeSheet->set_id($timeSheetID);
        $timeSheet->select();

        $timeUnit = new timeUnit();
        $unit_array = $timeUnit->get_assoc_array("timeUnitID", "timeUnitLabelA");

        $q = unsafe_prepare("SELECT * from timeSheetItem WHERE timeSheetID=%d ", $timeSheetID);
        $q .= unsafe_prepare("GROUP BY timeSheetItemID ORDER BY dateTimeSheetItem, timeSheetItemID");
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);

        $customerBilledDollars = $timeSheet->get_value("customerBilledDollars");
        $currency = Page::money($timeSheet->get_value("currencyTypeID"), '', "%S");

        return [$allocDatabase, $customerBilledDollars, $timeSheet, $unit_array, $currency];
    }

    public function get_timeSheetItem_list_money($timeSheetID)
    {
        $rows = [];
        $info = [];
        $units = [];
        $str = [];
        $d2s = [];
        $i = [];
        global $TPL;
        [$db, $customerBilledDollars, $timeSheet, $unit_array, $currency] = $this->get_timeSheetItem_vars($timeSheetID);

        $taxPercent = config::get_config_item("taxPercent");
        $taxPercentDivisor = ($taxPercent / 100) + 1;

        while ($db->next_record()) {
            $timeSheetItem = new timeSheetItem();
            $timeSheetItem->read_db_record($db);

            $taskID = sprintf("%d", $timeSheetItem->get_value("taskID"));

            $num = $timeSheetItem->calculate_item_charge($currency, $customerBilledDollars ?: $timeSheetItem->get_value("rate"));

            if ($taxPercent !== '') {
                $num_minus_gst = $num / $taxPercentDivisor;
                $gst = $num - $num_minus_gst;

                if ($num_minus_gst + $gst != $num) {
                    $num_minus_gst += $num - ($num_minus_gst + $gst); // round it up.
                }

                $rows[$taskID]["money"] += Page::money($timeSheet->get_value("currencyTypeID"), $num_minus_gst, "%mo");
                $rows[$taskID]["gst"] += Page::money($timeSheet->get_value("currencyTypeID"), $gst, "%mo");
                $info["total_gst"] += $gst;
                $info["total"] += $num_minus_gst;
            } else {
                $rows[$taskID]["money"] += Page::money($timeSheet->get_value("currencyTypeID"), $num, "%mo");
                $info["total"] += $num;
            }

            $unit = $unit_array[$timeSheetItem->get_value("timeSheetItemDurationUnitID")];
            $units[$taskID][$unit] += sprintf("%0.2f", $timeSheetItem->get_value("timeSheetItemDuration") * $timeSheetItem->get_value("multiplier"));

            unset($str);
            $d = $timeSheetItem->get_value('taskID', DST_HTML_DISPLAY) . ": " . $timeSheetItem->get_value('description', DST_HTML_DISPLAY);
            if ($d && !$rows[$taskID]["desc"]) {
                $str[] = "<b>" . $d . "</b>";
            } // inline because the PDF needs it that way

            // Get task description
            if ($taskID && $TPL["printDesc"]) {
                $t = new Task();
                $t->set_id($taskID);
                $t->select();
                $d2 = str_replace("\r\n", "\n", $t->get_value("taskDescription", DST_HTML_DISPLAY));
                $d2 .= "\n";

                if ($d2 && !$d2s[$taskID]) {
                    $str[] = $d2;
                }

                $d2 && ($d2s[$taskID] = true);
            }

            $c = str_replace("\r\n", "\n", $timeSheetItem->get_value("comment"));
            if (!$timeSheetItem->get_value("commentPrivate") && $c) {
                $str[] = Page::htmlentities($c);
            }

            if (is_array($str)) {
                $rows[$taskID]["desc"] .= trim(implode(DEFAULT_SEP, $str));
            }
        }

        // Group by units ie, a particular row/task might have  3 Weeks, 2 Hours
        // of work done.
        $commar = "";
        $units || ($units = []);
        foreach ($units as $tid => $u) {
            unset($commar);
            foreach ($u as $unit => $amount) {
                $rows[$tid]["units"] .= $commar . $amount . " " . $unit;
                $commar = ", ";
                $i[$unit] += $amount;
            }
        }

        $commar = "";
        $i || ($i = []);
        foreach ($i as $unit => $amount) {
            $info["total_units"] .= $commar . $amount . " " . $unit;
            $commar = ", ";
        }

        $info["total_inc_gst"] = Page::money($timeSheet->get_value("currencyTypeID"), $info["total"] + $info["total_gst"], "%s%mo");

        // If we are in dollar mode, then prefix the total with a dollar sign
        $info["total"] = Page::money($timeSheet->get_value("currencyTypeID"), $info["total"], "%s%mo");
        $info["total_gst"] = Page::money($timeSheet->get_value("currencyTypeID"), $info["total_gst"], "%s%mo");
        $rows || ($rows = []);
        $info || ($info = []);
        return [$rows, $info];
    }

    public function get_timeSheetItem_list_units($timeSheetID)
    {
        $units = [];
        $rows = [];
        $str = [];
        $d2s = [];
        $cs = [];
        $i = [];
        $info = [];
        global $TPL;
        [$db, $customerBilledDollars, $timeSheet, $unit_array, $currency] = $this->get_timeSheetItem_vars($timeSheetID);

        while ($db->next_record()) {
            $timeSheetItem = new timeSheetItem();
            $timeSheetItem->read_db_record($db);

            $taskID = sprintf("%d", $timeSheetItem->get_value("taskID"));
            $taskID || ($taskID = "hey"); // Catch entries without task selected. ie timesheetitem.comment entries.

            $num = sprintf("%0.2f", $timeSheetItem->get_value("timeSheetItemDuration"));
            // $info["total"] += $num;

            $unit = $unit_array[$timeSheetItem->get_value("timeSheetItemDurationUnitID")];
            $units[$taskID][$unit] += $num;

            unset($str);
            $d = $timeSheetItem->get_value('taskID', DST_HTML_DISPLAY) . ": " . $timeSheetItem->get_value('description', DST_HTML_DISPLAY);
            if ($d && !$rows[$taskID]["desc"]) {
                $str[] = "<b>" . $d . "</b>";
            }

            // Get task description
            if ($taskID && $TPL["printDesc"]) {
                $t = new Task();
                $t->set_id($taskID);
                $t->select();
                $d2 = str_replace("\r\n", "\n", $t->get_value("taskDescription", DST_HTML_DISPLAY));
                $d2 .= "\n";

                if ($d2 && !$d2s[$taskID]) {
                    $str[] = $d2;
                }

                $d2 && ($d2s[$taskID] = true);
            }

            $c = str_replace("\r\n", "\n", $timeSheetItem->get_value("comment"));
            if (!$timeSheetItem->get_value("commentPrivate") && $c && !$cs[$c]) {
                $str[] = Page::htmlentities($c);
            }

            $cs[$c] = true;

            if (is_array($str)) {
                $rows[$taskID]["desc"] .= trim(implode(DEFAULT_SEP, $str));
            }
        }

        // Group by units ie, a particular row/task might have  3 Weeks, 2 Hours
        // of work done.
        $commar = "";
        $units || ($units = []);
        foreach ($units as $tid => $u) {
            unset($commar);
            foreach ($u as $unit => $amount) {
                $rows[$tid]["units"] .= $commar . $amount . " " . $unit;
                $commar = ", ";
                $i[$unit] += $amount;
            }
        }

        $commar = "";
        $i || ($i = []);
        foreach ($i as $unit => $amount) {
            $info["total"] .= $commar . $amount . " " . $unit;
            $commar = ", ";
        }

        $timeSheet->load_pay_info();
        $info["total"] = $timeSheet->pay_info["summary_unit_totals"];
        $rows || ($rows = []);
        $info || ($info = []);
        return [$rows, $info];
    }

    public function get_timeSheetItem_list_items($timeSheetID)
    {
        $row_num = null;
        $info = [];
        $rows = [];
        $str = [];
        $d2s = [];
        global $TPL;
        [$db, $customerBilledDollars, $timeSheet, $unit_array, $currency] = $this->get_timeSheetItem_vars($timeSheetID);

        $meta = new Meta("timeSheetItemMultiplier");
        $multipliers = $meta->get_list();

        while ($db->next_record()) {
            $timeSheetItem = new timeSheetItem();
            $timeSheetItem->read_db_record($db);

            ++$row_num;
            $taskID = sprintf("%d", $timeSheetItem->get_value("taskID"));
            $num = sprintf("%0.2f", $timeSheetItem->get_value("timeSheetItemDuration"));

            $info["total"] += $num;
            $rows[$row_num]["date"] = $timeSheetItem->get_value("dateTimeSheetItem");
            $rows[$row_num]["units"] = $num . " " . $unit_array[$timeSheetItem->get_value("timeSheetItemDurationUnitID")];
            $rows[$row_num]["multiplier_string"] = $multipliers[$timeSheetItem->get_value("multiplier")]["timeSheetItemMultiplierName"];

            unset($str);
            $d = $timeSheetItem->get_value('taskID', DST_HTML_DISPLAY) . ": " . $timeSheetItem->get_value('description', DST_HTML_DISPLAY);
            if ($d && !$rows[$row_num]["desc"]) {
                $str[] = "<b>" . $d . "</b>";
            }

            // Get task description
            if ($taskID && $TPL["printDesc"]) {
                $t = new Task();
                $t->set_id($taskID);
                $t->select();
                $d2 = str_replace("\r\n", "\n", $t->get_value("taskDescription", DST_HTML_DISPLAY));
                $d2 .= "\n";

                if ($d2 && !$d2s[$taskID]) {
                    $str[] = $d2;
                }

                $d2 && ($d2s[$taskID] = true);
            }

            $c = str_replace("\r\n", "\n", $timeSheetItem->get_value("comment"));
            if (!$timeSheetItem->get_value("commentPrivate") && $c) {
                $str[] = Page::htmlentities($c);
            }

            if (is_array($str)) {
                $rows[$row_num]["desc"] .= trim(implode(DEFAULT_SEP, $str));
            }
        }

        $timeSheet->load_pay_info();
        $info["total"] = $timeSheet->pay_info["summary_unit_totals"];
        $rows || ($rows = []);
        $info || ($info = []);
        return [$rows, $info];
    }

    public function get_printable_timeSheet_file($timeSheetID, $timeSheetPrintMode, $printDesc, $format)
    {
        $cols_settings = [];
        $cols_settings2 = [];
        $contact_info = [];
        $ts_info = [];
        $totals = [];
        global $TPL;

        $TPL["timeSheetID"] = $timeSheetID;
        $TPL["timeSheetPrintMode"] = $timeSheetPrintMode;
        $TPL["printDesc"] = $printDesc;
        $TPL["format"] = $format;

        $allocDatabase = new AllocDatabase();

        if ($timeSheetID) {
            $timeSheet = new timeSheet();
            $timeSheet->set_id($timeSheetID);
            $timeSheet->select();
            $timeSheet->set_tpl_values();

            $person = $timeSheet->get_foreign_object("person");
            $TPL["timeSheet_personName"] = $person->get_name();
            $timeSheet->set_tpl_values("timeSheet_");

            // Display the project name.
            $project = new project();
            $project->set_id($timeSheet->get_value("projectID"));
            $project->select();
            $TPL["timeSheet_projectName"] = $project->get_value("projectName", DST_HTML_DISPLAY);

            // Get client name
            $client = $project->get_foreign_object("client");
            $client->set_tpl_values();
            $TPL["clientName"] = $client->get_value("clientName", DST_HTML_DISPLAY);
            $TPL["companyName"] = config::get_config_item("companyName");

            $TPL["companyNos1"] = config::get_config_item("companyACN");
            $TPL["companyNos2"] = config::get_config_item("companyABN");

            unset($br);
            $phone = config::get_config_item("companyContactPhone");
            $fax = config::get_config_item("companyContactFax");
            $phone && ($TPL["phone"] = "Ph: " . $phone);
            $fax && ($TPL["fax"] = "Fax: " . $fax);

            $timeSheet->load_pay_info();

            $allocDatabase->query(unsafe_prepare("SELECT max(dateTimeSheetItem) AS maxDate
                                      ,min(dateTimeSheetItem) AS minDate
                                      ,count(timeSheetItemID) as count
                                  FROM timeSheetItem
                                 WHERE timeSheetID=%d ", $timeSheetID));

            $allocDatabase->next_record();
            $timeSheet->set_id($timeSheetID);
            $timeSheet->select() || alloc_error("Unable to select time sheet, trying to use id: " . $timeSheetID);
            $TPL["period"] = format_date(DATE_FORMAT, $allocDatabase->f("minDate")) . " to " . format_date(DATE_FORMAT, $allocDatabase->f("maxDate"));

            $TPL["img"] = config::get_config_item("companyImage");
            $TPL["companyContactAddress"] = config::get_config_item("companyContactAddress");
            $TPL["companyContactAddress2"] = config::get_config_item("companyContactAddress2");
            $TPL["companyContactAddress3"] = config::get_config_item("companyContactAddress3");
            $email = config::get_config_item("companyContactEmail");
            $email && ($TPL["companyContactEmail"] = "Email: " . $email);
            $web = config::get_config_item("companyContactHomePage");
            $web && ($TPL["companyContactHomePage"] = "Web: " . $web);

            $TPL["footer"] = config::get_config_item("timeSheetPrintFooter");
            $TPL["taxName"] = config::get_config_item("taxName");

            $default_header = "Time Sheet";
            $default_id_label = "Time Sheet ID";
            $default_contractor_label = "Contractor";
            $default_total_label = "TOTAL AMOUNT PAYABLE";

            if ($timeSheetPrintMode == "money") {
                $default_header = "Tax Invoice";
                $default_id_label = "Invoice Number";
            }

            if ($timeSheetPrintMode == "estimate") {
                $default_header = "Estimate";
                $default_id_label = "Estimate Number";
                $default_contractor_label = "Issued By";
                $default_total_label = "TOTAL AMOUNT ESTIMATED";
            }

            if ($format != "html") {
                // Build PDF document
                $font1 = ALLOC_MOD_DIR . "util/fonts/Helvetica.afm";
                $font2 = ALLOC_MOD_DIR . "util/fonts/Helvetica-Oblique.afm";

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
                $cols_settings["two"] = [
                    "justification" => "right",
                    "width"         => 80,
                ];
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
                $cezpdf->ezStartPageNumbers(200, 80, 10, 'left', '<b>' . $default_id_label . ': </b>' . $TPL["timeSheetID"]);
                $cezpdf->ezSetY(775);

                $TPL["companyName"] && ($contact_info[] = [$TPL["companyName"]]);
                $TPL["companyContactAddress"] && ($contact_info[] = [$TPL["companyContactAddress"]]);
                $TPL["companyContactAddress2"] && ($contact_info[] = [$TPL["companyContactAddress2"]]);
                $TPL["companyContactAddress3"] && ($contact_info[] = [$TPL["companyContactAddress3"]]);
                $TPL["companyContactEmail"] && ($contact_info[] = [$TPL["companyContactEmail"]]);
                $TPL["companyContactHomePage"] && ($contact_info[] = [$TPL["companyContactHomePage"]]);
                $TPL["phone"] && ($contact_info[] = [$TPL["phone"]]);
                $TPL["fax"] && ($contact_info[] = [$TPL["fax"]]);

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
                    $y = $cezpdf->ezText($TPL["companyName"], 27, ["justification" => "right"]);
                }

                $nos_y = $line_y + 22;
                $TPL["companyNos2"] && ($nos_y = $line_y + 34);
                $cezpdf->ezSetY($nos_y);
                $TPL["companyNos1"] && ($y = $cezpdf->ezText($TPL["companyNos1"], 10, ["justification" => "right"]));
                $TPL["companyNos2"] && ($y = $cezpdf->ezText($TPL["companyNos2"], 10, ["justification" => "right"]));

                $cezpdf->ezSetY($line_y - 20);
                $y = $cezpdf->ezText($default_header, 20, ["justification" => "center"]);
                $cezpdf->ezSetY($y - 20);

                $ts_info[] = ["one" => "<b>" . $default_id_label . ":</b>", "two" => $TPL["timeSheetID"], "three" => "<b>Date Issued:</b>", "four" => date("d/m/Y")];
                $ts_info[] = ["one" => "<b>Client:</b>", "two" => $TPL["clientName"], "three" => "<b>Project:</b>", "four" => $TPL["timeSheet_projectName"]];
                $ts_info[] = ["one" => "<b>" . $default_contractor_label . ":</b>", "two" => $TPL["timeSheet_personName"], "three" => "<b>Billing Period:</b>", "four" => $TPL["period"]];
                if ($timeSheetPrintMode == "estimate") { // This line needs to be glued to the above line
                    $temp = array_pop($ts_info);
                    $temp["three"] = ""; // Nuke Billing Period for the Estimate version of the pdf.
                    $temp["four"] = ""; // Nuke Billing Period for the Estimate version of the pdf.
                    $ts_info[] = $temp;
                }

                $y = $cezpdf->ezTable($ts_info, $cols, "", $pdf_table_options2);

                $cezpdf->ezSetY($y - 20);

                if ($timeSheetPrintMode == "money" || $timeSheetPrintMode == "estimate") {
                    [$rows, $info] = $this->get_timeSheetItem_list_money($TPL["timeSheetID"]);
                    $cols2 = [
                        "desc"  => "Description",
                        "units" => "Units",
                        "money" => "Charges",
                        "gst"   => $TPL["taxName"],
                    ];
                    $taxPercent = config::get_config_item("taxPercent");
                    if ($taxPercent === '') {
                        unset($cols2["gst"]);
                    }

                    $rows[] = [
                        "desc"  => "<b>TOTAL</b>",
                        "units" => $info["total_units"],
                        "money" => $info["total"],
                        "gst"   => $info["total_gst"],
                    ];
                    $y = $cezpdf->ezTable($rows, $cols2, "", $pdf_table_options3);
                    $cezpdf->ezSetY($y - 20);
                    if ($taxPercent !== '') {
                        $totals[] = [
                            "one" => "TOTAL " . $TPL["taxName"],
                            "two" => $info["total_gst"],
                        ];
                    }

                    $totals[] = ["one" => "TOTAL CHARGES", "two" => $info["total"]];
                    $totals[] = [
                        "one" => "<b>" . $default_total_label . "</b>",
                        "two" => "<b>" . $info["total_inc_gst"] . "</b>",
                    ];
                    $y = $cezpdf->ezTable(
                        $totals,
                        $cols3,
                        "",
                        $pdf_table_options4
                    );
                } elseif ($timeSheetPrintMode == "units") {
                    [$rows, $info] = $this->get_timeSheetItem_list_units($TPL["timeSheetID"]);
                    $cols2 = ["desc" => "Description", "units" => "Units"];
                    $rows[] = [
                        "desc"  => "<b>TOTAL</b>",
                        "units" => "<b>" . $info["total"] . "</b>",
                    ];
                    $y = $cezpdf->ezTable($rows, $cols2, "", $pdf_table_options3);
                } elseif ($timeSheetPrintMode == "items") {
                    [$rows, $info] = $this->get_timeSheetItem_list_items($TPL["timeSheetID"]);
                    $cols2 = [
                        "date"              => "Date",
                        "units"             => "Units",
                        "multiplier_string" => "Multiplier",
                        "desc"              => "Description",
                    ];
                    $rows[] = [
                        "date"  => "<b>TOTAL</b>",
                        "units" => "<b>" . $info["total"] . "</b>",
                    ];
                    $y = $cezpdf->ezTable($rows, $cols2, "", $pdf_table_options3);
                }

                $cezpdf->ezSetY($y - 20);
                $cezpdf->ezText(str_replace(["<br>", "<br/>", "<br />"], "\n", $TPL["footer"]), 10);
                $cezpdf->ezStream(["Content-Disposition" => "timeSheet_" . $timeSheetID . ".pdf"]);

                // Else HTML format
            } else {
                if (file_exists(ALLOC_LOGO)) {
                    $TPL["companyName"] = '<img alt="Company logo" src="' . $TPL["url_alloc_logo"] . '" />';
                }

                $TPL["this_tsp"] = $this;
                $TPL["main_alloc_title"] = "Time Sheet - " . APPLICATION_NAME;
                include_template(__DIR__ . "/../templates/timeSheetPrintM.tpl");
            }
        }
    }
}
