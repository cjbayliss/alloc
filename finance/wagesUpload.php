<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

if (!config::get_config_item('outTfID')) {
    alloc_error('Please select a default Outgoing TF from the Setup -> Finance menu.');
}

$field_map = [
    ''                => 0,
    'transactionDate' => 1,
    'name'            => 2,
    'memo'            => 3,
    'account'         => 4,
    'amount'          => 5,
    'employeeNum'     => 7,
];

if ($_POST['upload'] && is_uploaded_file($_FILES['wages_file']['tmp_name'])) {
    $db = new AllocDatabase();

    $lines = file($_FILES['wages_file']['tmp_name']);

    reset($lines);
    foreach ($lines as $line) {
        // Read field values from the line
        $fields = explode("\t", $line);
        $transactionDate = trim($fields[$field_map['transactionDate']]);
        $employeeNum = trim($fields[$field_map['employeeNum']]);
        $amount = trim($fields[$field_map['amount']]);
        $memo = trim($fields[$field_map['memo']]);
        $account = trim($fields[$field_map['account']]);
        $name = trim($fields[$field_map['name']]);

        // Skip tax lines
        if (stristr($account, 'Payroll Liabilities')) {
            continue;
        }

        // Remove leading guff "789 - "
        // The dash isn't an ASCII dash, hence non-greedy anything match
        $account = preg_replace('/^\\d+\\s.*?\\s/', '', $account);

        // If there's a memo field then append it to account
        $memo && ($account .= ' - ' . $memo);
        // echo "<br>";
        // echo "<br>date: ".$transactionDate;
        // echo "<br>memo: ".$memo;
        // echo "<br>account: ".$account;
        // echo "<br>amount: ".$amount;
        // echo "<br>employeeNum: ".$employeeNum;
        // Ignore heading row, dividing lines and total rows
        if ('Date' == $transactionDate) {
            continue;
        }

        if ('' === $transactionDate) {
            continue;
        }

        if ('0' === $transactionDate) {
            continue;
        }

        if (false !== strpos('_____', $transactionDate)) {
            continue;
        }

        if (false !== strpos('���', $transactionDate)) {
            continue;
        }

        if (false !== stripos('total', $transactionDate)) {
            continue;
        }

        // If the employeeNum field is blank use the previous employeeNum
        // if (!$employeeNum) {
        //    $employeeNum = $prev_employeeNum;
        // }
        // $prev_employeeNum = $employeeNum;

        // Find the TF for the wage
        $query = unsafe_prepare('SELECT * FROM tf WHERE qpEmployeeNum=%d', $employeeNum);
        $db->query($query);
        if (!$db->next_record()) {
            $msg .= sprintf("<b>Warning: Could not find TF for employee number '%s' %s</b><br>", $employeeNum, $name);
            continue;
        }

        $fromTfID = $db->f('tfID');

        // Convert the date to yyyy-mm-dd
        if (!preg_match('|^(\\d{1,2})/(\\d{1,2})/(\\d{4})$|i', $transactionDate, $matches)) {
            $msg .= sprintf("<b>Warning: Could not convert date '%s'</b><br>", $transactionDate);
            continue;
        }

        $transactionDate = sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);

        // Strip $ and , from amount
        $amount = str_replace(['$', ','], [], $amount);
        if (!preg_match('/^[-]?\\d+(\\.\\d+)?$/', $amount)) {
            $msg .= sprintf("<b>Warning: Could not convert amount '%s'</b><br>", $amount);
            continue;
        }

        // Negate the amount - Wages are a debit from TF's
        $amount = -$amount;

        // Check for an existing transaction for this wage - note we have to use a range or amount because it is floating point
        $query = unsafe_prepare("SELECT transactionID
                        FROM transaction
                        WHERE fromTfID=%d AND transactionDate='%s' AND amount=%d", $fromTfID, $transactionDate, Page::money(config::get_config_item('currency'), $amount, '%mi'));
        $db->query($query);
        if ($db->next_record()) {
            $msg .= sprintf('Warning: Salary for employee #%s %s on %s already exists as transaction #', $employeeNum, $name, $transactionDate) . $db->f('transactionID') . '<br>';
            continue;
        }

        // Create a transaction object and then save it
        $transaction = new transaction();
        $transaction->set_value('currencyTypeID', config::get_config_item('currency'));
        $transaction->set_value('fromTfID', $fromTfID);
        $transaction->set_value('tfID', config::get_config_item('outTfID'));
        $transaction->set_value('transactionDate', $transactionDate);
        $transaction->set_value('amount', $amount);
        $transaction->set_value('companyDetails', '');
        $transaction->set_value('product', $account);
        $transaction->set_value('status', 'approved');
        $transaction->set_value('quantity', 1);
        $transaction->set_value('transactionType', 'salary');
        $transaction->save();

        $msg .= sprintf('$%s for employee %s %s on %s saved<br>', $amount, $employeeNum, $name, $transactionDate);
    }

    $TPL['msg'] = $msg;
}

$TPL['main_alloc_title'] = 'Upload Wages File - ' . APPLICATION_NAME;
include_template('templates/wagesUploadM.tpl');
