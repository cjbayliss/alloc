<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("PERM_APPROVE_PRODUCT_TRANSACTIONS", 256);
class productSale extends DatabaseEntity
{
    public $classname = "productSale";

    public $data_table = "productSale";

    public $key_field = "productSaleID";

    public $data_fields = [
        "clientID",
        "projectID",
        "personID",
        "tfID",
        "status",
        "productSaleCreatedTime",
        "productSaleCreatedUser",
        "productSaleModifiedTime",
        "productSaleModifiedUser",
        "productSaleDate",
        "extRef",
        "extRefDate",
    ];

    public $permissions = [PERM_APPROVE_PRODUCT_TRANSACTIONS => "approve product transactions"];

    public function validate($_ = null)
    {
        $rtn = [];
        if ($this->get_value("status") == "admin" || $this->get_value("status") == "finished") {
            $orig = new $this->classname;
            $orig->set_id($this->get_id());
            $orig->select();
            $orig_status = $orig->get_value("status");
            if ($orig_status == "allocate" && $this->get_value("status") == "admin") {
            } elseif (!$this->have_perm(PERM_APPROVE_PRODUCT_TRANSACTIONS)) {
                $rtn[] = "Unable to save Product Sale, user does not have correct permissions.";
            }
        }

        if ($this->get_value("extRef")) {
            $q = unsafe_prepare(
                "SELECT productSaleID FROM productSale WHERE productSaleID != %d AND extRef = '%s'",
                $this->get_id(),
                $this->get_value("extRef")
            );
            $allocDatabase = new AllocDatabase();
            if ($r = $allocDatabase->qr($q)) {
                $rtn[] = "Unable to save Product Sale, this external reference number is used in Sale " . $r["productSaleID"];
            }
        }

        return parent::validate($rtn);
    }

    public function is_owner($ignored = null)
    {
        $current_user = &singleton("current_user");
        if (!$this->get_id()) {
            return true;
        }

        if ($this->get_value("productSaleCreatedUser") == $current_user->get_id()) {
            return true;
        }

        return $this->get_value("personID") == $current_user->get_id();
    }

    public function delete()
    {
        $allocDatabase = new AllocDatabase();
        $query = unsafe_prepare(
            "SELECT *
               FROM productSaleItem
              WHERE productSaleID = %d",
            $this->get_id()
        );
        $allocDatabase->query($query);
        while ($allocDatabase->next_record()) {
            $productSaleItem = new productSaleItem();
            $productSaleItem->read_db_record($allocDatabase);
            $productSaleItem->delete();
        }

        $this->delete_transactions();
        return parent::delete();
    }

    public function translate_meta_tfID($tfID = "")
    {
        // The special -1 and -2 tfID's represent META TF, i.e. calculated at runtime
        // -1 == META: Project TF
        if ($tfID == -1) {
            if ($this->get_value("projectID")) {
                $project = new project();
                $project->set_id($this->get_value("projectID"));
                $project->select();
                $tfID = $project->get_value("cost_centre_tfID");
            }

            if (!$tfID) {
                alloc_error("Unable to use META: Project TF. Please ensure the project has a TF set, or adjust the transactions.");
            }

            // -2 == META: Salesperson TF
        } elseif ($tfID == -2) {
            if ($this->get_value("personID")) {
                $person = new person();
                $person->set_id($this->get_value("personID"));
                $person->select();
                $tfID = $person->get_value("preferred_tfID");
                if (!$tfID) {
                    alloc_error("Unable to use META: Salesperson TF. Please ensure the Saleperson has a Preferred Payment TF.");
                }
            } else {
                alloc_error("Unable to use META: Salesperson TF. No product salesperson set.");
            }
        } elseif ($tfID == -3) {
            $tfID = $this->get_value("tfID");
            $tfID || alloc_error("Unable to use META: Sale TF not set.");
        }

        return $tfID;
    }

