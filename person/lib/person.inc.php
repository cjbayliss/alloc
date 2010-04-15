<?php

/*
 * Copyright (C) 2006, 2007, 2008 Alex Lance, Clancy Malcolm, Cybersource
 * Pty. Ltd.
 * 
 * This file is part of the allocPSA application <info@cyber.com.au>.
 * 
 * allocPSA is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at
 * your option) any later version.
 * 
 * allocPSA is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with allocPSA. If not, see <http://www.gnu.org/licenses/>.
*/

define("PERM_PERSON_READ_DETAILS", 256);
define("PERM_PERSON_READ_MANAGEMENT", 512);
define("PERM_PERSON_WRITE_MANAGEMENT", 1024);
define("PERM_PERSON_WRITE_ROLES", 2048);
define("PERM_PERSON_SEND_EMAIL", 4096);

class person extends db_entity {
  public $classname = "person";
  public $data_table = "person";
  public $display_field_name = "username";
  public $key_field = "personID";
  public $data_fields = array("username"
                             ,"lastLoginDate"
                             ,"password"          => array("read_perm_name"=>PERM_PERSON_READ_DETAILS)
                             ,"perms"             => array("write_perm_name"=>PERM_PERSON_WRITE_ROLES)
                             ,"emailAddress"
                             ,"availability"      => array("read_perm_name"=>PERM_PERSON_READ_DETAILS)
                             ,"areasOfInterest"   => array("read_perm_name"=>PERM_PERSON_READ_DETAILS)
                             ,"comments"          => array("read_perm_name"=>PERM_PERSON_READ_DETAILS)
                             ,"managementComments"=> array("read_perm_name"=>PERM_PERSON_READ_MANAGEMENT
                                                          ,"write_perm_name"=>PERM_PERSON_WRITE_MANAGEMENT)
                             ,"firstName"
                             ,"surname"
                             ,"preferred_tfID"
                             ,"personActive"
                             ,"sessData"          => array("read_perm_name"=>PERM_PERSON_READ_DETAILS)
                             ,"phoneNo1"
			                       ,"phoneNo2"
      			                 ,"emergencyContact"
                             );
  
  public $prefs = array();
  public $permissions = array(PERM_PERSON_READ_DETAILS => "read details"
                             ,PERM_PERSON_READ_MANAGEMENT => "read management fields"
                             ,PERM_PERSON_WRITE_MANAGEMENT => "write management fields"
                             ,PERM_PERSON_WRITE_ROLES => "set roles"
                             ,PERM_PERSON_SEND_EMAIL => "send mail-outs");


  function get_tasks_for_email() {

    $format = "text";

    $options = array();
    #$options["projectType"] = "mine";
    $options["limit"] = 3;
    $options["current_user"] = $this->get_id();
    $options["personID"] = $this->get_id();
    $options["taskView"] = "prioritised";
    $options["return"] = $format;
    $options["taskStatus"] = "open";

    $summary = task::get_list($options);

    if ($summary) {
      $topThree = "\n\nTop Three Tasks";
      $topThree.= $summary;
    } 

    unset($summary);
    unset($options["limit"]);
    $options["taskDate"] = "due_today";
    $summary = task::get_list($options);

    if ($summary) {
      $dueToday = "\n\nTasks Due Today";
      $dueToday.= $summary;
    } 

    unset($summary);
    unset($options["limit"]);
    $options["taskDate"] = "new";
    $summary = task::get_list($options);

    if ($summary) {
      $newTasks = "\n\nNew Tasks";
      $newTasks.= $summary;
    } 

    return $topThree.$dueToday.$newTasks;
  }

  function get_announcements_for_email() {
    $db = new db_alloc;
    $db->query("SELECT * FROM announcement WHERE CURDATE() <= displayToDate AND CURDATE() >= displayFromDate");

    while ($db->next_record()) {
      $announcement["heading"] = "Announcement\n".$db->f("heading");
      $announcement["body"] = $db->f("body");
    }
    return $announcement;
  }

  function get_project_persons($extra_condition = "") {
    // Return an array of project_person objects that this user is associated with
    // $extra_condition: A SQL expression added to the WHERE clause of the query
    $query = "SELECT * FROM projectPerson WHERE projectPerson.personID=".$this->get_id();
    if ($extra_condition) {
      $query.= " AND (".$extra_condition.")";
    }
    $db = new db_alloc;
    $db->query($query);
    $rtn = array();
    while ($db->next_record()) {
      $project_person = new projectPerson;
      $project_person->read_db_record($db);
      $rtn[] = $project_person;
    }

    return $rtn;
  }

