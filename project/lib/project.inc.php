<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("PERM_PROJECT_VIEW_TASK_ALLOCS", 256);
define("PERM_PROJECT_ADD_TASKS", 512);

class project extends db_entity
{
    public $classname = "project";
    public $data_table = "project";
    public $display_field_name = "projectName";
    public $key_field = "projectID";
    public $data_fields = [
        "projectName",
        "projectShortName",
        "projectComments",
        "clientID",
        "projectType",
        "projectClientName",
        "projectClientPhone",
        "projectClientMobile",
        "projectClientEMail",
        "projectClientAddress",
        "dateTargetStart",
        "dateTargetCompletion",
        "dateActualStart",
        "dateActualCompletion",
        "projectBudget" => ["type" => "money"],
        "currencyTypeID",
        "projectPriority",
        "projectStatus",
        "cost_centre_tfID",
        "customerBilledDollars" => ["type" => "money"],
        "clientContactID",
        "projectCreatedTime",
        "projectCreatedUser",
        "projectModifiedTime",
        "projectModifiedUser",
        "defaultTaskLimit",
        "defaultTimeSheetRate" => ["type" => "money"],
        "defaultTimeSheetRateUnitID",
    ];

    public $permissions = [
        PERM_PROJECT_VIEW_TASK_ALLOCS => "view task allocations",
        PERM_PROJECT_ADD_TASKS        => "add tasks",
    ];

    /**
     * Save project and update related tasks based on the project status change.
     *
     * @return bool Result of the parent save method.
     */
    public function save()
    {
        global $TPL;
        $initialState = $this->all_row_fields;
        $taskIDs = [];
        $database = new db_alloc();

        if (
            $initialState["projectStatus"] != "Archived" &&
            $this->get_value("projectStatus") == "Archived"
        ) {
            $database->connect();
            $projectTaskList = $database->pdo->prepare(
                "SELECT taskID FROM task
                  WHERE projectID = :projectID
                    AND SUBSTRING(taskStatus,1,6) != 'closed'"
            );
            $projectTaskList->bindValue(":projectID", $this->get_id(), PDO::PARAM_INT);
            $projectTaskList->execute();

            while ($row = $projectTaskList->fetch(PDO::FETCH_ASSOC)) {
                $changeTaskStatus = $database->pdo->prepare(
                    "CALL change_task_status(:taskID, 'closed_archived')"
                );
                $changeTaskStatus->bindValue(":taskID", $row["taskID"], PDO::PARAM_INT);
                $changeTaskStatus->execute();
                $taskIDs[] = $row["taskID"];
            }

            $taskIDs = implode(', ', $taskIDs) ?: '';
            if (!empty($taskIDs)) {
                $TPL["message_good"][] =
                    "All open and pending tasks ({$taskIDs}) have had their
                    status changed to Closed: Archived.";
            }
        } else if (
            $initialState["projectStatus"] == "Archived" &&
            $this->get_value("projectStatus") != "Archived"
        ) {
            $database->connect();
            $projectTaskList = $database->pdo->prepare(
                "SELECT taskID FROM task
                  WHERE projectID = :projectID
                    AND taskStatus = 'closed_archived'"
            );
            $projectTaskList->bindValue(":projectID", $this->get_id(), PDO::PARAM_INT);
            $projectTaskList->execute();

            while ($row = $projectTaskList->fetch(PDO::FETCH_ASSOC)) {
                $changeTaskStatus = $database->pdo->prepare(
                    "CALL change_task_status(:taskID, get_most_recent_non_archived_taskStatus(:taskID))"
                );
                $changeTaskStatus->bindValue(":taskID", $row["taskID"], PDO::PARAM_INT);
                $changeTaskStatus->execute();
                $taskIDs[] = $row["taskID"];
            }

            $taskIDs = implode(', ', $taskIDs) ?: '';
            if (!empty($taskIDs)) {
                $TPL["message_good"][] =
                    "All archived tasks ({$taskIDs}) have been set back to their
                    former task status.";
            }
        }

        if (!isset($TPL["message"])) {
            $TPL["message_good"][] = "Project saved.";
        }
        return parent::save();
    }

    public function delete()
    {
        $database = new db_alloc();
        $database->connect();

        // FIXME: this results in a sql error due to an integrity constraint
        // violation with the 'audit' table, the bug existed before the refactor
        // of this function.
        $projectToDelete = $database->pdo->prepare("DELETE from projectPerson WHERE projectID = :projectID");
        $projectToDelete->bindValue(":projectID", $this->get_id(), PDO::PARAM_INT);
        $projectToDelete->execute();
        return parent::delete();
    }

    public function get_url()
    {
        global $sess;
        $sess or $sess = new session();

        $url = "project/project.php?projectID=" . $this->get_id();

        if ($sess->Started()) {
            $url = $sess->url(SCRIPT_PATH . $url);

            // This for urls that are emailed
        } else {
            static $prefix;
            $prefix or $prefix = config::get_config_item("allocURL");
            $url = $prefix . $url;
        }
        return $url;
    }

