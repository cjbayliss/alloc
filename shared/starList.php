<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once("../alloc.php");

$client_defaults = array("starred"=>true);
$clientContact_defaults = array("starred"=>true);
$project_defaults = array("starred"=>true);
$comment_defaults = array("starred"=>true);
$productSale_defaults = array("starred"=>true);
$wiki_defaults = array("starred"=>true);

$task_defaults = array("showHeader"  => true,
                       "showTaskID"  => true,
                       "showStarred" => true,
                       "showStatus"  => true,
                       "showProject" => true,
                       "starred"     => true);

$timeSheet_defaults = array("starred" => true, "noextra" => true);

$invoice_defaults = array("showHeader"            => true,
                          "showInvoiceNumber"     => true,
                          "showInvoiceClient"     => true,
                          "showInvoiceName"       => true,
                          "showInvoiceAmount"     => true,
                          "showInvoiceAmountPaid" => true,
                          "showInvoiceDate"       => true,
                          "showInvoiceStatus"     => true,
                          "starred"               => true);

$star_entities = array("client"        => array("label"=>"Clients"       ,"form"=> $client_defaults),
                       "clientContact" => array("label"=>"Contacts"      ,"form"=> $clientContact_defaults),
                       "project"       => array("label"=>"Projects"      ,"form"=> $project_defaults),
                       "task"          => array("label"=>"Tasks"         ,"form"=> $task_defaults),
                       "comment"       => array("label"=>"Comments"      ,"form"=> $comment_defaults),
                       "timeSheet"     => array("label"=>"Time Sheets"   ,"form"=> $timeSheet_defaults),
                       "invoice"       => array("label"=>"Invoices"      ,"form"=> $invoice_defaults),
                       "productSale"   => array("label"=>"Sales"         ,"form"=> $productSale_defaults),
                       "wiki"          => array("label"=>"Wiki Documents","form"=> $wiki_defaults),
                       //"tf"            => array("label"=>"Tagged Funds"  ,"form"=> $tf_defaults)
);

$TPL["star_entities"] = $star_entities;


include_template("templates/starListM.tpl");