    public function get_productSaleItems()
    {
        $q = unsafe_prepare("SELECT * FROM productSaleItem WHERE productSaleID = %d", $this->get_id());
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($q);

        $rows = [];
        while ($row = $allocDatabase->row()) {
            $rows[$row["productSaleItemID"]] = $row;
        }

        return $rows;
    }

    public function get_amounts()
    {

        $label = null;
        $rows = $this->get_productSaleItems();
        $rows || ($rows = []);
        $rtn = [];
        $sellPrice_label = null;
        $sellPriceCurr = [];
        $show = null;
        $total_margin = null;
        $total_sellPrice = null;
        $total_unallocated = null;

        foreach ($rows as $row) {
            $productSaleItem = new productSaleItem();
            $productSaleItem->read_row_record($row);
            // $rtn["total_spent"] += $productSaleItem->get_amount_spent();
            // $rtn["total_earnt"] += $productSaleItem->get_amount_earnt();
            // $rtn["total_other"] += $productSaleItem->get_amount_other();
            [$sp, $spcur] = [$productSaleItem->get_value("sellPrice"), $productSaleItem->get_value("sellPriceCurrencyTypeID")];

            $sellPriceCurr[$spcur] += Page::money($spcur, $sp, "%m");
            $total_sellPrice += exchangeRate::convert($spcur, $sp);
            $total_margin += $productSaleItem->get_amount_margin();
            $total_unallocated += $productSaleItem->get_amount_unallocated();
        }

        unset($sep, $label, $show);

        $sep = "";
        foreach ((array)$sellPriceCurr as $code => $amount) {
            $label .= $sep . Page::money($code, $amount, "%s%mo %c");
            $sep = " + ";
            if ($code != config::get_config_item("currency")) {
                $show = true;
            }
        }

        if ($show && $label) {
            $sellPrice_label = " (" . $label . ")";
        }

        $total_sellPrice_plus_gst = add_tax($total_sellPrice);

        $rtn["total_sellPrice"] = Page::money(config::get_config_item("currency"), $total_sellPrice, "%s%mo %c") . $sellPrice_label;
        $rtn["total_sellPrice_plus_gst"] = Page::money(config::get_config_item("currency"), $total_sellPrice_plus_gst, "%s%mo %c") . $sellPrice_label;
        $rtn["total_margin"] = Page::money(config::get_config_item("currency"), $total_margin, "%s%mo %c");
        $rtn["total_unallocated"] = Page::money(config::get_config_item("currency"), $total_unallocated, "%s%mo %c");
        $rtn["total_unallocated_number"] = Page::money(config::get_config_item("currency"), $total_unallocated, "%mo");

        $rtn["total_sellPrice_value"] = Page::money(config::get_config_item("currency"), $total_sellPrice, "%mo");
        return $rtn;
    }

    public function create_transactions()
    {
        $rows = $this->get_productSaleItems();
        $rows || ($rows = []);

        foreach ($rows as $row) {
            $productSaleItem = new productSaleItem();
            $productSaleItem->read_row_record($row);
            $productSaleItem->create_transactions();
        }
    }

    public function delete_transactions()
    {
        $rows = $this->get_productSaleItems();
        $rows || ($rows = []);

        foreach ($rows as $row) {
            $productSaleItem = new productSaleItem();
            $productSaleItem->read_row_record($row);
            $productSaleItem->delete_transactions();
        }
    }

