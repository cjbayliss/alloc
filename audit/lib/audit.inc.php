<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class audit extends DatabaseEntity
{
    public $data_table = "audit";
    public $key_field = "auditID";
    public $data_fields = [
        "auditID",
        "taskID",
        "projectID",
        "personID",
        "dateChanged",
        "field",
        "value",
    ];

    /**
     * Get a list of task history items with sophisticated filtering and
     * somewhat sophisticated output
     *
     * (n.b., the output from this generally needs to be post-processed to
     * handle the semantic meaning of changes in various fields)
     *
     * @param array $_FORM
     * @return array $rows an array of audit records
     */
    public static function get_list($_FORM)
    {
        $where_clause = null;
        $rows = [];
        $filter = audit::get_list_filter($_FORM);

        if (is_array($filter) && count($filter)) {
            $where_clause = " WHERE " . implode(" AND ", $filter);
        }

        if ($_FORM["projectID"]) {
            $entity = new project();
            $entity->set_id($_FORM["projectID"]);
            $entity->select();
        } else if ($_FORM["taskID"]) {
            $entity = new Task();
            $entity->set_id($_FORM["taskID"]);
            $entity->select();
        }

        $allocDatabase = new AllocDatabase();
        $allocDatabase->query("SELECT *
                      FROM audit
                    $where_clause
                  ORDER BY dateChanged");

        $items = [];
        while ($row = $allocDatabase->next_record()) {
            $audit = new audit();
            $audit->read_db_record($allocDatabase);
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Get an array to use as a filter
     *
     * @param array $filter
     * @return array $sql an array of project or task id to filter by. e.g.: Array([0] => (taskID = 1))
     */
    public static function get_list_filter($filter)
    {
        $sql = [];
        $filter["taskID"] and $sql[] = unsafe_prepare("(taskID = %d)", $filter["taskID"]);
        $filter["projectID"] and $sql[] = unsafe_prepare("(projectID = %d)", $filter["projectID"]);
        return $sql;
    }

    public function get_list_vars()
    {
        return [
            "taskID"    => "The task id to find audit records for",
            "projectID" => "The project id to find audit records for",
        ];
    }
}
