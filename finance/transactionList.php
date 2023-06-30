<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

function show_filter()
{
    global $TPL;
    global $defaults;
    $_FORM = transaction::load_form_data($defaults);
    $arr = transaction::load_transaction_filter($_FORM);
    if (is_array($arr)) {
        $TPL = array_merge($TPL, $arr);
    }

    include_template('templates/transactionFilterS.tpl');
}

($tfID = $_GET['tfID']) || ($tfID = $_POST['tfID']);
($startDate = $_GET['startDate']) || ($startDate = $_POST['startDate']);
($endDate = $_GET['endDate']) || ($endDate = $_POST['endDate']);
($monthDate = $_GET['monthDate']) || ($monthDate = $_POST['monthDate']);
($download = $_GET['download']) || ($download = $_POST['download']);
($applyFilter = $_GET['applyFilter']) || ($applyFilter = $_POST['applyFilter']);

if (!$startDate && !$endDate && !$monthDate && !$applyFilter) {
    $monthDate = date('Y-m-d');
}

$defaults = [
    'url_form_action' => $TPL['url_alloc_transactionList'],
    'form_name'       => 'transactionList_filter',
    'applyFilter'     => $applyFilter,
    'tfID'            => $tfID,
    'startDate'       => $startDate,
    'endDate'         => $endDate,
    'monthDate'       => $monthDate,
];

if ($download) {
    $_FORM = transaction::load_form_data($defaults);
    $rtn = transaction::get_list($_FORM);
    $totals = $rtn['totals'];
    $rows = $rtn['rows'];
    $csv = transaction::arr_to_csv($rows);
    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . strlen($csv));
    header('Content-Disposition: attachment; filename="' . date('Ymd_His') . '.csv"');
    echo $csv;
    exit;
}

// Check perm of requested tf
$tf = new tf();
$tf->set_id($tfID);
$tf->select();
$TPL['tfID'] = $tfID;

$_FORM = transaction::load_form_data($defaults);
$rtn = transaction::get_list($_FORM);
$TPL['totals'] = $rtn['totals'];
$TPL['transactionListRows'] = $rtn['rows'];

// Total balance
$TPL['balance'] = $tf->get_balance();

// Total balance pending
$TPL['pending_amount'] = $tf->get_balance(['status' => 'pending']);

// Page and header title
$TPL['title'] = 'Statement for tagged fund: ' . $tf->get_value('tfName');
$TPL['main_alloc_title'] = 'TF: ' . $tf->get_value('tfName') . ' - ' . APPLICATION_NAME;

include_template('templates/transactionListM.tpl');
