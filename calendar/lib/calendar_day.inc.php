<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class calendar_day
{
    public $date;          // Y-m-d
    public $day;           // Mon
    public $display_date;  // m-Y
    public $links;
    public $class;
    public $absences = [];
    public $start_tasks = [];
    public $complete_tasks = [];
    public $reminders = [];

    public function __construct()
    {
    }

    public function set_date($date)
    {
        $this->date = $date;
        $this->day = format_date("D", $date);
        $this->display_date = format_date("j M", $date);

        if ($this->date == date("Y-m-d")) {
            $this->class = "today";

            // Toggle every second month to have slightly different coloured shading
        } else if (date("n", format_date("U", $this->date)) % 2 == 0) {
            $this->class = "even";
        }
    }

    public function set_links($links)
    {
        $this->links = $links;
    }

    public function draw_day_html()
    {
        $rows = [];
        global $TPL;

        if ($this->absences) {
            $rows[] = "<br>Absent:";
            $rows[] = implode("<br>", $this->absences);
        }

        if ($this->start_tasks) {
            $rows[] = "<br>To be started:";
            $rows[] = implode("<br>", $this->start_tasks);
        }

        if ($this->complete_tasks) {
            $rows[] = "<br>To be complete:";
            $rows[] = implode("<br>", $this->complete_tasks);
        }
        if ($this->reminders) {
            $rows[] = "<br>Reminders:";
            $rows[] = implode("<br>", $this->reminders);
        }

        echo "\n<td class=\"calendar_day " . $this->class . "\">";
        echo "<h1>" . $this->links . $this->display_date . "</h1>";

        if (count($rows)) {
            echo implode("<br>", $rows);
        }

        echo "</td>";
    }
}
