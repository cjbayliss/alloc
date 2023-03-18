<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

function dump_email($uid, $mail)
{
    if ($uid) {
        exit();
    }
}



//$lockfile = ATTACHMENTS_DIR."mail.lock.person_".$current_user->get_id();

$info["host"] = config::get_config_item("allocEmailHost");
$info["port"] = config::get_config_item("allocEmailPort");
$info["username"] = config::get_config_item("allocEmailUsername");
$info["password"] = config::get_config_item("allocEmailPassword");
$info["protocol"] = config::get_config_item("allocEmailProtocol");

if (!$info["host"]) {
    alloc_error("Email mailbox host not defined, assuming email fetch function is inactive.", true);
}

if ($_REQUEST["commentID"]) {
    $c = new comment();
    $c->set_id($_REQUEST["commentID"]);
    $c->select();

    $entity = $c->get_value("commentMaster");
    $entityID = $c->get_value("commentMasterID");

    $mail = new email_receive($info);
    $mail->open_mailbox(config::get_config_item("allocEmailFolder")."/".$entity.$entityID);

    if ($_REQUEST["uid"]) {
        header('Content-Type: text/plain; charset=utf-8');
        list($h,$b) = $mail->get_raw_email_by_msg_uid($_REQUEST["uid"]);
        $mail->close();
        echo $h.$b;
        exit();
    }

    //$uids = $mail->get_all_email_msg_uids();

    $t = new token();
    $t->select_token_by_entity_and_action($c->get_value("commentType"), $c->get_value("commentLinkID"), "add_comment_from_email");
    $hash = $t->get_value("tokenHash");

    // First try a messageID search
    if ($c->get_value("commentEmailMessageID")) {
        $str = sprintf('TEXT "%s"', $c->get_value("commentEmailMessageID"));
        $uids = $mail->get_emails_UIDs_search($str);
        if (count($uids) == 1) {
            alloc_redirect($TPL["url_alloc_downloadEmail"]."commentID=".$_REQUEST["commentID"]."&uid=".$uids[0]);
        } else if (count($uids) > 1) {
            $all_uids += $uids;
        }
    }

    // Next try a hash lookup
    if ($hash) {
        $str = sprintf('TEXT "%s"', $hash);
        $uids = $mail->get_emails_UIDs_search($str);
        $uids and $all_uids += $uids;
    }


    $str = sprintf('FROM "%s" ', $c->get_value("commentCreatedUserText"));
    $str.= sprintf(' ON "%s"', format_date("d-M-Y", $c->get_value("commentCreatedTime")));
    $uids = $mail->get_emails_UIDs_search($str);
    $uids and $all_uids += $uids;


    // Couldn't get a body text search to work! Refuses to match long needles.
    //echo "<br><br>Using FROM and DATE:".print_r($uids,1);
    //$text = $c->get_value("comment");
    //$text = str_replace('\r\n','\n',$text);
    //$text = str_replace('\n',' ',$text);
    //$text = str_replace('\r',' ',$text);
    //$text = str_replace('"','\"',$text);
    //$text = substr($text,0,25);
    //$text = trim($text);
    //echo "<br><br>--".htmlentities($text)."--<br><br>";
    //$str = sprintf('BODY "%s"',$text);
    //$uids = $mail->get_emails_UIDs_search($str);
    //echo "<br><br>Using BODY:".print_r($uids,1);

    $mail->close();
}
