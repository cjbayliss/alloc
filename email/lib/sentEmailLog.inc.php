<?php

/*
 * Copyright (C) 2006, 2007, 2008 Alex Lance, Clancy Malcolm, Cybersource
 * Pty. Ltd.
 * 
 * This file is part of the allocPSA application <info@cyber.com.au>.
 * 
 * allocPSA is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at
 * your option) any later version.
 * 
 * allocPSA is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with allocPSA. If not, see <http://www.gnu.org/licenses/>.
*/


class sentEmailLog extends db_entity {
  var $classname = "sentEmailLog";
  var $data_table = "sentEmailLog";

  function sentEmailLog() {
      $this->db_entity();       // Call constructor of parent class
      $this->key_field = new db_field("sentEmailLogID");
      $this->data_fields = array("sentEmailTo"=>new db_field("sentEmailTo")
                                 , "sentEmailSubject"=>new db_field("sentEmailSubject")
                                 , "sentEmailBody"=>new db_field("sentEmailBody")
                                 , "sentEmailHeader"=>new db_field("sentEmailHeader")
                                 , "sentEmailType"=>new db_field("sentEmailType")
                                 , "sentEmailLogCreatedTime"=>new db_field("sentEmailLogCreatedTime")
                                 , "sentEmailLogCreatedUser"=>new db_field("sentEmailLogCreatedUser")
      );
  }
}


?>