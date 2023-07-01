<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';
$page = new Page();
$config = new config();

function get_person_list(array $person_ids, array $people)
{
    $selected_people = array_intersect_key($people, array_flip($person_ids));

    return [] !== $selected_people ? implode(', ', $selected_people) : '<i>none</i>';
}

// this function used to be part of util.inc.php, but wasn't used anywhere else
function getTimezoneOffsetList(): array
{
    $timezones = timezone_identifiers_list();
    $positiveTimezonesWithOffsets = [];
    $negativeTimezonesWithOffsets = [];
    $currentDateTime = new DateTime();

    // $index is used to make the list order correct once it goes through
    // (new Page())->select()
    $index = 0;
    foreach ($timezones as $timezone) {
        // Get the offset from GMT in seconds
        $offsetInSeconds = (new DateTimeZone($timezone))->getOffset($currentDateTime);

        // Determine the sign of the offset
        $sign = $offsetInSeconds < 0 ? '-' : '+';

        // Format the offset into the format "+HH:MM"
        $formattedOffset = sprintf('%s%02d:%02d', $sign, abs($offsetInSeconds) / 3600, abs($offsetInSeconds) % 3600 / 60);

        // Add the timezone and its formatted offset to the array
        if ($offsetInSeconds < 0) {
            $negativeTimezonesWithOffsets[$offsetInSeconds * 10_000 + $index++] = [$timezone, $formattedOffset . ' ' . $timezone];
        } else {
            $positiveTimezonesWithOffsets[$offsetInSeconds * 10_000 + $index++] = [$timezone, $formattedOffset . ' ' . $timezone];
        }
    }

    // Sort the arrays by keys (offsets)
    ksort($positiveTimezonesWithOffsets);
    krsort($negativeTimezonesWithOffsets);

    // Merge the two parts
    $timezonesWithOffsets = [...$negativeTimezonesWithOffsets, ...$positiveTimezonesWithOffsets];
    $finalList = [];
    foreach ($timezonesWithOffsets as $timezoneWithOffset) {
        $finalList[$timezoneWithOffset[0]] = $timezoneWithOffset[1];
    }

    return $finalList;
}

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

