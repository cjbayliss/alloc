<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class projectCommissionPerson extends db_entity
{
    public $data_table = "projectCommissionPerson";
    public $display_field_name = "projectID";
    public $key_field = "projectCommissionPersonID";
    public $data_fields = ["projectID", "tfID", "commissionPercent"];

    function is_owner($person = "")
    {
        $project = new project();
        $project->set_id($this->get_value("projectID"));
        $project->select();
        return $project->is_owner($person);
    }

    function save()
    {
        // Just ensure multiple 0 entries cannot be saved.
        if ($this->get_value("commissionPercent") == 0) {
            $q = prepare("SELECT * FROM projectCommissionPerson WHERE projectID = %d AND commissionPercent = 0 AND projectCommissionPersonID != %d", $this->get_value("projectID"), $this->get_id());
            $db = new db_alloc();
            $db->query($q);
            if ($db->next_record()) {
                $fail = true;
                alloc_error("Only one Time Sheet Commission is allowed to be set to 0%");
            }
        }
        if (!$fail) {
            parent::save();
        }
    }
}
