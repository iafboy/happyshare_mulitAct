<?php
require_once('../../index.php');
//支付之前的确认接口
try {

    $res = $leSharePayment->processOrder();
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}


