<?php
/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2015/12/20
 * Time: 20:02
 */
require_once('../index.php');
$customerId1 = '1';
$customerId2 = '2';
$amount = '10';
$data=$registry->get("CreditController")->giveCredit($customerId1, $customerId2, $amount);
$msg = new \leshare\json\message($data, 0, " success");
$msg->writeJson();