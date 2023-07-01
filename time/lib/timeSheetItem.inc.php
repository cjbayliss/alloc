<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class timeSheetItem extends DatabaseEntity
{
    public $currency;

    public $data_table = 'timeSheetItem';

    public $display_field_name = 'description';

    public $key_field = 'timeSheetItemID';

    public $skip_tsi_status_check;

    public $data_fields = [
        'timeSheetID',
        'dateTimeSheetItem',
        'timeSheetItemDuration',
        'timeSheetItemDurationUnitID',
        'rate' => ['type' => 'money'],
        'personID',
        'description',
        'comment',
        'taskID',
        'multiplier',
        'commentPrivate',
        'emailUID',
        'emailMessageID',
        'timeSheetItemCreatedTime',
        'timeSheetItemCreatedUser',
        'timeSheetItemModifiedTime',
        'timeSheetItemModifiedUser',
    ];

    public function save()
    {
        $current_user = &singleton('current_user');
        $timeSheet = new timeSheet();
        $timeSheet->set_id($this->get_value('timeSheetID'));
        $timeSheet->select();

        $timeSheet->load_pay_info();
        [$amount_used, $amount_allocated] = $timeSheet->get_amount_allocated('%mo');

        $this->currency = $timeSheet->get_value('currencyTypeID');

        $this->set_value('comment', rtrim($this->get_value('comment')));

        $amount_of_item = $this->calculate_item_charge($timeSheet->get_value('currencyTypeID'), $timeSheet->get_value('customerBilledDollars'));
        if ($amount_allocated && ($amount_of_item + $amount_used) > $amount_allocated) {
            alloc_error('Adding this Time Sheet Item would exceed the amount allocated on the Pre-paid invoice. Time Sheet Item not saved.');
        }

        // If unit is changed via CLI
        if (
            $this->get_value('timeSheetItemDurationUnitID') && $timeSheet->pay_info['project_rateUnitID']
            && $timeSheet->pay_info['project_rateUnitID'] != $this->get_value('timeSheetItemDurationUnitID') && !$timeSheet->can_edit_rate()
        ) {
            alloc_error('Not permitted to edit time sheet item unit.');
        }

        if (!$this->get_value('timeSheetItemDurationUnitID') && $timeSheet->pay_info['project_rateUnitID']) {
            $this->set_value('timeSheetItemDurationUnitID', $timeSheet->pay_info['project_rateUnitID']);
        }

        // Last ditch perm checking - useful for the CLI
        if (!is_object($timeSheet) || !$timeSheet->get_id()) {
            alloc_error('Unknown time sheet.');
        }

        if ('edit' != $timeSheet->get_value('status') && !$this->skip_tsi_status_check) {
            alloc_error('Time sheet is not at status edit');
        }

        if (!$this->is_owner()) {
            alloc_error('Time sheet is not editable for you.');
        }

        $rtn = parent::save();
        $timeSheet->update_related_invoices();

        return $rtn;
    }

    public static function parse_time_string(string $str = ''): array
    {
        $rtn = [];
        preg_match('/^(\d\d\d\d\-\d\d?\-\d\d?\s+)?([\d\.]+)?\s*'
            . '(hours|hour|hrs|hr|days|day|weeks|week|months|month|fixed)?' // unit
            . '\\s*'
            . '(x\\s*[\\d\\.]+)?'     // multiplier eg: x 1.5
            . '\\s*'
            . '(\\d+)?'             // task id
            . '\\s*'
            . '(.*)'               // comment
            . '\\s*'
            // ."(private)?"        # whether the comment is private
            . '$/i', $str, $m);

        ($rtn['date'] = trim($m[1])) || ($rtn['date'] = date('Y-m-d'));
        $rtn['duration'] = $m[2];
        $rtn['unit'] = $m[3];
        ($rtn['multiplier'] = str_replace(['x', 'X', ' '], '', $m[4])) || ($rtn['multiplier'] = 1);
        $rtn['taskID'] = $m[5];
        $rtn['comment'] = $m[6];
        // $rtn["private"] = $m[7];

        // use the first letter of the unit for the lookup
        $tu = ['h' => 1, 'd' => 2, 'w' => 3, 'm' => 4, 'f' => 5];
        $rtn['unit'] = (isset($rtn['unit'], $rtn['unit'][0]) && $tu[$rtn['unit'][0]]) ?? 1;

        // change 2010/10/27 to 2010-10-27
        $rtn['date'] = str_replace('/', '-', $rtn['date']);

        return $rtn;
    }

    public function calculate_item_charge($currency, $rate = 0)
    {
        return Page::money($currency, $rate * $this->get_value('timeSheetItemDuration') * $this->get_value('multiplier'), '%mo');
    }

    public function delete()
    {
        $timeSheetID = $this->get_value('timeSheetID');

        $allocDatabase = new AllocDatabase();
        $q = unsafe_prepare("SELECT invoiceItem.*
                        FROM invoiceItem
                   LEFT JOIN invoice ON invoiceItem.invoiceID = invoice.invoiceID
                       WHERE timeSheetID = %d
                         AND invoiceStatus != 'finished'", $timeSheetID);
        $allocDatabase->query($q);
        while ($row = $allocDatabase->row()) {
            $ii = new invoiceItem();
            $ii->set_id($row['invoiceItemID']);
            $ii->select();
            if ($ii->get_value('timeSheetItemID') == $this->get_id()) {
                $ii->delete();
            } elseif (!$ii->get_value('timeSheetItemID')) {
                invoiceEntity::save_invoice_timeSheet($row['invoiceID'], $timeSheetID);
                // will update the existing invoice item
            }
        }

        return parent::delete();
    }

    /**
     * Calculates the fortnightly averages for a given person or all persons.
     *
     * @param int $personID The ID of the person for whom to calculate the
     *                      averages. Default is 0, which means all persons.
     *
     * @return array An array containing two sub-arrays:
     *               - The first sub-array contains the fortnightly averages for
     *               each person.
     *               - The second sub-array contains the fortnightly converted
     *               averages for each person.
     */
    public function getFortnightlyAverage(int $personID = 0): array
    {
        // populate array of past year's fortnights
        $days = 0;
        $fortnightsLookup = [];
        while ($days < 365) {
            $fortnight = (int) ($days / 14);
            $fortnightsLookup[date('Y-m-d', strtotime(sprintf('-365 days +%s days', $days)))] = $fortnight;
            $days += 14;
        }

        $startDate = date('Y-m-d', strtotime('-365 days'));
        $personIDStatement = (0 !== $personID) ? 'AND personID = :personID' : '';

        $allocDatabase = new AllocDatabase();
        $allocDatabase->connect();

        $dateTimeSheetItemQuery = $allocDatabase->pdo->prepare(
            "SELECT DISTINCT dateTimeSheetItem, personID
                FROM timeSheetItem
                WHERE dateTimeSheetItem > :startDate
                {$personIDStatement}
                GROUP BY dateTimeSheetItem, personID"
        );

        if (0 !== $personID) {
            $dateTimeSheetItemQuery->bindParam(':personID', $personID, PDO::PARAM_INT);
        }

        $dateTimeSheetItemQuery->bindParam(':startDate', $startDate, PDO::PARAM_STR);

        $dateTimeSheetItemQuery->execute();

        $countFortnights = [1 => 0]; // initialise to [1 => 0] to prevent 'undefind offset'
        while ($dateTimeSheetItemRow = $dateTimeSheetItemQuery->fetch(PDO::FETCH_ASSOC)) {
            $personID = $dateTimeSheetItemRow['personID'];
            ++$countFortnights[$personID];
        }

        $fortnightlyAverages = [];
        [$averages, $currency] = $this->get_averages($startDate, $personID);

        foreach ($averages as $id => $avg) {
            $fortnightlyAverages[$id] = $avg / $countFortnights[$id];
        }

        $fortnightlyConvertedAverages = [];
        $totalConvertedAmounts = [];
        foreach ($currency as $id => $amounts) {
            $totalConvertedAmounts[$id] = 0;
            foreach ($amounts as $amount) {
                $totalConvertedAmounts[$id] += $amount['amount'];
            }

            $fortnightlyConvertedAverages[$id] = $totalConvertedAmounts[$id] / $countFortnights[$id];
        }

        return [$fortnightlyAverages, $fortnightlyConvertedAverages];
    }

    public function is_owner($ignored = null)
    {
        if ($this->get_value('timeSheetID')) {
            $timeSheet = new timeSheet();
            $timeSheet->set_id($this->get_value('timeSheetID'));
            $timeSheet->select();

            return $timeSheet->is_owner();
        }
    }

    public static function get_list_filter($filter = [])
    {
        $timeSheetIDs = [];
        $sql = [];
        // If timeSheetID is an array
        if ($filter['timeSheetID'] && is_array($filter['timeSheetID'])) {
            $timeSheetIDs = $filter['timeSheetID'];
            // Else
        } elseif ($filter['timeSheetID'] && is_numeric($filter['timeSheetID'])) {
            $timeSheetIDs[] = $filter['timeSheetID'];
        }

        if (is_array($timeSheetIDs) && count($timeSheetIDs)) {
            $sql[] = unsafe_prepare('(timeSheetItem.timeSheetID IN (%s))', $timeSheetIDs);
        }

        if ($filter['projectID']) {
            $sql[] = unsafe_prepare('(timeSheet.projectID = %d)', $filter['projectID']);
        }

        if ($filter['taskID']) {
            $sql[] = unsafe_prepare('(timeSheetItem.taskID = %d)', $filter['taskID']);
        }

        if ($filter['date']) {
            if (!in_array($filter['dateComparator'], ['=', '!=', '>', '>=', '<', '<='])) {
                $filter['dateComparator'] = '=';
            }

            $sql[] = unsafe_prepare('(timeSheetItem.dateTimeSheetItem ' . $filter['dateComparator'] . " '%s')", $filter['date']);
        }

        if ($filter['personID']) {
            $sql[] = unsafe_prepare('(timeSheetItem.personID = %d)', $filter['personID']);
        }

        if ($filter['timeSheetItemID']) {
            $sql[] = unsafe_prepare('(timeSheetItem.timeSheetItemID = %d)', $filter['timeSheetItemID']);
        }

        if ($filter['comment']) {
            $sql[] = unsafe_prepare("(timeSheetItem.comment LIKE '%%%s%%')", $filter['comment']);
        }

        if ($filter['tfID']) {
            $sql[] = unsafe_prepare('(timeSheet.recipient_tfID = %d)', $filter['tfID']);
        }

        return $sql;
    }

    public static function get_list($_FORM)
    {
        $rows = [];
        $print = null;
        // This is the definitive method of getting a list of timeSheetItems that need a sophisticated level of filtering

        global $TPL;
        $filter = timeSheetItem::get_list_filter($_FORM);

        $debug = $_FORM['debug'];
        $debug && (print '<pre>_FORM: ' . print_r($_FORM, 1) . '</pre>');
        $debug && (print '<pre>filter: ' . print_r($filter, 1) . '</pre>');
        $_FORM['return'] || ($_FORM['return'] = 'html');

        if (is_array($filter) && count($filter)) {
            $filter = ' WHERE ' . implode(' AND ', $filter);
        }

        $q = 'SELECT * FROM timeSheetItem
           LEFT JOIN timeSheet ON timeSheet.timeSheetID = timeSheetItem.timeSheetID
                     ' . $filter . '
            ORDER BY timeSheet.timeSheetID,dateTimeSheetItem asc';
        $debug && (print 'Query: ' . $q);
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);
        while ($row = $allocDatabase->next_record()) {
            $print = true;
            $t = new timeSheet();
            $t->read_db_record($allocDatabase);

            $tsi = new timeSheetItem();
            $tsi->read_db_record($allocDatabase);
            $tsi->currency = $t->get_value('currencyTypeID');

            $row['secondsBilled'] = $row['hoursBilled'] = $row['timeLimit'] = $row['limitWarning'] = ''; // set these for the CLI
            if ($tsi->get_value('taskID')) {
                $task = $tsi->get_foreign_object('task');
                $row['secondsBilled'] = $task->get_time_billed();
                $row['hoursBilled'] = sprintf('%0.2f', $row['secondsBilled'] / 60 / 60);
                if ($task->get_value('timeLimit') && $row['hoursBilled'] > $task->get_value('timeLimit')) {
                    $row['limitWarning'] = 'Exceeds Limit!';
                }

                $row['timeLimit'] = $task->get_value('timeLimit');
            }

            $row['rate'] = $tsi->get_value('rate', DST_HTML_DISPLAY);
            $row['worth'] = Page::money($tsi->currency, $row['rate'] * $tsi->get_value('multiplier') * $tsi->get_value('timeSheetItemDuration'), '%m');

            $rows[$row['timeSheetItemID']] = $row;
        }

        if (null === $print) {
            return;
        }

        if ('array' != $_FORM['return']) {
            return;
        }

        return $rows;
    }

    public function get_list_vars()
    {
        return [
            'return'      => '[MANDATORY] eg: array | html | dropdown_options',
            'timeSheetID' => 'Show items for a particular time sheet',
            'projectID'   => 'Show items for a particular project',
            'taskID'      => 'Show items for a particular task',
            'date'        => 'Show items for a particular date',
            'personID'    => 'Show items for a particular person',
            'comment'     => 'Show items that have a comment like eg: *uick brown fox jump*',
        ];
    }

    // function get_averages_past_fortnight($personID=false) {
    // $dateTimeSheetItem = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-14, date("Y")));
    // DON'T ERASE THIS!! This way will divide by the number of individual days worked
    // $rows = $this->get_averages($dateTimeSheetItem, $personID, "/ COUNT(DISTINCT dateTimeSheetItem)");

    // This will just get the sum of hours worked for the last two weeks
    // $rows = $this->get_averages($dateTimeSheetItem, $personID);

    // return $rows;
    // }

    public function get_averages($dateTimeSheetItem, $personID = false, $divisor = '', $endDate = null)
    {
        $personID_sql = null;
        $endDate_sql = null;
        $personID && ($personID_sql = unsafe_prepare(' AND timeSheetItem.personID = %d', $personID));
        $endDate && ($endDate_sql = unsafe_prepare(" AND timeSheetItem.dateTimeSheetItem <= '%s'", $endDate));

        $q = unsafe_prepare('SELECT personID
                           , SUM(timeSheetItemDuration*timeUnitSeconds) ' . $divisor . " AS avg
                        FROM timeSheetItem
                   LEFT JOIN timeUnit ON timeUnitID = timeSheetItemDurationUnitID
                       WHERE dateTimeSheetItem > '%s'
                             " . $personID_sql . '
                             ' . $endDate_sql . '
                    GROUP BY personID
                     ', $dateTimeSheetItem);

        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);

        $rows = [];
        while ($allocDatabase->next_record()) {
            $rows[$allocDatabase->f('personID')] = $allocDatabase->f('avg') / 3600;
        }

        // Calculate the dollar values
        $q = unsafe_prepare(
            "SELECT (rate * POW(10, -currencyType.numberToBasic) * timeSheetItemDuration * multiplier) as amount
                        , timeSheet.currencyTypeID as currency
                        , timeSheetItem.*
                     FROM timeSheetItem
                LEFT JOIN timeSheet on timeSheetItem.timeSheetID = timeSheet.timeSheetID
                LEFT JOIN currencyType ON timeSheet.currencyTypeID = currencyType.currencyTypeID
                    WHERE dateTimeSheetItem > '%s'
                          " . $personID_sql . '
                          ' . $endDate_sql,
            $dateTimeSheetItem
        );
        $allocDatabase->query($q);
        $rows_dollars = [];
        while ($row = $allocDatabase->row()) {
            $tsi = new timeSheetItem();
            $tsi->read_db_record($allocDatabase);
            $rows_dollars[$row['personID']][] = $row;
        }

        return [$rows, $rows_dollars];
    }

    public static function get_timeSheetItemComments($taskID = '', $starred = false)
    {
        $where = null;
        // Init
        $rows = [];

        if ($taskID) {
            $where = unsafe_prepare('timeSheetItem.taskID = %d', $taskID);
        } elseif ($starred) {
            $current_user = &singleton('current_user');
            $timeSheetItemIDs = isset($current_user->prefs['stars']) ?
                (isset($current_user->prefs['stars']['timeSheetItem']) ??
                    array_keys((array) $current_user->prefs['stars']['timeSheetItem'])) :
                [];

            if ([] !== $timeSheetItemIDs) {
                $where = unsafe_prepare('(timeSheetItem.timeSheetItemID in (%s))', $timeSheetItemIDs);
            }
        }

        $where ??= ' 1 ';

        // Get list of comments from timeSheetItem table
        $query = unsafe_prepare('SELECT timeSheetID
                               , timeSheetItemID
                               , dateTimeSheetItem AS date
                               , comment
                               , personID
                               , taskID
                               , timeSheetItemDuration as duration
                               , timeSheetItemCreatedTime
                            FROM timeSheetItem
                           WHERE ' . $where . ' AND (commentPrivate != 1 OR commentPrivate IS NULL)
                             AND emailUID is NULL
                             AND emailMessageID is NULL
                        ORDER BY dateTimeSheetItem,timeSheetItemID
                         ');

        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($query);
        while ($row = $allocDatabase->row()) {
            $rows[] = $row;
        }

        if (!is_array($rows)) {
            return [];
        }

        return $rows;
    }

    public static function get_total_hours_worked_per_day($personID, $start = null, $end = null)
    {
        $info = [];
        $points = [];
        $current_user = &singleton('current_user');

        $personID || ($personID = $current_user->get_id());
        $start || ($start = date('Y-m-d', time() - (60 * 60 * 24 * 28)));
        $end || ($end = date('Y-m-d'));

        $q = unsafe_prepare(
            "SELECT dateTimeSheetItem, sum(timeSheetItemDuration*timeUnitSeconds) / 3600 AS hours
               FROM timeSheetItem
          LEFT JOIN timeUnit ON timeUnitID = timeSheetItemDurationUnitID
              WHERE personID = %d
                AND dateTimeSheetItem >= '%s'
                AND dateTimeSheetItem <= '%s'
           GROUP BY dateTimeSheetItem",
            $personID,
            $start,
            $end
        );
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);
        while ($row = $allocDatabase->row()) {
            $info[$row['dateTimeSheetItem']] = $row;
        }

        [$sy, $sm, $sd] = explode('-', $start);
        [$ey, $em, $ed] = explode('-', $end);

        $x = 0;
        while (mktime(0, 0, 0, $sm, $sd + $x, $sy) <= mktime(0, 0, 0, $em, $ed, $ey)) {
            $d = date('Y-m-d', mktime(0, 0, 0, $sm, $sd + $x, $sy));
            $points[] = [$d . ' 12:00PM', sprintf('%.2F', $info[$d]['hours'] ?? 0)];
            ++$x;
        }

        return $points;
    }

    public static function get_total_hours_worked_per_month($personID, $start = null, $end = null)
    {
        $info = [];
        $points = [];
        $current_user = &singleton('current_user');

        $personID || ($personID = $current_user->get_id());
        $start || ($start = date('Y-m-d', time() - (60 * 60 * 24 * 28)));
        $end || ($end = date('Y-m-d'));

        $q = unsafe_prepare(
            "SELECT CONCAT(YEAR(dateTimeSheetItem),'-',MONTH(dateTimeSheetItem)) AS dateTimeSheetItem
                  , sum(timeSheetItemDuration*timeUnitSeconds) / 3600 AS hours
               FROM timeSheetItem
          LEFT JOIN timeUnit ON timeUnitID = timeSheetItemDurationUnitID
              WHERE personID = %d
                AND dateTimeSheetItem >= '%s'
                AND dateTimeSheetItem <= '%s'
           GROUP BY YEAR(dateTimeSheetItem), MONTH(dateTimeSheetItem)",
            $personID,
            $start,
            $end
        );
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);
        while ($row = $allocDatabase->row()) {
            $f = explode('-', $row['dateTimeSheetItem']);
            $info[sprintf('%4d-%02d', $f[0], $f[1])] = $row; // the %02d is just to make sure the months are consistently zero padded
        }

        $s = format_date('U', $start);
        $e = format_date('U', $end);
        $s_months = (date('Y', $s) * 12) + date('m', $s);
        $e_months = (date('Y', $e) * 12) + date('m', $e);

        $num_months_back = $e_months - $s_months;
        $x = 0;
        while ($x <= $num_months_back) {
            $time = mktime(0, 0, 0, date('m', $s) + $x, 1, date('Y', $s));
            $d = date('Y-m', $time);
            $points[] = [$d, sprintf('%d', $info[$d]['hours'])];
            ++$x;
        }

        return $points;
    }
}
