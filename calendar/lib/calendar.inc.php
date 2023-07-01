<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

// FIXME: why can't this be renamed to comply with PSR12 without breaking alloc?
class calendar
{
    private person $person;

    private int $weekStart;

    private int $weeksToDisplay;

    private array $daysOfWeek = [];

    private string $returnMode;

    private string $first_date;

    private string $last_date;

    private AllocDatabase $allocDatabase;

    private $firstDayOfWeek;

    public function __construct($weekStart = 1, $weeksToDisplay = 4)
    {
        $this->allocDatabase = new AllocDatabase();
        $this->firstDayOfWeek = (new config())->get_config_item('calendarFirstDay');
        $this->setDateRange($weekStart, $weeksToDisplay);
        $this->daysOfWeek = $this->getDaysOfWeek($this->firstDayOfWeek);
    }

    public function setPerson($personID)
    {
        $this->person = new person();
        $this->person->set_id($personID);
        $this->person->select();
    }

    private function setDateRange($weekStart, $weeksToDisplay)
    {
        $index = 0;
        $this->weekStart = $weekStart;
        $this->weeksToDisplay = $weeksToDisplay;

        // Wind the date forward till we find the starting day of week
        while (
            date('D', mktime(
                0,
                0,
                0,
                date('m'),
                date('d') + $index,
                date('Y')
            )) != $this->firstDayOfWeek
        ) {
            ++$index;
        }

        $fullDate = mktime(
            date('H'),
            date('i'),
            date('s'),
            date('m'),
            date('d') - ($this->weekStart * 7) + ($index - 7),
            date('Y')
        );

        // Set the first and last date on the page
        $this->first_date = date('Y-m-d', $fullDate);
        $this->last_date = date('Y-m-d', mktime(
            date('H', $fullDate),
            date('i', $fullDate),
            date('s', $fullDate),
            date('m', $fullDate),
            date('d', $fullDate) + (($this->weeksToDisplay * 7) - 1),
            date('Y', $fullDate)
        ));
    }

