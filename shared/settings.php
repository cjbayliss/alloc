<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

if ($_POST['customize_save']) {
    $current_user = &singleton('current_user');
    $current_user->load_prefs();
    $current_user->update_prefs($_POST);
    $current_user->store_prefs();
    alloc_redirect($TPL['url_alloc_home']);
}
