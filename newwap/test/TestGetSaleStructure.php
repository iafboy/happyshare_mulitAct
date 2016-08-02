<?php
/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2016/1/17
 * Time: 0:40
 */
require_once('../index.php');
$customerId=23;
$date="201602";
$data=$registry->get("CreditController")->getSaleStructure($customerId, $date);
$msg = new \leshare\json\message($data, 0, " success");
$msg->writeJson();