    public function move_forwards()
    {
        $taskDesc = [];
        $extra = null;
        $hasItems = null;
        $recipients = [];
        $ids = [];
        $order_the_hardware_taskID = null;
        $pay_the_supplier_taskID = null;
        $current_user = &singleton("current_user");
        global $TPL;
        $status = $this->get_value("status");
        $db = new AllocDatabase();

        if ($this->get_value("clientID")) {
            $c = $this->get_foreign_object("client");
            $extra = " for " . $c->get_value("clientName");
            $taskDesc[] = "";
        }

        $taskname1 = "Sale " . $this->get_id() . ": raise an invoice" . $extra;
        $taskname2 = "Sale " . $this->get_id() . ": place an order to the supplier";
        $taskname3 = "Sale " . $this->get_id() . ": pay the supplier";
        $taskname4 = "Sale " . $this->get_id() . ": deliver the goods / action the work";
        $cyberadmin = 59;

        $taskDesc[] = "Sale items:";
        $taskDesc[] = "";
        foreach ((array)$this->get_productSaleItems() as $psiID => $psi_row) {
            $p = new product();
            $p->set_id($psi_row["productID"]);
            $taskDesc[] = "  " . Page::money($psi_row["sellPriceCurrencyTypeID"], $psi_row["sellPrice"], "%S%mo")
                . " for " . $psi_row["quantity"]
                . " x " . $p->get_name();
            $hasItems = true;
        }

        if ($hasItems === null) {
            return alloc_error("No sale items have been added.");
        }

        $amounts = $this->get_amounts();
        $taskDesc[] = "";
        $taskDesc[] = "Total: " . $amounts["total_sellPrice"];
        $taskDesc[] = "Total inc " . config::get_config_item("taxName") . ": " . $amounts["total_sellPrice_plus_gst"];
        $taskDesc[] = "";
        $taskDesc[] = "Refer to the sale in alloc for up-to-date information:";
        $taskDesc[] = config::get_config_item("allocURL") . "sale/productSale.php?productSaleID=" . $this->get_id();

        $taskDesc = implode("\n", $taskDesc);

        if ($status == "edit") {
            $this->set_value("status", "allocate");
            $items = $this->get_productSaleItems();
            foreach ($items as $item) {
                $psi = new productSaleItem();
                $psi->set_id($item["productSaleItemID"]);
                $psi->select();
                if (!$db->qr("SELECT transactionID FROM transaction WHERE productSaleItemID = %d", $psi->get_id())) {
                    $psi->create_transactions();
                }
            }
        } elseif ($status == "allocate") {
            $this->set_value("status", "admin");
            // 1. from salesperson to admin
            $q = unsafe_prepare("SELECT * FROM task WHERE projectID = %d AND taskName = '%s'", $cyberadmin, $taskname1);
            if (config::for_cyber() && !$db->qr($q)) {
                $task = new Task();
                $task->set_value("projectID", $cyberadmin); // Cyber Admin Project
                $task->set_value("taskName", $taskname1);
                $task->set_value("managerID", $this->get_value("personID")); // salesperson
                $task->set_value("personID", 67); // Cyber Support people (jane)
                $task->set_value("priority", 3);
                $task->set_value("taskTypeID", "Task");
                $task->set_value("taskDescription", $taskDesc);
                $task->set_value("dateTargetStart", date("Y-m-d"));
                $task->set_value("dateTargetCompletion", date("Y-m-d", date("U") + (60 * 60 * 24 * 7)));
                $task->save();
                $TPL["message_good"][] = "Task created: " . $task->get_id() . " " . $task->get_value("taskName");

                $p1 = new person();
                $p1->set_id($this->get_value("personID"));
                $p1->select();
                $p2 = new person();
                $p2->set_id(67);
                $p2->select();
                $recipients[$p1->get_value("emailAddress")] = [
                    "name"     => $p1->get_name(),
                    "addIP"    => true,
                    "internal" => true,
                ];
                $recipients[$p2->get_value("emailAddress")] = [
                    "name"     => $p2->get_name(),
                    "addIP"    => true,
                    "internal" => true,
                ];

                $comment = $p2->get_name() . ",\n\n" . $taskname1 . "\n\n" . $taskDesc;
                $commentID = comment::add_comment("task", $task->get_id(), $comment, "task", $task->get_id());
                $emailRecipients = comment::add_interested_parties($commentID, null, $recipients);

                // Re-email the comment out, including any attachments
                if (!comment::send_comment($commentID, $emailRecipients)) {
                    alloc_error("Email failed to send.");
                } else {
                    $TPL["message_good"][] = "Emailed task comment to " . $p1->get_value("emailAddress") . ", " . $p2->get_value("emailAddress") . ".";
                }
            }
        } elseif ($status == "admin" && $this->have_perm(PERM_APPROVE_PRODUCT_TRANSACTIONS)) {
            $this->set_value("status", "finished");
            if ($_REQUEST["changeTransactionStatus"]) {
                $rows = $this->get_productSaleItems();
                foreach ($rows as $row) {
                    $ids[] = $row["productSaleItemID"];
                }

                if ($ids !== []) {
                    $q = unsafe_prepare("UPDATE transaction SET status = '%s' WHERE productSaleItemID in (%s)", $_REQUEST["changeTransactionStatus"], $ids);
                    $db = new AllocDatabase();
                    $db->query($q);
                }
            }

            // 2. from admin to salesperson
            $q = unsafe_prepare("SELECT * FROM task WHERE projectID = %d AND taskName = '%s'", $cyberadmin, $taskname2);
            if (config::for_cyber() && !$db->qr($q)) {
                $task = new Task();
                $task->set_value("projectID", $cyberadmin); // Cyber Admin Project
                $task->set_value("taskName", $taskname2);
                $task->set_value("managerID", 67); // Cyber Support people (jane)
                $task->set_value("personID", $this->get_value("personID")); // salesperson
                $task->set_value("priority", 3);
                $task->set_value("taskTypeID", "Task");
                $task->set_value("taskDescription", $taskDesc);
                $task->set_value("dateTargetStart", date("Y-m-d"));
                $task->set_value("dateTargetCompletion", date("Y-m-d", date("U") + (60 * 60 * 24 * 7)));
                $task->save();

                $q = unsafe_prepare(
                    "SELECT * FROM task WHERE projectID = %d AND taskName = '%s'",
                    $cyberadmin,
                    $taskname1
                );
                $rai_row = $db->qr($q);
                if ($rai_row) {
                    $task->add_pending_tasks($rai_row["taskID"]);
                }

                $order_the_hardware_taskID = $task->get_id();
                $TPL["message_good"][] = "Task created: " . $task->get_id() . " " . $task->get_value("taskName");

                $task->add_notification(
                    3,
                    1,
                    "Task " . $task->get_id() . " " . $taskname2,
                    "Task status moved from pending to open.",
                    [["field" => "metaPersonID", "who" => -2]]
                );
            }

            // 3. from salesperson to admin
            $q = unsafe_prepare("SELECT * FROM task WHERE projectID = %d AND taskName = '%s'", $cyberadmin, $taskname3);
            if (config::for_cyber() && !$db->qr($q)) {
                $task = new Task();
                $task->set_value("projectID", $cyberadmin); // Cyber Admin Project
                $task->set_value("taskName", $taskname3);
                $task->set_value("managerID", $this->get_value("personID")); // salesperson
                $task->set_value("personID", 67); // Cyber Support people (jane)
                $task->set_value("priority", 3);
                $task->set_value("taskTypeID", "Task");
                $task->set_value("taskDescription", $taskDesc);
                $task->set_value("dateTargetStart", date("Y-m-d"));
                $task->set_value("dateTargetCompletion", date("Y-m-d", date("U") + (60 * 60 * 24 * 7)));
                $task->save();
                $task->add_pending_tasks($order_the_hardware_taskID);
                $pay_the_supplier_taskID = $task->get_id();
                $TPL["message_good"][] = "Task created: " . $task->get_id() . " " . $task->get_value("taskName");

                $task->add_notification(
                    3,
                    1,
                    "Task " . $task->get_id() . " " . $taskname3,
                    "Task status moved from pending to open.",
                    [["field" => "metaPersonID", "who" => -2]]
                );
            }

            // 4. from admin to salesperson
            $q = unsafe_prepare("SELECT * FROM task WHERE projectID = %d AND taskName = '%s'", $cyberadmin, $taskname4);
            if (config::for_cyber() && !$db->qr($q)) {
                $task = new Task();
                $task->set_value("projectID", $cyberadmin); // Cyber Admin Project
                $task->set_value("taskName", $taskname4);
                $task->set_value("managerID", 67); // Cyber Support people
                $task->set_value("personID", $this->get_value("personID")); // salesperson
                $task->set_value("priority", 3);
                $task->set_value("taskTypeID", "Task");
                $task->set_value("taskDescription", $taskDesc);
                $task->set_value("dateTargetStart", date("Y-m-d"));
                $task->set_value("dateTargetCompletion", date("Y-m-d", date("U") + (60 * 60 * 24 * 7)));
                $task->save();
                $task->add_pending_tasks($pay_the_supplier_taskID);
                $TPL["message_good"][] = "Task created: " . $task->get_id() . " " . $task->get_value("taskName");

                $task->add_notification(
                    3,
                    1,
                    "Task " . $task->get_id() . " " . $taskname4,
                    "Task status moved from pending to open.",
                    [["field" => "metaPersonID", "who" => -2]]
                );
            }
        }
    }

