<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$db = new db_alloc();

// End of functions

if (!$current_user->have_role("god")) {
    alloc_error("Insufficient permissions. Backups may only be performed by super-users.", true);
}

$backup = new backups();

if ($_POST["create_backup"]) {
    $backup->backup();
}

if ($_POST["restore_backup"]) {
    $backup->backup();
    if ($backup->restore($_POST["file"])) {
        $TPL["message_good"][] = "Backup restored successfully: " . $_POST["file"];
        $TPL["message_good"][] = "You will now need to manually import the installation/db_triggers.sql file into your database. THIS IS VERY IMPORTANT.";
    } else {
        alloc_error("Error restoring backup: " . $_POST["file"]);
    }
}

if ($_POST["delete_backup"]) {
    // Can't go through the normal del_attachments thing because this isn't a real entity

    $file = $_POST["file"];

    if (bad_filename($file)) {
        alloc_error("File delete error: Name contains slashes.");
    }
    $path = ATTACHMENTS_DIR . "backups" . DIRECTORY_SEPARATOR . "0" . DIRECTORY_SEPARATOR . $file;
    if (!is_file($path)) {
        alloc_error("File delete error: Not a file.");
    }
    if (dirname(ATTACHMENTS_DIR . "backups" . DIRECTORY_SEPARATOR . "0" . DIRECTORY_SEPARATOR . ".") != dirname($path)) {
        alloc_error("File delete error: Bad path.");
    }

    unlink($path);
}

if ($_POST["save_attachment"]) {
    move_attachment("backups", 0);
}

$TPL["main_alloc_title"] = "Database Backups - " . APPLICATION_NAME;
include_template("templates/backupM.tpl");
