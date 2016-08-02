<?php
/**
 * 修改密码
 * Created by PhpStorm.
 * User: xiju
 * Date: 11/3/2015
 * Time: 11:30 PM
 */
require_once('../../index.php');
//require_once('../data/fakeData.php');
//$postStr=file_get_contents("php://input");


try {
//    $data = $postStr;
//    $customerId = json_decode($postStr, true)['mobile'];
//    $password = json_decode($postStr, true)['password'];
//    $newPassword = json_decode($postStr, true)['newPassword'];

    $customerId = $_POST["customerId"];
    $password = $_POST["password"];
    $newPassword = $_POST["newPassword"];
    if ($customerId == null) {
        throw new exception("用户id不能为空");
    }
    if ($password == null) {
        throw new exception("当前密码不能为空");
    }
    if ($newPassword == null) {
        throw new exception("新密码不能为空");
    }

    $customerController->verifyPassword($customerId,$password);
    $customerController->setPassword($customerId,$newPassword,null);

    $res = $db->getAll("select customer_id as userId, shareCode from " . getTable('customer') . " where customer_id=" . $customer_id . " limit 1");
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}