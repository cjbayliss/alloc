<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

$defaults = [
    'showProjectType' => true,
    'url_form_action' => $TPL['url_alloc_projectList'],
    'form_name'       => 'projectList_filter',
];
$project = new project();

function show_filter(): string
{
    global $TPL;
    global $defaults;
    $personSelect = null;
    $projectName = null;
    $projectStatusOptions = null;
    $projectTypeOptions = null;
    $sessID = null;
    $url_alloc_projectList = null;

    $_FORM = project::load_form_data($defaults);
    $arr = project::load_project_filter($_FORM);
    if (is_array($arr)) {
        $TPL = array_merge($TPL, $arr);
    }

    // TODO: remove global variables
    if (is_array($TPL)) {
        extract($TPL, EXTR_OVERWRITE);
    }

    return <<<HTML
            <form action="{$url_alloc_projectList}" method="get">
                <table class="filter corner">
                  <tr>
                    <td>Status</td>
                    <td>Type</td>
                    <td>Allocated To</td>
                    <td>Name Containing</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td><select name="projectStatus[]" multiple="true">{$projectStatusOptions}</select></td>
                    <td><select name="projectType[]" multiple="true">{$projectTypeOptions}</select></td>
                    <td>{$personSelect}</td>
                    <td><input type="text" name="projectName" value="{$projectName}"></td>
                    <td><button type="submit" name="applyFilter" value="1" class="filter_button">Filter<i class="icon-cogs"></i></button></td>
                  </tr>
                </table>
                <input type="hidden" name="sessID" value="{$sessID}">
            </form>
        HTML;
}

$_FORM = $project->load_form_data($defaults);
$TPL['projectListRows'] = $project->getFilteredProjectList($_FORM);
$TPL['_FORM'] = $_FORM;

if (!isset($current_user->prefs['projectList_filter'])) {
    $TPL['message_help'][] = '

allocPSA helps you manage Projects. This page allows you to see a list of
Projects.

<br><br>

Simply adjust the filter settings and click the <b>Filter</b> button to
display a list of previously created Projects.
If you would prefer to create a new Project, click the <b>New Project</b> link
in the top-right hand corner of the box below.';
}

$TPL['main_alloc_title'] = 'Project List - ' . APPLICATION_NAME;

// TODO: remove global variables
if (is_array($TPL)) {
    extract($TPL, EXTR_OVERWRITE);
}

$page = new Page();
echo $page->header();
echo $page->toolbar();

$totalRecords = is_countable($projectListRows) ? count($projectListRows) : 0;
$filter = show_filter();
$projectListHTML = $project->listHTML($projectListRows, $_FORM);

echo <<<HTML
    <table class="box">
      <tr>
        <th class="header">Projects
          <b> - {$totalRecords} records</b>
          <span>
            <a class='magic toggleFilter' href=''>Show Filter</a>
            <a href="{$url_alloc_project}">New Project</a>
          </span>
        </th>
      </tr>
      <tr>
        <td align="center">{$filter}</td>
      </tr>
      <tr>
        <td>
          {$projectListHTML}
        </td>
      </tr>
    </table>
    HTML;

echo $page->footer();
