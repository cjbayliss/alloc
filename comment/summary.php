<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';
$current_user = &singleton('current_user');
$page = new Page();
$project = new project();
$comment = new comment();
global $TPL;

if (isset($_REQUEST['filter'])) {
    $current_user->prefs['comment_summary_list'] = $_REQUEST;
} elseif (isset($current_user->prefs['comment_summary_list'])) {
    $_REQUEST = $current_user->prefs['comment_summary_list'];
}

$main_alloc_title = 'Task Comment Summary';

// TODO: remove global variables
if (is_array($TPL)) {
    extract($TPL, EXTR_OVERWRITE);
}

$page->header($main_alloc_title);
$page->toolbar();

$people = &get_cached_table('person');
foreach ($people as $personID => $person) {
    if ($person['personActive']) {
        $ops[$personID] = $person['name'];
    }
}

$checked = isset($_REQUEST['clients']) ? 'checked' : '';
if (isset($_REQUEST['filter'])) {
    $_REQUEST['showTaskHeader'] = true;
}

$projectDropDownList = $project->get_list_dropdown('current', $_REQUEST['projectID'] ?? '');

// default to two weeks berfore and two weeks after current time
$fromDate = $_REQUEST['fromDate'] ?? date('Y-m-d', time() - 1_209_600);
$toDate = $_REQUEST['toDate'] ?? date('Y-m-d', time() + 1_209_600);

echo <<<HTML
    <table class="box">
      <tr>
        <th class="header nobr">Task Comment Summary
          <span>
            <a class='magic toggleFilter' href=''>Show Filter</a>
          </span>
        </th>
      </tr>
      <tr>
        <td>
            <form action="{$url_alloc_commentSummary}" method="get">
            <table align="center" class="filter corner">
              <tr>
                <td>Project</td><td>People</td><td>From Date</td><td>To Date</td><td>Task Status</td>
              </tr>
              <tr>
                <td style='vertical-align:top'>{$projectDropDownList}</td>
                <td style='vertical-align:top'>

                  <select name="personID[]" multiple="true" size="9">
                    {$page->select_options($ops, $_REQUEST['personID'] ?? $current_user->get_id())}
                  </select>
                </td>
                <td class="top">{$page->calendar('fromDate', $fromDate)}</td>
                <td class="top">{$page->calendar('toDate', $toDate)}</td>
                <td class="top">
                  <select name="taskStatus[]" multiple="true">{$page->select_options(Task::get_task_statii_array(), $_REQUEST['taskStatus'] ?? false)}</select>
                </td>
                <td class="top">
                  Include Client Comments <input type="checkbox" name="clients" value="clients"{$checked}>
                </td>
                <td class="top">
                  <button type="submit" name="filter" value="1" class="filter_button">Filter<i class="icon-cogs"></i></button>
                </td>
              </tr>
            </table>
            <input type="hidden" name="sessID" value="{$sessID}">
            </form>

        </td>
      </tr>
      <tr>
        <td>
        {$comment->get_list_summary($_REQUEST)}
        </td>
      </tr>
    </table>
    HTML;

$page->footer();
