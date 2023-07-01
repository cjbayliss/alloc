<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class TimeSheetHomeItem extends HomeItem
{
    public function __construct()
    {
        parent::__construct(
            'time_edit',
            'New Time Sheet Item',
            'time',
            'narrow',
            24,
        );
    }

    public function visible(): bool
    {
        $current_user = &singleton('current_user');

        if (!isset($current_user->prefs['showTimeSheetItemHome'])) {
            $current_user->prefs['showTimeSheetItemHome'] = 1;
        }

        return (bool) $current_user->prefs['showTimeSheetItemHome'];
    }

    public function render(): bool
    {
        return true;
    }

    public function getHTML(): string
    {
        $page = new Page();
        $allocUpdateTimeSheetURL = $page->getURL('url_alloc_updateTimeSheetHome');
        $allocHomeURL = $page->getURL('url_alloc_home');

        return <<<HTML
                <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var debounceTimeout;
                    document.getElementById("time_item").addEventListener("keyup", function (event) {
                        clearTimeout(debounceTimeout);

                        debounceTimeout = setTimeout(function () {
                            allocPostRequest(
                                "{$allocUpdateTimeSheetURL}",
                                "?time_item=",
                                event.target.value,
                                function (response) {
                                    document.getElementById("time_item_results").classList.remove("hidden");
                                    document.getElementById("time_item_results").innerHTML = response;
                                }
                            );
                        }, 350);
                    });
                });
                </script>
                <style>
                    #time_item_results table tr td {
                        border-top:none !important;
                    }
                </style>
                <form action="{$allocHomeURL}" method="post">
                    <table class="list">
                        <tr>
                            <th>Add time: DURATION > TASKID > COMMENT</th>
                            <th class="right">{$page->help('home_timeSheet')}</th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input style="width:100%" placeholder="DURATION  TASKID  COMMENT" type="text" name="time_item" id="time_item">
                                <div id="time_item_results" class="hidden" style="margin:10px;"></div>
                            </td>
                        </tr>
                    </table>
                </form>
            HTML;
    }
}
