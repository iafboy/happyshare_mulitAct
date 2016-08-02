<?php

/**
 *
 * 积分提现
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/1/11
 * Time: 14:06
 */
require_once('../../index.php');

try {
//    $customerId = $_GET["customerId"];
//    $amount = $_GET["credit"];
//    $bankId = $_GET["bankId"];
//    $cardId = $_GET["cardId"];
//    $cardHolder = $_GET["cardHolder"];
//    $cardHolderId = $_GET["cardHolderId"];
//    $receiverEmail = $_GET["receiverEmail"];
//    $verifyCodeFromPage = $_GET["verifyCode"];


    $customerId = $_POST["customerId"];
    $amount = $_POST["credit"];
    $bankId = $_POST["bankId"];
    $cardId = $_POST["cardId"];
    $cardHolder = $_POST["cardHolder"];
    $cardHolderId = $_POST["cardHolderId"];
    $receiverEmail = $_POST["receiverEmail"];
//    $verifyCodeFromPage = $_POST["verifyCode"];


    if ($customerId == null) {
        throw new exception("用户id不能为空");
    }

    if ($amount == null) {
        throw new exception("积分值不能为空");
    }
    if ($bankId == null) {
        throw new exception("请选择具体银行");
    }
    if ($cardId == null) {
        throw new exception("银行卡号不能为空");
    }
    if ($cardHolder == null) {
        throw new exception("持卡人姓名不能为空");
    }
    if ($cardHolderId == null) {
        throw new exception("持卡人证件号码不能为空");
    }
    if ($receiverEmail == null) {
        throw new exception("收款人Email不能为空");
    }
//    $rightVerifyCode =$_SESSION['withdrawVerifyCode'];
//
//    if(0!=strcasecmp($verifyCodeFromPage,$rightVerifyCode)){
//        throw new exception("验证码不正确");
//    }

    $res = $creditController->creditToCash($customerId, $amount, $bankId, $cardId, $cardHolder, $cardHolderId, $receiverEmail);

//    unset($_SESSION['withdrawVerifyCode']);


    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}

