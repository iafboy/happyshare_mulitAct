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

    if ($customerId == null) {
        throw new exception("用户id不能为空");
    }
    $res = $customerController->queryCustomerAddressList($customerId);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}