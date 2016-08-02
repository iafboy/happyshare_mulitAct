<?php
/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2016/1/17
 * Time: 0:40
 */
require_once('../index.php');
$orderNo='qgmrio0g6wily2mo';
$isSuccess=1;
$respMsg="测试confirmPayOrder";
$transactionId="jelsie";
$data=$registry->get("LeSharePayment")->confirmPayOrder($orderNo, $isSuccess, $respMsg, $transactionId);
$msg = new \leshare\json\message($data, 0, " success");
$msg->writeJson();