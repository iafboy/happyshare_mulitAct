<?php
require_once('../../index.php');
try {

    $orderNo = $_GET["orderNo"];
    if($orderNo == null){
        throw new exception("订单号不能为空");
    }
    $res = $leSharePayment->cancelOrder($orderNo);
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
