<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define('NO_AUTH', 1);
require_once __DIR__ . '/../alloc.php';

$page = new Page();

$session = new Session();
if (isset($_POST['forwardUrl'])) {
    $url = $_POST['forwardUrl'];
} elseif (isset($_GET['forward'])) {
    $url = $_GET['forward'];
} else {
    $url = $session->GetUrl($page->getURL('url_alloc_home'));
}

if ($session->Started()) {
    alloc_redirect($url);
    exit;
}

if (!empty($_POST['login'])) {
    $person = new person();
    $row = $person->get_valid_login_row($_POST['username'], $_POST['password']);
    if ([] !== $row) {
        $session->Start($row);

        $q = unsafe_prepare(
            "UPDATE person SET lastLoginDate = '%s' WHERE personID = %d",
            date('Y-m-d H:i:s'),
            $row['personID']
        );
        $db = new AllocDatabase();
        $db->query($q);

        if ($session->TestCookie()) {
            $session->UseCookie();
            $session->SetTestCookie($_POST['username']);
        } else {
            $session->UseGet();
        }

        $session->Save();
        alloc_redirect($url);
    }

    $error = 'Invalid username or password.';
} elseif (!empty($_POST['new_pass'])) {
    $db = new AllocDatabase();
    $db->query("SELECT * FROM person WHERE emailAddress = '%s'", $_POST['email']);
    if ($db->next_record()) {
        // generate new random password
        $password = '';
        $pwSource = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!?';
        mt_srand((float) microtime() * 1_000_000);
        for ($i = 0; $i < 8; ++$i) {
            $password .= substr($pwSource, random_int(0, strlen($pwSource)), 1);
        }

        $q = unsafe_prepare("UPDATE person SET password = '%s' WHERE emailAddress = '%s'
                 ", password_hash($password, PASSWORD_BCRYPT), $_POST['email']);
        $db2 = new AllocDatabase();
        $db2->query($q);

        $e = new email_send($_POST['email'], 'New Password', 'Your new temporary password: ' . $password, 'new_password');

        if ($e->send()) {
            $TPL['message_good'][] = 'New password sent to: ' . $_POST['email'];
        } else {
            $error = 'Unable to send email.';
        }
    } else {
        $error = 'Invalid email address.';
    }

    // Else if just visiting the page
} elseif (!$session->TestCookie()) {
    $session->SetTestCookie();
}

if (!empty($error)) {
    alloc_error($error);
}

$account = $_POST['account'] ?? '';
$account = '' !== $account ? $_GET['account'] : '';
$TPL['account'] = $account;

if (isset($_POST['username'])) {
    $TPL['username'] = $_POST['username'];
} elseif ('alloc_test_cookie' != $session->TestCookie()) {
    $TPL['username'] = $session->TestCookie();
} else {
    $TPL['username'] = '';
}

if (isset($_GET['forward'])) {
    $TPL['forward_url'] = strip_tags($_GET['forward']);
}

$TPL['status_line'] = APPLICATION_NAME . ' ' . APPLICATION_VERSION . ' &copy; ' . date('Y') . ' <a href="http://www.cyber.com.au">Cyber IT Solutions</a>';

if (!is_dir(ATTACHMENTS_DIR . 'whatsnew' . DIRECTORY_SEPARATOR . '0')) {
    mkdir(ATTACHMENTS_DIR . 'whatsnew' . DIRECTORY_SEPARATOR . '0');
}

$files = get_attachments('whatsnew', 0);

if (is_array($files) && count($files)) {
    while ($f = array_pop($files)) {
        // Only show entries that are newer that 4 weeks old
        if (format_date('U', basename($f['path'])) > time() - (60 * 60 * 24 * 28)) {
            ++$x;
            if ($x > 3) {
                break;
            }

            $str .= $br . '<b>' . $f['restore_name'] . '</b>';
            $str .= '<br><ul>' . trim(file_get_contents($f['path'])) . '</ul>';
            $br = '<br><br>';
        }
    }

    $str && ($TPL['latest_changes'] = $str);
}

$TPL['main_alloc_title'] = 'allocPSA login';

// include_template('templates/login.tpl');
// TODO: remove global variables
if (is_array($TPL)) {
    extract($TPL, EXTR_OVERWRITE);
}

echo $page->header(); ?>

<div class="width">
<?php echo $page->messages(); ?>
</div>

<form action="<?php echo $url_alloc_login; ?>" method="post" id="login_form">
<?php if (!empty($forward_url)) { ?>
<input type="hidden" name="forwardUrl" value="<?php echo $forward_url; ?>" />
<?php }
?>
<div class="width whitely corner shadow">
  <div id="links"><a onclick="javascript:$('.toggleable').toggle(); return false;" href="">New Password</a></div>

  <div class="toggleable">
    <span>Username</span>
    <span><input type="text" name="username" id="username" value="<?php echo $username; ?>" maxlength="32"></span>
    <span>Password</span>
    <span><input type="password" id="password" name="password" maxlength="32"></span>
    <span>&nbsp;</span>
    <span style="margin:25px 5px 30px 9px"><input type="submit" name="login" value="&nbsp;&nbsp;Login&nbsp;&nbsp;"></span>
  </div>

  <div class="toggleable" style="display:none">
    <span>Email</span>
    <span><input type="text" name="email" size="20" maxlength="32"></span>
    <span>&nbsp;</span>
    <span style="margin:25px 5px 30px 9px"><input type="submit" name="new_pass" value="Send Password"></span>
  </div>

  <div id="footer"><?php echo $status_line; ?><input type="hidden" name="account" value="<?php echo $account; ?>"></div>
</div>
<input type="hidden" name="sessID" value="<?php echo $sessID; ?>">
</form>

<?php if (!empty($latest_changes)) { ?>
<div class="width" style="font-size:90%">
    <?php echo $latest_changes; ?>
</div>
    <?php
}

echo $page->footer(); ?>