  function have_role($perm_name) {
    $perms = explode(",",$this->get_value("perms"));
    return (in_array("god",$perms) || in_array($perm_name,$perms));
  }

  function check_role($perm_name) {
    if (!$this->have_role($perm_name)) {
      die("Pemission denied");
    }
  }

  function is_employee() {
    // Function to check if the person is an employee
    global $current_user;
    return true;
    $permissions = explode(",", $current_user->get_value("perms"));

    if (in_array("employee", $permissions)) {
      return true;
    } else {
      return false;
    }
  }

  function check_employee() {
    // Ensure the current user is an employee
    if (!$this->is_employee()) {
      die("You must be an employee to access this function");
    }
  }

  function get_skills($proficiency) {
    // Return a string of skills with a given proficiency
    $query = "SELECT * FROM proficiency LEFT JOIN skill on proficiency.skillID=skill.skillID";
    $query.= sprintf(" WHERE personID=%d AND skillProficiency='%s' ORDER BY skillName", $this->get_id(), $proficiency);

    $db = new db_alloc;
    $db->query($query);
    while ($db->next_record()) {
      $skill = new skill;
      $skill->read_db_record($db);
      if ($rtn) {
        $rtn.= ", ";
      }
      $rtn.= $skill->get_value('skillName');
    }
    return $rtn;
  }

  function get_username_list($push_personID="") {
    static $rows;

    // Cache rows
    if(!$rows) {
      $q = sprintf("SELECT personID, username, firstName, surname, personActive FROM person ORDER BY firstname,surname,username");
      $db = new db_alloc;
      $db->query($q);
      while($db->next_record()) {
        if ($db->f("firstName") && $db->f("surname")){
          $name = $db->f("firstName")." ".$db->f("surname");
        } else {
          $name = $db->f("username");
        }
      
        $rows[$db->f("personID")] = array("active"=>$db->f("personActive"), "name"=>$name);

      }
    }

    // If person is active or the person is the selected person (to enable drpodown list to have people who have since been made inactive)
    foreach ($rows as $personID => $info) {
      if ($info["active"] || $personID == $push_personID) {
        $rtn[$personID] = $info["name"];
      }
    }

    return $rtn;
  }

  // Static
  function get_fullname($personID) {
    // Get vars for the emails below
    $people_cache = get_cached_table("person");
    return $people_cache[$personID]["name"];
  } 

  function get_username($long_format=false) {
    
    // If id is a $person object
    if ($this->get_value("username")) {
      $firstName = $this->get_value("firstName");
      $surname   = $this->get_value("surname");
      $username  = $this->get_value("username");
    } 
    if ($long_format && $firstName && $surname){
      return $firstName." ".$surname;
    } else {
      return $username;
    }
  }

  function get_tfIDs() {
    $db = new db_alloc;
    $db->query("SELECT tfID FROM tfPerson WHERE personID = %d",$this->get_id());
    while ($row = $db->row()) {
      $tfIDs[] = $row["tfID"];
    }
    return $tfIDs;
  }

  function get_valid_login_row($username, $password="") {
    $db = new db_alloc;
    $q = sprintf("SELECT * FROM person WHERE username = '%s' AND personActive = 1"
                 ,db_esc($username));

    $db->query($q);
    $row = $db->row();

    if (check_password($password, $row["password"])) {
      return $row;
    }
  }

  function load_current_user($personID) {
    $this->set_id($personID);
    if ($this->select()) {
      $this->load_prefs();
    }
  }

  function load_prefs() {
    $this->prefs = unserialize($this->get_value("sessData"));
    isset($this->prefs["topTasksNum"]) or $this->prefs["topTasksNum"] = 5;
    $this->prefs["topTasksStatus"] or $this->prefs["topTasksStatus"] = "open";
    isset($this->prefs["projectListNum"]) or $this->prefs["projectListNum"] = "10";
    isset($this->prefs["tasksGraphPlotHome"]) or $this->prefs["tasksGraphPlotHome"] = "4";
    isset($this->prefs["tasksGraphPlotHomeStart"]) or $this->prefs["tasksGraphPlotHomeStart"] = "1";
    isset($this->prefs["receiveOwnTaskComments"]) or $this->prefs["receiveOwnTaskComments"] = "no";
  }

  function store_prefs() {
    $p = new person;
    $p->set_id($this->get_id());
    $p->select();
    $p->load_prefs();
  
    $old_prefs = $p->prefs or $old_prefs = array();
    foreach ($old_prefs as $k => $v) {
      if ($this->prefs[$k] != $v) {
        $save = true;
      }
    }
    foreach ($this->prefs as $k => $v) {
      if ($old_prefs[$k] != $v) {
        $save = true;
      }
    }

    if ($save || (!is_array($old_prefs) || !count($old_prefs))) {
      $arr = serialize($this->prefs);
      $p->set_value("sessData",$arr);
      $p->save();
    }

  }

