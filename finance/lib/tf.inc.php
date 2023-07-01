<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class tf extends DatabaseEntity
{
    public $data_table = 'tf';

    public $display_field_name = 'tfName';

    public $key_field = 'tfID';

    public $data_fields = [
        'tfName',
        'tfComments',
        'tfModifiedUser',
        'tfModifiedTime',
        'qpEmployeeNum',
        'quickenAccount',
        'tfActive',
    ];

    public function get_balance($where = [], $debug = '')
    {
        $tfID = null;
        $current_user = &singleton('current_user');

        // If no status is requested then default to approved.
        $where['status'] || ($where['status'] = 'approved');

        if (!$this->is_owner() && !$current_user->have_role('admin')) {
            return false;
        }

        // Get belance
        $allocDatabase = new AllocDatabase();
        $query = unsafe_prepare(
            'SELECT sum( if(fromTfID=%d,-amount,amount) * pow(10,-currencyType.numberToBasic)) AS balance
               FROM transaction
          LEFT JOIN currencyType ON transaction.currencyTypeID = currencyType.currencyTypeID
              WHERE (tfID = %d or fromTfID = %d) ',
            $this->get_id(),
            $this->get_id(),
            $this->get_id()
        );

        // Build up the rest of the WHERE sql
        foreach ($where as $column_name => $value) {
            $op = ' = ';
            if (is_array($value)) {
                $op = $value[0];
                $value = $value[1];
            }

            $query .= ' AND ' . $column_name . $op . " '" . db_esc($value) . "'";
        }

        // echo "<br>".$debug." q: ".$query;
        $allocDatabase->query($query);
        $allocDatabase->next_record() || alloc_error(sprintf('TF %s not found in tf::get_balance', $tfID));

        return $allocDatabase->f('balance');
    }

    public function is_owner($person = ''): bool
    {
        $current_user = &singleton('current_user');
        static $owners;
        if ('' == $person) {
            $person = $current_user;
        }

        if (!$this->get_id()) {
            return false;
        }

        // optimization
        if (isset($owners[$person->get_id()])) {
            return in_array($this->get_id(), $owners[$person->get_id()]);
        }

        $owners[$person->get_id()] = $this->get_tfs_for_person($person->get_id());

        return in_array($this->get_id(), (array) $owners[$person->get_id()]);
    }

    public function get_tfs_for_person($personID)
    {
        $owners = [];
        $query = unsafe_prepare('SELECT * FROM tfPerson WHERE personID=%d', $personID);
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($query);
        while ($row = $allocDatabase->row()) {
            $owners[] = $row['tfID'];
        }

        return $owners;
    }

    public function get_nav_links()
    {
        global $TPL;
        $current_user = &singleton('current_user');

        $nav_links = [];

        // Alla melded the have entity perm for transactionRepeat into the
        // have entity perm for transaction because I figured they were the
        // same and it nukes the error message!

        if (have_entity_perm('tf', PERM_UPDATE, $current_user, $this->is_owner())) {
            $statement_url = $TPL['url_alloc_tf'] . 'tfID=' . $this->get_id();
            $statement_link = sprintf('<a href="%s">Edit TF</a>', $statement_url);
            $nav_links[] = $statement_link;
        }

        return $nav_links;
    }

    public function get_link($ignored = false)
    {
        $current_user = &singleton('current_user');
        global $TPL;
        if (have_entity_perm('transaction', PERM_READ, $current_user, $this->is_owner())) {
            return '<a href="' . $TPL['url_alloc_transactionList'] . 'tfID=' . $this->get_id() . '">' . $this->get_value('tfName', DST_HTML_DISPLAY) . '</a>';
        }

        return $this->get_value('tfName', DST_HTML_DISPLAY);
    }

    public function get_name($tfID = false)
    {
        if ($tfID) {
            $allocDatabase = new AllocDatabase();
            $allocDatabase->query(unsafe_prepare('SELECT tfName FROM tf WHERE tfID=%d', $tfID));
            $allocDatabase->next_record();

            return $allocDatabase->f('tfName');
        }
    }

    public static function get_tfID($name)
    {
        $rtn = [];
        if ($name) {
            $allocDatabase = new AllocDatabase();
            $q = 'SELECT tfID FROM tf WHERE ' . sprintf_implode("tfName = '%s'", $name);
            $allocDatabase->query($q);
            while ($row = $allocDatabase->row()) {
                $rtn[] = $row['tfID'];
            }
        }

        return (array) $rtn;
    }

    public static function get_permitted_tfs($requested_tfs = [])
    {
        $r = [];
        $rtn = [];
        $current_user = &singleton('current_user');
        // If admin, just use the requested tfs
        if ($current_user->have_role('admin')) {
            $rtn = $requested_tfs;

            // If not admin, then remove the items from $requested_tfs that the user can't access
        } else {
            $allowed_tfs = (array) (new tf())->get_tfs_for_person($current_user->get_id());
            foreach ((array) $requested_tfs as $tf) {
                if (in_array($tf, $allowed_tfs)) {
                    $rtn[] = $tf;
                }
            }
        }

        // db_esc everything
        foreach ((array) $rtn as $tf) {
            $r[] = db_esc($tf);
        }

        return (array) array_unique((array) $r);
    }

    public static function get_list_filter($_FORM = [])
    {
        $filter1 = [];
        $filter2 = [];
        $current_user = &singleton('current_user');

        if (!isset($_FORM['tfIDs']) && !$current_user->have_role('admin')) {
            $_FORM['owner'] = true;
        }

        if (isset($_FORM['owner'])) {
            $filter1[] = sprintf_implode('tfPerson.personID = %d', $current_user->get_id());
        }

        $tfIDs = tf::get_permitted_tfs($_FORM['tfIDs'] ?? []);
        $tfIDs && ($filter1[] = sprintf_implode('tf.tfID = %d', $tfIDs));
        $tfIDs && ($filter2[] = sprintf_implode('tf.tfID = %d', $tfIDs));
        if (empty($_FORM['showall'])) {
            $filter1[] = '(tf.tfActive = 1)';
        }

        if (empty($_FORM['showall'])) {
            $filter2[] = '(tf.tfActive = 1)';
        }

        return [$filter1, $filter2];
    }

    public static function get_list($_FORM = [])
    {
        $f2 = null;
        $adds = [];
        $pending_adds = [];
        $subs = [];
        $pending_subs = [];
        $f = null;
        $rows = [];
        $current_user = &singleton('current_user');

        [$filter1, $filter2] = tf::get_list_filter($_FORM);

        if (is_array($filter1) && count($filter1)) {
            $f = ' AND ' . implode(' AND ', $filter1);
        }

        if (is_array($filter2) && count($filter2)) {
            $f2 = ' AND ' . implode(' AND ', $filter2);
        }

        $allocDatabase = new AllocDatabase();
        $q = unsafe_prepare("SELECT transaction.tfID as id, tf.tfName, transactionID, transaction.status,
                             sum(amount * pow(10,-currencyType.numberToBasic)) AS balance
                        FROM transaction
                   LEFT JOIN currencyType ON currencyType.currencyTypeID = transaction.currencyTypeID
                   LEFT JOIN tf on transaction.tfID = tf.tfID
                       WHERE 1 AND transaction.status != 'rejected' " . $f2 . '
                    GROUP BY transaction.status,transaction.tfID');
        $allocDatabase->query($q);
        while ($row = $allocDatabase->row()) {
            if ('approved' == $row['status']) {
                $adds[$row['id']] = $row['balance'];
            } elseif ('pending' == $row['status']) {
                $pending_adds[$row['id']] = $row['balance'];
            }
        }

        $q = unsafe_prepare("SELECT transaction.fromTfID as id, tf.tfName, transactionID, transaction.status,
                             sum(amount * pow(10,-currencyType.numberToBasic)) AS balance
                        FROM transaction
                   LEFT JOIN currencyType ON currencyType.currencyTypeID = transaction.currencyTypeID
                   LEFT JOIN tf on transaction.fromTfID = tf.tfID
                       WHERE 1 AND transaction.status != 'rejected' " . $f2 . '
                    GROUP BY transaction.status,transaction.fromTfID');
        $allocDatabase->query($q);
        while ($row = $allocDatabase->row()) {
            if ('approved' == $row['status']) {
                $subs[$row['id']] = $row['balance'];
            } elseif ('pending' == $row['status']) {
                $pending_subs[$row['id']] = $row['balance'];
            }
        }

        $q = unsafe_prepare('SELECT tf.*
                        FROM tf
                   LEFT JOIN tfPerson ON tf.tfID = tfPerson.tfID
                       WHERE 1 ' . $f . '
                    GROUP BY tf.tfID
                    ORDER BY tf.tfName');

        $allocDatabase->query($q);
        $total = 0;
        $pending_total = 0;
        while ($row = $allocDatabase->row()) {
            $tf = new tf();
            $tf->read_db_record($allocDatabase);
            $tf->set_values();

            if (!empty($adds[$allocDatabase->f('tfID')])) {
                $total = ($adds[$allocDatabase->f('tfID')] - $subs[$allocDatabase->f('tfID')]);
                $pending_total = $pending_adds[$allocDatabase->f('tfID')] - $pending_subs[$allocDatabase->f('tfID')];
            }

            if (have_entity_perm('transaction', PERM_READ, $current_user, $tf->is_owner())) {
                $row['tfBalance'] = Page::money(config::get_config_item('currency'), $total, '%s%m %c');
                $row['tfBalancePending'] = Page::money(config::get_config_item('currency'), $pending_total, '%s%m %c');
                $row['total'] = $total;
                $row['pending_total'] = $pending_total;
            } else {
                $row['tfBalance'] = '';
                $row['tfBalancePending'] = '';
                $row['total'] = '';
                $row['pending_total'] = '';
            }

            $nav_links = $tf->get_nav_links();
            $row['nav_links'] = implode(' ', $nav_links);
            $row['tfActive_label'] = '';
            $tf->get_value('tfActive') && ($row['tfActive_label'] = 'Y');
            $rows[$tf->get_id()] = $row;
        }

        return (array) $rows;
    }
}
