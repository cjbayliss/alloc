<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class token extends db_entity
{
    public $classname = "token";
    public $data_table = "token";
    public $key_field = "tokenID";
    public $data_fields = [
        "tokenHash",
        "tokenEntity",
        "tokenEntityID",
        "tokenActionID",
        "tokenExpirationDate",
        "tokenUsed",
        "tokenMaxUsed",
        "tokenActive",
        "tokenCreatedBy",
        "tokenCreatedDate",
    ];

    public function set_hash($hash, $validate = true)
    {
        $extra = null;
        $validate and $extra = " AND tokenActive = 1";
        $validate and $extra .= " AND (tokenUsed < tokenMaxUsed OR tokenMaxUsed IS NULL OR tokenMaxUsed = 0)";
        $validate and $extra .= unsafe_prepare(" AND (tokenExpirationDate > '%s' OR tokenExpirationDate IS NULL)", date("Y-m-d H:i:s"));

        $q = unsafe_prepare("SELECT * FROM token
                       WHERE tokenHash = '%s'
                      $extra
                     ", $hash);
        // echo "<br><br>".$q;
        $dballoc = new db_alloc();
        $dballoc->query($q);
        if ($dballoc->next_record()) {
            $this->set_id($dballoc->f("tokenID"));
            $this->select();
            return true;
        }
    }

    public function execute()
    {
        $tokenAction = null;
        if ($this->get_id()) {
            if ($this->get_value("tokenActionID")) {
                $tokenAction = new tokenAction();
                $tokenAction->set_id($this->get_value("tokenActionID"));
                $tokenAction->select();
            }
            if ($this->get_value("tokenEntity")) {
                $class = $this->get_value("tokenEntity");
                $entity = new $class;
                if ($this->get_value("tokenEntityID")) {
                    $entity->set_id($this->get_value("tokenEntityID"));
                    $entity->select();
                }
                $method = $tokenAction->get_value("tokenActionMethod");
                $this->increment_tokenUsed();
                if ($entity->get_id()) {
                    return [$entity, $method];
                }
            }
        }
        return [false, false];
    }

    public function increment_tokenUsed()
    {
        $q = unsafe_prepare("UPDATE token SET tokenUsed = coalesce(tokenUsed,0) + 1 WHERE tokenID = %d", $this->get_id());
        $dballoc = new db_alloc();
        $dballoc->query($q);
    }

    public function decrement_tokenUsed()
    {
        $q = unsafe_prepare("UPDATE token SET tokenUsed = coalesce(tokenUsed,0) - 1 WHERE tokenID = %d", $this->get_id());
        $dballoc = new db_alloc();
        $dballoc->query($q);
    }

    public function get_hash_str()
    {
        [$usec, $sec] = explode(' ', microtime());
        $seed = $sec + ($usec * 100000);
        mt_srand($seed);
        $randval = random_int(1, 99_999_999); // get a random 8 digit number
        $randval = sprintf("%-08d", $randval);
        $randval = base_convert($randval, 10, 36);
        return $randval;
    }

    public function generate_hash()
    {
        // Make an eight character base 36 garbage fds3ys79 / also check that we haven't used this ID already
        $randval = $this->get_hash_str();
        while (strlen($randval) < 8 || $this->set_hash($randval, false)) {
            $randval .= $this->get_hash_str();
            $randval = substr($randval, -8);
        }
        return $randval;
    }

    public function select_token_by_entity_and_action($entity, $entityID, $action)
    {
        $q = unsafe_prepare("SELECT token.*, tokenAction.*
                        FROM token
                   LEFT JOIN tokenAction ON token.tokenActionID = tokenAction.tokenActionID
                       WHERE tokenEntity = '%s'
                         AND tokenEntityID = %d
                         AND tokenAction.tokenActionMethod = '%s'
                     ", $entity, $entityID, $action);
        $dballoc = new db_alloc();
        $dballoc->query($q);
        if ($dballoc->next_record()) {
            $this->set_id($dballoc->f("tokenID"));
            $this->select();
            return true;
        }
    }

    public function get_list_filter($filter = [])
    {
        $sql = [];
        $filter["tokenEntity"] and $sql[] = sprintf_implode("token.tokenEntity = '%s'", $filter["tokenEntity"]);
        $filter["tokenEntityID"] and $sql[] = sprintf_implode("token.tokenEntityID = %d", $filter["tokenEntityID"]);
        $filter["tokenHash"] and $sql[] = sprintf_implode("token.tokenHash = '%s'", $filter["tokenHash"]);
        return $sql;
    }

    public static function get_list($_FORM)
    {
        $rows = [];
        $filter = (new token())->get_list_filter($_FORM);

        if (is_array($filter) && count($filter)) {
            $filter = " WHERE " . implode(" AND ", $filter);
        }

        $q = "SELECT * FROM token " . $filter;
        $dballoc = new db_alloc();
        $dballoc->query($q);
        while ($row = $dballoc->next_record()) {
            $rows[$row["tokenID"]] = $row;
        }
        return (array)$rows;
    }
}