    public function get_name($_FORM = [])
    {
        if ($_FORM["showShortProjectLink"] && $this->get_value("projectShortName")) {
            $field = "projectShortName";
        } else {
            $field = "projectName";
        }

        if ($_FORM["return"] == "html") {
            return $this->get_value($field, DST_HTML_DISPLAY);
        } else {
            return $this->get_value($field);
        }
    }

    public function is_owner($person = "")
    {
        $current_user = &singleton("current_user");
        $person or $person = $current_user;

        // If brand new record then let it be created.
        if (!$this->get_id()) {
            return true;
        }

        // Else check that user has isManager or timeSheetRecipient permission for this project
        return is_object($person) && ($person->have_role("manage") || $this->has_project_permission($person, ["isManager", "timeSheetRecipient"]));
    }

    /**
     * Checks if a person has project permissions for the current project.
     *
     * @param mixed $person (optional) The person to check permissions for.
     *              Defaults to the current user.
     * @param array $permissions (optional) An array of permission names to
     *              filter by. Defaults to an empty array.
     *
     * @return mixed|false An associative array of project permissions for the
     *                     person, or false if an error occurs.
     */
    public function has_project_permission($person = "", $permissions = [])
    {
        $person = $person ?: singleton("current_user");
        if (!is_object($person)) {
            // FIXME: why is this needed?
            return false;
        }

        $database = new db_alloc();
        $database->connect();

        $projectPermissionsQuery =
            "SELECT personID, projectID, pp.roleID, ppr.roleName, ppr.roleHandle
               FROM projectPerson pp
          LEFT JOIN role ppr ON ppr.roleID = pp.roleID
              WHERE projectID = :projectID and personID = :personID";

        if (!empty($permissions) && is_array($permissions)) {
            $permissionFilter = implode("', '", $permissions);
            $projectPermissionsQuery .= " AND ppr.roleHandle IN ('$permissionFilter')";
        }

        $projectPermissionsForPerson = $database->pdo->prepare($projectPermissionsQuery);
        $projectPermissionsForPerson->bindValue(':projectID', $this->get_id(), PDO::PARAM_INT);
        $projectPermissionsForPerson->bindValue(':personID', $person->get_id(), PDO::PARAM_INT);
        $projectPermissionsForPerson->execute();
        return $projectPermissionsForPerson->fetch(PDO::FETCH_ASSOC);
    }

    public function get_timeSheetRecipients()
    {
        $rows = $this->get_project_people_by_role("timeSheetRecipient");

        // Fallback time sheet manager person
        if (!$rows) {
            $people = config::get_config_item("defaultTimeSheetManagerList");
            $rows = $people ? $people : null;
        }

        return $rows;
    }

    /**
     * Retrieves the list of person IDs associated with a specific role in a
     * project.
     *
     * @param string $roleHandle The role handle to filter the results by.
     * @return array An array of person IDs associated with the specified role.
     */
    public function get_project_people_by_role($roleHandle = "")
    {
        $database = new db_alloc();
        $database->connect();

        $projectPeopleByRole = $database->pdo->prepare(
            "SELECT projectPerson.personID as personID
               FROM projectPerson
          LEFT JOIN role ON projectPerson.roleID = role.roleID
              WHERE projectPerson.projectID = :projectID AND role.roleHandle = :roleHandle"
        );
        $projectPeopleByRole->bindValue(':projectID', $this->get_id(), PDO::PARAM_INT);
        $projectPeopleByRole->bindValue(':roleHandle', $roleHandle, PDO::PARAM_STR);
        $projectPeopleByRole->execute();

        $projectPeopleIDs = [];
        while ($personByRole = $projectPeopleByRole->fetch(PDO::FETCH_ASSOC)) {
            $projectPeopleIDs[] = $personByRole["personID"];
        }

        return $projectPeopleIDs;
    }

    /**
     * Retrieves the ID of the project manager based on their role.
     *
     * First tries to find a person with the "timeSheetRecipient" role, and if
     * no person is found, it tries to find a person with the "isManager" role.
     * If no project manager is found, the function returns false.
     *
     * @return int|bool The ID of the project manager, or false if not found.
     */
    public function get_project_manager()
    {
        $projectManager = $this->get_project_people_by_role("timeSheetRecipient");
        if (!empty($projectManager)) {
            return $projectManager[0];
        }

        $projectManager = $this->get_project_people_by_role("isManager");
        if (!empty($projectManager)) {
            return $projectManager[0];
        }

        return false;
    }

