<?php
require_once('../../index.php');
try {
    $orderNo  = $_GET["orderNo"];
    $res = $leSharePayment->showSameProductWithOrder($orderNo);
    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}


