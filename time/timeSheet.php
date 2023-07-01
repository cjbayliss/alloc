<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

if (!$current_user->is_employee()) {
    alloc_error('You do not have permission to access time sheets', true);
}

function show_transaction_list($template_name)
{
    $rows = [];
    $has_transactions = null;
    global $timeSheet;
    global $TPL;

    $db = new AllocDatabase();

    $amount_so_far = $timeSheet->get_amount_so_far(true);
    $total_incoming = $timeSheet->pay_info['total_customerBilledDollars'] ?? 0;

    $db->query('SELECT * FROM transaction WHERE timeSheetID = %d AND fromTfID != %d
               ', $timeSheet->get_id(), config::get_config_item('inTfID'));

    while ($row = $db->row()) {
        $has_transactions = true;
        $rows[] = $row;
    }

    $total_allocated = transaction::get_actual_amount_used($rows);
    $TPL['total_allocated'] = Page::money($timeSheet->get_value('currencyTypeID'), $total_allocated, '%s%mo %c');
    $TPL['total_dollars'] = Page::money($timeSheet->get_value('currencyTypeID'), $timeSheet->pay_info['total_dollars_not_null'] ?? '', '%s%m %c');
    // used in js preload_field()
    $TPL['total_remaining'] = Page::money($timeSheet->get_value('currencyTypeID'), $total_incoming - $amount_so_far, '%m');

    if ($has_transactions || 'invoiced' == $timeSheet->get_value('status') || 'finished' == $timeSheet->get_value('status')) {
        if ($timeSheet->have_perm(PERM_TIME_INVOICE_TIMESHEETS) && 'invoiced' == $timeSheet->get_value('status')) {
            $p_button = '<input style="padding:1px 4px" type="submit" name="p_button" value="P" title="Mark transactions pending">&nbsp;';
            $a_button = '<input style="padding:1px 4px" type="submit" name="a_button" value="A" title="Mark transactions approved">&nbsp;';
            $r_button = '<input style="padding:1px 4px" type="submit" name="r_button" value="R" title="Mark transactions rejected">&nbsp;';
            $session = '<input type="hidden" name="sessID" value="' . $TPL['sessID'] . '">';
            $TPL['p_a_r_buttons'] = '<form action="' . $TPL['url_alloc_timeSheet'] . 'timeSheetID=' . $timeSheet->get_id() . '" method="post">' . $p_button . $a_button . $r_button . $session . '</form>';

            $TPL['create_transaction_buttons'] = '<tr><td colspan="8" align="center" style="padding:10px;">';
            $TPL['create_transaction_buttons'] .= '<form action="' . $TPL['url_alloc_timeSheet'] . 'timeSheetID=' . $timeSheet->get_id() . '" method="post">';

            $TPL['create_transaction_buttons'] .= '
         <button type="submit" name="create_transactions_default" value="1" class="save_button">Create Default Transactions<i class="icon-cogs"></i></button>
        ';

            $TPL['create_transaction_buttons'] .= '
        <button type="submit" name="delete_all_transactions" value="1" class="delete_button">Delete Transactions<i class="icon-trash"></i></button>
        ';

            $TPL['create_transaction_buttons'] .= '<input type="hidden" name="sessID" value="' . $TPL['sessID'] . '"></form></tr></tr>';
        }

        include_template($template_name);
    }
}

function show_transaction_listR($template_name)
{
    $tf_array = [];
    $taggedFund = new tf();
    $empty = null;
    global $timeSheet;
    global $TPL;
    $current_user = &singleton('current_user');
    global $percent_array;
    $db = new AllocDatabase();
    $db->query('SELECT * FROM transaction WHERE timeSheetID = %d', $timeSheet->get_id());

    if ($db->next_record() || 'invoiced' == $timeSheet->get_value('status') || 'finished' == $timeSheet->get_value('status')) {
        $db->query('SELECT *
               FROM tf
              WHERE tfActive = 1
                 OR tfID = %d
                 OR tfID = %d
           ORDER BY tfName', $db->f('tfID'), $db->f('fromTfID'));

        while ($db->row()) {
            $tf_array[$db->f('tfID')] = $db->f('tfName');
        }

        $status_options = [
            'pending'  => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ];
        $transactionType_options = transaction::get_transactionTypes();

        if ($timeSheet->have_perm(PERM_TIME_INVOICE_TIMESHEETS) && 'invoiced' == $timeSheet->get_value('status')) {
            $db->query('SELECT * FROM transaction WHERE timeSheetID = %d ORDER BY transactionID', $timeSheet->get_id());

            while ($db->next_record()) {
                $transaction = new transaction();
                $transaction->read_db_record($db);
                $transaction->set_tpl_values('transaction_');

                $TPL['currency'] = Page::money($transaction->get_value('currencyTypeID'), '', '%S');
                $TPL['currency_code'] = Page::money($transaction->get_value('currencyTypeID'), '', '%C');
                $TPL['tf_options'] = Page::select_options($tf_array, $TPL['transaction_tfID']);
                $TPL['from_tf_options'] = Page::select_options($tf_array, $TPL['transaction_fromTfID']);
                $TPL['status_options'] = Page::select_options($status_options, $transaction->get_value('status'));
                $TPL['transactionType_options'] = Page::select_options($transactionType_options, $transaction->get_value('transactionType'));
                $TPL['percent_dropdown'] = Page::select_options($percent_array, $empty);
                $TPL['transaction_buttons'] = '
            <button type="submit" name="transaction_delete" value="1" class="delete_button">Delete<i class="icon-trash"></i></button>
            <button type="submit" name="transaction_save" value="1" class="save_button">Save<i class="icon-ok-sign"></i></button>
          ';
                if ('invoice' == $transaction->get_value('transactionType')) {
                    $TPL['transaction_transactionType'] = $transaction->get_transaction_type_link();
                    $TPL['transaction_fromTfID'] = $taggedFund->get_name($transaction->get_value('fromTfID'));
                    $TPL['transaction_tfID'] = $taggedFund->get_name($transaction->get_value('tfID'));
                    $TPL['currency_amount'] = Page::money($transaction->get_value('currencyTypeID'), $transaction->get_value('amount'), '%S%mo %c');
                    include_template('templates/timeSheetTransactionListViewR.tpl');
                } else {
                    include_template($template_name);
                }
            }
        } else {
            // If you don't have perm INVOICE TIMESHEETS then only select
            // transactions which you have permissions to see.

            $query = unsafe_prepare('SELECT *
                            FROM transaction
                           WHERE timeSheetID = %d
                        ORDER BY transactionID', $timeSheet->get_id());

            $db->query($query);

            while ($db->next_record()) {
                $transaction = new transaction();
                $transaction->read_db_record($db);
                $transaction->set_tpl_values('transaction_');
                unset($TPL['transaction_amount_pos'], $TPL['transaction_amount_neg']);

                $TPL['currency_amount'] = Page::money($transaction->get_value('currencyTypeID'), $transaction->get_value('amount'), '%S%mo %c');
                $TPL['transaction_fromTfID'] = $taggedFund->get_name($transaction->get_value('fromTfID'));
                $TPL['transaction_tfID'] = $taggedFund->get_name($transaction->get_value('tfID'));
                $TPL['transaction_transactionType'] = $transactionType_options[$transaction->get_value('transactionType')];
                include_template('templates/timeSheetTransactionListViewR.tpl');
            }
        }
    }
}

function show_new_transaction($template)
{
    $none = null;
    $empty = null;
    global $timeSheet;
    global $TPL;
    global $db;
    global $percent_array;

    if ('invoiced' == $timeSheet->get_value('status') && $timeSheet->have_perm(PERM_TIME_INVOICE_TIMESHEETS)) {
        $tf = new tf();
        $options = $tf->get_assoc_array('tfID', 'tfName');
        $TPL['tf_options'] = Page::select_options($options, $none);

        $transactionType_options = transaction::get_transactionTypes();
        $TPL['transactionType_options'] = Page::select_options($transactionType_options);

        $status_options = [
            'pending'  => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ];
        $TPL['status_options'] = Page::select_options($status_options);
        $TPL['transaction_timeSheetID'] = $timeSheet->get_id();
        $TPL['transaction_transactionDate'] = date('Y-m-d');
        $TPL['transaction_product'] = '';
        $TPL['transaction_buttons'] = '
            <button type="submit" name="transaction_save" value="1" class="save_button">Add<i class="icon-plus-sign"></i></button>
      ';
        $TPL['percent_dropdown'] = Page::select_options($percent_array, $empty);
        include_template($template);
    }
}

function show_main_list()
{
    global $timeSheet;
    $current_user = &singleton('current_user');
    if (!$timeSheet->get_id()) {
        return;
    }

    $db = new AllocDatabase();
    $q = unsafe_prepare('SELECT COUNT(*) AS tally FROM timeSheetItem WHERE timeSheetID = %d AND timeSheetItemID != %d', $timeSheet->get_id(), $_POST['timeSheetItem_timeSheetItemID'] ?? 0);
    $db->query($q);
    $db->next_record();
    if ('' === $db->f('tally')) {
        return;
    }

    if ('0' === $db->f('tally')) {
        return;
    }

    include_template('templates/timeSheetItemM.tpl');
}

function show_timeSheet_list($template)
{
    $default_rate = [];
    global $TPL;
    global $timeSheet;
    global $db;
    global $tskDesc;
    global $timeSheetItem;
    global $timeSheetID;

    $db_task = new AllocDatabase();

    if (is_object($timeSheet) && ('edit' == $timeSheet->get_value('status') || 'rejected' == $timeSheet->get_value('status'))) {
        $TPL['timeSheetItem_buttons'] = '
        <button type="submit" name="timeSheetItem_delete" value="1" class="delete_button">Delete<i class="icon-trash"></i></button>
        <button type="submit" name="timeSheetItem_edit" value="1">Edit<i class="icon-edit"></i></button>';
    }

    $TPL['currency'] = Page::money($timeSheet->get_value('currencyTypeID'), '', '%S');

    $timeUnit = new timeUnit();
    $unit_array = $timeUnit->get_assoc_array('timeUnitID', 'timeUnitLabelA');

    $item_query = unsafe_prepare('SELECT * from timeSheetItem WHERE timeSheetID=%d', $timeSheetID);
    // If editing a timeSheetItem then don't display it in the list
    $timeSheetItemID = $_POST['timeSheetItemID'] ?? $_GET['timeSheetItemID'] ?? '';
    $timeSheetItemID && ($item_query .= unsafe_prepare(' AND timeSheetItemID != %d', $timeSheetItemID));
    $item_query .= unsafe_prepare(' GROUP BY timeSheetItemID ORDER BY dateTimeSheetItem, timeSheetItemID');
    $db->query($item_query);

    if (is_object($timeSheet)) {
        $project = $timeSheet->get_foreign_object('project');
        $row_projectPerson = projectPerson::get_projectPerson_row($project->get_id(), $timeSheet->get_value('personID'));
        $default_rate = [];
        if ($row_projectPerson && $row_projectPerson['rate'] > 0) {
            $default_rate['rate'] = $row_projectPerson['rate'];
            $default_rate['unit'] = $row_projectPerson['rateUnitID'];
        }
    }

    $TPL['timeSheet_totalHours'] ??= 0;
    while ($db->next_record()) {
        $timeSheetItem = new timeSheetItem();
        $timeSheetItem->currency = $timeSheet->get_value('currencyTypeID');
        $timeSheetItem->read_db_record($db);
        $timeSheetItem->set_tpl_values('timeSheetItem_');

        $TPL['timeSheet_totalHours'] += $timeSheetItem->get_value('timeSheetItemDuration');

        $TPL['unit'] = $unit_array[$timeSheetItem->get_value('timeSheetItemDurationUnitID')];

        $br = '';
        $commentPrivateText = '';

        $text = $timeSheetItem->get_value('description', DST_HTML_DISPLAY);
        if ($timeSheetItem->get_value('commentPrivate')) {
            $commentPrivateText = '<b>[Private Comment]</b> ';
        }

        $text && ($TPL['timeSheetItem_description'] = '<a href="' . $TPL['url_alloc_task'] . 'taskID=' . $timeSheetItem->get_value('taskID') . '">' . $text . '</a>');
        if ($text && $timeSheetItem->get_value('comment')) {
            $br = '<br>';
        }

        $timeSheetItem->get_value('comment') && ($TPL['timeSheetItem_comment'] = $br . $commentPrivateText . Page::to_html($timeSheetItem->get_value('comment')));
        $TPL['timeSheetItem_unit_times_rate'] = $timeSheetItem->calculate_item_charge($timeSheet->get_value('currencyTypeID'), $timeSheetItem->get_value('rate'));

        $m = new Meta('timeSheetItemMultiplier');
        $tsMultipliers = $m->get_list();
        $timeSheetItem->get_value('multiplier') && ($TPL['timeSheetItem_multiplier'] = $tsMultipliers[$timeSheetItem->get_value('multiplier')]['timeSheetItemMultiplierName']);

        // Check to see if this tsi is part of an overrun
        $TPL['timeSheetItem_class'] = 'panel';
        $TPL['timeSheetItem_status'] = '';
        $row_messages = [];
        if ($timeSheetItem->get_value('taskID')) {
            $task = new Task();
            $task->set_id($timeSheetItem->get_value('taskID'));
            $task->select();
            if ($task->get_value('timeLimit') > 0) {
                $total_billed_time = $task->get_time_billed(false) / 3600;    // get_time_billed returns seconds, limit hours is in hours
                if ($total_billed_time > $task->get_value('timeLimit')) {
                    $row_messages[] = "<em class='faint warn nobr'>[ Exceeds Limit ]</em>";
                }
            }
        }

        // Highlight the rate if the project person has a non-zero rate and it doesn't match the item's rate
        if (
            $default_rate && ($timeSheetItem->get_value('rate') != $default_rate['rate']
            || $timeSheetItem->get_value('timeSheetItemDurationUnitID') != $default_rate['unit'])
        ) {
            $row_messages[] = "<em class='faint warn nobr'>[ Modified rate ]</em>";
        }

        if ($row_messages) {
            $TPL['timeSheetItem_status'] = implode('<br />', $row_messages);
            $TPL['timeSheetItem_class'] = 'panel loud';
        }

        include_template($template);
    }

    $TPL['summary_totals'] = $timeSheet->pay_info['summary_unit_totals'];
}

function show_new_timeSheet($template)
{
    $taskID = null;
    $multiplier_array = [];
    $timeSheetItemMultiplier = null;
    global $TPL;
    global $timeSheet;
    global $timeSheetID;
    $current_user = &singleton('current_user');

    // Don't show entry form for new timeSheet.
    if (!$timeSheetID) {
        return;
    }

    if (
        is_object($timeSheet) && ('edit' == $timeSheet->get_value('status') || 'rejected' == $timeSheet->get_value('status'))
        && ($timeSheet->get_value('personID') == $current_user->get_id() || $timeSheet->have_perm(PERM_TIME_INVOICE_TIMESHEETS))
    ) {
        $TPL['currency'] = Page::money($timeSheet->get_value('currencyTypeID'), '', '%S');
        // If we are editing an existing timeSheetItem
        $timeSheetItem_edit = $_POST['timeSheetItem_edit'] ?? $_GET['timeSheetItem_edit'] ?? '';
        $timeSheetItemID = $_POST['timeSheetItemID'] ?? $_GET['timeSheetItemID'] ?? '';
        if ($timeSheetItemID && $timeSheetItem_edit) {
            $timeSheetItem = new timeSheetItem();
            $timeSheetItem->currency = $timeSheet->get_value('currencyTypeID');
            $timeSheetItem->set_id($timeSheetItemID);
            $timeSheetItem->select();
            $timeSheetItem->set_values('tsi_');
            $TPL['tsi_rate'] = $timeSheetItem->get_value('rate', DST_HTML_DISPLAY);
            $taskID = $timeSheetItem->get_value('taskID');
            $TPL['tsi_buttons'] = '
         <button type="submit" name="timeSheetItem_delete" value="1" class="delete_button">Delete<i class="icon-trash"></i></button>
         <button type="submit" name="timeSheetItem_save" value="1" class="save_button default">Save Item<i class="icon-ok-sign"></i></button>
         ';

            $timeSheetItemDurationUnitID = $timeSheetItem->get_value('timeSheetItemDurationUnitID');
            $TPL['tsi_commentPrivate'] && ($TPL['commentPrivateChecked'] = ' checked');

            $TPL['ts_rate_editable'] = $timeSheet->can_edit_rate();

            $timeSheetItemMultiplier = $timeSheetItem->get_value('multiplier');

            // Else default values for creating a new timeSheetItem
        } else {
            $TPL['tsi_buttons'] = '<button type="submit" name="timeSheetItem_save" value="1" class="save_button">Add Item<i class="icon-plus-sign"></i></button>';

            $TPL['tsi_personID'] = $current_user->get_id();
            $timeSheet->load_pay_info();
            $TPL['tsi_rate'] = $timeSheet->pay_info['project_rate'];
            $timeSheetItemDurationUnitID = $timeSheet->pay_info['project_rateUnitID'];
            $TPL['ts_rate_editable'] = $timeSheet->can_edit_rate();
        }

        $taskID ??= $_GET['taskID'] ?? 0;

        $TPL['taskListDropdown_taskID'] = $taskID;
        $TPL['taskListDropdown'] = $timeSheet->get_task_list_dropdown('mine', $timeSheet->get_id(), $taskID);
        $TPL['tsi_timeSheetID'] = $timeSheet->get_id();

        $timeUnit = new timeUnit();
        $unit_array = $timeUnit->get_assoc_array('timeUnitID', 'timeUnitLabelA');
        $TPL['tsi_unit_options'] = Page::select_options($unit_array, $timeSheetItemDurationUnitID);
        $timeSheetItemDurationUnitID && ($TPL['tsi_unit_label'] = $unit_array[$timeSheetItemDurationUnitID]);

        $m = new Meta('timeSheetItemMultiplier');
        $tsMultipliers = $m->get_list();

        foreach ($tsMultipliers as $k => $v) {
            $multiplier_array[$k] = $v['timeSheetItemMultiplierName'];
        }

        $TPL['tsi_multiplier_options'] = Page::select_options($multiplier_array, $timeSheetItemMultiplier);

        include_template($template);
    }
}

function show_comments()
{
    global $timeSheetID;
    global $TPL;
    global $timeSheet;
    if ($timeSheetID) {
        $TPL['commentsR'] = comment::util_get_comments('timeSheet', $timeSheetID);
        $TPL['class_new_comment'] = 'hidden';
        ($TPL['allParties'] = $timeSheet->get_all_parties($timeSheet->get_value('projectID'))) || ($TPL['allParties'] = []);
        $TPL['entity'] = 'timeSheet';
        $TPL['entityID'] = $timeSheet->get_id();
        $p = $timeSheet->get_foreign_object('project');
        $TPL['clientID'] = $p->get_value('clientID');
        $commentTemplate = new commentTemplate();
        $ops = $commentTemplate->get_assoc_array('commentTemplateID', 'commentTemplateName', '', ['commentTemplateType' => 'timeSheet']);
        $TPL['commentTemplateOptions'] = '<option value="">Comment Templates</option>' . Page::select_options($ops);

        $timeSheetPrintOptions = config::get_config_item('timeSheetPrintOptions');
        $timeSheetPrint = config::get_config_item('timeSheetPrint');
        $ops = ['' => 'Format as...'];
        foreach ($timeSheetPrint as $value) {
            $ops[$value] = $timeSheetPrintOptions[$value];
        }

        $TPL['attach_extra_files'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $TPL['attach_extra_files'] .= 'Attach Time Sheet ';
        $TPL['attach_extra_files'] .= '<select name="attach_timeSheet">' . Page::select_options($ops) . '</select><br>';

        // TODO: remove global variables
        if (is_array($TPL)) {
            extract($TPL, EXTR_OVERWRITE);
        }

        $comment = new comment();
        $comment->commentSectionHTML();
    }
}

// ============ END FUNCTIONS

global $timeSheet;
global $timeSheetItem;
global $timeSheetItemID;
global $db;
$current_user = &singleton('current_user');
global $TPL;

$timeSheetID = $_POST['timeSheetID'] ?? $_GET['timeSheetID'] ?? '';

$db = new AllocDatabase();
$timeSheet = new timeSheet();

if (!empty($timeSheetID)) {
    $timeSheet = new timeSheet();
    $timeSheet->set_id($timeSheetID);
    $timeSheet->select();
    $timeSheet->set_values();
}

// Manually update the Client Billing field
if (isset($_REQUEST['updateCB']) && $timeSheet->get_id() && $timeSheet->can_edit_rate()) {
    $project = new project();
    $project->set_id($timeSheet->get_value('projectID'));
    $project->select();
    $timeSheet->set_value('customerBilledDollars', Page::money($project->get_value('currencyTypeID'), $project->get_value('customerBilledDollars'), '%mo'));
    $timeSheet->set_value('currencyTypeID', $project->get_value('currencyTypeID'));
    $timeSheet->save();
}

// Manually update the person's rate
if (isset($_REQUEST['updateRate']) && $timeSheet->get_id() && $timeSheet->can_edit_rate()) {
    $row_projectPerson = projectPerson::get_projectPerson_row($timeSheet->get_value('projectID'), $timeSheet->get_value('personID'));
    if ([] === $row_projectPerson) {
        alloc_error('The person has not been added to the project.');
    } else {
        $q = unsafe_prepare('SELECT timeSheetItemID from timeSheetItem WHERE timeSheetID = %d', $timeSheet->get_id());
        $db = new AllocDatabase();
        $db->query($q);
        while ($row = $db->row()) {
            $tsi = new timeSheetItem();
            $tsi->set_id($row['timeSheetItemID']);
            $tsi->select();
            $v = $row_projectPerson['rateUnitID'] ?: '';

            $tsi->set_value('timeSheetItemDurationUnitID', $v);
            $tsi->set_value('rate', Page::money($timeSheet->get_value('currencyTypeID'), $row_projectPerson['rate'], '%mo'));
            $tsi->skip_tsi_status_check = true;
            $tsi->save();
        }
    }
}

if (
    isset($_POST['save'])
    || isset($_POST['save_and_new'])
    || isset($_POST['save_and_returnToList'])
    || isset($_POST['save_and_returnToProject'])
    || isset($_POST['save_and_MoveForward'])
    || isset($_POST['save_and_MoveBack'])
) {
    // Saving a record
    $timeSheet->read_globals();
    $timeSheet->read_globals('timeSheet_');
    $projectID = $timeSheet->get_value('projectID');
    if (0 != $projectID) {
        $project = new project();
        $project->set_id($projectID);
        $project->select();

        $projectManagers = $project->get_timeSheetRecipients();

        if (!$timeSheet->get_id()) {
            $timeSheet->set_value('customerBilledDollars', Page::money($project->get_value('currencyTypeID'), $project->get_value('customerBilledDollars'), '%mo'));
            $timeSheet->set_value('currencyTypeID', $project->get_value('currencyTypeID'));
        }
    } else {
        $save_error = true;
        $TPL['message_help'][] = 'Begin a Time Sheet by selecting a Project and clicking the Create Time Sheet button. A manager must add you to the project before you can create time sheets for it.';
        alloc_error('Please select a Project and then click the Create Time Sheet button.');
    }

    // If it's a Pre-paid project, join this time sheet onto an invoice
    if (is_object($project) && $project->get_id() && 'Prepaid' == $project->get_value('projectType')) {
        $invoiceID = $project->get_prepaid_invoice();

        if (!$invoiceID) {
            $save_error = true;
            alloc_error('Unable to find a Pre-paid Invoice for this Project or Client.');
        } elseif (!$timeSheet->get_id()) {
            $add_timeSheet_to_invoiceID = $invoiceID;
        }
    }

    if (isset($_POST['save_and_MoveForward'])) {
        $msg .= $timeSheet->change_status('forwards');
    } elseif (isset($_POST['save_and_MoveBack'])) {
        $msg .= $timeSheet->change_status('backwards');
    }

    $timeSheet->set_value('billingNote', rtrim($timeSheet->get_value('billingNote')));
    if ($TPL['message'] || $save_error) {
        // don't save or sql will complain
        $url = $TPL['url_alloc_timeSheet'];
    } elseif (!$timeSheet->get_value('personID') && $timeSheetID) {
        // if TS ID is set but person ID is not, it's an existing timesheet this
        // user doesn't have access to (and will overwrite). Don't proceed.
        $url = $TPL['url_alloc_timeSheet'];
    } elseif (!$TPL['message'] && $timeSheet->save()) {
        if ($add_timeSheet_to_invoiceID) {
            $invoice = new invoice();
            $invoice->set_id($add_timeSheet_to_invoiceID);
            $invoice->add_timeSheet($timeSheet->get_id());
        }

        if (isset($_POST['save_and_new'])) {
            $url = $TPL['url_alloc_timeSheet'];
        } elseif (isset($_POST['save_and_returnToList'])) {
            $url = $TPL['url_alloc_timeSheetList'];
        } elseif (isset($_POST['save_and_returnToProject'])) {
            $url = $TPL['url_alloc_project'] . 'projectID=' . $timeSheet->get_value('projectID');
        } else {
            $msg = Page::htmlentities(urlencode($msg));
            $url = $TPL['url_alloc_timeSheet'] . 'timeSheetID=' . $timeSheet->get_id() . '&msg=' . $msg . '&dont_send_email=' . $_POST['dont_send_email'];
            // Pass the taskID forward if we came from a task
            $url .= '&taskID=' . $_POST['taskID'];
        }

        alloc_redirect($url);
        exit;
    }
} elseif (isset($_POST['delete'])) {
    // Deleting a record
    $timeSheet->read_globals();
    $timeSheet->select();
    $timeSheet->delete();
    if (!$TPL['message']) {
        alloc_redirect($TPL['url_alloc_timeSheetList']);
    }
} elseif ($timeSheetID) {
    // Displaying a record
    $timeSheet->set_id($timeSheetID);
    $timeSheet->select();
} else {
    // create a new record
    $timeSheet->read_globals();
    $timeSheet->read_globals('timeSheet_');
    $timeSheet->set_value('status', 'edit');
    $TPL['message_help'] = 'Begin a Time Sheet by selecting a Project and clicking the Create Time Sheet button. A manager must add you to the project before you can create time sheets for it.';
}

// THAT'S THE END OF THE BIG SAVE.

$person = $timeSheet->get_foreign_object('person');
$TPL['timeSheet_personName'] = $person->get_name();
$timeSheet->set_values('timeSheet_');

if (!$timeSheetID) {
    $timeSheet->set_value('personID', $current_user->get_id());
}

if (isset($_POST['create_transactions_default']) && $timeSheet->have_perm(PERM_TIME_INVOICE_TIMESHEETS)) {
    $msg .= $timeSheet->createTransactions();
} elseif (isset($_POST['delete_all_transactions']) && $timeSheet->have_perm(PERM_TIME_INVOICE_TIMESHEETS)) {
    $msg .= $timeSheet->destroyTransactions();
}

// Take care of saving transactions
if ((isset($_POST['p_button']) || isset($_POST['a_button']) || isset($_POST['r_button'])) && $timeSheet->have_perm(PERM_TIME_INVOICE_TIMESHEETS)) {
    if (isset($_POST['p_button'])) {
        $status = 'pending';
    } elseif (isset($_POST['a_button'])) {
        $status = 'approved';
    } elseif (isset($_POST['r_button'])) {
        $status = 'rejected';
    }

    $query = unsafe_prepare("UPDATE transaction SET status = '%s' WHERE timeSheetID = %d AND transactionType != 'invoice'", $status, $timeSheet->get_id());
    $db = new AllocDatabase();
    $db->query($query);
    // Take care of the transaction line items on an invoiced timesheet created by admin
} elseif ((isset($_POST['transaction_save']) || isset($_POST['transaction_delete'])) && $timeSheet->have_perm(PERM_TIME_INVOICE_TIMESHEETS)) {
    $transaction = new transaction();
    $transaction->read_globals();
    $transaction->read_globals('transaction_');
    if (isset($_POST['transaction_save'])) {
        if (is_numeric($_POST['percent_dropdown'])) {
            $transaction->set_value('amount', $_POST['percent_dropdown']);
        }

        $transaction->set_value('currencyTypeID', $timeSheet->get_value('currencyTypeID'));
        $transaction->save();
    } elseif (isset($_POST['transaction_delete'])) {
        $transaction->delete();
    }
}

// display the approved by admin and managers name and date
$person = new person();

if ($timeSheet->get_value('approvedByManagerPersonID')) {
    $person_approvedByManager = new person();
    $person_approvedByManager->set_id($timeSheet->get_value('approvedByManagerPersonID'));
    $person_approvedByManager->select();
    $TPL['timeSheet_approvedByManagerPersonID_username'] = $person_approvedByManager->get_name();
    $TPL['timeSheet_approvedByManagerPersonID'] = $timeSheet->get_value('approvedByManagerPersonID');
}

if ($timeSheet->get_value('approvedByAdminPersonID')) {
    $person_approvedByAdmin = new person();
    $person_approvedByAdmin->set_id($timeSheet->get_value('approvedByAdminPersonID'));
    $person_approvedByAdmin->select();
    $TPL['timeSheet_approvedByAdminPersonID_username'] = $person_approvedByAdmin->get_name();
    $TPL['timeSheet_approvedByAdminPersonID'] = $timeSheet->get_value('approvedByAdminPersonID');
}

// display the project name.
if (('edit' == $timeSheet->get_value('status') || 'rejected' == $timeSheet->get_value('status')) && !$timeSheet->get_value('projectID')) {
    $query = unsafe_prepare("SELECT * FROM project WHERE projectStatus = 'Current' ORDER by projectName");
    // .unsafe_prepare("  LEFT JOIN projectPerson on projectPerson.projectID = project.projectID ")
    // .unsafe_prepare("WHERE projectPerson.personID = '%d' ORDER BY projectName", $current_user->get_id());
} else {
    $query = unsafe_prepare('SELECT * FROM project ORDER by projectName');
}

// This needs to be just above the newTimeSheet_projectID logic
$projectID = $timeSheet->get_value('projectID');

// If we are entering the page from a project link: New time sheet
if (isset($_GET['newTimeSheet_projectID']) && !$projectID) {
    if (isset($_GET['taskID'])) {
        $tid = '&taskID=' . $_GET['taskID'];
    }

    $projectID = $_GET['newTimeSheet_projectID'];
    $db = new AllocDatabase();
    $q = unsafe_prepare("SELECT * FROM timeSheet WHERE status = 'edit' AND personID = %d AND projectID = %d", $current_user->get_id(), $projectID);
    $db->query($q);
    if ($db->next_record()) {
        alloc_redirect($TPL['url_alloc_timeSheet'] . 'timeSheetID=' . $db->f('timeSheetID') . $tid);
    }
}

if (isset($_GET['newTimeSheet_projectID']) && !$db->qr('SELECT * FROM projectPerson WHERE personID = %d AND projectID = %d', $current_user->get_id(), $_GET['newTimeSheet_projectID'])) {
    alloc_error('You are not a member of the project (id:' . Page::htmlentities($_GET['newTimeSheet_projectID']) . '), please get a manager to add you to the project.');
}

$db->query($query);
while ($db->row()) {
    $project_array[$db->f('projectID')] = $db->f('projectName');
}

$TPL['timeSheet_projectName'] = $project_array[$projectID] ?? '';
$TPL['timeSheet_projectID'] = $projectID ?? '';
$TPL['taskID'] = $_GET['taskID'] ?? '';

// Get the project record to determine which button for the edit status.
if (!empty($projectID)) {
    $project = new project();
    $project->set_id($projectID);
    $project->select();

    $projectManagers = $project->get_timeSheetRecipients();

    if (!$projectManagers) {
        $TPL['managers'] = 'N/A';
        $TPL['timeSheet_dateSubmittedToManager'] = 'N/A';
        $TPL['timeSheet_approvedByManagerPersonID_username'] = 'N/A';
    } else {
        if ((is_countable($projectManagers) ? count($projectManagers) : 0) > 1) {
            $TPL['manager_plural'] = 's';
        }

        $people = &get_cached_table('person');
        $TPL['managers'] ??= '';
        $commar = '';
        foreach ($projectManagers as $projectManager) {
            $TPL['managers'] .= $commar . $people[$projectManager]['name'];
            $commar = ', ';
        }
    }

    $clientID = $project->get_value('clientID');
    $projectID = $project->get_id();

    // Get client name
    $client = $project->get_foreign_object('client');
    $TPL['clientName'] = $client_link ?? '';
    $TPL['clientID'] = $clientID = $client->get_id();
    $TPL['show_client_options'] = $client_link ?? '';
}

$clientID ??= '';
$projectID ??= '';
[$client_select, $client_link, $project_select, $project_link]
    = client::get_client_and_project_dropdowns_and_links($clientID, $projectID, true);

$TPL['invoice_link'] = $timeSheet->get_invoice_link();
[$amount_used, $amount_allocated] = $timeSheet->get_amount_allocated();
if ($amount_allocated) {
    $TPL['amount_allocated_label'] = 'Amount Used / Allocated:';
    $TPL['amount_allocated'] = $amount_allocated;
    $TPL['amount_used'] = $amount_used . ' / ';
}

if (!$timeSheet->get_id() || 'edit' == $timeSheet->get_value('status') || 'rejected' == $timeSheet->get_value('status')) {
    $TPL['show_project_options'] = $project_select;
    $TPL['show_client_options'] = $client_select;
} else {
    $TPL['show_project_options'] = $project_link;
    $TPL['show_client_options'] = $client_link;
}

if (is_object($timeSheet) && $timeSheet->get_id() && $timeSheet->have_perm(PERM_TIME_INVOICE_TIMESHEETS) && !$timeSheet->get_invoice_link() && 'finished' != $timeSheet->get_value('status')) {
    $p = $timeSheet->get_foreign_object('project');
    $ops['invoiceStatus'] = 'edit';
    $ops['clientID'] = $p->get_value('clientID');
    $ops['return'] = 'dropdown_options';
    $invoice_list = invoice::get_list($ops);
    $q = unsafe_prepare('SELECT * FROM invoiceItem WHERE timeSheetID = %d', $timeSheet->get_id());
    $db = new AllocDatabase();
    $db->query($q);
    $row = $db->row();
    // $sel_invoice = $row['invoiceID'] ?? (int)null;
    // $TPL["attach_to_invoice_button"] = "<select name=\"attach_to_invoiceID\">";
    // $TPL["attach_to_invoice_button"].= "<option value=\"create_new\">Create New Invoice</option>";
    // $TPL["attach_to_invoice_button"].= Page::select_options($invoice_list,$sel_invoice)."</select>";
    // $TPL["attach_to_invoice_button"].= "<input type=\"submit\" name=\"attach_transactions_to_invoice\" value=\"Add to Invoice\"> ";
}

// msg passed in url and print it out pretty..
$msg ??= $msg = $_GET['msg'] ?? $msg = $_POST['msg'] ?? '';

if (!empty($msg)) {
    $TPL['message_good'][] = $msg;
}

global $percent_array;
if (isset($_POST['dont_send_email'])) {
    $TPL['dont_send_email_checked'] = ' checked';
} elseif ('invoiced' == $timeSheet->get_value('status')) {
    // if this is the invoice -> completed step it should be checked by default
    $TPL['dont_send_email_checked'] = ' checked';
} else {
    $TPL['dont_send_email_checked'] = '';
}

$timeSheet->load_pay_info();

$timeSheet->pay_info['total_dollars'] ??= 0;
$percent_array = [
    ''                                                              => 'Calculate %',
    'A'                                                             => 'Standard',
    sprintf('%0.2f', $timeSheet->pay_info['total_dollars'] * 1)     => '100%',
    sprintf('%0.2f', $timeSheet->pay_info['total_dollars'] * 0.715) => '71.5%',
    sprintf('%0.2f', $timeSheet->pay_info['total_dollars'] * 0.665) => '66.5%',
    sprintf('%0.2f', $timeSheet->pay_info['total_dollars'] * 0.615) => '61.5%',
    sprintf('%0.2f', $timeSheet->pay_info['total_dollars'] * 0.285) => '28.5%',
    'B'                                                             => 'Agency',
    sprintf('%0.2f', $timeSheet->pay_info['total_dollars'] * 0.765) => '76.5%',
    sprintf('%0.2f', $timeSheet->pay_info['total_dollars'] * 0.715) => '71.5%',
    sprintf('%0.2f', $timeSheet->pay_info['total_dollars'] * 0.665) => '66.5%',
    sprintf('%0.2f', $timeSheet->pay_info['total_dollars'] * 0.235) => '23.5%',
    'C'                                                             => 'Commission',
    sprintf('%0.2f', $timeSheet->pay_info['total_dollars'] * 0.050) => '5.0%',
    sprintf('%0.2f', $timeSheet->pay_info['total_dollars'] * 0.025) => '2.5%',
    'D'                                                             => 'Old Rates',
    sprintf('%0.2f', $timeSheet->pay_info['total_dollars'] * 0.772) => '77.2%',
    sprintf('%0.2f', $timeSheet->pay_info['total_dollars'] * 0.722) => '72.2%',
    sprintf('%0.2f', $timeSheet->pay_info['total_dollars'] * 0.228) => '22.8%',
];

// display the buttons to move timesheet forward and backward.

if (!$timeSheet->get_id()) {
    $TPL['timeSheet_ChangeStatusButton'] = '<button type="submit" name="save" value="1" class="save_button">Create Time Sheet<i class="icon-ok-sign"></i></button>';
}

$radio_email = '<input type="checkbox" id="dont_send_email" name="dont_send_email" value="1"' . $TPL['dont_send_email_checked'] . "> <label for=\"dont_send_email\">Don't send email</label><br>";

$statii = timeSheet::get_timeSheet_statii();

if (!isset($projectManagers)) {
    unset($statii['manager']);
}

foreach ($statii as $s => $label) {
    unset($pre, $suf); // prefix and suffix
    $status = $timeSheet->get_value('status');
    unset($red);
    if ('rejected' == $status) {
        $red = true;
    }

    if ('rejected' == $status) {
        $status = 'edit';
    }

    if (!$timeSheet->get_id()) {
        $status = 'create';
    }

    if ($s == $status) {
        $red = isset($red) ? ' class="warn"' : '';
        $pre = '<b' . $red . '>';
        $suf = '</b>';
    }

    if ('rejected' == $s) {
        continue;
    }

    // make sure these aren't empty
    $sep ??= '';
    $pre ??= '';
    $suf ??= '';
    $TPL['timeSheet_status_text'] ??= '';

    $TPL['timeSheet_status_text'] .= $sep . $pre . $label . $suf;
    $sep = '&nbsp;&nbsp;|&nbsp;&nbsp;';
}

switch ($timeSheet->get_value('status')) {
    case 'edit':
    case 'rejected':
        if (($timeSheet->get_value('personID') == $current_user->get_id() || $timeSheet->have_perm(PERM_TIME_INVOICE_TIMESHEETS)) && $timeSheetID) {
            $destlabel = 'Admin';
            $projectManagers && ($destlabel = 'Manager');
            $TPL['timeSheet_ChangeStatusButton'] = '
        <button type="submit" name="delete" value="1" class="delete_button">Delete<i class="icon-trash"></i></button>
        <button type="submit" name="save" value="1" class="save_button">Save<i class="icon-ok-sign"></i></button>
        <button type="submit" name="save_and_MoveForward" value="1" class="save_button">Time Sheet to ' . $destlabel . '<i class="icon-arrow-right"></i></button>';
        }

        break;

    case 'manager':
        if (
            in_array($current_user->get_id(), $projectManagers)
            || $timeSheet->have_perm(PERM_TIME_APPROVE_TIMESHEETS)
        ) {
            $TPL['timeSheet_ChangeStatusButton'] = '
        <button type="submit" name="save_and_MoveBack" value="1" class="save_button"><i class="icon-arrow-left" style="margin:0px; margin-right:5px"></i>Back</button>
        <button type="submit" name="save" value="1" class="save_button">Save<i class="icon-ok-sign"></i></button>
        <button type="submit" name="save_and_MoveForward" value="1" class="save_button">Time Sheet to Admin<i class="icon-arrow-right"></i></button>';

            $TPL['radio_email'] = $radio_email;
        }

        break;

    case 'admin':
        if ($timeSheet->have_perm(PERM_TIME_INVOICE_TIMESHEETS)) {
            $TPL['timeSheet_ChangeStatusButton'] = '
        <button type="submit" name="save_and_MoveBack" value="1" class="save_button"><i class="icon-arrow-left" style="margin:0px; margin-right:5px"></i>Back</button>
        <button type="submit" name="save" value="1" class="save_button">Save<i class="icon-ok-sign"></i></button>
        <button type="submit" name="save_and_MoveForward" value="1" class="save_button">Time Sheet to Invoiced<i class="icon-arrow-right"></i></button>';

            $TPL['radio_email'] = $radio_email;
        }

        break;

    case 'invoiced':
        if ($timeSheet->have_perm(PERM_TIME_INVOICE_TIMESHEETS)) {
            $TPL['timeSheet_ChangeStatusButton'] = '
        <button type="submit" name="save_and_MoveBack" value="1" class="save_button"><i class="icon-arrow-left" style="margin:0px; margin-right:5px"></i>Back</button>
        <button type="submit" name="save" value="1" class="save_button">Save<i class="icon-ok-sign"></i></button>
        <button type="submit" name="save_and_MoveForward" value="1" class="save_button">Time Sheet Complete<i class="icon-arrow-right"></i></button>';

            $TPL['radio_email'] = $radio_email;
        }

        break;

    case 'finished':
        if ($timeSheet->have_perm(PERM_TIME_INVOICE_TIMESHEETS)) {
            $TPL['timeSheet_ChangeStatusButton'] = '
        <button type="submit" name="save_and_MoveBack" value="1" class="save_button"><i class="icon-arrow-left" style="margin:0px; margin-right:5px"></i>Back</button>';
        }

        break;
}

// Get recipient_tfID
$taggedFund = new tf();
if ('edit' == $timeSheet->get_value('status')) {
    $tf_db = new AllocDatabase();
    $tf_db->query('select preferred_tfID from person where personID = %d', $timeSheet->get_value('personID'));
    $tf_db->next_record();

    if (($preferred_tfID = $tf_db->f('preferred_tfID')) !== '' && ($preferred_tfID = $tf_db->f('preferred_tfID')) !== '0') {
        $tf_db->query('SELECT *
               FROM tfPerson
              WHERE personID = %d
                AND tfID = %d', $timeSheet->get_value('personID'), $preferred_tfID);

        if ($tf_db->next_record()) {        // The person has a preferred TF, and is a tfPerson for it too
            $TPL['recipient_tfID_name'] = $taggedFund->get_name($tf_db->f('tfID'));
            $TPL['recipient_tfID'] = $tf_db->f('tfID');
        }
    } else {
        $TPL['recipient_tfID_name'] = 'No Preferred Payment TF nominated.';
        $TPL['recipient_tfID'] = '';
        $TPL['recipient_tfID_class'] = 'bad';
    }
} else {
    $TPL['recipient_tfID_name'] = $taggedFund->get_name($timeSheet->get_value('recipient_tfID'));
    $TPL['recipient_tfID'] = $timeSheet->get_value('recipient_tfID');
}

$timeSheet->load_pay_info();
if (isset($timeSheet->pay_info['total_customerBilledDollars'])) {
    $TPL['total_customerBilledDollars'] = Page::money($timeSheet->get_value('currencyTypeID'), $timeSheet->pay_info['total_customerBilledDollars'], '%s%m %c');
    config::get_config_item('taxPercent') && ($TPL['ex_gst'] = ' (' . $timeSheet->pay_info['currency'] . $timeSheet->pay_info['total_customerBilledDollars_minus_gst'] . ' excl ' . config::get_config_item('taxPercent') . '% ' . config::get_config_item('taxName') . ')');
}

if (isset($timeSheet->pay_info['total_dollars'])) {
    $TPL['total_dollars'] = Page::money($timeSheet->get_value('currencyTypeID'), $timeSheet->pay_info['total_dollars'], '%s%m %c');
}

$TPL['total_units'] = $timeSheet->pay_info['summary_unit_totals'] ?? '';

if ($timeSheetID) {
    $TPL['period'] = $timeSheet->get_value('dateFrom') . ' to ' . $timeSheet->get_value('dateTo');

    if ('edit' == $timeSheet->get_value('status') && 0 == $db->f('count')) {
        $TPL['message_help'][] = 'Enter Time Sheet Items and click the Add Time Sheet Item Button.';
    } elseif ('edit' == $timeSheet->get_value('status') && $db->f('count') > 0) {
        $TPL['message_help'][] = 'When finished adding Time Sheet Line Items, click the To Manager/Admin button to submit this Time Sheet.';
    }
}

if ($timeSheetID) {
    $TPL['main_alloc_title'] = 'Time Sheet ' . $timeSheet->get_id() . ' - ' . APPLICATION_NAME;
} else {
    $TPL['main_alloc_title'] = 'New Time Sheet - ' . APPLICATION_NAME;
}

$TPL['taxName'] = config::get_config_item('taxName');
$TPL['ts_rate_editable'] = $timeSheet->can_edit_rate();

$TPL['is_manager'] = $timeSheet->have_perm(PERM_TIME_APPROVE_TIMESHEETS);
$TPL['is_admin'] = $timeSheet->have_perm(PERM_TIME_INVOICE_TIMESHEETS);

include_template('templates/timeSheetFormM.tpl');
