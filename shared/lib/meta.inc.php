<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class meta extends db_entity
{

    private $t;

    // This variable contains the definitive list of all the referential
    // integrity tables that the user is allowed to edit.
    public static $tables = [
        "absenceType"  => "Absence Types",
        "clientStatus" => "Client Statuses",
        // "configType"                => "Config Types",
        // "invoiceStatus"             => "Invoice Statuses",
        "itemType"      => "Item Types",
        "projectType"   => "Project Types",
        "currencyType"  => "Currency Types",
        "projectStatus" => "Project Statuses",
        "taskStatus"    => "Task Statuses",
        // "roleLevel"                 => "Role Levels",
        // "reminderRecuringInterval"  => "Reminder Intervals",
        // "reminderAdvNoticeInterval" => "Advanced Notice Int",
        // "sentEmailType"             => "Sent Email Types",
        "skillProficiency" => "Skill Proficiencies",
        // "changeType"                => "Change Types",
        // "timeSheetStatus"           => "Time Sheet Statuses",
        // "transactionStatus"         => "Transaction Statuses",
        "transactionType"         => "Transaction Types",
        "timeSheetItemMultiplier" => "Time Sheet Multipliers",
        // "productSaleStatus"         => "Product Sale Statuses",
        "taskType" => "Task Types",
    ];

    public function __construct($table = "")
    {
        $this->classname = $table;
        $this->data_table = $table;
        $this->display_field_name = $table . "ID";
        $this->key_field = $table . "ID";
        $this->data_fields = [$table . "Seq", $table . "Active"];
        if ($table == "taskStatus") {
            $this->data_fields[] = "taskStatusLabel";
            $this->data_fields[] = "taskStatusColour";
        } else if ($table == "currencyType") {
            $this->data_fields[] = "currencyTypeLabel";
            $this->data_fields[] = "currencyTypeName";
            $this->data_fields[] = "numberToBasic";
        }
        $this->t = $table; // for internal use
        return parent::__construct();
    }

    public function get_tables()
    {
        return self::$tables;
    }

    public function get_list($include_inactive = false)
    {
        $where = [];
        if ($this->data_table) {
            $include_inactive and $where[$this->data_table . "Active"] = "all"; // active and inactive
            return $this->get_assoc_array(false, false, false, $where);
        }
    }

    public function get_label()
    {
        if ($this->data_table) {
            return self::$tables[$this->data_table];
        }
    }

    public function validate()
    {
        $err = [];
        $this->get_id() or $err[] = "Please enter a Value/ID for the " . $this->get_label();
        $this->get_value($this->t . "Seq") or $err[] = "Please enter a Sequence Number for the " . $this->get_label();
        return parent::validate($err);
    }
}