    public function get_navigation_links($ops = [])
    {
        $links = [];
        global $TPL;

        // Client
        if ($this->get_value("clientID")) {
            $url = $TPL["url_alloc_client"] . "clientID=" . $this->get_value("clientID");
            $links[] = "<a href=\"$url\" class=\"nobr noprint\">Client</a>";
        }

        // Project
        if ($ops["showProject"]) {
            $url = $TPL["url_alloc_project"] . "projectID=" . $this->get_id();
            $links[] = "<a href=\"$url\" class=\"nobr noprint\">Project</a>";
        }

        // Tasks
        if ($this->have_perm()) {
            $url = $TPL["url_alloc_taskList"] . "applyFilter=1&amp;taskStatus=open&amp;taskView=byProject&amp;projectID=" . $this->get_id();
            $links[] = "<a href=\"$url\" class=\"nobr noprint\">Tasks</a>";
        }

        // Graph
        if ($this->have_perm()) {
            $url = $TPL["url_alloc_projectGraph"] . "applyFilter=1&projectID=" . $this->get_id() . "&taskStatus=open&showTaskID=true";
            $links[] = "<a href=\"$url\" class=\"nobr noprint\">Graph</a>";
        }

        // Allocation
        if ($this->have_perm(PERM_PROJECT_VIEW_TASK_ALLOCS)) {
            $url = $TPL["url_alloc_personGraph"] . "projectID=" . $this->get_id();
            $links[] = "<a href=\"$url\" class=\"nobr noprint\">Allocation</a>";
        }

        // To Time Sheet
        if ($this->have_perm(PERM_PROJECT_ADD_TASKS)) {
            $extra = $ops["taskID"] ? "&taskID=" . $ops["taskID"] : "";
            $url = $TPL["url_alloc_timeSheet"] . "newTimeSheet_projectID=" . $this->get_id() . $extra;
            $links[] = "<a href=\"$url\" class=\"nobr noprint\">Time Sheet</a>";
        }

        // New Task
        if ($this->have_perm(PERM_PROJECT_ADD_TASKS)) {
            $url = $TPL["url_alloc_task"] . "projectID=" . $this->get_id();
            $links[] = "<a href=\"$url\" class=\"nobr noprint\">New Task</a>";
        }

        // Join links up with space
        if (is_array($links)) {
            return implode(" ", $links);
        }
    }

    /**
     * Prepares a PDOStatement to fetch project information based on a specified
     * query type.
     *
     * @param PDO $database The database connection instance.
     * @param string $queryType The type of project query: "mine", "pm", "tsm",
     *               "pmORtsm", "all", or a specific project status.
     * @param int|bool $personID The person's ID. Defaults to the current user's
     *                 ID if not provided.
     * @param string|bool $projectStatus The project status filter. If provided,
     *                    the query will be filtered based on the project
     *                    status.
     *
     * @return PDOStatement A prepared PDOStatement to fetch project
     *                      information.
     */
    public static function get_project_type_query(
        $databaseConnection,
        $queryType = "mine",
        $personID = false,
        $projectStatus = false
    ) {
        $current_user = &singleton("current_user");
        $personID = $personID ? $personID : $current_user->get_id();

        $queryType or $queryType = "mine";
        $queryProjectStatus = $projectStatus ? " AND project.projectStatus = :projectStatus " : "";

        $baseSql = "SELECT project.projectID, project.projectName
                      FROM project
                 LEFT JOIN projectPerson ON project.projectID = projectPerson.projectID
                 LEFT JOIN role ON projectPerson.roleID = role.roleID
                     WHERE projectPerson.personID = :personID " . $queryProjectStatus;

        switch ($queryType) {
            case "mine":
                $sql = $baseSql . " GROUP BY projectID ORDER BY project.projectName";
                break;
            case "pm":
                $sql = $baseSql . " AND role.roleHandle = 'isManager'
                               GROUP BY projectID ORDER BY project.projectName";
                break;
            case "tsm":
                $sql = $baseSql . " AND role.roleHandle = 'timeSheetRecipient'
                               GROUP BY projectID ORDER BY project.projectName";
                break;
            case "pmORtsm":
                $sql = $baseSql . " AND (role.roleHandle = 'isManager' or role.roleHandle = 'timeSheetRecipient')
                               GROUP BY projectID ORDER BY project.projectName";
                break;
            case "all":
                $sql = "SELECT projectID, projectName FROM project
                      ORDER BY projectName";
                break;
            default:
                $sql = "SELECT projectID, projectName FROM project
                         WHERE project.projectStatus = :queryType
                      ORDER BY projectName";
                break;
        }

        $projectIDsAndNamesTypesQuery = $databaseConnection->pdo->prepare($sql);
        $projectIDsAndNamesTypesQuery->bindValue(':personID', $personID, PDO::PARAM_INT);

        if ($projectStatus) {
            $projectIDsAndNamesTypesQuery->bindValue(':projectStatus', $projectStatus, PDO::PARAM_STR);
        }

        if (!in_array($queryType, ["all", "mine", "pm", "tsm", "pmORtsm"])) {
            $projectIDsAndNamesTypesQuery->bindValue(':queryType', $queryType, PDO::PARAM_STR);
        }

        return $projectIDsAndNamesTypesQuery;
    }

    public static function get_list_by_client($clientID = false, $onlymine = false)
    {
        $options = [];
        $current_user = &singleton("current_user");
        $clientID and $options["clientID"] = $clientID;
        $options["projectStatus"] = "Current";
        $options["showProjectType"] = true;
        if ($onlymine) {
            $options["personID"] = $current_user->get_id();
        }
        $ops = self::getFilteredProjectList($options);
        return array_kv($ops, "projectID", "label");
    }

    public static function get_list_dropdown($type = "mine", $projectIDs = [])
    {
        $options = self::get_list_dropdown_options($type, $projectIDs);
        return "<select name=\"projectID[]\" size=\"9\" style=\"width:275px;\" multiple=\"true\">" . $options . "</select>";
    }

