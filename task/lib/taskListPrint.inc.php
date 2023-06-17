<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("DEFAULT_SEP", "\n");

class taskListPrint
{

    public function get_printable_file($_FORM = [])
    {
        $fields = [];
        $contact_info = [];
        global $TPL;

        $allocDatabase = new AllocDatabase();

        $TPL["companyName"] = config::get_config_item("companyName");
        $TPL["companyNos1"] = config::get_config_item("companyACN");
        $TPL["companyNos2"] = config::get_config_item("companyABN");
        $TPL["img"] = config::get_config_item("companyImage");
        $TPL["companyContactAddress"] = config::get_config_item("companyContactAddress");
        $TPL["companyContactAddress2"] = config::get_config_item("companyContactAddress2");
        $TPL["companyContactAddress3"] = config::get_config_item("companyContactAddress3");
        $email = config::get_config_item("companyContactEmail");
        $email and $TPL["companyContactEmail"] = "Email: " . $email;
        $web = config::get_config_item("companyContactHomePage");
        $web and $TPL["companyContactHomePage"] = "Web: " . $web;
        $phone = config::get_config_item("companyContactPhone");
        $fax = config::get_config_item("companyContactFax");
        $phone and $TPL["phone"] = "Ph: " . $phone;
        $fax and $TPL["fax"] = "Fax: " . $fax;

        $taskPriorities = config::get_config_item("taskPriorities");
        $projectPriorities = config::get_config_item("projectPriorities");

        // Add requested fields to pdf
        $_FORM["showEdit"] = false;
        $fields["taskID"] = "ID";
        $fields["taskName"] = "Task";
        $_FORM["showProject"] and $fields["projectName"] = "Project";
        $_FORM["showPriority"] || $_FORM["showPriorityFactor"]
            and $fields["priorityFactor"] = "Pri";
        $_FORM["showPriority"] and $fields["taskPriority"] = "Task Pri";
        $_FORM["showPriority"] and $fields["projectPriority"] = "Proj Pri";
        $_FORM["showCreator"] and $fields["creator_name"] = "Creator";
        $_FORM["showManager"] and $fields["manager_name"] = "Manager";
        $_FORM["showAssigned"] and $fields["assignee_name"] = "Assigned To";
        $_FORM["showDate1"] and $fields["dateTargetStart"] = "Targ Start";
        $_FORM["showDate2"] and $fields["dateTargetCompletion"] = "Targ Compl";
        $_FORM["showDate3"] and $fields["dateActualStart"] = "Start";
        $_FORM["showDate4"] and $fields["dateActualCompletion"] = "Compl";
        $_FORM["showDate5"] and $fields["dateCreated"] = "Created";
        $_FORM["showTimes"] and $fields["timeBestLabel"] = "Best";
        $_FORM["showTimes"] and $fields["timeExpectedLabel"] = "Likely";
        $_FORM["showTimes"] and $fields["timeWorstLabel"] = "Worst";
        $_FORM["showTimes"] and $fields["timeActualLabel"] = "Actual";
        $_FORM["showTimes"] and $fields["timeLimitLabel"] = "Limit";
        $_FORM["showPercent"] and $fields["percentComplete"] = "%";
        $_FORM["showStatus"] and $fields["taskStatusLabel"] = "Status";

        $rows = Task::get_list($_FORM);
        $taskListRows = [];
        foreach ((array)$rows as $row) {
            $row["taskPriority"] = $taskPriorities[$row["priority"]]["label"];
            $row["projectPriority"] = $projectPriorities[$row["projectPriority"]]["label"];
            $row["taskDateStatus"] = strip_tags($row["taskDateStatus"]);
            $row["percentComplete"] = strip_tags($row["percentComplete"]);
            $taskListRows[] = $row;
        }

        if ($_FORM["format"] != "html" && $_FORM["format"] != "html_plus") {
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
            $pdf_table_options3 = [
                "showLines"   => 2,
                "shaded"      => 0,
                "width"       => 750,
                "xPos"        => "center",
                "fontSize"    => 10,
                "lineCol"     => [0.8, 0.8, 0.8],
                "splitRows"   => 1,
                "protectRows" => 0,
            ];

            $cezpdf = new Cezpdf(null, 'landscape');
            $cezpdf->ezSetMargins(40, 40, 40, 40);
            $cezpdf->selectFont($font1);
            $cezpdf->ezStartPageNumbers(436, 30, 10, 'center', 'Page {PAGENUM} of {TOTALPAGENUM}');
            $cezpdf->ezSetY(560);

            $TPL["companyContactAddress"] and $contact_info[] = [$TPL["companyContactAddress"]];
            $TPL["companyContactAddress2"] and $contact_info[] = [$TPL["companyContactAddress2"]];
            $TPL["companyContactAddress3"] and $contact_info[] = [$TPL["companyContactAddress3"]];
            $TPL["companyContactEmail"] and $contact_info[] = [$TPL["companyContactEmail"]];
            $TPL["companyContactHomePage"] and $contact_info[] = [$TPL["companyContactHomePage"]];
            $TPL["phone"] and $contact_info[] = [$TPL["phone"]];
            $TPL["fax"] and $contact_info[] = [$TPL["fax"]];

            $cezpdf->selectFont($font2);
            $y = $cezpdf->ezTable($contact_info, false, "", $pdf_table_options);
            $cezpdf->selectFont($font1);

            $line_y = $y - 10;
            $cezpdf->setLineStyle(1, "round");
            $cezpdf->line(40, $line_y, 801, $line_y);

            $cezpdf->ezSetY(570);

            $image_jpg = ALLOC_LOGO;
            if (file_exists($image_jpg)) {
                $cezpdf->ezImage($image_jpg, 0, sprintf("%d", config::get_config_item("logoScaleX")), 'none');
                $y = 700;
            } else {
                $y = $cezpdf->ezText($TPL["companyName"], 27, ["justification" => "right"]);
            }
            $nos_y = $line_y + 22;
            $TPL["companyNos2"] and $nos_y = $line_y + 34;
            $cezpdf->ezSetY($nos_y);
            $TPL["companyNos1"] and $y = $cezpdf->ezText($TPL["companyNos1"], 10, ["justification" => "right"]);
            $TPL["companyNos2"] and $y = $cezpdf->ezText($TPL["companyNos2"], 10, ["justification" => "right"]);

            $cezpdf->ezSetY($line_y - 10);
            $y = $cezpdf->ezText("Task List", 20, ["justification" => "center"]);
            $cezpdf->ezSetY($y - 20);

            $y = $cezpdf->ezTable($taskListRows, $fields, "", $pdf_table_options3);

            $cezpdf->ezSetY($y - 20);
            $cezpdf->ezStream();

            // Else HTML format
        } else {
            echo Task::get_list_html($taskListRows, $_FORM);
        }
    }
}