  function has_messages() {
    if (is_object($this)) {
      $db = new db_alloc;
      $query = sprintf("SELECT * 
                          FROM task 
                         WHERE taskTypeID = %d
                           AND personID = %d
                           AND taskStatus != 'closed'"
                       ,TT_MESSAGE,$this->get_id());
      $db->query($query);
      if ($db->next_record()) {
        return true;
      } 
    } 
    return false;
  } 

  function find_by_name($name=false) {

    $stack1 = array();
    $people = get_cached_table("person");
    foreach ($people as $personID => $row) {
      similar_text($row["name"],$name,$percent1);
      $stack1[$personID] = $percent1;
    }

    asort($stack1);
    end($stack1);
    $probable1_personID = key($stack1);
    $person_percent1 = current($stack1);

    if ($probable1_personID && $person_percent1 > 70) {
      return $probable1_personID;
    }
  }

  function find_by_email($email=false) {
    $email = str_replace(array("<",">"),"",$email);
    $people = get_cached_table("person");
    foreach ($people as $personID => $row) {
      if ($email == str_replace(array("<",">"),"",$row["emailAddress"])) {
        return $personID;
      }
    }
  }

  function get_from() {
    $name = $this->get_username(1);
    $email = $this->get_value("emailAddress");
    if ($email) {
      $str = $name;
      $str and $str.= " <";
      $str and $end = ">";
      return $str.$email.$end;
    }
  }

  function get_list_filter($filter=array()) {
    if ($filter["username"]) {
      $sql[] = sprintf("(username = '%s')", db_esc($filter["username"]));
    }
    if ($filter["personActive"]) {
      $sql[] = sprintf("(personActive = '%d')", db_esc($filter["personActive"]));
    }
    if ($filter["firstName"]) {
      $sql[] = sprintf("(firstName = '%s')", db_esc($filter["firstName"]));
    }
    if ($filter["surname"]) {
      $sql[] = sprintf("(surname = '%s')", db_esc($filter["surname"]));
    }
    if ($filter["personID"]) {
      $sql[] = sprintf("(personID = '%d')", db_esc($filter["personID"]));
    }
    if ($filter["skill"]) {
      $sql[] = sprintf("(skillID=%d)", $filter["skill"]);
    } else if ($filter["skill_class"]) {
      $q = sprintf("SELECT * FROM skill WHERE skillClass='%s'", db_esc($filter["skill_class"]));
      $db = new db_alloc();
      $db->query($q);
      while ($db->next_record()) {
        $skill = new skill;
        $skill->read_db_record($db);
        $sql2[] = sprintf("(skillID=%d)", $skill->get_id());
      } 
    }
    if ($filter["expertise"]) {
      $sql[] = sprintf("(skillProficiency='%s')", db_esc($filter["expertise"]));
    } 

    return array($sql,$sql2);
  }

