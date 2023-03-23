<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


require_once("../alloc.php");

if ($_POST['import']) {
    $projectID = $_POST["projectID"];
    $result = import_csv($_POST["filename"], $_POST["columns"], $_POST["headerRow"]);
    $TPL['result'] = $result;
    $TPL['main_alloc_title'] = "CSV Import Results";
    $TPL['projectID'] = $projectID;
    include_template("templates/csvImportResultM.tpl");
    exit;
}

//$_GET["filename"] = "/var/local/alloc/tmp/test.csv";
$basepath = ATTACHMENTS_DIR . 'tmp' . DIRECTORY_SEPARATOR;
$rp = realpath($basepath . $_GET['filename']);
if ($rp === false || strpos($rp, $basepath) !== 0) {
    alloc_error("Illegal path", true);
}

$fh = fopen($rp, "r");
if ($fh === false) {
    alloc_error("File won't open.", true);
}

$rows = [];

$header = fgetcsv($fh);
$rows[] = $header;

// only displaying the first 3 rows so the user can assign fields
for ($i = 0; $i < 2; $i++) {
    $rows[] = fgetcsv($fh);
}

$columns = [];

// see if it's possible to make sense of the header row
if (in_array("name", $header)) {
    // official-ish header row, try to pre-parse it
    $TPL["headerRow"] = 'checked="checked"';
} else {
    $header = [];
}

$options = [
    'ignore'            => 'Ignore',
    'name'              => 'Task name',
    'description'       => 'Task description',
    'manager'           => 'Task manager',
    'assignee'          => 'Task assignee',
    'limit'             => 'Time limit (hours)',
    'timeBest'          => 'Best-case estimate (hours)',
    'timeExpected'      => 'Expected estimate (hours)',
    'timeWorst'         => 'Worst-case estimate (hours)',
    'startDate'         => 'Estimated start date',
    'completionDate'    => 'Estimated completion date',
    'interestedParties' => 'Interested parties'
];

// there are 10 available fields, so max at 11 available rows
// Each row is <dropdown> <data> <data> <data>

$TPL["rows"] = [];
for ($i = 0; $i < min(11, count($rows[0])); $i++) {
    $TPL["rows"][] = [
        'name' => "row_$i",
        'dropdown' => page::select_options($options, $header[$i]),
        'cols' => [$rows[0][$i], $rows[1][$i], $rows[2][$i]]
    ];
}

$TPL['message_help'] = "Use the dropdowns to indicate how each column should be interpreted.";

$TPL['filename'] = $_GET['filename'];
$TPL['projectID'] = $_GET['projectID'];
$TPL['main_alloc_title'] = "Process CSV upload";
include_template("templates/csvImportM.tpl");
