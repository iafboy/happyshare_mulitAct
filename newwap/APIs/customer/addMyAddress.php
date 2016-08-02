<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/31
 * Time: 20:33
 */
require_once('../../index.php');
try {


    $customerId = $_GET["customerId"];
    $name = $_GET["name"];
    $mobile  = $_GET["mobile"];
    $province = $_GET["province"];
    $district  =$_GET["district"];
    $city = $_GET["city"];
    $address =  $_GET["address"];
    //1 是 // 0 否
    $isDefault = $_GET["isDefault"];



    $res = $customerController->modifyCustomerAddress(null,$customerId, $name, $mobile, $province, $district, $city, $address,$isDefault);


    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}