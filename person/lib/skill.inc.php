<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class skill extends db_entity
{
    public $data_table = "skill";
    public $display_field_name = "skillName";
    public $key_field = "skillID";
    public $data_fields = ["skillName", "skillDescription", "skillClass"];

    // return true if a skill with same name and class already exists
    // and update fields of current if it does exist
    public function skill_exists()
    {
        $query = "SELECT * FROM skill";
        $query .= unsafe_prepare(" WHERE skillName='%s'", $this->get_value('skillName'));
        $query .= unsafe_prepare(" AND skillClass='%s'", $this->get_value('skillClass'));
        $db = new db_alloc();
        $db->query($query);
        if ($db->next_record()) {
            $skill = new skill();
            $skill->read_db_record($db);
            $this->set_id($skill->get_id());
            $this->set_value('skillDescription', $skill->get_value('skillDescription'));
            return true;
        }
        return false;
    }

    public static function get_skill_classes()
    {
        $db = new db_alloc();
        $skill_classes = ["" => "Any Class"];
        $query = "SELECT skillClass FROM skill ORDER BY skillClass";
        $db->query($query);
        while ($db->next_record()) {
            $skill = new skill();
            $skill->read_db_record($db);
            if (!in_array($skill->get_value('skillClass'), $skill_classes)) {
                $skill_classes[$skill->get_value('skillClass')] = $skill->get_value('skillClass');
            }
        }
        return $skill_classes;
    }

    public static function get_skills()
    {
        global $TPL;
        global $skill_class;
        $skills = ["" => "Any Skill"];
        $query = "SELECT * FROM skill";
        if ($skill_class != "") {
            $query .= unsafe_prepare(" WHERE skillClass='%s'", $skill_class);
        }
        $query .= " ORDER BY skillClass,skillName";
        $db = new db_alloc();
        $db->query($query);
        while ($db->next_record()) {
            $skill = new skill();
            $skill->read_db_record($db);
            $skills[$skill->get_id()] = sprintf("%s - %s", $skill->get_value('skillClass'), $skill->get_value('skillName'));
        }
        return $skills;
    }
}
