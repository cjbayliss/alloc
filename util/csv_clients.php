<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

/*
  ClientName
  Phone Number
  Fax Number
  Postal Address
  Postal Suburb
  Postal State
  Postal Postcode
  Street Address
  Street Suburb
  Comment
  Main_Contact
  Main Contact Email

*/

$row = 1;
if (($handle = fopen("../../David_Clients.csv", "r")) !== false) {
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        foreach ($data as $key => $val) {
            $data[$key] = utf8_encode($data[$key]);
        }

        unset($comment_id, $cc_id);
        $client = new client();
        $client->set_value("clientName", $data[0]);
        $client->set_value("clientPhoneOne", $data[1]);
        $client->set_value("clientFaxOne", $data[2]);
        $client->set_value("clientStreetAddressOne", $data[3]);
        $client->set_value("clientSuburbOne", $data[4]);
        $client->set_value("clientStateOne", $data[5]);
        $client->set_value("clientPostcodeOne", $data[6]);
        $client->set_value("clientStreetAddressTwo", $data[7]);
        $client->set_value("clientSuburbTwo", $data[8]);
        $client->set_value("clientStatus", "current");
        $client->set_value("clientModifiedUser", $current_user->get_id());
        $client->save();

        if ($client->get_id()) {
            if (rtrim($data[9])) {
                $comment = new comment();
                $comment->set_value("commentMaster", "client");
                $comment->set_value("commentMasterID", $client->get_id());
                $comment->set_value("commentType", "client");
                $comment->set_value("commentLinkID", $client->get_id());
                $comment->set_value("comment", $data[9]);
                $comment->save();
                $comment_id = $comment->get_id();
            }

            if ($data[10] || $data[11]) {
                $cc = new clientContact();
                $cc->set_value("clientID", $client->get_id());
                $cc->set_value("primaryContact", 1);
                $cc->set_value("clientContactName", $data[10]);
                $cc->set_value("clientContactEmail", $data[11]);
                $cc->save();
                $cc_id = $cc->get_id();
            }
        }
        $x++;
        echo "<br>".$client->get_id()." --- ".$cc_id." --- ".$comment_id;
        if ($x>4) {
            //die();
        }
    }
    fclose($handle);
}
