<?php
require_once('../../index.php');
//获取订单的支付金额
try {
    $orderNo  = $_GET["orderNoStr"];
    if(!is_valid($orderNo)){
        throw new Exception('订单号错误');
    }
    $orderNoArray = explode(',',$orderNo);
    $customerId  = $_GET["customerId"];
    if(!is_valid($customerId)){
        throw new Exception('客户ID错误');
    }
    $res = $leSharePayment->showOrderFeeInfoByOrderNos($orderNoArray,$customerId);
    $msg = new \leshare\json\message($res, 0, " success");


} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}


