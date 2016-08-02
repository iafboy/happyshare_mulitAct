<?php
/**
 * 积分赠送
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/27
 * Time: 10:56
 */
require_once('../../index.php');

try {
    $customerId = $_GET["customerId"];
    $mobile =  $_GET["targetMobile"];
    $credit =  $_GET["credit"];

    if ($customerId == null) {
        throw new exception("用户id不能为空");
    }

    if ($mobile == null) {
        throw new exception("赠送手机号不能为空");
    }
    if ($credit == null) {
        throw new exception("积分值不能为空");
    }

    $targetCustomerId = $customerController->queryCustomerIdByMobile($mobile);
    //这个是liutao写的
    //$customerController->creditTrans($customerId,$targetCustomerId,$credit);
    //2016 01 10 改为曾纪鹏提供的api
    $creditController->giveCredit($customerId,$targetCustomerId,$credit);


    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}


