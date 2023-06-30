<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

function sort_home_items($a, $b)
{
    return $a->seq > $b->seq;
}

function show_home_items($width, $home_items)
{
    global $TPL;
    $items = [];

    foreach ($home_items as $item) {
        $i = new $item();
        $items[] = $i;
    }

    uasort($items, 'sort_home_items');

    foreach ((array) $items as $item) {
        if ($item->width == $width && $item->visible()) {
            $TPL['item'] = $item;
            if ($item->render()) {
                include_template('templates/homeItemS.tpl');
            }
        }
    }
}

global $modules;
$current_user = &singleton('current_user');
$home_items = [];
foreach ($modules as $module_name => $module) {
    if ($module->home_items) {
        $home_items = array_merge((array) $home_items, $module->home_items);
    }
}

$TPL['home_items'] = $home_items;

if (isset($_POST['tsiHint_item'])) {
    $t = tsiHint::parse_tsiHint_string($_POST['tsiHint_item']);
    if (is_numeric($t['duration']) && $current_user->get_id()) {
        $tsiHint = new tsiHint();
        $tsi_row = $tsiHint->add_tsiHint($t);
        alloc_redirect($TPL['url_alloc_home']);
    } else {
        alloc_error('Time hint not added. No duration set.');
        alloc_error(print_r($t, 1));
    }
}

$TPL['main_alloc_title'] = 'Home Page - ' . APPLICATION_NAME;
$media = $_GET['media'] ?? '';
if ('print' == $media) {
    include_template('templates/homePrintableM.tpl');
} else {
    include_template('templates/homeM.tpl');
}
