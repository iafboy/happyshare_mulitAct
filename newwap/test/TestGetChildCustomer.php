<?php
/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2015/12/20
 * Time: 20:02
 */
require_once('../index.php');
$customerId = '1';
$data=$registry->get("CreditController")->getChildCustomer($customerId);
$msg = new \leshare\json\message($data, 0, " success");
$msg->writeJson();