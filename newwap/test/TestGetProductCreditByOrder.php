<?php
/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2016/1/17
 * Time: 0:40
 */
require_once('../index.php');
$orderId=32;
$data=$registry->get("ProductController")->getProductCreditByOrder($orderId);
$msg = new \leshare\json\message($data, 0, " success");
$msg->writeJson();