  function get_list($_FORM=array()) {
    global $TPL, $current_user;
    list($filter,$filter2) = person::get_list_filter($_FORM);

    $debug = $_FORM["debug"];
    $debug and print "<pre>_FORM: ".print_r($_FORM,1)."</pre>";
    $debug and print "<pre>filter: ".print_r($filter,1)."</pre>";

    $_FORM["return"] or $_FORM["return"] = "html";

    // Get averages for hours worked over the past fortnight and year
    if ($current_user->have_perm(PERM_PERSON_READ_MANAGEMENT) && $_FORM["showHours"]) {
      $t = new timeSheetItem;
      list($ts_hrs_col_1,$ts_dollars_col_1) = $t->get_averages(date("Y-m-d",mktime(0,0,0,date("m"),date("d")-14, date("Y"))));
      list($ts_hrs_col_2,$ts_dollars_col_2) = $t->get_fortnightly_average();
    } else {
      unset($_FORM["showHours"]);
    }

    // A header row
    $summary.= person::get_list_tr_header($_FORM);

    if (is_array($filter) && count($filter)) {
      $filter = " WHERE ".implode(" AND ",$filter);
    }
    if (is_array($filter2) && count($filter2)) {
      $filter.= " AND ".implode(" OR ",$filter2);
    }

    $q = "SELECT person.*
            FROM person
       LEFT JOIN proficiency ON person.personID = proficiency.personID
           ".$filter."
        GROUP BY username
        ORDER BY firstName,surname,username";

    $debug and print "Query: ".$q;
    $db = new db_alloc;
    $db->query($q);

    while ($row = $db->next_record()) {
      $p = new person;
      if (!$p->read_db_record($db,false)) {
        continue;
      }
      $row = $p->perm_cleanup($row); // this is not the right way to do this - alla
      $print = true;
      $_FORM["showHours"] and $row["hoursSum"] = $ts_hrs_col_1[$row["personID"]];
      $_FORM["showHours"] and $row["hoursAvg"] = $ts_hrs_col_2[$row["personID"]];

      $row["name"] = $p->get_username(1);
      $row["name_link"] = $p->get_link();
      $row["personActive_label"] = $p->get_value("personActive") == 1 ? "Y":"";
      
      if ($_FORM["showSkills"]) {
        $senior_skills = $p->get_skills('Senior');
        $advanced_skills = $p->get_skills('Advanced');
        $intermediate_skills = $p->get_skills('Intermediate');
        $junior_skills = $p->get_skills('Junior');
        $novice_skills = $p->get_skills('Novice');

        $skills = array();
        $senior_skills       and $skills[] = "<img src=\"../images/skill_senior.png\" alt=\"Senior=\"> ".$senior_skills;
        $advanced_skills     and $skills[] = "<img src=\"../images/skill_advanced.png\" alt=\"Advanced=\"> ".$advanced_skills;
        $intermediate_skills and $skills[] = "<img src=\"../images/skill_intermediate.png\" alt=\"Intermediate=\"> ".$intermediate_skills;
        $junior_skills       and $skills[] = "<img src=\"../images/skill_junior.png\" alt=\"Junior=\"> ".$junior_skills;
        $novice_skills       and $skills[] = "<img src=\"../images/skill_novice.png\" alt=\"Novice\"> ".$novice_skills;
        $row["skills_list"] = implode("<br>",$skills);
      }
  
      if ($_FORM["showLinks"]) {
        $row["navLinks"] = '<a href="'.$TPL["url_alloc_taskList"].'personID='.$row["personID"].'&taskView=byProject&applyFilter=1';
        $row["navLinks"].= '&dontSave=1&taskStatus=open&projectType=curr">Tasks</a>&nbsp;&nbsp;';
        $row["navLinks"].= '<a href="'.$TPL["url_alloc_personGraph"].'personID='.$row["personID"].'">Graph</a>&nbsp;&nbsp;';
        $row["navLinks"].= '<a href="'.$TPL["url_alloc_taskCalendar"].'personID='.$row["personID"].'">Calendar</a>';
      }

      $summary.= person::get_list_tr($row,$_FORM);
      $rows[$row["personID"]] = $row;
    }

    $rows or $rows = array();
    if ($print && $_FORM["return"] == "array") {
      return $rows;

    } else if ($print && $_FORM["return"] == "html") {
      return "<table class=\"list sortable\">".$summary."</table>";

    } else if (!$print && $_FORM["return"] == "html") {
      return "<table style=\"width:100%\"><tr><td colspan=\"10\" style=\"text-align:center\"><b>No People Found</b></td></tr></table>";
    }
  }

  function get_list_tr_header($_FORM) {
    if ($_FORM["showHeader"]) {
      $summary[] = "<tr>";
      $_FORM["showName"]    and $summary[] = "<th>Name</th>";
      $_FORM["showActive"]  and $summary[] = "<th>Enabled</th>";
      $_FORM["showNos"]     and $summary[] = "<th>Contact</th>";
      if ($_FORM["showSkills"]) {
        $summary[] = "<th>";
        $summary[] = "Senior";
        $summary[] = '<img src="../images/skill_senior.png" alt="S" align="absmiddle">';
        $summary[] = '<img src="../images/skill_advanced.png" alt="A" align="absmiddle">';
        $summary[] = '<img src="../images/skill_intermediate.png" alt="I" align="absmiddle">';
        $summary[] = '<img src="../images/skill_junior.png" alt="J" align="absmiddle">';
        $summary[] = '<img src="../images/skill_novice.png" alt="N" align="absmiddle"> Novice';
        $summary[] = "</th>";
      }
      $_FORM["showHours"]   and $summary[] = "<th>Sum Prev Fort</th>";
      $_FORM["showHours"]   and $summary[] = "<th>Avg Per Fort</th>";
      $_FORM["showLinks"]   and $summary[] = "<th class=\"noprint\">Actions</th>";
      $summary[] ="</tr>";
      $summary = "\n".implode("\n",$summary);
      return $summary;
    }
  }

