<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/2/5
 * Time: 20:32
 */
require_once('../../index.php');
try {
    $addressId =  $_GET["addressId"];
    $customerId =  $_GET["customerId"];
    $res = $customerController->setDefaultAddress($customerId,$addressId);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}