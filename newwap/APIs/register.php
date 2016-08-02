<?php
/**
 * Created by PhpStorm.
 * User: xiju
 * Date: 11/8/2015
 * Time: 12:10 PM
 */
require_once('../index.php');
require_once('../core/phpqrcode/phpqrcode.php');

//require_once('../data/fakeData.php');
try {
    $rightVerifyCode =$_SESSION['verifyCode'];

    $mobile = $_POST['mobile'];
    $customer_name = $_POST['userName'];
    $password = $_POST['password'];
    $verifyCode = $_POST['verifyCode'];
    $shareCode = $_POST['shareCode'];

    //test
//    $rightVerifyCode =$_GET['verifyCode'];
//    $mobile = $_GET['mobile'];
//    $customer_name = $_GET['userName'];
//    $password = $_GET['password'];
//    $verifyCode = $_GET['verifyCode'];
//    $shareCode = $_GET['shareCode'];

    //TODO
//    $avatarId =  $_POST['avatarId'];
    //现无头像上传模块，统一使用同一用户头像
    $avatarId = 1;
    if ($mobile == null) {
        throw new exception("手机号不能为空");
    }
    if ($password == null) {
        throw new exception("密码不能为空");
    }
    $customer_name = $mobile;
    if ($customer_name == null) {
        throw new exception("用户名不能为空");
    }
    if ($avatarId == null) {
        throw new exception("请选择头像");
    }

    if ($verifyCode == null) {
        throw new exception("验证码不能为空");
    }

    if(0!=strcasecmp($verifyCode,$rightVerifyCode)){
        throw new exception("验证码不正确");
    }
    if ($shareCode == null) {
        throw new exception("推荐码不能为空");
    }

    //验证推荐码
    $fromCustId = $customerController->getCustomerIdByShareCode($shareCode);
    if($fromCustId == null){
        throw new exception("推荐码无效");

    }


    //根据手机号找到mcc_customer表中的记录
    $sql = "select customer_id as userId from " . getTable('customer') . " where telephone = '" . $db->escape($mobile) . "'";

    $res = $db->getAll($sql);
    if (count($res) > 0) {
        $msg = new \leshare\json\message(null, 2, "手机号对应用户已存在");
    } else {
        //注册
        $shareId = $_SESSION['shareId'];
        if($shareCode != null && $shareId == null){
            //有推荐码传过来，但是没有调用过增加点击次数
            $type = 1;
            $shareId = $sharingController->newShareRecord($type,null,null,$shareCode);
        }
        //$customerController->registerCustomer 会统一增加积分，此处去除
//        if($shareId!=null){
//             $sharingController->activeShareRecord($shareId);
//        }
        $res = $customerController->registerCustomer($customer_name, $mobile, $password, $avatarId, $shareCode,$shareId);

        //设置session
        unset($_SESSION['verifyCode']);
        $_SESSION['customerId'] = $res['userId'];

        $msg = new \leshare\json\message($res, 0, " success");
    }


} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}