<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

if (!have_entity_perm('config', PERM_UPDATE, $current_user, true)) {
    alloc_error('Permission denied.', true);
}

if (!empty($_POST['test_email_gateway'])) {
    $info['host'] = config::get_config_item('allocEmailHost');
    $info['port'] = config::get_config_item('allocEmailPort');
    $info['username'] = config::get_config_item('allocEmailUsername');
    $info['password'] = config::get_config_item('allocEmailPassword');
    $info['protocol'] = config::get_config_item('allocEmailProtocol');

    if (!$info['host']) {
        alloc_error('Email mailbox host not defined, assuming email receive function is inactive.');
    } else {
        $mail = new email_receive($info, $lockfile);
        $mail->open_mailbox(config::get_config_item('allocEmailFolder'));
        $mail->check_mail();
        $TPL['message_good'][] = 'Connection succeeded!';
    }
}

$db = new AllocDatabase();
$db->query('SELECT name,value,type FROM config');
while ($db->next_record()) {
    $fields_to_save[] = $db->f('name');
    $types[$db->f('name')] = $db->f('type');

    if ('text' == $db->f('type')) {
        $TPL[$db->f('name')] = Page::htmlentities($db->f('value'));
    } elseif ('array' == $db->f('type')) {
        $TPL[$db->f('name')] = unserialize($db->f('value'));
    }
}

if (!empty($_POST['update_currencyless_transactions']) && !empty($_POST['currency'])) {
    $db = new AllocDatabase();
    $q = unsafe_prepare("UPDATE transaction SET currencyTypeID = '%s' WHERE currencyTypeID IS NULL", $_POST['currency']);
    $db->query($q);
    $q = unsafe_prepare("UPDATE transactionRepeat SET currencyTypeID = '%s' WHERE currencyTypeID IS NULL", $_POST['currency']);
    $db->query($q);
    $q = unsafe_prepare("UPDATE product SET sellPriceCurrencyTypeID = '%s' WHERE sellPriceCurrencyTypeID IS NULL", $_POST['currency']);
    $db->query($q);
    $q = unsafe_prepare("UPDATE productCost SET currencyTypeID = '%s' WHERE currencyTypeID IS NULL", $_POST['currency']);
    $db->query($q);
    $q = unsafe_prepare("UPDATE productSaleItem SET sellPriceCurrencyTypeID = '%s' WHERE sellPriceCurrencyTypeID IS NULL", $_POST['currency']);
    $db->query($q);
    $q = unsafe_prepare("UPDATE project SET currencyTypeID = '%s' WHERE currencyTypeID IS NULL", $_POST['currency']);
    $db->query($q);
    $q = unsafe_prepare("UPDATE timeSheet SET currencyTypeID = '%s' WHERE currencyTypeID IS NULL", $_POST['currency']);
    $db->query($q);
    $q = unsafe_prepare("UPDATE invoice SET invoice.currencyTypeID = '%s' WHERE invoice.currencyTypeID IS NULL", $_POST['currency']);
    $db->query($q);

    // Update currencyType table too
    $q = unsafe_prepare("UPDATE currencyType SET currencyTypeSeq = 1, currencyTypeActive = true WHERE currencyTypeID = '%s'", $_POST['currency']);
    $db->query($q);
    $_POST['save'] = true;
}

if (!empty($_POST['fetch_exchange_rates'])) {
    $rtn = exchangeRate::download();
    $rtn && ($TPL['message_good'] = $rtn);
}

