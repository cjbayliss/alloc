<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

/*
  Username,
  First_Name,
  Surname,
  Password,
  E-mail,
  Phone No,
  Comments
*/

$cur = config::get_config_item("currency");

$row = 1;
if (($handle = fopen("../../David_People.csv", "r")) !== false) {
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        foreach ($data as $key => $val) {
            //  $data[$key] = utf8_encode($data[$key]);
        }

        $person = new person();
        $person->currency = $cur;
        $person->set_value("username", $data[0]);
        $person->set_value("firstName", $data[1]);
        $person->set_value("surname", $data[2]);
        $person->set_value("password", password_hash($data[3], PASSWORD_BCRYPT));
        $person->set_value("emailAddress", $data[4]);
        $person->set_value("phoneNo1", $data[5]);
        $person->set_value("comments", $data[6]);
        $person->set_value("perms", "employee");
        $person->set_value("personActive", 1);
        $person->set_value("personModifiedUser", $current_user->get_id());
        $person->save();

        $x++;
        echo "<br>here: " . $person->get_id() . $data[0];
        if ($x > 4) {
            // die();
        }
    }
    fclose($handle);
}
