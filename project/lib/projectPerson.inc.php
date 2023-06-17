<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("PERM_PROJECT_PERSON_READ_DETAILS", 256);

class projectPerson extends DatabaseEntity
{
    public $data_table = "projectPerson";
    public $display_field_name = "projectID";
    public $key_field = "projectPersonID";
    public $data_fields = [
        "personID",
        "projectID",
        "emailType",
        "emailDateRegex",
        "rate" => ["type" => "money"],
        "rateUnitID",
        "projectPersonModifiedUser",
        "roleID",
    ];

    public function is_owner($person = "")
    {

        if (!$this->get_id()) {
            return true;
        } else {
            $project = new project();
            $project->set_id($this->get_value("projectID"));
            $project->select();
            return $project->is_owner($person);
        }
    }

    // This is a wrapper to simplify inserts into the projectPerson table using
    // the new role methodology.. role handle is canEditTasks, or isManager atm
    public function set_value_role($roleHandle)
    {
        $allocDatabase = new AllocDatabase();
        $allocDatabase->connect();
        // FIXME: 'role' is a reserved word in mariadb
        // https://mariadb.com/kb/en/roles_overview/
        $matchRole = $allocDatabase->pdo->prepare(
            "SELECT * FROM role
              WHERE roleHandle = ':roleHandle'
                AND roleLevel = 'project'"
        );
        $matchRole->bindValue(":roleHandle", $roleHandle, PDO::PARAM_STR);
        $matchRole->execute();

        $this->set_value("roleID", $matchRole->fetch(PDO::FETCH_ASSOC)["roleID"]);
    }

    /**
     * @deprecated use get_rate instead
     *
     * @param int $projectID
     * @param int $personID
     * @return array
     */
    public static function get_projectPerson_row($projectID, $personID)
    {
        $allocDatabase = new AllocDatabase();
        $allocDatabase->connect();

        $getProjectPerson = $allocDatabase->pdo->prepare(
            "SELECT *
               FROM projectPerson
              WHERE projectID = :projectID
                AND personID = :personID"
        );
        $getProjectPerson->bindValue(":projectID", $projectID, PDO::PARAM_INT);
        $getProjectPerson->bindValue(":personID", $personID, PDO::PARAM_INT);
        $getProjectPerson->execute();

        return $getProjectPerson->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * try to get the default rate in order of: project -> person -> global
     *
     * @param int $projectID
     * @param int $personID
     * @return array ['rate' => int, 'unit' => int]
     */
    public static function get_rate($projectID, $personID)
    {
        // check the project's default rate
        $project = new project($projectID);
        $defaultProjectRate = [
            'rate' => $project->get_value("defaultTimeSheetRate"),
            'unit' => $project->get_value("defaultTimeSheetRateUnitID"),
        ];
        if ((isset($defaultProjectRate['rate']) && (bool)strlen($defaultProjectRate['rate'])) && $defaultProjectRate['unit']) {
            return $defaultProjectRate;
        }

        // otherwise, check user's default rate
        $allocDatabase = new AllocDatabase();
        $allocDatabase->connect();
        $getDefaultTimeSheetRate = $allocDatabase->pdo->prepare(
            "SELECT defaultTimeSheetRate as rate, defaultTimeSheetRateUnitID as unit 
               FROM person 
              WHERE personID = :personID"
        );
        $getDefaultTimeSheetRate->bindValue(":personID", $personID, PDO::PARAM_INT);
        $getDefaultTimeSheetRate->execute();
        $defaultTimeSheetRate = $getDefaultTimeSheetRate->fetch(PDO::FETCH_ASSOC);

        if ((isset($defaultTimeSheetRate['rate']) && (bool)strlen($defaultTimeSheetRate['rate'])) && $defaultTimeSheetRate['unit']) {
            if ($project->get_value("currencyTypeID") != config::get_config_item("currency")) {
                $defaultTimeSheetRate['rate'] = exchangeRate::convert(
                    config::get_config_item("currency"),
                    $defaultTimeSheetRate["rate"],
                    $project->get_value("currencyTypeID")
                );
            }
            return $defaultTimeSheetRate;
        }

        // last, try the global rate
        $rate = config::get_config_item("defaultTimeSheetRate");
        $unit = config::get_config_item("defaultTimeSheetUnit");
        if ((isset($rate) && (bool)strlen($rate)) && $unit) {
            if (config::get_config_item("currency") && $project->get_value("currencyTypeID")) {
                $rate = exchangeRate::convert(
                    config::get_config_item("currency"),
                    $rate,
                    $project->get_value("currencyTypeID")
                );
            }
            return ['rate' => $rate, 'unit' => $unit];
        }
    }
}
