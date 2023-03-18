<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

// For use like get_attachment.php?entity=project&id=5&file=foo.bar

require_once("../alloc.php");

$id = $_GET["id"] or $id = $_POST["id"];
$file = $_GET["file"] or $file = $_POST["file"];
$entity = $_GET["entity"] or $entity = $_POST["entity"];

$id = sprintf("%d", $id);



if ($id && $file
    && !preg_match("/\.\./", $file) && !preg_match("/\//", $file)
    && !preg_match("/\.\./", $entity) && !preg_match("/\//", $entity)) {
    $e = new $entity;
    $e->set_id($id);
    $e->select();

    $dir = ATTACHMENTS_DIR.$entity.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR;
    $file = $dir.$file;

    if ($e->has_attachment_permission_delete($current_user) && file_exists($file)) {
        if (dirname($file) == dirname($dir.".")) { // last check
            unlink($file);
            alloc_redirect($TPL["url_alloc_".$entity].$entity."ID=".$id."&sbs_link=attachments");
            exit();
        }
    }
}

// return by default
alloc_redirect($TPL["url_alloc_".$entity].$entity."ID=".$id."&sbs_link=attachments");
