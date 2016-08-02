<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/31
 * Time: 21:13
 */
require_once('../../index.php');
try {

    $customerId = $_GET["customerId"];
    $addressId =  $_GET["addressId"];
    $name = $_GET["name"];
    $mobile  = $_GET["mobile"];
    $province = $_GET["province"];
    $district  =$_GET["district"];
    $city = $_GET["city"];
    $address =  $_GET["address"];
    //1 是 // 0 否
    $isDefalut = $_GET["isDefalut"];



    $res = $customerController->modifyCustomerAddress($addressId,$customerId, $name, $mobile, $province, $district, $city, $address,$isDefalut);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}