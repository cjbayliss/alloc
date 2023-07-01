<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class TimeSheetStatisticsHomeItem extends HomeItem
{
    public function __construct()
    {
        parent::__construct(
            'time_status_list',
            'Time Sheet Statistics',
            'time',
            'narrow',
            29,
        );
    }

    public function visible(): bool
    {
        $current_user = &singleton('current_user');

        if (!isset($current_user->prefs['showTimeSheetStatsHome'])) {
            $current_user->prefs['showTimeSheetStatsHome'] = 1;
        }

        if (!isset($current_user)) {
            return false;
        }

        if (!$current_user->is_employee()) {
            return false;
        }

        return (bool) $current_user->prefs['showTimeSheetStatsHome'];
    }

    public function render(): bool
    {
        return true;
    }

    public function getHTML(): string
    {
        // Get averages for hours worked over the past fortnight and year
        $current_user = &singleton('current_user');
        $page = new Page();
        $timeSheetItem = new timeSheetItem();

        $today = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
        $yestA = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')));
        $yestB = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
        $fortn = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 14, date('Y')));

        [$hours_sum_today, $dollars_sum_today] = $timeSheetItem->get_averages($today, $current_user->get_id());
        [$hours_sum_yesterday, $dollars_sum_yesterday] = $timeSheetItem->get_averages($yestA, $current_user->get_id(), null, $yestB);
        [$hours_sum_fortnight, $dollars_sum_fortnight] = $timeSheetItem->get_averages($fortn, $current_user->get_id());
        [$hours_avg_fortnight, $dollars_avg_fortnight] = $timeSheetItem->getFortnightlyAverage($current_user->get_id());

        $hours_sum_today = sprintf('%0.2f', $hours_sum_today[$current_user->get_id()] ?? 0);
        $dollars_sum_today = $page->money_print($dollars_sum_today[$current_user->get_id()] ?? []);

        $hours_sum_yesterday = sprintf('%0.2f', $hours_sum_yesterday[$current_user->get_id()] ?? 0);
        $dollars_sum_yesterday = $page->money_print($dollars_sum_yesterday[$current_user->get_id()] ?? []);

        $hours_sum_fortnight = sprintf('%0.2f', $hours_sum_fortnight[$current_user->get_id()] ?? 0);
        $dollars_sum_fortnight = $page->money_print($dollars_sum_fortnight[$current_user->get_id()] ?? []);

        $hours_avg_fortnight = sprintf('%0.2f', $hours_avg_fortnight[$current_user->get_id()] ?? 0);
        $dollars_avg_fortnight = $page->money((new config())->get_config_item('currency'), $dollars_avg_fortnight[$current_user->get_id()] ?? 0, '%s%m %c');

        $dateFrom = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 28, date('Y')));
        $dateTo = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')));

        $current_user = &singleton('current_user');
        $userID = $current_user->get_id();
        $allocTimeSheetGraphURL = (new Page())->getURL('url_alloc_timeSheetGraph');

        return <<<HTML
                <script>
                $(document).ready(function() {
                    $.getJSON("../time/updateTimeGraph.php", function(data){
                        var points = data["points"];
                        var plot1 = $.jqplot('chart1', [points], {  
                            series:[{ showMarker:false }],
                            seriesColors: [ "#539cf6" ],
                            seriesDefaults:{
                                renderer: $.jqplot.BarRenderer,
                                rendererOptions: {
                                    barPadding: 10,
                                    barMargin: 10,
                                    barWidth: 8
                                }
                            },
                            axesDefaults: {
                                tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                                tickOptions: {
                                  angle: -60,
                                  fontSize: '6pt'
                                }
                            },
                            axes:{
                                xaxis:{
                                    min:"{$dateFrom} 12:00PM",
                                    max:"{$dateTo} 12:00PM",
                                    renderer:$.jqplot.DateAxisRenderer,
                                    tickOptions:{ formatString:'%b %#d' },
                                    tickInterval:'1 day'
                                },
                                yaxis:{
                                min:0,
                                max:12,
                                tickOptions: { angle: 0 }
                                }
                            }
                        });
                    });
                });
                </script>

                <a href="{$allocTimeSheetGraphURL}?personID={$userID}&dateFrom={$dateFrom}&dateTo={$dateTo}&applyFilter=true"><div id="chart1" style="height:150px; margin-bottom:5px;"></div></a>

                <table class='list'>
                    <tr>
                        <th style="font-size:90%">Today:</th><td>{$hours_sum_today}hrs</td><td class="right obfuscate">{$dollars_sum_today}</td>
                    </tr>
                    <tr>
                        <th style="font-size:90%">Yesterday:</th><td>{$hours_sum_yesterday}hrs</td><td class="right obfuscate">{$dollars_sum_yesterday}</td>
                    </tr>
                    <tr>
                        <th style="font-size:90%">Last 2 weeks:</th><td>{$hours_sum_fortnight}hrs</td><td class="right obfuscate">{$dollars_sum_fortnight}</td>
                    </tr>
                    <tr>
                        <th style="font-size:90%">2 week average:</th><td>{$hours_avg_fortnight}hrs</td><td class="right obfuscate">{$dollars_avg_fortnight}</td>
                    </tr>
                </table>
            HTML;
    }
}
