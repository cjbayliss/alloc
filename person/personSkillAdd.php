<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once(__DIR__ . "/../alloc.php");

// add new skill to database
if ($_POST["add_skill"]) {
    $failed = false;
    $skill = new skill();
    if ($_POST["new_skill_class"] != "") {
        $skill->set_value('skillClass', $_POST["new_skill_class"]);
    } elseif ($_POST["other_new_skill_class"] != "") {
        $skill->set_value('skillClass', $_POST["other_new_skill_class"]);
    } else {
        $failed = true;
    }

    if ($_POST["other_new_skill_name"] != "") {
        $skill->set_value('skillName', $_POST["other_new_skill_name"]);
        // description for now can be the same as the name
        $skill->set_value('skillDescription', $_POST["other_new_skill_name"]);
    } else {
        $failed = true;
    }

    if ($failed == false && $skill->skill_exists() == false) {
        $skill->save();
    }
}

if ($_POST["delete_skill"]) {
    $skill = new skill();
    if ($_POST["new_skill_name"] != "") {
        $skill->set_id($_POST["new_skill_name"]);
        $skill->delete();
    }
}

$skill_classes = skill::get_skill_classes();
$skill_classes[""] = ">> OTHER >>";
$TPL["new_skill_classes"] = Page::select_options($skill_classes, $_POST["skill_class"]);

$skills = skill::get_skills();
// if a skill class is selected and a skill that is not in that class is also selected, clear the skill as this is what the filter options will do
if ($skill_class && !in_array($skills[$_POST["skill"]], $skills)) {
    $_POST["skill"] = "";
}

$skills[""] = ">> NEW >>";
$TPL["new_skills"] = Page::select_options($skills, $_POST["skill"]);

$TPL["main_alloc_title"] = "Edit Skills - " . APPLICATION_NAME;
if ($current_user->have_perm(PERM_PERSON_READ_MANAGEMENT)) {
    include_template("templates/personSkillAdd.tpl");
}
