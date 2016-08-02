<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/27
 * Time: 23:09
 */
require_once('../index.php');
try {
    $mobile = $_POST["mobile"];

    if($mobile == null){
        throw new exception("手机号码不能为空");
    }
    $verifyCode = $commonController->generateRegCode($mobile);

    //$message = "欢迎注册好就分享，您的校验码是".$verifyCode."";
    $message = "欢迎注册好就分享，您的校验码是".$verifyCode."【深圳市享用网络科技】";
    $smsController->pushSMS2SoapClient($mobile,$message);
    $res = array("verifyCode"=>$verifyCode);
    //设置到session
    $_SESSION['verifyCode'] = $verifyCode;
    $log->info('Register Code : '.$verifyCode.' ---> '.$mobile);
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}