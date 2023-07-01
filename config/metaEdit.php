<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

/*
 * IMPORTANT: this file is for editing various config items on the config page.
 */

require_once __DIR__ . '/../alloc.php';

$page = new Page();

if (!have_entity_perm('config', PERM_UPDATE, $current_user, true)) {
    alloc_error('Permission denied.', true);
}

$table = $_POST['configName'] ?? $_GET['configName'] ?? '';
$TPL['table'] = $table;

if (isset($_POST['save'])) {
    foreach ((array) $_POST[$table . 'ID'] as $k => $tableID) {
        // Delete
        if (isset($_POST['delete']) && in_array($tableID, (array) $_POST['delete'])) {
            $t = new Meta($table);
            $t->set_id($tableID);
            $t->delete();

            // Save
        } else {
            // FIXME: why is $table . 'Seq' set twice? -- cjb, 2023-07
            $a = [
                $table . 'ID'     => $tableID,
                $table . 'Seq'    => $_POST[$table . 'Seq'][$k] ?? null,
                $table . 'Label'  => $_POST[$table . 'Label'][$k] ?? null,
                $table . 'Name'   => $_POST[$table . 'Name'][$k] ?? null,
                $table . 'Colour' => $_POST[$table . 'Colour'][$k] ?? null,
                $table . 'Seq'    => $_POST[$table . 'Seq'][$k] ?? null,
                'numberToBasic'   => $_POST['numberToBasic'][$k] ?? null,
                // currencyType field
                $table . 'Active' => isset($_POST[$table . 'Active']) ? in_array($tableID, $_POST[$table . 'Active']) : false,
            ];

            $orig_tableID = $_POST[$table . 'IDOrig'][$k] ?? null;
            $t = new Meta($table);
            $t->read_array($a);
            $errs = $t->validate();
            if (!$errs) {
                if ($orig_tableID && $orig_tableID != $tableID) {
                    $a[$table . 'Active'] = in_array($orig_tableID, $_POST[$table . 'Active']);
                    $t->read_array($a);
                    $t->set_id($orig_tableID);
                    $k = new DatabaseField($table . 'ID'); // If the primary key has changed, then it needs special handling.
                    $k->set_value($tableID);        // The primary keys in the referential integrity tables are not
                    $t->data_fields[] = $k;         // usually just auto-incrementing IDs like every other table in alloc
                    $t->update();                   // So we have to trick db_entity into letting us update a primary key.
                } else {
                    $t->save();
                }
            }
        }
    }
}

// TODO: remove global variables
if (is_array($TPL)) {
    extract($TPL, EXTR_OVERWRITE);
}

$page->header();
$page->toolbar();

$t = new meta($table);
$rows = $t->get_list(true);
$label = $t->get_label();

echo <<<HTML
    <form action="{$url_alloc_metaEdit}" method="post">
    <input type="hidden" name="configName" value="{$table}">
    <table class="box">
      <tr>
        <th>{$label}</th>
      </tr>
      <tr>
        <td>

          <table class="list">
            <tr>
              <th>Value</th>
              <th>Sequence</th>
    HTML;

if (isset($t->data_fields[$table . 'Label'])) {
    echo '<th>Label</th>';
}

if (isset($t->data_fields[$table . 'Name'])) {
    echo '<th>Name</th>';
}

if (isset($t->data_fields[$table . 'Colour'])) {
    echo '<th>Colour</th>';
}

if (isset($t->data_fields['numberToBasic'])) {
    echo '<th>No. to basic</th>';
}

echo <<<'HTML'
              <th colspan="2">Active</th>
              <th class="right">
                <a href="#x" class="magic" onClick="$('#rows_footer').before('<tr>'+$('#row').html()+'</tr>');">New</a>
              </th>
            </tr>
    HTML;

foreach ((array) $rows as $row) {
    echo <<<HTML
                <tr>
                  <td>
                    <input type="text" name="{$table}ID[]" size="20" value="{$row[$table . 'ID']}">
                    <input type="hidden" name="{$table}IDOrig[]" size="20" value="{$row[$table . 'ID']}">
                  </td>
                  <td><input type="text" name="{$table}Seq[]" size="20" value="{$row[$table . 'Seq']}"></td>
        HTML;
    if (isset($t->data_fields[$table . 'Label'])) {
        echo <<<HTML
                        <td><input type="text" name="{$table}Label[]" size="20" value="{$row[$table . 'Label']}"></td>
            HTML;
    }

    if (isset($t->data_fields[$table . 'Name'])) {
        echo <<<HTML
                        <td><input type="text" name="{$table}Name[]" size="20" value="{$row[$table . 'Name']}"></td>
            HTML;
    }

    if (isset($t->data_fields[$table . 'Colour'])) {
        echo <<<HTML
                        <td><input type="text" name="{$table}Colour[]" size="20" value="{$row[$table . 'Colour']}"></td>
            HTML;
    }

    if (isset($t->data_fields['numberToBasic'])) {
        echo <<<HTML
                        <td><input type="text" name="numberToBasic[]" size="20" value="{$row['numberToBasic']}"></td>
            HTML;
    }

    unset($checked);
    $row[$table . 'Active'] && ($checked = ' checked');
    echo <<<HTML
                  <td colspan="2"><input type="checkbox" name="{$table}Active[]" size="20" value="{$row[$table . 'ID']}"{$checked}></td>
                  <td class="right nobr">
                    <input type="checkbox" name=delete[] value="{$row[$table . 'ID']}" id="delete{$row[$table . 'ID']}">
                    <label for="delete{$row[$table . 'ID']}"> Delete</label>
                  </td>
                </tr>
        HTML;
}

echo <<<HTML
            <tr id="row" class="hidden">
              <td>
                <input type="text" name="{$table}ID[]" size="20" value="">
              </td>
              <td><input type="text" name="{$table}Seq[]" size="20" value=""></td>
    HTML;

if (isset($t->data_fields[$table . 'Label']) ?? '') {
    echo '<td><input type="text" name="' . $table . 'Label[]" size="20" value=""></td>';
}

if (isset($t->data_fields[$table . 'Name']) ?? '') {
    echo '<td><input type="text" name="' . $table . 'Name[]" size="20" value=""></td>';
}

if (isset($t->data_fields[$table . 'Colour']) ?? '') {
    echo '<td><input type="text" name="' . $table . 'Colour[]" size="20" value=""></td>';
}

if (isset($t->data_fields['numberToBasic']) ?? '') {
    echo '<td><input type="text" name="numberToBasic[]" size="20" value=""></td>';
}

echo <<<HTML
              <td colspan="2">
              <!-- <input type="checkbox" name="{$table}Active[]" size="20" value="1"> -->
              </td>
              <td class="right nobr">
              </td>
            </tr>

            <tr id="rows_footer">
              <th colspan="50" class="center">
                <input type="submit" name="save" value="Save">
              </th>
            </tr>

          </table>

        </td>
      </tr>
    </table>
    <input type="hidden" name="sessID" value="{$sessID}">
    </form>
    HTML;

$page->footer();