    public function get_transactions($productSaleItemID = false)
    {
        $done = null;
        $rows = [];
        $query = unsafe_prepare(
            "SELECT transaction.*
                   ,productCost.productCostID  as pc_productCostID
                   ,productCost.amount         as pc_amount
                   ,productCost.isPercentage   as pc_isPercentage
                   ,productCost.currencyTypeID as pc_currency
               FROM transaction
          LEFT JOIN productCost on transaction.productCostID = productCost.productCostID
              WHERE productSaleID = %d
                AND productSaleItemID = %d
           ORDER BY transactionID",
            $this->get_id(),
            $productSaleItemID
        );
        $allocDatabase = new AllocDatabase();
        $allocDatabase->query($query);
        while ($row = $allocDatabase->row()) {
            if ($row["transactionType"] == "tax") {
                $row["saleTransactionType"] = "tax";
            } elseif ($row["pc_productCostID"]) {
                $row["saleTransactionType"] = $row["pc_isPercentage"] ? "aPerc" : "aCost";
            } elseif (!$done && $row["transactionType"] == "sale" && !$row["productCostID"]) {
                $done = true;
                $row["saleTransactionType"] = "sellPrice";
            }

            $rows[] = $row;
        }

        return $rows;
    }

    public function move_backwards()
    {
        $current_user = &singleton("current_user");

        if ($this->get_value("status") == "finished" && $current_user->have_role("admin")) {
            $this->set_value("status", "admin");
        } elseif ($this->get_value("status") == "admin" && $current_user->have_role("admin")) {
            $this->set_value("status", "allocate");
        } elseif ($this->get_value("status") == "allocate") {
            $this->set_value("status", "edit");
        }
    }