if (!empty($_POST['save'])) {
    if (!empty($_POST['hoursInDay'])) {
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
    if (!empty($_POST['AllocFromEmailAddress'])) {
        $_POST['AllocFromEmailAddress'] = preg_replace('/^.*</', '', $_POST['AllocFromEmailAddress']);
        $_POST['AllocFromEmailAddress'] = str_replace('>', '', $_POST['AllocFromEmailAddress']);
    }

    // Save the companyLogo and a smaller version too.
    if (!empty($_FILES['companyLogo']) && !$_FILES['companyLogo']['error']) {
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
        if (0 === $name) {
            continue;
        }

        if ('' === $name) {
            continue;
        }

        if (in_array($name, $fields_to_save)) {
            $id = config::get_config_item_id($name);
            $c = new config();
            $c->set_id($id);
            $c->select();

            if ('text' == $types[$name]) {
                if (empty($value)) {
                    continue;
                }

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
    if (!empty($_POST['sbs_link']) && 'rss' == $_POST['sbs_link'] && !$_POST['rssShowProject']) {
        $c = new config();
        $c->set_id(config::get_config_item_id('rssShowProject'));
        $c->select();
        $c->set_value('value', '0');
        $c->save();
    }

    $TPL['message'] ?? $TPL['message_good'] = 'Saved configuration.';
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

// TODO: remove global variables
if (is_array($TPL)) {
    extract($TPL, EXTR_OVERWRITE);
}

echo $page->header();
echo $page->toolbar();

echo $page->side_by_side_links(
    $url_alloc_config,
    [
        'basic'         => 'Basic Setup',
        'company_info'  => 'Company Info',
        'finance'       => 'Finance',
        'time_sheets'   => 'Time Sheets',
        'email_gateway' => 'Email Gateway',
        'email_subject' => 'Email Subject Lines',
        'rss'           => 'RSS Feed',
        'misc'          => 'Miscellaneous',
    ],
    true
);

$toChecked = ('to' == $allocEmailAddressMethod) ? ' checked' : '';
$bccChecked = ('bcc' == $allocEmailAddressMethod) ? ' checked' : '';
$tobccChecked = ('tobcc' == $allocEmailAddressMethod) ? ' checked' : '';
$rssShowProjectChecked = isset($rssShowProject) ? 'checked' : '';
$companyLogoDeletable = file_exists(ALLOC_LOGO) ? '<input type="submit" name="delete_logo" value="Delete Current Logo">' : '';

$defaultInterestedPartiesHTML = '';
$br = '';
foreach ($defaultInterestedParties as $k => $v) {
    $defaultInterestedPartiesHTML .= $br . $k . ' ' . $v;
    $br = ', ';
}

$projectPrioritiesHTML = '';
$br = '';
foreach ($projectPriorities as $k => $arr) {
    $projectPrioritiesHTML .= $br . '<span style="color:' . $arr['colour'] . '">' . $k . ' ' . $arr['label'] . '</span>';
    $br = ', ';
}

$taskPrioritiesHTML = '';
$br = '';
foreach ($taskPriorities as $k => $arr) {
    $taskPrioritiesHTML .= $br . '<span style="color:' . $arr['colour'] . '">' . $k . ' ' . $arr['label'] . '</span>';
    $br = ', ';
}

$clientCategoriesHTML = '';
$br = '';
foreach ($clientCategories as $k => $arr) {
    $clientCategoriesHTML .= $br . $arr['label'];
    $br = ', ';
}

$metaTablesHTML = '';
$meta = new meta();
foreach ((array) $meta->get_tables() as $table => $label) {
    $tableHTML = '';
    $br = '';
    $t = new meta($table);
    $rows = $t->get_list();
    foreach ($rows as $row) {
        $tableHTML .= $br . $row[$table . 'ID'];
        $br = ', ';
    }

    $metaTablesHTML .= <<<HTML
          <tr>
            <td>{$label}</td>
            <td>
            <a href="{$url_alloc_metaEdit}configName={$table}">Edit:</a>
            {$tableHTML}
            </td>
          </tr>
        HTML;
}

echo <<<HTML
    <div id="basic">
    <form action="{$url_alloc_config}" method="post">
    <table class="box">

      <tr>
        <th colspan="3">Basic Setup</th>
      </tr>

      <tr>
        <td width="20%"><nobr>allocPSA Tabs</nobr></td>
        <td><select name="allocTabs[]" multiple>{$allocTabsOptions}</select></td>
        <td width="1%">{$page->help('config_allocTabs')}</td>
      </tr>

      <tr>
        <td width="20%"><nobr>allocPSA Base URL</nobr></td>
        <td><input type="text" size="70" value="{$allocURL}" name="allocURL"></td>
        <td width="1%">{$page->help('config_allocURL')}</td>
      </tr>

      <tr>
        <td width="20%"><nobr>Time Zone</nobr></td>
        <td><select name="allocTimezone">{$page->select_options(getTimezoneOffsetList(), $config->get_config_item('allocTimezone'))}</select></td>
        <td width="1%">{$page->help('config_allocTimezone')}</td>
      </tr>

      <tr>
        <td width="20%"><nobr>Calendar 1st Day</nobr></td>
        <td><select name="calendarFirstDay">{$calendarFirstDayOptions}</select></td>
        <td width="1%">{$page->help('config_calendarFirstDay')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Adminstrator Email Address</nobr></td>
        <td><input type="text" size="70" value="{$allocEmailAdmin}" name="allocEmailAdmin"></td>
        <td width="1%">{$page->help('config_allocEmailAdmin')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Email Addressing Method</nobr></td>
        <td>
          <label for="eam_to">Use "To:"</label><input id="eam_to" type="radio" name="allocEmailAddressMethod" value="to"{$toChecked}>&nbsp;&nbsp;&nbsp;&nbsp;
          <label for="eam_bcc">Use "Bcc:"</label><input id="eam_bcc" type="radio" name="allocEmailAddressMethod" value="bcc"{$bccChecked}>&nbsp;&nbsp;&nbsp;&nbsp;
          <label for="eam_tobcc">Use Both with special "To:"</label><input id="eam_tobcc" type="radio" name="allocEmailAddressMethod" value="tobcc"{$tobccChecked}>
        </td>
        <td width="1%">{$page->help('config_allocEmailAddressMethod')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Session Timeout Minutes</nobr></td>
        <td><input type="text" size="70" value="{$allocSessionMinutes}" name="allocSessionMinutes"></td>
        <td width="1%">{$page->help('config_allocSessionMinutes')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Task Window Num Days</nobr></td>
        <td><input type="text" size="70" value="{$taskWindow}" name="taskWindow"></td>
        <td width="1%">{$page->help('config_taskWindow')}</td>
      </tr>
      <tr>
        <td colspan="3" align="center"><input type="submit" name="save" value="Save"></td>
      </tr>
    </table>
    <input type="hidden" name="sessID" value="{$sessID}">
    </form>
    </div>

    <div id="finance">
    <form action="{$url_alloc_config}" method="post">
    <table class="box">
      <tr>
        <th colspan="3">Finance Setup</th>
      </tr>
      <tr>
        <td width="20%">Main Currency</td>
        <td><select name="currency">{$currencyOptions}</select><input type="submit" name="update_currencyless_transactions" value="Update Transactions That Have No Currency"></td>
        <td width="1%">{$page->help('config_currency')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Finance Tagged Fund</nobr></td>
        <td><select name="mainTfID"><option value="">{$mainTfOptions}</select></td>
        <td width="1%">{$page->help('config_mainTfID')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Outgoing Funds TF</nobr></td>
        <td><select name="outTfID"><option value="">{$outTfOptions}</select></td>
        <td width="1%">{$page->help('config_outTfID')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Incoming Funds TF</nobr></td>
        <td><select name="inTfID"><option value="">{$inTfOptions}</select></td>
        <td width="1%">{$page->help('config_inTfID')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Expense Form TF</nobr></td>
        <td><select name="expenseFormTfID"><option value="">{$expenseFormTfOptions}</option></td>
        <td width="1%">{$page->help('config_expenseFormTfID')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Tax Tagged Fund</nobr></td>
        <td><select name="taxTfID"><option value="">{$taxTfOptions}</select></td>
        <td width="1%">{$page->help('config_taxTfID')}</td>
      </tr>
      <tr>
        <td>Services Tax Name</td>
        <td><input type="text" size="70" value="{$taxName}" name="taxName"></td>
        <td width="1%">{$page->help('config_taxName')}</td>
      </tr>
      <tr>
        <td>Services Tax Percent</td>
        <td><input type="text" size="70" value="{$taxPercent}" name="taxPercent"></td>
        <td width="1%">{$page->help('config_taxPercent')}</td>
      </tr>
      <tr>
        <td colspan="3" align="center">
          <input type="submit" name="save" value="Save">
        </td>
      </tr>
    </table>
    <input type="hidden" name="sbs_link" value="finance">
    <input type="hidden" name="sessID" value="{$sessID}">
    </form>
    </div>

    <div id="email_gateway">
    <form action="{$url_alloc_config}" method="post">
    <table class="box">
      <tr>
        <th colspan="2">Email Gateway</th>
        <th class="right">{$page->help('config_allocEmailGateway')}</th>
      </tr>
      <tr>
        <td width="20%"><nobr>From Address</nobr></td>
        <td><input type="text" size="70" value="{$AllocFromEmailAddress}" name="AllocFromEmailAddress"></td>
        <td width="1%">{$page->help('config_AllocFromEmailAddress')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Mail Server Hostname/IP</nobr></td>
        <td><input type="text" size="70" value="{$allocEmailHost}" name="allocEmailHost"></td>
        <td width="1%">{$page->help('config_allocEmailHost')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Mail Server Port</nobr></td>
        <td><input type="text" size="70" value="{$allocEmailPort}" name="allocEmailPort"></td>
        <td width="1%">{$page->help('config_allocEmailPort')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Mail Server Username</nobr></td>
        <td><input type="text" size="70" value="{$allocEmailUsername}" name="allocEmailUsername"></td>
        <td width="1%">{$page->help('config_allocEmailUsername')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Mail Server Password</nobr></td>
        <td><input type="password" size="70" value="{$allocEmailPassword}" name="allocEmailPassword"></td>
        <td width="1%">{$page->help('config_allocEmailPassword')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Mail Protocol</nobr></td>
        <td><select name="allocEmailProtocol">{$page->select_options(['imap' => 'IMAP', 'pop3' => 'POP3'], $allocEmailProtocol)}</select></td>
        <td width="1%">{$page->help('config_allocEmailProtocol')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Mail Box Name</nobr></td>
        <td><input type="text" size="70" value="{$allocEmailFolder}" name="allocEmailFolder"></td>
        <td width="1%">{$page->help('config_allocEmailFolder')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Email Connect Extra</nobr></td>
        <td><input type="text" size="70" value="{$allocEmailExtra}" name="allocEmailExtra"></td>
        <td width="1%">{$page->help('config_allocEmailExtra')}</td>
      </tr>
      <tr>
        <td colspan="3" align="center">
          <input type="submit" name="save" value="Save">
          <input type="submit" name="test_email_gateway" value="Test Connection">
        </td>
      </tr>
    </table>
    <input type="hidden" name="sbs_link" value="email_gateway">
    <input type="hidden" name="sessID" value="{$sessID}">
    </form>
    </div>

    <div id="email_subject">
    <form action="{$url_alloc_config}" method="post">
    <table class="box">
      <tr>
        <th colspan="2">Email Subject Lines</th>
        <th class="right">{$page->help('config_allocEmailSubject')}</th>
      </tr>
      <tr>
        <td width="20%"><nobr>Task Comments</nobr></td>
        <td><input type="text" size="70" value="{$emailSubject_taskComment}" name="emailSubject_taskComment"></td>
        <td width="1%">{$page->help('config_taskSubjectLine')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Daily Digest</nobr></td>
        <td colspan="2"><input type="text" size="70" value="{$emailSubject_dailyDigest}" name="emailSubject_dailyDigest"></td>
      </tr>
      <tr>
        <td width="20%"><nobr>Time sheet submitted to manager</nobr></td>
        <td><input type="text" size="70" value="{$emailSubject_timeSheetToManager}" name="emailSubject_timeSheetToManager"></td>
        <td width="1%">{$page->help('config_timeSheetSubjectLine')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Time sheet rejected by manager</nobr></td>
        <td colspan="2"><input type="text" size="70" value="{$emailSubject_timeSheetFromManager}" name="emailSubject_timeSheetFromManager"></td>
      </tr>
      <tr>
        <td width="20%"><nobr>Time sheet submitted to administrator</nobr></td>
        <td colspan="2"><input type="text" size="70" value="{$emailSubject_timeSheetToAdministrator}" name="emailSubject_timeSheetToAdministrator"></td>
      </tr>
      <tr>
        <td width="20%"><nobr>Time sheet rejected by administrator</nobr></td>
        <td colspan="2"><input type="text" size="70" value="{$emailSubject_timeSheetFromAdministrator}" name="emailSubject_timeSheetFromAdministrator"></td>
      </tr>
      <tr>
        <td width="20%"><nobr>Time sheet completed</nobr></td>
        <td colspan="2"><input type="text" size="70" value="{$emailSubject_timeSheetCompleted}" name="emailSubject_timeSheetCompleted"></td>
      </tr>
      <tr>
        <td width="20%"><nobr>Reminder about a client</nobr></td>
        <td><input type="text" size="70" value="{$emailSubject_reminderClient}" name="emailSubject_reminderClient"></td>
        <td width="1%">{$page->help('config_clientSubjectLine')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Reminder about a project</nobr></td>
        <td><input type="text" size="70" value="{$emailSubject_reminderProject}" name="emailSubject_reminderProject"></td>
        <td width="1%">{$page->help('config_projectSubjectLine')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Reminder about a task</nobr></td>
        <td><input type="text" size="70" value="{$emailSubject_reminderTask}" name="emailSubject_reminderTask"></td>
        <td width="1%">{$page->help('config_taskSubjectLine')}</td>
      </tr>
      <tr>
        <td width="20%"><nobr>Other reminder</nobr></td>
        <td colspan="2"><input type="text" size="70" value="{$emailSubject_reminderOther}" name="emailSubject_reminderOther"></td>
      </tr>
      <tr>
        <td colspan="3" align="center"><input type="submit" name="save" value="Save"></td>
      </tr>
    </table>
    <input type="hidden" name="sbs_link" value="email_subject">
    <input type="hidden" name="sessID" value="{$sessID}">
    </form>
    </div>

    <div id="time_sheets">
    <form action="{$url_alloc_config}" method="post">
    <table class="box">
      <tr>
        <th colspan="3">Time Sheets Setup</th>
      </tr>
      <tr>
        <td>Time Sheet Manager</td>
        <td><a href="{$url_alloc_configEdit}configName=defaultTimeSheetManagerList&amp;configType=people">Edit:</a>
        {$defaultTimeSheetManagerListText}
        </td>
        <td width="1%">{$page->help('config_timeSheetManagerEmail')}</td>
      </tr>
      <tr>
        <td>Time Sheet Administrator</td>
        <td><a href="{$url_alloc_configEdit}configName=defaultTimeSheetAdminList&amp;configType=people">Edit:</a>
        {$defaultTimeSheetAdminListText}
        </td>
        <td width="1%">{$page->help('config_timeSheetAdminEmail')}</td>
      </tr>
      <tr>
        <td>Hours in a Working Day</td>
        <td><input type="text" size="70" value="{$hoursInDay}" name="hoursInDay"></td>
        <td width="1%">{$page->help('config_hoursInDay')}</td>
      </tr>
      <tr>
        <td>Default timesheet rate</td>
        <td><input type="text" size="70" value="{$page->money(0, $defaultTimeSheetRate, '%mo')}" name="defaultTimeSheetRate"></td>
        <td width="1%"></td>
      </tr>
      <tr>
        <td>Default timesheet unit</td>
        <td><select name="defaultTimeSheetUnit"><option value="">{$timesheetRate_options}</select></td>
        <td width="1%"></td>
      </tr>
      <tr>
        <td valign="top">Time Sheet Print Options</td>
        <td><select size="9" name="timeSheetPrint[]" multiple><option value="">{$timeSheetPrintOptions}</select><a href="{$url_alloc_configEdit}configName=timeSheetPrintOptions">Edit</a></td>
        <td width="1%" valign="top">{$page->help('config_timeSheetPrint')}</td>
      </tr>
      <tr>
        <td colspan="3" align="center"><input type="submit" name="save" value="Save"></td>
      </tr>
    </table>
    <input type="hidden" name="sbs_link" value="time_sheets">
    <input type="hidden" name="sessID" value="{$sessID}">
    </form>
    </div>

    <div id="company_info">
    <form action="{$url_alloc_config}" method="post" enctype="multipart/form-data">
    <table class="box">
      <tr>
        <th colspan="2">Company Information</th>
        <th width="1%">{$page->help('config_companyInfo')}</th>
      </tr>
      <tr>
        <td width="20%">Company Name</td>
        <td><input type="text" size="70" value="{$companyName}" name="companyName"></td>
      </tr>
      <tr>
        <td>Company Phone</td>
        <td><input type="text" size="70" value="{$companyContactPhone}" name="companyContactPhone"></td>
      </tr>
      <tr>
        <td>Company Fax</td>
        <td><input type="text" size="70" value="{$companyContactFax}" name="companyContactFax"></td>
      </tr>
      <tr>
        <td>Company Email</td>
        <td><input type="text" size="70" value="{$companyContactEmail}" name="companyContactEmail"></td>
      </tr>
      <tr>
        <td>Company Home Page</td>
        <td><input type="text" size="70" value="{$companyContactHomePage}" name="companyContactHomePage"></td>
      </tr>
      <tr>
        <td>Company Address (line 1)</td>
        <td><input type="text" size="70" value="{$companyContactAddress}" name="companyContactAddress"></td>
      </tr>
      <tr>
        <td>Company Address (line 2)</td>
        <td><input type="text" size="70" value="{$companyContactAddress2}" name="companyContactAddress2"></td>
      </tr>
      <tr>
        <td>Company Address (line 3)</td>
        <td><input type="text" size="70" value="{$companyContactAddress3}" name="companyContactAddress3"></td>
      </tr>
      <tr>
        <td>Company Logo</td>
        <td>
          <input type="file" name="companyLogo" size="70">
          {$companyLogoDeletable}
        </td>
        <td width="1%">{$page->help('config_companyLogo')}</td>
      </tr>
      <tr>
        <td>Invoice / Time Sheet PDF Header 2</td>
        <td><input type="text" size="70" value="{$companyACN}" name="companyACN"></td>
      </tr>
      <tr>
        <td>Invoice / Time Sheet PDF Header 3</td>
        <td><input type="text" size="70" value="{$companyABN}" name="companyABN"></td>
      </tr>
      <tr>
        <td>Invoice / Time Sheet PDF Footer</td>
        <td><input type="text" size="70" value="{$timeSheetPrintFooter}" name="timeSheetPrintFooter"></td>
        <td width="1%">{$page->help('config_timeSheetPrintFooter')}</td>
      </tr>
      <tr>
        <td colspan="3" align="center"><input type="submit" name="save" value="Save"></td>
      </tr>
    </table>
    <input type="hidden" name="sbs_link" value="company_info">
    <input type="hidden" name="sessID" value="{$sessID}">
    </form>
    </div>

    <div id="rss">
    <form action="{$url_alloc_config}" method="post">
    <table class="box">
      <tr>
        <th colspan="2">RSS Feed Setup</th>
        <th>{$page->help('config_rssFeed')}</th>
      </tr>
      <tr>
        <td>Number of entries</td>
        <td><input type="text" size="70" value="{$rssEntries}" name="rssEntries"></td>
        <td width="1%">{$page->help('config_rssEntries')}</tr>
      </tr>
      <tr>
        <td>Status changes to include</td>
        <td><select size="9" name="rssStatusFilter[]" multiple>{$rssStatusFilterOptions}</select></td>
        <td width="1%">{$page->help('config_rssStatusFilter')}</td>
      <tr>
        <td>Show project name in feed</td>
        <td><input type="checkbox" name="rssShowProject" checked="{$rssShowProjectChecked}"></td>
        <td></td>
      <tr>
        <td colspan="3" align="center"><input type="submit" name="save" value="Save"></td>
      </tr>
    </table>
    <input type="hidden" name="sbs_link" value="rss">
    <input type="hidden" name="sessID" value="{$sessID}">
    </form>
    </div>
    <div id="misc">
    <form action="{$url_alloc_config}" method="post">
    <table class="box">
      <tr>
        <th colspan="2">Miscellaneous Setup</th>
        <th width="1%">{$page->help('config_misc_setup')}</th>
      </tr>
      <tr>
        <td valign="top" width="20%"><nobr>Extra Interested Parties Options</nobr></td>
        <td>
          <a href="{$url_alloc_configEdit}configName=defaultInterestedParties">Edit:</a>
          {$defaultInterestedPartiesHTML}
        </td>
        <td width="1%">{$page->help('config_defaultInterestedParties.html')}</td>
      </tr>
      <tr>
        <td valign="top" width="20%"><nobr>Project Priorities</nobr></td>
        <td>
          <a href="{$url_alloc_configEdit}configName=projectPriorities">Edit:</a>
          {$projectPrioritiesHTML}
        </td>
        <td width="1%">{$page->help('config_projectPriorities.html')}</td>
      </tr>
      <tr>
        <td valign="top" width="20%"><nobr>Task Priorities</nobr></td>
        <td>
          <a href="{$url_alloc_configEdit}configName=taskPriorities">Edit:</a>
          {$taskPrioritiesHTML}
        </td>
        <td width="1%">{$page->help('config_taskPriorities.html')}</td>
      </tr>
      <tr>
        <td valign="top" width="20%"><nobr>Client Categories</nobr></td>
        <td>
          <a href="{$url_alloc_configEdit}configName=clientCategories">Edit:</a>
          {$clientCategoriesHTML}
        </td>
        <td width="1%">{$page->help('config_clientCategories.html')}</td>
      </tr>

      {$metaTablesHTML}
      <tr>
        <td>Map URL</td>
        <td><input type="text" size="70" value="{$mapURL}" name="mapURL"></td>
        <td width="1%">{$page->help('config_mapURL')}</td>
      </tr>
      <tr>
        <td>Task Priority Spread</td>
        <td><input type="text" size="70" value="{$taskPrioritySpread}" name="taskPrioritySpread"></td>
        <td width="1%">{$page->help('config_taskPrioritySpread')}</td>
      </tr>
      <tr>
        <td>Task Priority Scale</td>
        <td><input type="text" size="70" value="{$taskPriorityScale}" name="taskPriorityScale"></td>
        <td width="1%">{$page->help('config_taskPriorityScale')}</td>
      </tr>
      <tr>
        <td>SQL Debug in Page Footer</td>
        <td><input type="text" size="70" value="{$sqlDebug}" name="sqlDebug"></td>
        <td width="1%">{$page->help('config_sqlDebug')}</td>
      </tr>
      <tr>
        <td colspan="3" align="center"><input type="submit" name="save" value="Save"></td>
      </tr>
    </table>
    <input type="hidden" name="sbs_link" value="misc">
    <input type="hidden" name="sessID" value="{$sessID}">
    </form>
    </div>
    HTML;

echo $page->footer();
