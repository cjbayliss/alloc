<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

/*
 * IMPORTANT: this file is for editing various config items on the config page.
 * it will create either an 'array' type configEdit.php page, or a 'people' type
 * configEdit.php page.
 */

require_once __DIR__ . '/../alloc.php';

$page = new Page();
$person = new person();

if (!have_entity_perm('config', PERM_UPDATE, $current_user, true)) {
    alloc_error('Permission denied.', true);
}

$configName = $_POST['configName'] ?? $_GET['configName'] ?? '';
$TPL['configName'] = $configName;

$configType = $_POST['configType'] ?? $_GET['configType'] ?? 'array';
$TPL['configType'] = $configType;

if ($configName) {
    $config = new config();
    $id = config::get_config_item_id($configName);
    $config->set_id($id);
    $config->select();
}

if (isset($_POST['save'])) {
    if ('people' == $configType) {
        $arr = config::get_config_item($configName);
        if (!in_array($_POST['value'], $arr)) {
            $arr[] = $_POST['value'];
            $config->set_value('value', serialize($arr));
            $config->save();
        }
    } else {
        $arr = config::get_config_item($configName);
        $arr[$_POST['key']] = $_POST['value'];
        $config->set_value('value', serialize($arr));
        $config->save();
    }
} elseif (isset($_POST['delete'])) {
    $arr = config::get_config_item($configName);
    if ('people' == $configType) {
        unset($arr[array_search($_POST['value'], $arr, true)]);
    } else {
        unset($arr[$_POST['key']]);
    }

    $config->set_value('value', serialize($arr));
    $config->save();
}

// TODO: remove global variables
if (is_array($TPL)) {
    extract($TPL, EXTR_OVERWRITE);
}

$page->header();
$page->toolbar();

if ('people' == $configName || 'people' == $configType) {
    $configItemHTML = '';
    $rows = config::get_config_item($configName, true) ?? [];
    foreach ($rows as $key => $value) {
        $configItemHTML .= <<<HTML
            <tr>
                <td>
                    <form action="{$url_alloc_configEdit}" method="post">
                        <table>
                            <tr>
                                <td></td>
                                <td>
                                    {$person->get_fullname($value)}
                                </td>

                                <td>
                                    <input type="hidden" name="value" value="{$value}">
                                    <input type="submit" name="delete" value="Delete">
                                    <input type="hidden" name="configName" value="{$configName}">
                                    <input type="hidden" name="configType" value="{$configType}">
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="sessID" value="{$sessID}">
                    </form>
                </td>
            </tr>
            HTML;
    }

    echo <<<HTML
        <table class="box">
            <tr>
                <th colspan="4">Setup Item Edit</th>
            </tr>
            {$configItemHTML}
            <tr>
                <td>
                    <form action="{$url_alloc_configEdit}" method="post">
                        <table>
                            <tr>
                                <td></td>
                                <td>
                                    <select name="value">
                                         {$page->select_options($person->get_username_list())};
                                    </select>
                                <td>
                                    <input type="submit" name="save" value="Add">
                                    <input type="hidden" name="configName" value="{$configName}">
                                    <input type="hidden" name="configType" value="{$configType}">
                                </td>
                            </tr>
                            <tr></tr>
                        </table>
                        <input type="hidden" name="sessID" value="{$sessID}">
                    </form>
                </td>
            </tr>
        </table> 
        HTML;
} else {
    $configItemHTML = '';
    $rows = config::get_config_item($configName, true) ?? [];
    foreach ($rows as $key => $value) {
        if (is_array($value) && !isset($count_array)) {
            $count_array = $value;
        }

        $configInputItemHTML = '';
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $configInputItemHTML .= <<<HTML
                    <td><input type="text" name="value[{$k}]" size="20" value="{$v}">&nbsp;&nbsp;</td>
                    HTML;
            }
        } else {
            $configInputItemHTML .= '<td><input type="text" name="value" size="20" value="' . $value . '"></td>';
        }

        $configItemHTML .= <<<HTML
            <tr>
                <td>
                    <form action="{$url_alloc_configEdit}" method="post">
                        <table>
                            <tr>
                                <td></td><td><input type="text" name="key" size="20" value="{$key}"></td>
                                {$configInputItemHTML}
                                <td>
                                    <input type="submit" name="delete" value="Delete" class="delete_button">
                                    <input type="submit" name="save" value="Save" class="default">
                                    <input type="hidden" name="configName" value="{$configName}">
                                </td>
                            </tr>
                        </table>
                        <input type="hidden" name="sessID" value="{$sessID}">
                    </form>
                  </td>
            </tr>
            HTML;
    }

    $newConfigInputItemHTML = '';
    if (is_array($value)) {
        foreach ($count_array as $value_k => $blah) {
            $newConfigInputItemHTML .= <<<HTML
                <td>
                  <input type="text" name="value[{$value_k}]" size="20">&nbsp;&nbsp;
                </td>
                HTML;
        }
    } else {
        $newConfigInputItemHTML = '<td><input type="text" name="value" size="20"></td>';
    }

    echo <<<HTML
        <table class="box">
            <tr>
                <th colspan="4">Setup Item Edit</th>
            </tr>
                {$configItemHTML}
            <tr>
                <td>
                    <form action="{$url_alloc_configEdit}" method="post">
                        <table>
                            <tr>
                                <td></td><td><input type="text" name="key" size="20"></td>
                                {$newConfigInputItemHTML}
                                <td>
                                    <input type="submit" name="save" value="Save">
                                    <input type="hidden" name="configName" value="{$configName}">
                                </td>
                            </tr>
                            <tr></tr>
                        </table>
                        <input type="hidden" name="sessID" value="{$sessID}">
                    </form>
                </td>
            </tr>
        </table>
        HTML;
}

$page->footer();