    public static function get_list_dropdown_options(
        $queryType = "mine",
        $projectIDs = [],
        $maxlength = 35
    ) {
        $database = new db_alloc();
        $database->connect();
        $projectIDsAndNames = self::get_project_type_query($database, $queryType);
        $projectIDsAndNames->execute();

        $optionsArry = [];
        while ($row = $projectIDsAndNames->fetch(PDO::FETCH_ASSOC)) {
            $optionsArry[$row["projectID"]] = $row["projectName"];
        }

        return page::select_options($optionsArry, $projectIDs, $maxlength);
    }

    public function get_dropdown_by_client($clientID = null, $onlymine = false)
    {
        $dropdownHtml = "<select id=\"projectID\" name=\"projectID\"><option></option>";
        $clientList = project::get_list_by_client($clientID, $onlymine);

        if (is_object($this) && $this->get_id()) {
            $clientList[$this->get_id()] = $this->get_value("projectName");
        }

        $dropdownHtml .= page::select_options($clientList, $this->get_id()) . "</select>";

        return $dropdownHtml;
    }

    // FIXME: these two functions should not exist
    public function has_attachment_permission($person)
    {
        return $this->has_project_permission($person);
    }

    public function has_attachment_permission_delete($person)
    {
        return $this->has_project_permission($person, ["isManager"]);
    }

    /**
     * Generates an array of SQL conditions based on the provided filter.
     *
     * This method processes the given filter array and constructs an array of
     * SQL conditions to be used in an SQL query. The filter can contain various
     * keys such as projectID, clientID, personID, etc. The method supports
     * filtering by starred projects, project status, project type, and other
     * parameters.
     *
     * @param array $filter An associative array of filter criteria, with keys
     *                      representing the column names and values
     *                      representing the corresponding filter values.
     * @return array An array of SQL conditions based on the provided filter
     * criteria.
     */
    private static function createSQLFilerConditions($filter = [])
    {
        if ($filter["starred"]) {
            foreach ((array)singleton("current_user")->prefs["stars"]["project"] as $projectID => $_) {
                $filter["projectID"][] = $projectID;
            }

            if (!is_array($filter["projectID"])) {
                $filter["projectID"][] = -1;
            }
        }

        $sql = [];
        if ($filter["projectID"]) {
            $parts = array_map(function ($projectID) {
                return "IFNULL(project.projectID, 0) = $projectID";
            }, (array)$filter["projectID"]);

            $sql[] = implode(" OR ", $parts);
        }

        if ($filter["projectID"] || $filter["starred"]) {
            return $sql;
        }

        // FIXME: is '!== "undefined"' needed by the other filters?
        if ($filter["clientID"] && $filter["clientID"] !== "undefined") {
            $parts = array_map(function ($clientID) {
                return "IFNULL(project.clientID, 0) = $clientID";
            }, (array)$filter["clientID"]);

            $sql[] = implode(" OR ", $parts);
        }

        if ($filter["personID"]) {
            $parts = array_map(function ($personID) {
                return "IFNULL(projectPerson.personID, 0) = $personID";
            }, (array)$filter["personID"]);

            $sql[] = implode(" OR ", $parts);
        }

        if ($filter["projectStatus"]) {
            $parts = array_map(function ($projectStatus) {
                return "IFNULL(project.projectStatus, '') = '$projectStatus'";
            }, (array)$filter["projectStatus"]);

            $sql[] = implode(" OR ", $parts);
        }

        if ($filter["projectType"]) {
            $parts = array_map(function ($projectType) {
                return "IFNULL(project.projectType, 0) = $projectType";
            }, (array)$filter["projectType"]);

            $sql[] = implode(" OR ", $parts);
        }

        if ($filter["projectName"]) {
            $parts = array_map(function ($projectName) {
                return "IFNULL(project.projectName, '') LIKE '%%" . $projectName . "%%'";
            }, (array)$filter["projectName"]);

            $sql[] = implode(" OR ", $parts);
        }

        if ($filter["projectShortName"]) {
            $parts = array_map(function ($projectShortName) {
                return "IFNULL(project.projectShortName, '') LIKE '%%" . $projectShortName . "%%'";
            }, (array)$filter["projectShortName"]);

            $sql[] = implode(" OR ", $parts);
        }

        if ($filter["projectNameMatches"]) {
            $parts = array_map(function ($projectNameMatches) {
                return "project.projectName LIKE '%%" . $projectNameMatches . "%%' OR project.projectShortName LIKE '%%" . $projectNameMatches . "%%' OR project.projectID = " . $projectNameMatches;
            }, (array)$filter["projectNameMatches"]);

            $sql[] = implode(" OR ", $parts);
        }

        return $sql;
    }

