<?php
/**
 * Created by PhpStorm.
 * User: xiju
 * Date: 11/3/2015
 * Time: 11:30 PM
 */
require_once('../../index.php');



try {
//$data = $postStr;
//$mobile = json_decode($postStr, true)['mobile'];
    $mobile = $_POST["mobile"];

//    $mobile = $_GET["mobile"];
    if($mobile == null){
        throw new exception("mobile不能为空");
    }

//根据手机号找到mcc_customer表中的记录
    $sql = "select customer_id from ".getTable('customer')." where telephone = '". $db->escape($mobile)."'";
    $res = $db->getAll($sql);
    if(count($res)==0){
        throw new exception("对应用户不存在,mobile:".$mobile);
    }
    $customer_id = $res[0]['customer_id'];

//取salt值
    $salt = substr(md5(uniqid(rand(), true)), 0, 9);

//通过randmon方法获取随机密码
    $password = $commonController->generatePassword();
    $pwValue = sha1($salt . sha1($salt . sha1($password)));

//重设秘钥值

//sql中使用$db->escape(sha1($salt . sha1($salt . sha1($password))))更新数据库中的密码

//更新mcc_customer表
    $sql = "update ". getTable('customer') ." set salt = '".$db->escape($salt) ."', password = '".$db->escape($pwValue)."' where customer_id=" . $customer_id ;
    $db->query($sql);

//将新密码组成发json消息，发送至短信发送模块
    //$message = "密码变更为:" . $password;
    $message="欢迎使用好就分享，您的密码是".$password."【深圳市享用网络科技】";
    $smsController->pushSMS2SoapClient($mobile, $message);



    $res = $db->getAll("select customer_id as userId, shareCode from " . getTable('customer') . " where customer_id=" . $customer_id . " limit 1");
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}