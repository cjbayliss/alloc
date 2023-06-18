<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_AUTH", 1);
require_once(__DIR__ . "/../alloc.php");

$sess = new Session();
if (isset($_POST["forwardUrl"])) {
    $url = $_POST["forwardUrl"];
} elseif (isset($_GET["forward"])) {
    $url = $_GET["forward"];
} else {
    $url = $sess->GetUrl($TPL["url_alloc_home"]);
}

// If we already have a session
if ($sess->Started()) {
    alloc_redirect($url);
    exit();
    // Else log the user in
} elseif (!empty($_POST["login"])) {
    $person = new person();
    $row = $person->get_valid_login_row($_POST["username"], $_POST["password"]);
    if ($row) {
        $sess->Start($row);

        $q = unsafe_prepare(
            "UPDATE person SET lastLoginDate = '%s' WHERE personID = %d",
            date("Y-m-d H:i:s"),
            $row["personID"]
        );
        $db = new AllocDatabase();
        $db->query($q);

        if ($sess->TestCookie()) {
            $sess->UseCookie();
            $sess->SetTestCookie($_POST["username"]);
        } else {
            $sess->UseGet();
        }

        $sess->Save();
        alloc_redirect($url);
    }

    $error = "Invalid username or password.";
} elseif (!empty($_POST["new_pass"])) {
    $db = new AllocDatabase();
    $db->query(["SELECT * FROM person WHERE emailAddress = '%s'", $_POST["email"]]);
    if ($db->next_record()) {
        // generate new random password
        $password = "";
        $pwSource = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!?";
        mt_srand((float) microtime() * 1_000_000);
        for ($i = 0; $i < 8; ++$i) {
            $password .= substr($pwSource, random_int(0, strlen($pwSource)), 1);
        }

        $q = unsafe_prepare("UPDATE person SET password = '%s' WHERE emailAddress = '%s'
                 ", password_hash($password, PASSWORD_BCRYPT), $_POST["email"]);
        $db2 = new AllocDatabase();
        $db2->query($q);

        $e = new email_send($_POST["email"], "New Password", "Your new temporary password: " . $password, "new_password");
        // echo "Your new temporary password: ".$password;
        if ($e->send()) {
            $TPL["message_good"][] = "New password sent to: " . $_POST["email"];
        } else {
            $error = "Unable to send email.";
        }
    } else {
        $error = "Invalid email address.";
    }

    // Else if just visiting the page
} elseif (!$sess->TestCookie()) {
    $sess->SetTestCookie();
}

if (!empty($error)) {
    alloc_error($error);
}

$account = $_POST["account"] ?? "";
$account = $account !== "" ? $_GET["account"] : "";
$TPL["account"] = $account;

if (isset($_POST["username"])) {
    $TPL["username"] = $_POST["username"];
} elseif ($sess->TestCookie() != "alloc_test_cookie") {
    $TPL["username"] = $sess->TestCookie();
} else {
    $TPL["username"] = "";
}

if (isset($_GET["forward"])) {
    $TPL["forward_url"] = strip_tags($_GET["forward"]);
}

$TPL["status_line"] = APPLICATION_NAME . " " . APPLICATION_VERSION . " &copy; " . date("Y") . ' <a href="http://www.cyber.com.au">Cyber IT Solutions</a>';

if (!is_dir(ATTACHMENTS_DIR . "whatsnew" . DIRECTORY_SEPARATOR . "0")) {
    mkdir(ATTACHMENTS_DIR . "whatsnew" . DIRECTORY_SEPARATOR . "0");
}

$files = get_attachments("whatsnew", 0);

if (is_array($files) && count($files)) {
    while ($f = array_pop($files)) {
        // Only show entries that are newer that 4 weeks old
        if (format_date("U", basename($f["path"])) > time() - (60 * 60 * 24 * 28)) {
            ++$x;
            if ($x > 3) {
                break;
            }

            $str .= $br . "<b>" . $f["restore_name"] . "</b>";
            $str .= "<br><ul>" . trim(file_get_contents($f["path"])) . "</ul>";
            $br = "<br><br>";
        }
    }

    $str && ($TPL["latest_changes"] = $str);
}

$TPL["body_id"] = "login";
$TPL["main_alloc_title"] = "allocPSA login";

include_template("templates/login.tpl");