    public static function get_list_filter($filter = [])
    {
        $sql = [];
        $current_user = &singleton("current_user");

        // If they want starred, load up the productSaleID filter element
        if (isset($filter["starred"])) {
            $starredSales = isset($current_user->prefs["stars"]) ?
                ($current_user->prefs["stars"]["productSale"] ?? "") : "";
            if (!empty($starredSales) && is_array($starredSales)) {
                foreach (array_keys($starredSales) as $k) {
                    $filter["productSaleID"][] = $k;
                }
            }

            if (!is_array($filter["productSaleID"] ?? "")) {
                $filter["productSaleID"][] = -1;
            }
        }

        // Filter productSaleID
        if (isset($filter["productSaleID"])) {
            $sql[] = sprintf_implode("productSale.productSaleID = %d", $filter["productSaleID"]);
        }

        // No point continuing if primary key specified, so return
        if (isset($filter["productSaleID"]) || isset($filter["starred"])) {
            return $sql;
        }

        $id_fields = [
            "clientID",
            "projectID",
            "personID",
            "tfID",
            "productSaleCreatedUser",
            "productSaleModifiedUser",
        ];
        foreach ($id_fields as $id_field) {
            if (isset($filter[$id_field])) {
                $sql[] = sprintf_implode("productSale." . $id_field . " = %d", $filter[$id_field]);
            }
        }

        if (isset($filter["status"])) {
            $sql[] = sprintf_implode("productSale.status = '%s'", $filter["status"]);
        }

        return $sql;
    }

