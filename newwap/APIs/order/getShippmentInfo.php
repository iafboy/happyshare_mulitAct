<?php
/**
 * deprecated
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/11
 * Time: 22:48
 */
require_once('../../index.php');
try {
    $orderId = $_GET["orderId"];
    if ($orderId != null) {
        $res = $shippmentController->getShippmentInfo($orderId);
        $msg = new \leshare\json\message($res, 0, " success");

    } else {
        throw new exception("orderId不能为空");
    }
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}


