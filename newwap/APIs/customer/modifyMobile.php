<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/1/10
 * Time: 23:32
 */
require_once('../../index.php');
try {

    $pwd = $_POST['pwd'];
    $mobile =  $_POST['mobile'];
    $customerId =  $_POST['customerId'];
    $verifyCode = $_POST['verifyCode'];
    $rightVerifyCode =$_SESSION['modifyPhoneVerifyCode'];

    if ($mobile == null) {
        throw new exception("手机号不能为空");
    }
    if ($verifyCode == null) {
        throw new exception("验证码不能为空");
    }

    if(0!=strcasecmp($verifyCode,$rightVerifyCode)){
        throw new exception("验证码不正确");
    }

    $res = $customerController->modifyCustomerMobile($customerId,$mobile,$pwd);
    unset($_SESSION['modifyPhoneVerifyCode']);


    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}