<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

function sort_home_items($a, $b)
{
    return $a->get_seq() > $b->get_seq();
}

function show_home_items($width, $home_items): string
{
    $items = [];
    $html = '';
    $configLink = '';

    foreach ($home_items as $item) {
        $i = new $item();
        $items[] = $i;
    }

    uasort($items, 'sort_home_items');

    foreach ((array) $items as $item) {
        if ($item->get_width() != $width) {
            continue;
        }

        if (!$item->visible()) {
            continue;
        }

        if (!$item->render()) {
            continue;
        }

        if ($item->get_config()) {
            $configLink = '<a href="#x" class="config-link icon-wrench" id="config_' . $item->get_name() . '"></a>';
        }

        $html .= <<<HTML
                <table class="box">
                  <tr>
                    <th class="header" colspan="3">
                      {$item->get_title()}
                      <span style="position:relative;width:15%;" class="hidden-links">
                        {$configLink}
                      </span>
                    </th>
                  </tr>
                  <tr>
                    <td>{$item->show()}</td>
                  </tr>
                </table>

            HTML;
    }

    return $html;
}

global $modules;
$current_user = &singleton('current_user');
$home_items = [];
foreach ($modules as $module_name => $module) {
    if ($module->home_items) {
        $home_items = array_merge((array) $home_items, $module->home_items);
    }
}

$newTimeSheetItem = $_POST['time_item'] ?? false;

if ($newTimeSheetItem) {
    $parsedTimeSheetItem = (new timeSheetItem())->parse_time_string($newTimeSheetItem);
    if (is_numeric($parsedTimeSheetItem['duration'])) {
        (new timeSheet())->add_timeSheetItem($parsedTimeSheetItem);
    } else {
        alloc_error('Timesheet not added. No duration set');
    }
}

$main_alloc_title = 'Home Page - ' . APPLICATION_NAME;
$media = $_GET['media'] ?? '';

$page = new Page();
echo $page->header($main_alloc_title);
echo $page->toolbar();

if ('print' == $media) {
    echo show_home_items('standard', $home_items);
    echo show_home_items('narrow', $home_items);
} else {
    $showHomeItemsStandard = show_home_items('standard', $home_items);
    $showHomeItemsNarrow = show_home_items('narrow', $home_items);
    $allocSettingsURL = $page->getURL('url_alloc_settings');
    $weeks = [
        '0' => 0,
        1   => 1,
        2   => 2,
        3   => 3,
        4   => 4,
        8   => 8,
        12  => 12,
        30  => 30,
        52  => 52,
    ];
    $timeSheetWarning = $current_user->prefs['timeSheetHoursWarn'] ?? '';

    echo <<<HTML
            <div style="float:left; width:70%; vertical-align:top; padding:0; margin:0px; margin-right:1%; min-width:400px;">
                {$showHomeItemsStandard}
            </div>
            <div style="float:left; width:29%; vertical-align:top; padding:0; margin:0px;">
                {$showHomeItemsNarrow}
            </div>

            <!-- hidden preferences options. -->
            <div class="config_top_ten_tasks hidden config-pane lazy">
            </div>

            <div class="config_task_calendar_home_item hidden config-pane">
            <form action="{$allocSettingsURL}" method="post">
            <div>
                <h6>Calendar Weeks<div>Weeks Back</div></h6> 
                <div style="float:left; width:30%;">
                    <select name="weeks">{$page->select_options($weeks, $current_user->prefs['tasksGraphPlotHome'])}</select>
                    {$page->help('<b>Calendar Weeks</b><br><br>Control the number of weeks that the home page calendar displays.')}
                </div>
                <div style="float:right; width:50%;">
                    <select name="weeksBack">{$page->select_options($weeks, $current_user->prefs['tasksGraphPlotHomeStart'])}</select>
                    {$page->help('<b>Weeks Back</b><br><br>Control how many weeks in arrears are displayed on the home page calendar.')}
                </div>
            </div>
            <br><br>
            <span style="float:right">
                <a href="#x" onClick="$(this).parent().parent().parent().fadeOut();">Cancel</a>
                <button type="submit" name="customize_save" value="1" class="save_button">Save<i class="icon-ok-sign"></i></button>
            </span>
            </form>
            </div>

            <div class="config_project_list hidden config-pane">
            <form action="{$allocSettingsURL}" method="post">
            <div>
                <h6>Project List</h6> 
                <div style="float:left; width:30%;">
                    <select name="projectListNum">{$page->select_options(['0' => 0, 5 => 5, 10 => 10, 15 => 15, 20 => 20, 30 => 30, 40 => 40, 50 => 50, 'all' => 'All'], $current_user->prefs['projectListNum'])}</select>
                    {$page->help('<b>Project List</b><br><br>Control the number of projects displayed on your home page.')}
                </div>
            </div>
            <br><br>
            <span style="float:right">
                <a href="#x" onClick="$(this).parent().parent().parent().fadeOut();">Cancel</a>
                <button type="submit" name="customize_save" value="1" class="save_button">Save<i class="icon-ok-sign"></i></button>
            </span>
            </form>
            </div>

            <div class="config_time_list hidden config-pane">
            <form action="{$allocSettingsURL}" method="post">
                <div>
                    <h6>Time Sheet Hours<div>Time Sheet Days</div></h6> 
                    <div style="float:left; width:30%;">
                        <input type="text" size="5" name="timeSheetHoursWarn" value="{$timeSheetWarning}">
                        {$page->help('<b>Time Sheet Hours</b><br><br>Time sheets that go over this number of hours and are still in edit status will be flagged for you.')}
                    </div>
                    <div style="float:right; width:50%;">
                        <input type="text" size="5" name="timeSheetDaysWarn" value="{$timeSheetWarning}">
                        {$page->help('<b>Time Sheet Days</b><br><br>Time sheets that are older than this many days and are still in edit status will be flagged for you.')}
                    </div>
                </div>
            <br><br>
            <span style="float:right">
                <a href="#x" onClick="$(this).parent().parent().parent().fadeOut();">Cancel</a>
                <button type="submit" name="customize_save" value="1" class="save_button">Save<i class="icon-ok-sign"></i></button>
            </span>
            </form>
            </div>
        HTML;
}

echo $page->footer();
