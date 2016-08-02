<?php
/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2015/12/20
 * Time: 20:02
 */
require_once('../index.php');
$orderId=32;
$data=$registry->get("CreditController")->removeCreditByOrder($orderId);
$msg = new \leshare\json\message($data, 0, " success");
$msg->writeJson();