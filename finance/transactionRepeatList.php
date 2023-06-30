<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

global $TPL;

$db = new AllocDatabase();
$TPL['tfID'] = $_GET['tfID'];

$TPL['main_alloc_title'] = 'Repeating Expenses List - ' . APPLICATION_NAME;
include_template('templates/transactionRepeatListM.tpl');

function show_expenseFormList($template_name)
{
    $sql = null;
    $i = null;
    global $db;
    global $TPL;
    global $transactionRepeat;
    $current_user = &singleton('current_user');

    $db = new AllocDatabase();
    $transactionRepeat = new transactionRepeat();

    if (!$_GET['tfID'] && !$current_user->have_role('admin')) {
        $tfIDs = $current_user->get_tfIDs();
        $tfIDs && ($sql = unsafe_prepare('WHERE tfID in (%s)', $tfIDs));
    } elseif ($_GET['tfID']) {
        $sql = unsafe_prepare('WHERE tfID = %d', $_GET['tfID']);
    }

    $db->query('select * FROM transactionRepeat ' . $sql);

    $taggedFund = new tf();
    while ($db->next_record()) {
        ++$i;
        $transactionRepeat->read_db_record($db);
        $transactionRepeat->set_values();
        $TPL['tfName'] = $taggedFund->get_name($transactionRepeat->get_value('tfID'));
        $TPL['fromTfName'] = $taggedFund->get_name($transactionRepeat->get_value('fromTfID'));
        include_template($template_name);
    }
}
