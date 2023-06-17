<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

// For use like get_attachment.php?entity=project&id=5&file=foo.bar

require_once("../alloc.php");

$file = $_GET["file"];

if (isset($_GET["id"]) && $file && !bad_filename($file)) {
    $entity = new $_GET["entity"];
    $entity->set_id(sprintf("%d", $_GET["id"]));
    $entity->select();

    $file = ATTACHMENTS_DIR . $_GET["entity"] . "/" . $_GET["id"] . "/" . $file;

    if ($entity->has_attachment_permission($current_user)) {
        if (file_exists($file)) {
            $fp = fopen($file, "rb");
            $mimetype = mime_content_type($file);

            // Forge html for the whatsnew files
            if (basename(dirname($file, 2)) == "whatsnew") {
                $forged_suffix = ".html";
                $mimetype = "text/html";
            }

            header('Content-Type: ' . $mimetype);
            header("Content-Length: " . filesize($file));
            header('Content-Disposition: inline; filename="' . basename($file) . $forged_suffix . '"');
            fpassthru($fp);
            exit;
        } else {
            echo "File not found.";
            exit;
        }
    } else {
        echo "Permission denied.";
        exit;
    }
}
