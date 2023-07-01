<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

$current_user = &singleton('current_user');

$calendar = new calendar(2, 20);
$calendar->setPerson($_GET['personID'] ?? $current_user->get_id());
$calendar->setReturnMode('calendar');
$showTaskCalendar = $calendar->draw();

$username = (new person())->get_fullname($_GET['personID'] ?? $current_user->get_id());

$page = new Page();

echo $page->header();
echo $page->toolbar();
echo <<<HTML
        <table class="box">
          <tr>
            <th>Calendar: {$username}</th>
          </tr>
          <tr>
            <td>
              {$showTaskCalendar}
            </td>
          </tr>
        </table>
    HTML;
echo $page->footer();
