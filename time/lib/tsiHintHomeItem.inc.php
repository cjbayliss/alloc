<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class tsiHintHomeItem extends home_item
{
    public function __construct()
    {
        parent::__construct('tsiHint_edit', 'Time Sheet Item Hint', 'time', 'tsiHintH.tpl', 'narrow', 25);
    }

    public function visible()
    {
        $current_user = &singleton('current_user');
        if (!$current_user->have_role('manage')) {
            return;
        }

        if (!isset($current_user->prefs['showTimeSheetItemHintHome'])) {
            return;
        }

        return true;
    }

    public function render(): bool
    {
        return true;
    }
}
