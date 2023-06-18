<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class projectCommissionPerson extends DatabaseEntity
{
    public $data_table = "projectCommissionPerson";

    public $display_field_name = "projectID";

    public $key_field = "projectCommissionPersonID";

    public $data_fields = ["projectID", "tfID", "commissionPercent"];

    public function is_owner($person = "")
    {
        $project = new project();
        $project->set_id($this->get_value("projectID"));
        $project->select();
        return $project->is_owner($person);
    }

    public function save()
    {
        // ensure multiple 0% entries cannot be saved.
        if ($this->get_value("commissionPercent") == 0) {
            $allocDatabase = new AllocDatabase();
            $allocDatabase->connect();

            $getProjectCommissionPerson = $allocDatabase->pdo->prepare(
                "SELECT * FROM projectCommissionPerson
                  WHERE projectID = :projectID
                    AND commissionPercent = 0
                    AND projectCommissionPersonID != :projectCommissionPersonID"
            );

            $getProjectCommissionPerson->bindValue(":projectID", $this->get_value("projectID"), PDO::PARAM_INT);
            $getProjectCommissionPerson->bindValue(":projectCommissionPersonID", $this->get_id(), PDO::PARAM_INT);
            $getProjectCommissionPerson->execute();

            if ($getProjectCommissionPerson->fetch(PDO::FETCH_ASSOC)) {
                alloc_error("Only one Time Sheet Commission is allowed to be set to 0%");
                return false;
            }
        }

        return parent::save();
    }
}
