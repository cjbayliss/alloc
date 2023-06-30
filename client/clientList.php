<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

$defaults = [
    'url_form_action' => $TPL['url_alloc_clientList'],
    'form_name'       => 'clientList_filter',
];

function show_filter()
{
    global $TPL;
    global $defaults;
    $alphabet_filter = null;
    $clientCategoryOptions = null;
    $clientName = null;
    $clientStatusOptions = null;
    $contactName = null;
    $sessID = null;
    $url_alloc_clientList = null;
    $_FORM = client::load_form_data($defaults);
    $arr = client::load_client_filter($_FORM);
    if (is_array($arr)) {
        $TPL = array_merge($TPL, $arr);
    }

    // FIXME: 😔
    if (is_array($TPL)) {
        extract($TPL, EXTR_OVERWRITE);
    }

    $clientListFilterHelp = (new Page())->help('clientListFilter');
    echo <<<HTML
            <form action="{$url_alloc_clientList}" method="get">
              <table class="filter corner" align="center">
                <tr>
                  <td>&nbsp;</td>
                  <td>Status</td>
                  <td>Client Name</td>
                  <td>Contact Name</td>
                  <td>Category</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><select name="clientStatus[]" multiple="true">{$clientStatusOptions}</select></td>
                  <td><input type="text" name="clientName" value="{$clientName}"></td>
                  <td><input type="text" name="contactName" value="{$contactName}"></td>
                  <td><select name="clientCategory[]" multiple="true">{$clientCategoryOptions}</select></td>
                  <td><button type="submit" name="applyFilter" value="1" class="filter_button">Filter<i class="icon-cogs"></i></button></td>
                  <td>{$clientListFilterHelp}</td> 
                </tr>
                <tr>
                  <td align="center" colspan="6"><nobr>{$alphabet_filter}</nobr></td>
                </tr>
              </table>
            <input type="hidden" name="sessID" value="{$sessID}">
            </form>
        HTML;
}

$_FORM = client::load_form_data($defaults);
$TPL['clientListRows'] = client::get_list($_FORM);

if (!isset($current_user->prefs['clientList_filter'])) {
    $TPL['message_help'][] = '

allocPSA allows you to store pertinent information about your Clients and
the organisations that you interact with. This page allows you to see a list of Clients.

<br><br>

Simply adjust the filter settings and click the <b>Filter</b> button to
display a list of previously created Clients.
If you would prefer to create a new Client, click the <b>New Client</b> link
in the top-right hand corner of the box below.';
}

$TPL['main_alloc_title'] = 'Client List - ' . APPLICATION_NAME;

// FIXME: 😔
if (is_array($TPL)) {
    extract($TPL, EXTR_OVERWRITE);
}

$page = new Page();
$page->header();
$page->toolbar();
$totalRecords = is_countable($clientListRows) ? count($clientListRows) : 0;
$filter = show_filter();
$clientListHTML = (new client())->listHTML($clientListRows, $_FORM);

echo <<<HTML
    <table class="box">
      <tr>
        <th class="header">Clients 
          <b> - {$totalRecords} records</b>
          <span>
            <a class='magic toggleFilter' href=''>Show Filter</a>
            <a href="{$url_alloc_client}">New Client</a>
          </span>  
        </th>
      </tr>
      <tr>
        <td align="center">{$filter}</td>
      </tr>
      <tr>
        <td>
            {$clientListHTML}
        </td>
      </tr>
    </table>
    HTML;
$page->footer();