    /**
     * Retrieves a list of projects based on the provided filters in the $_FORM
     * array.
     *
     * This function queries the database to fetch a list of projects and
     * clients that match the filter criteria specified in the $_FORM array. The
     * filter criteria can include personID, limit, showProjectType, and other
     * parameters.
     *
     * The function returns an array of project details, including project name,
     * project link, navigation links, and an optional project type label.
     *
     * @param array $_FORM An associative array containing filter criteria and
     *                     other options to customize the output of the
     *                     function.
     * @return array An array of project details, indexed by the project ID.
     */
    public static function getFilteredProjectList($_FORM)
    {
        $_FORM["return"] = $_FORM["return"] ?: "html";

        $filter = self::createSQLFilerConditions($_FORM);
        if (is_array($filter) && count($filter)) {
            $filter = " WHERE " . implode(" AND ", $filter);
        } else {
            $filter = "";
        }

        $from = $_FORM["personID"] ?
            " LEFT JOIN projectPerson on projectPerson.projectID = project.projectID " : "";

        $database = new db_alloc();
        $database->connect();
        $projectsAndClientsQuery =
            "SELECT project.*, client.*
               FROM project {$from}
          LEFT JOIN client ON project.clientID = client.clientID
                    {$filter}
           GROUP BY project.projectID
           ORDER BY projectName";

        if (isset($_FORM["limit"])) {
            // FIXME: cast to int within the function parameters after PHP7
            $limit = (int)$_FORM["limit"];
            $projectsAndClientsQuery .= " LIMIT :limit";
            $getProjectsAndClients = $database->pdo->prepare($projectsAndClientsQuery);
            $getProjectsAndClients->bindParam(":limit", $limit, PDO::PARAM_INT);
        } else {
            $getProjectsAndClients = $database->pdo->prepare($projectsAndClientsQuery);
        }
        $getProjectsAndClients->execute();

        $rows = [];
        while ($row = $getProjectsAndClients->fetch(PDO::FETCH_ASSOC)) {
            $projectInstance = new project();
            $projectInstance->set_id($row["projectID"]);

            $row["projectName"] = $projectInstance->get_name();
            $row["projectLink"] = $projectInstance->get_link();
            $row["navLinks"] = $projectInstance->get_navigation_links();
            $label = $row["projectName"];
            if ($_FORM["showProjectType"]) {
                $label .= " [{$projectInstance->get_project_type()}]";
            }
            $row["label"] = $label;

            $rows[$row["projectID"]] = $row;
        }

        return $rows;
    }

    /**
     * FIXME: This is a temporary function to allow calls to the old get_list()
     * method
     *
     * @deprecated DO NOT USE!!
     *
     * @param array ...$args
     * @return array
     */
    public static function get_list(...$args)
    {
        return self::getFilteredProjectList(...$args);
    }

    public static function get_list_vars()
    {
        return [
            "projectID"       => "The Project ID",
            "projectStatus"   => "Status of the project eg: Current | Potential | Archived",
            "clientID"        => "Show projects that are owned by this Client",
            "projectType"     => "Type of project eg: Contract | Job | Project | Prepaid",
            "personID"        => "Projects that have this person on them.",
            "projectName"     => "Project name like *something*",
            "limit"           => "Limit the number of records returned",
            "url_form_action" => "The submit action for the filter form",
            "form_name"       => "The name of this form, i.e. a handle for referring to this saved form",
            "dontSave"        => "A flag that allows the user to specify that the filter preferences should not be saved this time",
            "applyFilter"     => "Saves this filter as the persons preference",
            "showProjectType" => "Show the project type",
        ];
    }

    public static function load_form_data($defaults = [])
    {
        $current_user = &singleton("current_user");

        $page_vars = array_keys(project::get_list_vars());

        $_FORM = get_all_form_data($page_vars, $defaults);

        if (!$_FORM["applyFilter"]) {
            $_FORM = $current_user->prefs[$_FORM["form_name"]];
            if (!isset($current_user->prefs[$_FORM["form_name"]])) {
                $_FORM["projectStatus"] = "Current";
                $_FORM["personID"] = $current_user->get_id();
            }
        } else if ($_FORM["applyFilter"] && is_object($current_user) && !$_FORM["dontSave"]) {
            $url = $_FORM["url_form_action"];
            unset($_FORM["url_form_action"]);
            $current_user->prefs[$_FORM["form_name"]] = $_FORM;
            $_FORM["url_form_action"] = $url;
        }

        return $_FORM;
    }

    public static function load_project_filter($_FORM)
    {

        $rtn = [];
        global $TPL;
        $current_user = &singleton("current_user");

        $personSelect = "<select name=\"personID[]\" multiple=\"true\">";
        $personSelect .= page::select_options(person::get_username_list($_FORM["personID"]), $_FORM["personID"]);
        $personSelect .= "</select>";

        $rtn["personSelect"] = $personSelect;
        $m = new meta("projectStatus");
        $projectStatus_array = $m->get_assoc_array("projectStatusID", "projectStatusID");
        $rtn["projectStatusOptions"] = page::select_options($projectStatus_array, $_FORM["projectStatus"]);
        $rtn["projectTypeOptions"] = page::select_options(project::get_project_type_array(), $_FORM["projectType"]);
        $rtn["projectName"] = $_FORM["projectName"];

        // Get
        $rtn["FORM"] = "FORM=" . urlencode(serialize($_FORM));

        return $rtn;
    }

    public static function get_project_type_array()
    {
        // optimization
        static $rows;
        if (!$rows) {
            $m = new meta("projectType");
            $rows = $m->get_assoc_array("projectTypeID", "projectTypeID");
        }
        return $rows;
    }

