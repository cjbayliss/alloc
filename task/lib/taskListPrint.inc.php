<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define('DEFAULT_SEP', "\n");

class taskListPrint
{
    public function get_printable_file($_FORM = [])
    {
        $fields = [];
        $contact_info = [];
        global $TPL;

        $allocDatabase = new AllocDatabase();

        $TPL['companyName'] = config::get_config_item('companyName');
        $TPL['companyNos1'] = config::get_config_item('companyACN');
        $TPL['companyNos2'] = config::get_config_item('companyABN');
        $TPL['img'] = config::get_config_item('companyImage');
        $TPL['companyContactAddress'] = config::get_config_item('companyContactAddress');
        $TPL['companyContactAddress2'] = config::get_config_item('companyContactAddress2');
        $TPL['companyContactAddress3'] = config::get_config_item('companyContactAddress3');
        $email = config::get_config_item('companyContactEmail');
        $email && ($TPL['companyContactEmail'] = 'Email: ' . $email);
        $web = config::get_config_item('companyContactHomePage');
        $web && ($TPL['companyContactHomePage'] = 'Web: ' . $web);
        $phone = config::get_config_item('companyContactPhone');
        $fax = config::get_config_item('companyContactFax');
        $phone && ($TPL['phone'] = 'Ph: ' . $phone);
        $fax && ($TPL['fax'] = 'Fax: ' . $fax);

        $taskPriorities = config::get_config_item('taskPriorities');
        $projectPriorities = config::get_config_item('projectPriorities');

        // Add requested fields to pdf
        $_FORM['showEdit'] = false;
        $fields['taskID'] = 'ID';
        $fields['taskName'] = 'Task';
        $_FORM['showProject'] && ($fields['projectName'] = 'Project');
        if ($_FORM['showPriority'] || $_FORM['showPriorityFactor']) {
            $fields['priorityFactor'] = 'Pri';
        }

        $_FORM['showPriority'] && ($fields['taskPriority'] = 'Task Pri');
        $_FORM['showPriority'] && ($fields['projectPriority'] = 'Proj Pri');
        $_FORM['showCreator'] && ($fields['creator_name'] = 'Creator');
        $_FORM['showManager'] && ($fields['manager_name'] = 'Manager');
        $_FORM['showAssigned'] && ($fields['assignee_name'] = 'Assigned To');
        $_FORM['showDate1'] && ($fields['dateTargetStart'] = 'Targ Start');
        $_FORM['showDate2'] && ($fields['dateTargetCompletion'] = 'Targ Compl');
        $_FORM['showDate3'] && ($fields['dateActualStart'] = 'Start');
        $_FORM['showDate4'] && ($fields['dateActualCompletion'] = 'Compl');
        $_FORM['showDate5'] && ($fields['dateCreated'] = 'Created');
        $_FORM['showTimes'] && ($fields['timeBestLabel'] = 'Best');
        $_FORM['showTimes'] && ($fields['timeExpectedLabel'] = 'Likely');
        $_FORM['showTimes'] && ($fields['timeWorstLabel'] = 'Worst');
        $_FORM['showTimes'] && ($fields['timeActualLabel'] = 'Actual');
        $_FORM['showTimes'] && ($fields['timeLimitLabel'] = 'Limit');
        $_FORM['showPercent'] && ($fields['percentComplete'] = '%');
        $_FORM['showStatus'] && ($fields['taskStatusLabel'] = 'Status');

        $rows = Task::get_list($_FORM);
        $taskListRows = [];
        foreach ((array) $rows as $row) {
            $row['taskPriority'] = $taskPriorities[$row['priority']]['label'];
            $row['projectPriority'] = $projectPriorities[$row['projectPriority']]['label'];
            $row['taskDateStatus'] = strip_tags($row['taskDateStatus']);
            $row['percentComplete'] = strip_tags($row['percentComplete']);
            $taskListRows[] = $row;
        }

        if ('html' != $_FORM['format'] && 'html_plus' != $_FORM['format']) {
            // Build PDF document
            $font1 = ALLOC_MOD_DIR . 'util/fonts/Helvetica.afm';
            $font2 = ALLOC_MOD_DIR . 'util/fonts/Helvetica-Oblique.afm';

            $pdf_table_options = [
                'showLines'    => 0,
                'shaded'       => 0,
                'showHeadings' => 0,
                'xPos'         => 'left',
                'xOrientation' => 'right',
                'fontSize'     => 10,
                'rowGap'       => 0,
                'fontSize'     => 10,
            ];
            $pdf_table_options3 = [
                'showLines'   => 2,
                'shaded'      => 0,
                'width'       => 750,
                'xPos'        => 'center',
                'fontSize'    => 10,
                'lineCol'     => [0.8, 0.8, 0.8],
                'splitRows'   => 1,
                'protectRows' => 0,
            ];

            $cezpdf = new Cezpdf(null, 'landscape');
            $cezpdf->ezSetMargins(40, 40, 40, 40);
            $cezpdf->selectFont($font1);
            $cezpdf->ezStartPageNumbers(436, 30, 10, 'center', 'Page {PAGENUM} of {TOTALPAGENUM}');
            $cezpdf->ezSetY(560);

            $TPL['companyContactAddress'] && ($contact_info[] = [$TPL['companyContactAddress']]);
            $TPL['companyContactAddress2'] && ($contact_info[] = [$TPL['companyContactAddress2']]);
            $TPL['companyContactAddress3'] && ($contact_info[] = [$TPL['companyContactAddress3']]);
            $TPL['companyContactEmail'] && ($contact_info[] = [$TPL['companyContactEmail']]);
            $TPL['companyContactHomePage'] && ($contact_info[] = [$TPL['companyContactHomePage']]);
            $TPL['phone'] && ($contact_info[] = [$TPL['phone']]);
            $TPL['fax'] && ($contact_info[] = [$TPL['fax']]);

            $cezpdf->selectFont($font2);
            $y = $cezpdf->ezTable($contact_info, false, '', $pdf_table_options);
            $cezpdf->selectFont($font1);

            $line_y = $y - 10;
            $cezpdf->setLineStyle(1, 'round');
            $cezpdf->line(40, $line_y, 801, $line_y);

            $cezpdf->ezSetY(570);

            $image_jpg = ALLOC_LOGO;
            if (file_exists($image_jpg)) {
                $cezpdf->ezImage($image_jpg, 0, sprintf('%d', config::get_config_item('logoScaleX')), 'none');
                $y = 700;
            } else {
                $y = $cezpdf->ezText($TPL['companyName'], 27, ['justification' => 'right']);
            }

            $nos_y = $line_y + 22;
            $TPL['companyNos2'] && ($nos_y = $line_y + 34);
            $cezpdf->ezSetY($nos_y);
            $TPL['companyNos1'] && ($y = $cezpdf->ezText($TPL['companyNos1'], 10, ['justification' => 'right']));
            $TPL['companyNos2'] && ($y = $cezpdf->ezText($TPL['companyNos2'], 10, ['justification' => 'right']));

            $cezpdf->ezSetY($line_y - 10);
            $y = $cezpdf->ezText('Task List', 20, ['justification' => 'center']);
            $cezpdf->ezSetY($y - 20);

            $y = $cezpdf->ezTable($taskListRows, $fields, '', $pdf_table_options3);

            $cezpdf->ezSetY($y - 20);
            $cezpdf->ezStream();

            // Else HTML format
        } else {
            echo Task::get_list_html($taskListRows, $_FORM);
        }
    }
}
