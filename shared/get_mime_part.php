<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

// For use like get_attachment.php?entity=project&id=5&file=foo.bar

require_once("../alloc.php");

if (isset($_GET["id"]) && $_GET["part"]) {
    $comment = new comment();
    $comment->set_id($_GET["id"]);
    $comment->select() or die("Bad _GET[id]");
    list($mail, $text, $mimebits) = $comment->find_email(false, true);
    if (!$mail) {
        list($mail, $text, $mimebits) = $comment->find_email(false, true, true);
    }

    if ($comment->has_attachment_permission($current_user)) {
        foreach ((array)$mimebits as $bit) {
            if ($bit["part"] == $_GET["part"]) {
                $thing = $bit["blob"];
                $filename = $bit["name"];
                break;
            }
        }
        header('Content-Type: ' . $mimetype);
        header("Content-Length: " . strlen($thing));
        header('Content-Disposition: inline; filename="' . basename($filename) . '"');
        echo $thing;
        exit;
    } else {
        echo "Permission denied.";
        exit;
    }
}