    public function get_project_type()
    {
        $ops = $this->get_project_type_array();
        return $ops[$this->get_value("projectType")];
    }

    /**
     * Retrieves the first prepaid invoice for a project or client.
     *
     * @return int|null The invoice ID, or null if no matching invoice is found.
     */
    public function get_prepaid_invoice()
    {
        $database = new db_alloc();
        $database->connect();

        $getMatchingInvoicesByProjectID = $database->pdo->prepare(
            "SELECT *
               FROM invoice
              WHERE projectID = :projectID
                AND invoiceStatus != 'finished'
           ORDER BY invoiceDateFrom ASC
              LIMIT 1"
        );
        $getMatchingInvoicesByProjectID->bindParam(":projectID", $this->get_id(), PDO::PARAM_INT);
        $getMatchingInvoicesByProjectID->execute();

        if ($row = $getMatchingInvoicesByProjectID->fetch(PDO::FETCH_ASSOC)) {
            return $row["invoiceID"];
        }

        if ($this->get_value("clientID")) {
            $getMatchingInvoicesByClientID = $database->pdo->prepare(
                "SELECT *
                   FROM invoice
                  WHERE clientID = :clientID
                    AND (projectID IS NULL OR projectID = 0 OR projectID = '')
                    AND invoiceStatus != 'finished'
               ORDER BY invoiceDateFrom ASC
                  LIMIT 1"
            );
            $getMatchingInvoicesByClientID->bindParam(":clientID", $this->get_value("clientID"), PDO::PARAM_INT);
            $getMatchingInvoicesByClientID->execute();

            if ($row = $getMatchingInvoicesByClientID->fetch(PDO::FETCH_ASSOC)) {
                return $row["invoiceID"];
            }
        }

        return null;
    }

