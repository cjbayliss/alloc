<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class tsiHint extends DatabaseEntity
{
    public $classname = 'tsiHint';

    public $data_table = 'tsiHint';

    public $display_field_name = 'projectID';

    public $key_field = 'tsiHintID';

    public $data_fields = [
        'taskID',
        'personID',
        'duration',
        'date',
        'comment',
        'tsiHintCreatedTime',
        'tsiHintCreatedUser',
        'tsiHintModifiedTime',
        'tsiHintModifiedUser',
    ];

    public function add_tsiHint($stuff)
    {
        $extra = null;
        $task = null;
        $ID = null;
        $current_user = &singleton('current_user');
        $errstr = 'Failed to record new time sheet item hint. ';
        $username = $stuff['username'];

        $people = person::get_people_by_username();
        $personID = $people[$username]['personID'];
        $personID || alloc_error('Person ' . $username . ' not found.');

        $taskID = $stuff['taskID'];
        $projectID = $stuff['projectID'];
        $duration = $stuff['duration'];
        $comment = $stuff['comment'];
        $date = $stuff['date'];

        if ($taskID) {
            $task = new Task();
            $task->set_id($taskID);
            $task->select();
            $projectID = $task->get_value('projectID');
            $extra = ' for task ' . $taskID;
        }

        $projectID || alloc_error(sprintf($errstr . 'No project found%s.', $extra));

        $row_projectPerson = projectPerson::get_projectPerson_row($projectID, $current_user->get_id());
        $row_projectPerson || alloc_error($errstr . 'The person(' . $current_user->get_id() . ') has not been added to the project(' . $projectID . ').');

        if ($row_projectPerson && $projectID) {
            // Add new time sheet item
            $tsiHint = new tsiHint();
            ($d = $date) || ($d = date('Y-m-d'));
            $tsiHint->set_value('date', $d);
            $tsiHint->set_value('duration', $duration);
            if (is_object($task)) {
                $tsiHint->set_value('taskID', sprintf('%d', $taskID));
            }

            $tsiHint->set_value('personID', $personID);
            $tsiHint->set_value('comment', $comment);
            $tsiHint->save();
            $ID = $tsiHint->get_id();
        }

        if ($ID) {
            return [
                'status'  => 'yay',
                'message' => $ID,
            ];
        }

        alloc_error($errstr . 'Time hint not added.');
    }

    public static function parse_tsiHint_string($str)
    {
        $rtn = [];
        preg_match('/^([a-zA-Z0-9]+)\s*(\d\d\d\d\-\d\d?\-\d\d?\s+)?([\d\.]+)?\s*(\d+)?\s*(.*)\s*$/i', $str, $m);

        $rtn['username'] = $m[1];
        ($rtn['date'] = trim($m[2])) || ($rtn['date'] = date('Y-m-d'));
        $rtn['duration'] = $m[3];
        $rtn['taskID'] = $m[4];
        $rtn['comment'] = $m[5];

        // change 2010/10/27 to 2010-10-27
        $rtn['date'] = str_replace('/', '-', $rtn['date']);

        return $rtn;
    }
}