  function get_list_tr($row, $_FORM) {
    $row["phoneNo1"] && $row["phoneNo2"] and $row["phoneNo1"].= " / ";
    $summary[] = "<tr>";
    $_FORM["showName"]    and $summary[] = "  <td>".$row["name_link"]."</td>";
    $_FORM["showActive"]  and $summary[] = "  <td>".$row["personActive_label"]."</td>";
    $_FORM["showNos"]     and $summary[] = "  <td>".$row["phoneNo1"].$row["phoneNo2"]."</td>";
    $_FORM["showSkills"]  and $summary[] = "  <td>".$row["skills_list"]."</td>";
    $_FORM["showHours"]   and $summary[] = "  <td>".sprintf("%0.1f",$row["hoursSum"])."</td>";
    $_FORM["showHours"]   and $summary[] = "  <td>".sprintf("%0.1f",$row["hoursAvg"])."</td>";
    $_FORM["showLinks"]   and $summary[] = "  <td class=\"nobr noprint\" align=\"right\" width=\"1%\">".$row["navLinks"]."</td>";
    $summary[] = "</tr>";
    $summary = "\n".implode("\n",$summary);
    return $summary;
  }

  function get_list_vars() {
    return array("return"       => "[MANDATORY] eg: array | html"
                ,"username"     => "Search by the person username"
                ,"personActive" => "Search by persons active/inactive status eg: 1 | 0"
                ,"firstName"    => "Search by persons first name"
                ,"surname"      => "Search by persons last name"
                ,"personID"     => "Search by persons ID"
                ,"skill"        => "Search by a particular skill"
                ,"skill_class"  => "Search by a particular class of skill"
                ,"expertise"    => "Search by a level of expertise eg: Novice | Junior | Intermediate | Advanced | Senior"
                ,"applyFilter"  => "Saves this filter as the persons preference"
                ,"dontSave"     => "A flag that allows the user to specify that the filter preferences should not be saved this time"
                ,"form_name"    => "The name of this form, i.e. a handle for referring to this saved form"
                ,"showHeader"   => "Show the HTML header row of the table"
                ,"showName"     => "Show the persons name"
                ,"showActive"   => "Show the persons active/inactive status"
                ,"showNos"      => "Show the persons contact numbers"
                ,"showHours"    => "Show the persons time sheeted hours figures"
                ,"showLinks"    => "Show the person action links"
                ,"showSkills"   => "Show the persons skills"
                );
  }

  function load_form_data($defaults=array()) {
    global $current_user;
    $page_vars = array_keys(person::get_list_vars());
    $_FORM = get_all_form_data($page_vars,$defaults);

    if (!$_FORM["applyFilter"]) {
      $_FORM = $current_user->prefs[$_FORM["form_name"]];
      if (!isset($current_user->prefs[$_FORM["form_name"]])) {
        $_FORM["personActive"] = true;
      }

    } else if ($_FORM["applyFilter"] && is_object($current_user) && !$_FORM["dontSave"]) {
      $url = $_FORM["url_form_action"];
      unset($_FORM["url_form_action"]);
      $current_user->prefs[$_FORM["form_name"]] = $_FORM;
      $_FORM["url_form_action"] = $url;
    }

    return $_FORM;
  }

  function load_person_filter($_FORM) {
    global $TPL, $current_user;

    $db = new db_alloc;
    $_FORM["showSkills"]   and $rtn["show_skills_checked"] = " checked";
    $_FORM["showHours"]    and $rtn["show_hours_checked"] = " checked";
    $_FORM["personActive"] and $rtn["show_all_users_checked"] = " checked";

    $employee_expertise = array(""            =>"Any Expertise"
                               ,"Novice"      =>"Novice"
                               ,"Junior"      =>"Junior"
                               ,"Intermediate"=>"Intermediate"
                               ,"Advanced"    =>"Advanced"
                               ,"Senior"      =>"Senior"
                               );
    $rtn["employee_expertise"] = page::select_options($employee_expertise, $_FORM["expertise"]);

    $skill_classes = skill::get_skill_classes();
    $rtn["skill_classes"] = page::select_options($skill_classes, $_FORM["skill_class"]);

    $skills = skill::get_skills();
    // if a skill class is selected and a skill that is not in that class is also selected, 
    // clear the skill as this is what the filter options will do
    if ($skill_class && !in_array($skills[$_FORM["skill"]], $skills)) { 
      $_FORM["skill"] = ""; 
    }
    $rtn["skills"] = page::select_options($skills, $_FORM["skill"]);

    return $rtn;
  }

  function get_link() {
    global $TPL;
    return "<a href=\"".$TPL["url_alloc_person"]."personID=".$this->get_id()."\">".$this->get_username(1)."</a>";
  }

}

?>