if (!empty($_POST['save'])) {
    if ($_POST['hoursInDay']) {
        $db = new AllocDatabase();
        $day = $_POST['hoursInDay'] * 60 * 60;
        $q = unsafe_prepare("UPDATE timeUnit SET timeUnitSeconds = '%d' WHERE timeUnitName = 'day'", $day);
        $db->query($q);
        $q = unsafe_prepare("UPDATE timeUnit SET timeUnitSeconds = '%d' WHERE timeUnitName = 'week'", $day * 5);
        $db->query($q);
        $q = unsafe_prepare("UPDATE timeUnit SET timeUnitSeconds = '%d' WHERE timeUnitName = 'month'", ($day * 5) * 4);
        $db->query($q);
    }

    // remove bracketed [Alex Lance <]alla@cyber.com.au[>] bits, leaving just alla@cyber.com.au
    if ($_POST['AllocFromEmailAddress']) {
        $_POST['AllocFromEmailAddress'] = preg_replace('/^.*</', '', $_POST['AllocFromEmailAddress']);
        $_POST['AllocFromEmailAddress'] = str_replace('>', '', $_POST['AllocFromEmailAddress']);
    }

    // Save the companyLogo and a smaller version too.
    if ($_FILES['companyLogo'] && !$_FILES['companyLogo']['error']) {
        $img = image_create_from_file($_FILES['companyLogo']['tmp_name']);
        if ($img) {
            imagejpeg($img, ALLOC_LOGO, 100);
            $x = imagesx($img);
            $y = imagesy($img);
            $save = imagecreatetruecolor($x / ($y / 40), $y / ($y / 40));
            imagecopyresized($save, $img, 0, 0, 0, 0, imagesx($save), imagesy($save), $x, $y);
            imagejpeg($save, ALLOC_LOGO_SMALL, 100);
        }
    }

    foreach ($_POST as $name => $value) {
        if (in_array($name, $fields_to_save)) {
            $id = config::get_config_item_id($name);
            $c = new config();
            $c->set_id($id);
            $c->select();

            if ('text' == $types[$name]) {
                // current special case for the only money field
                if ('defaultTimeSheetRate' == $name) {
                    $value = Page::money(0, $_POST[$name], '%mi');
                    $c->set_value('value', $value);
                } else {
                    $c->set_value('value', $_POST[$name]);
                }

                $TPL[$name] = Page::htmlentities($value);
            } elseif ('array' == $types[$name]) {
                $c->set_value('value', serialize($_POST[$name]));
                $TPL[$name] = $_POST[$name];
            }

            $c->save();
            $TPL['message_good'] = 'Saved configuration.';
        }
    }

    // Handle the only checkbox specially. If more checkboxes are added this
    // should be rewritten.
    // echo var_dump($_POST);
    if ('rss' == $_POST['sbs_link'] && !$_POST['rssShowProject']) {
        $c = new config();
        $c->set_id(config::get_config_item_id('rssShowProject'));
        $c->select();
        $c->set_value('value', '0');
        $c->save();
    }

    $TPL['message'] || ($TPL['message_good'] = 'Saved configuration.');
} elseif (!empty($_POST['delete_logo'])) {
    foreach ([ALLOC_LOGO, ALLOC_LOGO_SMALL] as $logo) {
        if (file_exists($logo) && unlink($logo)) {
            $TPL['message_good'][] = 'Deleted ' . $logo;
        }

        if (file_exists($logo)) {
            alloc_error('Unable to delete ' . $logo);
        }
    }
}

get_cached_table('config', true); // flush cache

if (has('finance')) {
    $tf = new tf();
    $options = $tf->get_assoc_array('tfID', 'tfName');
}

$TPL['mainTfOptions'] = Page::select_options($options, config::get_config_item('mainTfID'));
$TPL['outTfOptions'] = Page::select_options($options, config::get_config_item('outTfID'));
$TPL['inTfOptions'] = Page::select_options($options, config::get_config_item('inTfID'));
$TPL['taxTfOptions'] = Page::select_options($options, config::get_config_item('taxTfID'));
$TPL['expenseFormTfOptions'] = Page::select_options($options, config::get_config_item('expenseFormTfID'));

$tabops = [
    'home'    => 'Home',
    'client'  => 'Clients',
    'project' => 'Projects',
    'task'    => 'Tasks',
    'time'    => 'Time',
    'invoice' => 'Invoices',
    'sale'    => 'Sales',
    'person'  => 'People',
    'inbox'   => 'Inbox',
    'tools'   => 'Tools',
];

($selected_tabops = config::get_config_item('allocTabs')) || ($selected_tabops = array_keys($tabops));
$TPL['allocTabsOptions'] = Page::select_options($tabops, $selected_tabops);

$m = new Meta('currencyType');
$currencyOptions = $m->get_assoc_array('currencyTypeID', 'currencyTypeName');
$TPL['currencyOptions'] = Page::select_options($currencyOptions, config::get_config_item('currency'));

$db = new AllocDatabase();
$display = ['', 'username', ', ', 'emailAddress'];

$person = new person();
$people_by_id = array_column(get_cached_table('person'), 'name', 'personID');

// get the default time sheet manager/admin options
$TPL['defaultTimeSheetManagerListText'] = get_person_list(config::get_config_item('defaultTimeSheetManagerList'), $people_by_id);
$TPL['defaultTimeSheetAdminListText'] = get_person_list(config::get_config_item('defaultTimeSheetAdminList'), $people_by_id);

$days = ['Sun' => 'Sun', 'Mon' => 'Mon', 'Tue' => 'Tue', 'Wed' => 'Wed', 'Thu' => 'Thu', 'Fri' => 'Fri', 'Sat' => 'Sat'];
$TPL['calendarFirstDayOptions'] = Page::select_options($days, config::get_config_item('calendarFirstDay'));

$TPL['timeSheetPrintOptions'] = Page::select_options($TPL['timeSheetPrintOptions'], $TPL['timeSheetPrint']);

$commentTemplate = new commentTemplate();
$ops = $commentTemplate->get_assoc_array('commentTemplateID', 'commentTemplateName');

$TPL['rssStatusFilterOptions'] = Page::select_options(Task::get_task_statii_array(true), config::get_config_item('rssStatusFilter'));

if (has('timeUnit')) {
    $timeUnit = new timeUnit();
    $rate_type_array = $timeUnit->get_assoc_array('timeUnitID', 'timeUnitLabelB');
}

$TPL['timesheetRate_options'] = Page::select_options($rate_type_array, config::get_config_item('defaultTimeSheetUnit'));

$TPL['main_alloc_title'] = 'Setup - ' . APPLICATION_NAME;
include_template('templates/configM.tpl');

function get_person_list(array $person_ids, array $people)
{
    $selected_people = array_intersect_key($people, array_flip($person_ids));

    return [] !== $selected_people ? implode(', ', $selected_people) : '<i>none</i>';
}
