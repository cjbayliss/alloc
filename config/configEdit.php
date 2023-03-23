<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

if (!have_entity_perm("config", PERM_UPDATE, $current_user, true)) {
    alloc_error("Permission denied.", true);
}

$configName = $_POST["configName"] or $configName = $_GET["configName"];
$TPL["configName"] = $configName;

$configType = $_POST["configType"] or $configType = $_GET["configType"] or $configType = "array";
$TPL["configType"] = $configType;

if ($configName) {
    $config = new config();
    $id = config::get_config_item_id($configName);
    $config->set_id($id);
    $config->select();
}

if ($_POST["save"]) {
    if ($configType == "people") {
        $arr = config::get_config_item($configName);
        if (!in_array($_POST['value'], $arr)) {
            $arr[] = $_POST["value"];
            $config->set_value("value", serialize($arr));
            $config->save();
        }
    } else {
        $arr = config::get_config_item($configName);
        $arr[$_POST["key"]] = $_POST["value"];
        $config->set_value("value", serialize($arr));
        $config->save();
    }
} else if ($_POST["delete"]) {
    $arr = config::get_config_item($configName);
    if ($configType == "people") {
        unset($arr[array_search($_POST["value"], $arr)]);
    } else {
        unset($arr[$_POST["key"]]);
    }
    $config->set_value("value", serialize($arr));
    $config->save();
}


if (file_exists("templates/configEdit_" . $configName . ".tpl")) {
    include_template("templates/configEdit_" . $configName . ".tpl");
} else if (file_exists("templates/configEdit_" . $configType . ".tpl")) {
    include_template("templates/configEdit_" . $configType . ".tpl");
} else {
    include_template("templates/configEdit.tpl");
}
