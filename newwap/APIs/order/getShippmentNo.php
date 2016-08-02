<?php
require_once('../../index.php');
try {
    $orderNo = $_GET["orderNo"];
    if ($orderNo != null) {
        $res = $shippmentController->getShippmentOrderNo($orderNo);

    } else {
        throw new exception("订单号码不能为空");
    }
    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}