    private function getReminders(): array
    {
        $reminders = [];

        $query = unsafe_prepare("SELECT *
                            FROM reminder
                            JOIN reminderRecipient ON reminderRecipient.reminderID = reminder.reminderID
                           WHERE personID = %d
                             AND (reminderRecuringInterval = 'No' OR (reminderRecuringInterval != 'No' AND reminderActive))
                        GROUP BY reminder.reminderID", $this->person->get_id());
        $this->allocDatabase->query($query);

        while ($row = $this->allocDatabase->row()) {
            $reminder = new reminder();
            $reminder->read_db_record($this->allocDatabase);

            if ($reminder->is_alive()) {
                $reminderTime = format_date('U', $reminder->get_value('reminderTime'));

                // If repeating reminder
                if ('No' != $reminder->get_value('reminderRecuringInterval') && 0 != $reminder->get_value('reminderRecuringValue')) {
                    $interval = $reminder->get_value('reminderRecuringValue');
                    $intervalUnit = $reminder->get_value('reminderRecuringInterval');

                    while ($reminderTime < format_date('U', $this->last_date) + 86400) {
                        $row['reminderTime'] = $reminderTime;
                        $reminders[date('Y-m-d', $reminderTime)][] = $row;
                        $reminderTime = $reminder->get_next_reminder_time($reminderTime, $interval, $intervalUnit);
                    }

                    // Else if once off reminder
                } else {
                    $row['reminderTime'] = $reminderTime;
                    $reminders[date('Y-m-d', $reminderTime)][] = $row;
                }
            }
        }

        return $reminders;
    }

    private function getTasksToStart(): array
    {
        [,, $taskStatusClosed] = (new Task())->get_task_status_in_set_sql();
        $tasksToStart = [];

        $query = unsafe_prepare(
            "SELECT *
               FROM task
              WHERE personID = %d
                AND dateTargetStart >= '%s'
                AND dateTargetStart < '%s'
                AND taskStatus NOT IN (" . $taskStatusClosed . ')',
            $this->person->get_id(),
            $this->first_date,
            $this->last_date
        );

        $this->allocDatabase->query($query);

        while ($row = $this->allocDatabase->next_record()) {
            $tasksToStart[$row['dateTargetStart']][] = $row;
        }

        return $tasksToStart;
    }

    private function getTasksToComplete(): array
    {
        [,, $ts_closed] = (new Task())->get_task_status_in_set_sql();
        $tasksToComplete = [];

        $query = unsafe_prepare(
            "SELECT *
               FROM task
              WHERE personID = %d
                AND dateTargetCompletion >= '%s'
                AND dateTargetCompletion < '%s'
                AND taskStatus NOT IN (" . $ts_closed . ')',
            $this->person->get_id(),
            $this->first_date,
            $this->last_date
        );

        $this->allocDatabase->query($query);
        while ($row = $this->allocDatabase->next_record()) {
            $tasksToComplete[$row['dateTargetCompletion']][] = $row;
        }

        return $tasksToComplete;
    }

    private function getAbsences(): array
    {
        $prev_date = null;
        $query = unsafe_prepare(
            "SELECT *
               FROM absence
              WHERE (dateFrom >= '%s' OR dateTo <= '%s')",
            $this->first_date,
            $this->last_date
        );

        $current_user = &singleton('current_user');
        if (!$current_user->have_role('admin') && !$current_user->have_role('manage')) {
            $query .= unsafe_prepare(' AND personID = %d', $current_user->get_id());
        }

        $this->allocDatabase->query($query);
        $absences = [];
        while ($row = $this->allocDatabase->row()) {
            $start_time = format_date('U', $row['dateFrom']);
            $end_time = format_date('U', $row['dateTo']);
            while ($start_time < $end_time) {
                if (date('Y-m-d', $start_time) == $prev_date) {
                    // Can't use timezone friendly date magic before 5.3.
                    // If the date didn't increment by a day, as is want to happen on DST days, then
                    // we manually roll it forward by one hour. Thus hopefully knocking it into the next day.
                    $start_time += 3600;
                }

                $row['dates'] = date('Y-m-d H:i:s', $start_time) . ' ---- ' . date('Y-m-d H:i:s', $end_time);
                $absences[date('Y-m-d', $start_time)][] = $row;
                $prev_date = date('Y-m-d', $start_time);
                $start_time += 86400;
            }
        }

        return $absences;
    }

    private function getDaysOfWeek($first_day)
    {
        $daysOfWeek = [];
        $go = false;
        // Generate a list of days, being mindful that a user may not want
        // Sunday to be the first day of the week
        $days = [
            'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat',
            'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat',
        ];
        foreach ($days as $day) {
            if (($day == $first_day || $go) && count($daysOfWeek) < 7) {
                $daysOfWeek[] = $day;
                $go = true;
            }
        }

        return $daysOfWeek;
    }

    public function setReturnMode($mode)
    {
        $this->returnMode = $mode;
    }

    public function draw(): string
    {
        $dates_of_week = [];
        $page = new Page();
        $html = '';
        $html .= $this->drawCanvas();
        $html .= $this->drawRowHeader();
        $html .= $this->drawBody();
        $index = -7;

        while (date('D', mktime(0, 0, 0, date('m'), date('d') + $index, date('Y'))) != $this->firstDayOfWeek) {
            ++$index;
        }

        $index -= $this->weekStart * 7;
        $sunday_day = date('d', mktime(0, 0, 0, date('m'), date('d') + $index, date('Y')));
        $sunday_month = date('m', mktime(0, 0, 0, date('m'), date('d') + $index, date('Y')));
        $sunday_year = date('Y', mktime(0, 0, 0, date('m'), date('d') + $index, date('Y')));

        $index = 0;

        $absences = $this->getAbsences();
        $reminders = $this->getReminders();
        $tasks_to_start = $this->getTasksToStart();
        $tasks_to_complete = $this->getTasksToComplete();

        // For each single week...
        while ($index < $this->weeksToDisplay) {
            $html .= $this->drawRow();

            $a = 0;
            while ($a < 7) {
                $dates_of_week[$this->daysOfWeek[$a]] = date('Y-m-d', mktime(
                    0,
                    0,
                    0,
                    $sunday_month,
                    $sunday_day + (7 * $index) + $a,
                    $sunday_year
                ));
                ++$a;
            }

            foreach ($dates_of_week as $day => $date) {
                $d = new calendar_day();
                $d->set_date($date);
                $d->set_links($this->getNewTaskLink($date) . $this->getNewReminderLink($date) . $this->getNewAbsenceLink($date));

                // Tasks to be Started
                $tasks_to_start[$date] ??= [];
                foreach ($tasks_to_start[$date] as $t) {
                    $extra = '';
                    $t['timeLimit'] && ($extra = ' (' . sprintf('Limit %0.1fhrs', $t['timeLimit']) . ')');
                    $d->start_tasks[] = '<a href="' . $page->getURL('url_alloc_task') . '?taskID=' . $t['taskID'] . '">' . $page->escape($t['taskName'] . $extra) . '</a>';
                }

                // Tasks to be Completed
                $tasks_to_complete[$date] ??= [];
                foreach ($tasks_to_complete[$date] as $t) {
                    unset($extra);
                    $t['timeLimit'] && ($extra = ' (' . sprintf('Limit %0.1fhrs', $t['timeLimit']) . ')');
                    $d->complete_tasks[] = '<a href="' . $page->getURL('url_alloc_task') . '?taskID=' . $t['taskID'] . '">' . $page->escape($t['taskName'] . $extra) . '</a>';
                }

                // Reminders
                $reminders[$date] ??= [];
                foreach ($reminders[$date] as $r) {
                    unset($wrap_start, $wrap_end);
                    if (!$r['reminderActive']) {
                        $wrap_start = '<strike>';
                        $wrap_end = '</strike>';
                    }

                    $text = $page->escape($r['reminderSubject']);
                    $r['reminderTime'] && ($text = date('g:ia', $r['reminderTime']) . ' ' . $text);
                    $d->reminders[] = '<a href="' . $page->getURL('url_alloc_reminder') . '?&step=3&reminderID=' . $r['reminderID'] . '&returnToParent=' . $this->returnMode . '&personID=' . $r['personID'] . '">' . $wrap_start . $text . $wrap_end . '</a>';
                }

                // Absences
                $absences[$date] ??= [];
                foreach ($absences[$date] as $a) {
                    $d->absences[] = '<a href="' . $page->getURL('url_alloc_absence') . '?absenceID=' . $a['absenceID'] . '&returnToParent=' . $this->returnMode . '">' . (new person())->get_fullname($a['personID']) . ': ' . $page->escape($a['absenceType']) . '</a>';
                }

                $html .= $d->draw_day_html();
            }

            ++$index;
            $html .= $this->drawRowEnd();
        }

        $html .= $this->drawBodyEnd();

        return $html . $this->drawCanvasEnd();
    }

    private function drawCanvas(): string
    {
        return "<table border='0' cellspacing='0' class='alloc_calendar' cellpadding='3'>";
    }

    private function drawCanvasEnd(): string
    {
        return '</table>';
    }

    private function drawBody(): string
    {
        // Unfortunately browser support for this seems to be quite bad.
        // Eventually this should cause the table to have headers draw at the
        // start of each page where the table is broken, but for now it doesn't
        // seem to work.
        return '<tbody>';
    }

    private function drawBodyEnd(): string
    {
        return '</tbody>';
    }

    private function drawRow(): string
    {
        return "\n<tr>";
    }

    private function drawRowEnd(): string
    {
        return '</tr>';
    }

    private function drawRowHeader(): string
    {
        $html = "\n<thead><tr>";
        foreach ($this->daysOfWeek as $dayOfWeek) {
            $html .= '<th>' . $dayOfWeek . '</th>';
        }

        return $html . '</tr></thead>';
    }

    private function getNewTaskLink($date): string
    {
        $link = '<a href="' . (new Page())->getURL('url_alloc_task') . '?dateTargetStart=' . $date . '&personID=' . $this->person->get_id() . '">';
        $link .= $this->getNewModuleImage('task', 'New Task');

        return $link . '</a>';
    }

    private function getNewReminderLink($date): string
    {
        global $TPL;
        $time = urlencode($date . ' 9:00am');
        $link = '<a href="' . $TPL['url_alloc_reminder'] . 'parentType=general&step=2&returnToParent=' . $this->returnMode . '&reminderTime=' . $time;
        $link .= '&personID=' . $this->person->get_id() . '">';
        $link .= $this->getNewModuleImage('reminder', 'New Reminder');

        return $link . '</a>';
    }

    private function getNewAbsenceLink($date): string
    {
        global $TPL;
        $link = '<a href="' . $TPL['url_alloc_absence'] . 'date=' . $date . '&personID=' . $this->person->get_id() . '&returnToParent=' . $this->returnMode . '">';
        $link .= $this->getNewModuleImage('absence', 'New Absence');

        return $link . '</a>';
    }

    private function getNewModuleImage(string $imageName, string $title): string
    {
        return '<img border="0" src="' . (new Page())->getURL('url_alloc_images') . $imageName . '.gif" alt="' . $title . '" title="' . $title . '">';
    }
}
