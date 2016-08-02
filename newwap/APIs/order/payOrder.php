<?php
require_once('../../index.php');
//支付之前的确认接口
try {
//      orderNo,scoreAmount,payMethod,payAmount    :
//      scoreAmount: '积分支付数额'，payMethod:'wx/ali/union',payAmount:'现金支付金额'

    $orderNo = $_GET["orderNo"];
    $scoreAmount = $_GET["scoreAmount"];
    $payMethod = $_GET["payMethod"];
    $payAmount = $_GET["payAmount"];
    $customerId = $_GET["customerId"];
    $relateType = $_GET["relateType"];
    $orderGroupNo = $_GET["orderGroupNo"];

    $orderPaymentNo = $leSharePayment->payOrder($orderNo, $scoreAmount, $payMethod, $payAmount,$customerId,$relateType,$orderGroupNo);
    $msg = new \leshare\json\message(['orderPaymentNo'=>$orderPaymentNo], 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}