    public static function get_list($_FORM = [])
    {

        $f = null;
        $filter = productSale::get_list_filter($_FORM);

        if (is_array($filter) && count($filter)) {
            $f = " WHERE " . implode(" AND ", $filter);
        }

        $f .= " ORDER BY IFNULL(productSaleDate,productSaleCreatedTime)";

        $allocDatabase = new AllocDatabase();
        $query = unsafe_prepare("SELECT productSale.*, project.projectName, client.clientName
                        FROM productSale
                   LEFT JOIN client ON productSale.clientID = client.clientID
                   LEFT JOIN project ON productSale.projectID = project.projectID
                    " . $f);
        $allocDatabase->query($query);
        $statii = productSale::get_statii();
        $people = &get_cached_table("person");
        $rows = [];
        while ($row = $allocDatabase->next_record()) {
            $productSale = new productSale();
            $productSale->read_db_record($allocDatabase);
            $row["amounts"] = $productSale->get_amounts();
            $row["statusLabel"] = $statii[$row["status"]];
            $row["salespersonLabel"] = $people[$row["personID"]]["name"];
            $row["creatorLabel"] = $people[$row["productSaleCreatedUser"]]["name"];
            $row["productSaleLink"] = $productSale->get_link();
            $rows[] = $row;
        }

        return (array)$rows;
    }

    public function get_link($row = []): string
    {
        global $TPL;
        if (is_object($this)) {
            return '<a href="' . $TPL["url_alloc_productSale"] . "productSaleID=" . $this->get_id() . '">' . $this->get_id() . "</a>";
        }

        return '<a href="' . $TPL["url_alloc_productSale"] . "productSaleID=" . $row["productSaleID"] . '">' . $row["productSaleID"] . "</a>";
    }

    public static function get_statii()
    {
        return [
            "create"   => "Create",
            "edit"     => "Add Sale Items",
            "allocate" => "Allocate",
            "admin"    => "Administrator",
            "finished" => "Completed",
        ];
    }

    public function get_all_parties($projectID = "")
    {
        $allocDatabase = new AllocDatabase();
        $interestedPartyOptions = [];

        if (!$projectID && is_object($this)) {
            $projectID = $this->get_value("projectID");
        }

        if ($projectID) {
            $project = new project($projectID);
            $interestedPartyOptions = $project->get_all_parties();
        }

        ($extra_interested_parties = config::get_config_item("defaultInterestedParties")) || ($extra_interested_parties = []);
        foreach ($extra_interested_parties as $name => $email) {
            $interestedPartyOptions[$email] = ["name" => $name];
        }

        if (is_object($this)) {
            if ($this->get_value("personID")) {
                $p = new person();
                $p->set_id($this->get_value("personID"));
                $p->select();
                $p->get_value("emailAddress") && ($interestedPartyOptions[$p->get_value("emailAddress")] = [
                    "name"     => $p->get_name(),
                    "selected" => true,
                    "personID" => $this->get_value("personID"),
                ]);
            }

            if ($this->get_value("productSaleCreatedUser")) {
                $p = new person();
                $p->set_id($this->get_value("productSaleCreatedUser"));
                $p->select();
                $p->get_value("emailAddress") && ($interestedPartyOptions[$p->get_value("emailAddress")] = [
                    "name"     => $p->get_name(),
                    "selected" => true,
                    "personID" => $this->get_value("productSaleCreatedUser"),
                ]);
            }

            $this_id = $this->get_id();
        }

        // return an aggregation of the current proj/client parties + the existing interested parties
        $interestedPartyOptions = InterestedParty::get_interested_parties("productSale", $this_id, $interestedPartyOptions);
        return $interestedPartyOptions;
    }

    public static function get_list_vars()
    {
        return [
            "return"          => "[MANDATORY] eg: array | html",
            "productSaleID"   => "Sale that has this ID",
            "starred"         => "Sale that have been starred",
            "clientID"        => "Sales that belong to this Client",
            "projectID"       => "Sales that belong to this Project",
            "personID"        => "Sales for this person",
            "status"          => "Sale status eg: edit | allocate | admin | finished",
            "url_form_action" => "The submit action for the filter form",
            "form_name"       => "The name of this form, i.e. a handle for referring to this saved form",
            "dontSave"        => "Specify that the filter preferences should not be saved this time",
            "applyFilter"     => "Saves this filter as the persons preference",
        ];
    }

    public static function load_form_data(array $defaults = []): array
    {
        $current_user = &singleton("current_user");

        $page_vars = array_keys(productSale::get_list_vars());

        $_FORM = get_all_form_data($page_vars, $defaults);

        if (!isset($_FORM["applyFilter"])) {
            if (isset($_FORM["form_name"]) && isset($current_user->prefs[$_FORM["form_name"]])) {
                $_FORM = $current_user->prefs[$_FORM["form_name"]];
            }

            if (!isset($current_user->prefs[$_FORM["form_name"] ?? ""])) {
                $_FORM["status"] = "edit";
                $_FORM["personID"] = $current_user->get_id();
            }
        } elseif (isset($_FORM["applyFilter"]) && is_object($current_user) && !$_FORM["dontSave"]) {
            $url = $_FORM["url_form_action"];
            unset($_FORM["url_form_action"]);
            $current_user->prefs[$_FORM["form_name"]] = $_FORM;
            $_FORM["url_form_action"] = $url;
        }

        return $_FORM;
    }

    public static function load_productSale_filter(array $_FORM): array
    {
        $filter = null;
        $rtn = [];
        $options = [];
        $current_user = &singleton("current_user");

        // display the list of project name.
        if (!isset($_FORM['showAllProjects'])) {
            $filter = "WHERE projectStatus = 'Current' ";
        }

        $query = unsafe_prepare(sprintf('SELECT projectID AS value, projectName AS label FROM project %s ORDER by projectName', $filter));
        $rtn["show_project_options"] = Page::select_options($query, $_FORM["projectID"] ?? [], 70);

        // display the list of user name.
        if (have_entity_perm("productSale", PERM_READ, $current_user, false)) {
            $rtn["show_userID_options"] = Page::select_options(person::get_username_list(), $_FORM["personID"]);
        } else {
            $person = new person();
            $person->set_id($current_user->get_id());
            $person->select();
            $person_array = [$current_user->get_id() => $person->get_name()];
            $rtn["show_userID_options"] = Page::select_options($person_array, $_FORM["personID"]);
        }

        // display a list of status
        $status_array = productSale::get_statii();
        unset($status_array["create"]);

        $rtn["show_status_options"] = Page::select_options($status_array, $_FORM["status"]);

        // display the date from filter value
        $rtn["showAllProjects"] = $_FORM["showAllProjects"] ?? "";

        $options["clientStatus"] = ["Current"];
        $options["return"] = "dropdown_options";
        $ops = client::get_list($options);
        $ops = array_kv($ops, "clientID", "clientName");
        $rtn["clientOptions"] = Page::select_options($ops, $_FORM["clientID"] ?? []);

        // Get
        $rtn["FORM"] = "FORM=" . urlencode(serialize($_FORM));

        return $rtn;
    }

    public static function get_list_html($rows = [], $_FORM = [])
    {
        global $TPL;
        $TPL["productSaleListRows"] = $rows;
        $_FORM["taxName"] = config::get_config_item("taxName");
        $TPL["_FORM"] = $_FORM;
        include_template(__DIR__ . "/../templates/productSaleListS.tpl");
    }
}