    public function update_search_index_doc(&$index)
    {
        $clientName = null;
        $p = &get_cached_table("person");
        $projectModifiedUser = $this->get_value("projectModifiedUser");
        $projectModifiedUser_field = $projectModifiedUser . " " . $p[$projectModifiedUser]["username"] . " " . $p[$projectModifiedUser]["name"];
        $projectName = $this->get_name();
        $projectShortName = $this->get_name(["showShortProjectLink" => true]);
        $projectShortName && $projectShortName != $projectName and $projectName .= " " . $projectShortName;

        if ($this->get_value("clientID")) {
            $c = new client();
            $c->set_id($this->get_value("clientID"));
            $c->select();
            $clientName = $c->get_name();
        }

        $doc = new Zend_Search_Lucene_Document();
        $doc->addField(Zend_Search_Lucene_Field::Keyword('id', $this->get_id()));
        $doc->addField(Zend_Search_Lucene_Field::Text('name', $projectName, "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('desc', $this->get_value("projectComments"), "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('cid', $this->get_value("clientID"), "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('client', $clientName, "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('modifier', $projectModifiedUser_field, "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('type', $this->get_value("projectType"), "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('dateTargetStart', str_replace("-", "", $this->get_value("dateTargetStart")), "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('dateTargetCompletion', str_replace("-", "", $this->get_value("dateTargetCompletion")), "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('dateStart', str_replace("-", "", $this->get_value("dateActualStart")), "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('dateCompletion', str_replace("-", "", $this->get_value("dateActualCompletion")), "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('status', $this->get_value("projectStatus"), "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('priority', $this->get_value("projectPriority"), "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('tf', $this->get_value("cost_centre_tfID"), "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('billed', $this->get_value("customerBilledDollars"), "utf-8"));
        $index->addDocument($doc);
    }

    public function format_client_old()
    {
        $str = null;
        $this->get_value("projectClientName") and $str .= $this->get_value("projectClientName", DST_HTML_DISPLAY) . "<br>";
        $this->get_value("projectClientAddress") and $str .= $this->get_value("projectClientAddress", DST_HTML_DISPLAY) . "<br>";
        $this->get_value("projectClientPhone") and $str .= $this->get_value("projectClientPhone", DST_HTML_DISPLAY) . "<br>";
        $this->get_value("projectClientMobile") and $str .= $this->get_value("projectClientMobile", DST_HTML_DISPLAY) . "<br>";
        $this->get_value("projectClientEMail") and $str .= $this->get_value("projectClientEMail", DST_HTML_DISPLAY) . "<br>";
        return $str;
    }

    public static function get_projectID_sql($filter, $table = "project")
    {
        $projectIDs = [];

        if (!$filter["projectID"] && $filter["projectType"] && $filter["projectType"] != "all") {
            $database = new db_alloc();
            $database->connect();
            $projectIDsAndNames = self::get_project_type_query(
                $database,
                $filter["projectType"],
                $filter["current_user"],
                "current"
            );
            $projectIDsAndNames->execute();

            while ($row = $projectIDsAndNames->fetch(PDO::FETCH_ASSOC)) {
                $projectIDs[] = $row["projectID"];
            }
        } elseif ($filter["projectID"] && is_array($filter["projectID"])) {
            $projectIDs = $filter["projectID"];
        } elseif ($filter["projectID"] && is_numeric($filter["projectID"])) {
            $projectIDs[] = $filter["projectID"];
        }

        if (!empty($projectIDs)) {
            $statement = array_map(function ($projectID) use ($table) {
                return "($table.projectID = $projectID)";
            }, (array)$projectIDs);
            return implode(" OR ", $statement);
        }

        return sprintf("(%s.projectID = 0)", $table);
    }

    public function get_cc_list_select($projectID = "")
    {
        $options = [];
        $interestedParty = [];
        $interestedPartyOptions = [];

        if (is_object($this)) {
            $interestedPartyOptions = $this->get_all_parties($projectID);
        } else {
            $project = new project($projectID);
            $interestedPartyOptions = $project->get_all_parties();
        }

        if (is_array($interestedPartyOptions)) {
            foreach ($interestedPartyOptions as $email => $info) {
                $name = $info["name"];
                $identifier = $info["identifier"];

                if ($info["role"] == "interested" && $info["selected"]) {
                    $interestedParty[] = $identifier;
                }

                if ($email) {
                    $name = trim($name);
                    $str = trim(page::htmlentities($name . " <" . $email . ">"));
                    $options[$identifier] = $str;
                }
            }
        }
        $str = "<select name=\"interestedParty[]\" multiple=\"true\">" . page::select_options($options, $interestedParty, 100, false) . "</select>";
        return $str;
    }

    public function get_all_parties($projectID = false, $task_exists = false)
    {

        if (!$projectID && is_object($this)) {
            $projectID = $this->get_id();
        }

        if ($projectID) {
            $interestedPartyOptions = [];
            $interestedParties = config::get_config_item("defaultInterestedParties");
            $name = null;
            foreach ((array)$interestedParties as $name => $email) {
                $interestedPartyOptions[$email]["name"] = $name;
            }
            $database = new db_alloc();
            $database->connect();

            // Get primary client contact from Project page
            $getClientNameAndEmail = $database->pdo->prepare(
                "SELECT projectClientName, projectClientEMail
                   FROM project
                  WHERE projectID = :projectID"
            );
            $getClientNameAndEmail->bindParam(':projectID', $projectID, PDO::PARAM_INT);
            $getClientNameAndEmail->execute();

            $clientNameAndEmail = $getClientNameAndEmail->fetch(PDO::FETCH_ASSOC);
            $interestedPartyOptions[$clientNameAndEmail["projectClientEMail"]]["name"] = $clientNameAndEmail["projectClientName"];
            $interestedPartyOptions[$clientNameAndEmail["projectClientEMail"]]["external"] = "1";

            // Get all other client contacts from the Client pages for this Project
            $getClientID = $database->pdo->prepare(
                "SELECT clientID FROM project WHERE projectID = :projectID"
            );
            $getClientID->bindParam(':projectID', $projectID, PDO::PARAM_INT);
            $getClientID->execute();

            $clientID = $getClientID->fetch(PDO::FETCH_ASSOC)["clientID"];
            if ($clientID) {
                $client = new client($clientID);
                $interestedPartyOptions = array_merge(
                    (array)$interestedPartyOptions,
                    (array)$client->get_all_parties()
                );
            }

            // Get all the project people for this tasks project
            $getContactDetails = $database->pdo->prepare(
                "SELECT emailAddress, firstName, surname, person.personID, username
                   FROM projectPerson
              LEFT JOIN person on projectPerson.personID = person.personID
                  WHERE projectPerson.projectID = :projectID AND person.personActive = 1"
            );
            $getContactDetails->bindParam(':projectID', $projectID, PDO::PARAM_INT);
            $getContactDetails->execute();

            while ($contact = $getContactDetails->fetch(PDO::FETCH_ASSOC)) {
                // FIXME: alloc should not care about first names and surnames
                if ($contact["firstName"] && $contact["surname"]) {
                    $name = $contact["firstName"] . " " . $contact["surname"];
                } else if ($contact["firstName"] || $contact["surname"]) {
                    $name = $contact["firstName"] ? $contact["fistName"] : $contact["surname"];
                } else {
                    $name = $contact["username"];
                }
                $interestedPartyOptions[$contact["emailAddress"]]["name"] = $name;
                $interestedPartyOptions[$contact["emailAddress"]]["personID"] = $contact["personID"];
                $interestedPartyOptions[$contact["emailAddress"]]["internal"] = true;
            }
        }

        $current_user = &singleton("current_user");
        if (is_object($current_user) && $current_user->get_id()) {
            $interestedPartyOptions[$current_user->get_value("emailAddress")]["name"] = $current_user->get_name();
            $interestedPartyOptions[$current_user->get_value("emailAddress")]["personID"] = $current_user->get_id();
        }

        // return an aggregation of the current task/proj/client parties + the existing interested parties
        $interestedPartyOptions = interestedParty::get_interested_parties(
            "project",
            $projectID,
            $interestedPartyOptions,
            $task_exists
        );
        return (array)$interestedPartyOptions;
    }

    public static function get_priority_label($p = "")
    {
        $projectPriorities = config::get_config_item("projectPriorities") or $projectPriorities = [];
        $pp = [];
        foreach ($projectPriorities as $key => $arr) {
            $pp[$key] = $arr["label"];
        }
        return $pp[$p];
    }

    public static function get_list_html($rows = [], $ops = [])
    {
        global $TPL;
        $TPL["projectListRows"] = $rows;
        $TPL["_FORM"] = $ops;
        include_template(__DIR__ . "/../templates/projectListS.tpl");
    }

    public function get_changes_list()
    {
        // This function returns HTML rows for the changes that have been made to this project
        $rows = [];
        $people_cache = &get_cached_table("person");
        $timeUnit = new timeUnit();
        $timeUnits = array_reverse($timeUnit->get_assoc_array("timeUnitID", "timeUnitLabelA"), true);
        $options = ["projectID" => $this->get_id()];
        $changes = audit::get_list($options);
        foreach ((array)$changes as $audit) {
            $changeDescription = "";
            $newValue = $audit['value'];
            switch ($audit['field']) {
                case 'created':
                    $changeDescription = $newValue;
                    break;
                case 'dip':
                    $changeDescription = "Default parties set to " . interestedParty::abbreviate($newValue);
                    break;
                case 'projectShortName':
                    $changeDescription = "Project nickname set to '$newValue'.";
                    break;
                case 'projectComments':
                    $changeDescription = "Project description set to <a class=\"magic\" href=\"#x\" onclick=\"$('#audit" . $audit["auditID"] . "').slideToggle('fast');\">Show</a> <div class=\"hidden\" id=\"audit" . $audit["auditID"] . "\"><div>" . $newValue . "</div></div>";
                    break;
                case 'clientID':
                    $newClient = new client($newValue);
                    is_object($newClient) and $newClientLink = $newClient->get_link();
                    $newClientLink or $newClientLink = "&lt;empty&gt;";
                    $changeDescription = "Client set to " . $newClientLink . ".";
                    break;
                case 'clientContactID':
                    $newClientContact = new clientContact($newValue);
                    is_object($newClientContact) and $newClientContactLink = $newClientContact->get_link();
                    $newClientContactLink or $newClientContactLink = "&lt;empty&gt;";
                    $changeDescription = "Client contact set to " . $newClientContactLink . ".";
                    break;
                case 'projectType':
                    $changeDescription = "Project type set to " . $newValue . ".";
                    break;
                case 'projectBudget':
                    $changeDescription = "Project budget set to " . page::money($this->get_value("currencyTypeID"), $newValue) . ".";
                    break;
                case 'currencyTypeID':
                    $changeDescription = "Project currency set to " . $newValue . ".";
                    break;
                case 'projectStatus':
                    $changeDescription = "Project status set to " . $newValue . ".";
                    break;
                case 'projectName':
                    $changeDescription = "Project name set to '$newValue'.";
                    break;
                case 'cost_centre_tfID':
                    $newCostCentre = new tf($newValue);
                    is_object($newCostCentre) and $newCostCentreLink = $newCostCentre->get_link();
                    $newCostCentreLink or $newCostCentreLink = "&lt;empty&gt;";
                    $changeDescription = "Cost centre TF set to " . $newCostCentreLink . ".";
                    break;
                case 'customerBilledDollars':
                    $changeDescription = "Client billing set to " . page::money($this->get_value("currencyTypeID"), $newValue) . ".";
                    break;
                case 'defaultTaskLimit':
                    $changeDescription = "Default task limit set to " . $newValue . ".";
                    break;
                case 'defaultTimeSheetRate':
                    $changeDescription = "Default time sheet rate set to " . page::money($this->get_value("currencyTypeID"), $newValue) . ".";
                    break;
                case 'defaultTimeSheetRateUnitID':
                    $changeDescription = "Default time sheet rate unit set to '" . $timeUnits[$newValue] . "'.";
                    break;
                case 'projectPriority':
                    $priorities = config::get_config_item("projectPriorities");
                    $changeDescription = sprintf(
                        'Project priority set to <span style="color: %s;">%s</span>.',
                        $priorities[$newValue]["colour"],
                        $priorities[$newValue]["label"]
                    );
                    break;
                case 'dateActualCompletion':
                case 'dateActualStart':
                case 'dateTargetStart':
                case 'dateTargetCompletion':
                    // these cases are more or less identical
                    switch ($audit['field']) {
                        case 'dateActualCompletion':
                            $fieldDesc = "actual completion date";
                            break;
                        case 'dateActualStart':
                            $fieldDesc = "actual start date";
                            break;
                        case 'dateTargetStart':
                            $fieldDesc = "estimate/target start date";
                            break;
                        case 'dateTargetCompletion':
                            $fieldDesc = "estimate/target completion date";
                            break;
                    }
                    if (!$newValue) {
                        $changeDescription = "The $fieldDesc was removed.";
                    } else {
                        $changeDescription = "The $fieldDesc set to $newValue.";
                    }
                    break;
            }
            $rows[] = "<tr><td class=\"nobr\">" . $audit["dateChanged"] . "</td><td>$changeDescription</td><td>" . page::htmlentities($people_cache[$audit["personID"]]["name"]) . "</td></tr>";
        }

        return implode("\n", $rows);
    }
}
