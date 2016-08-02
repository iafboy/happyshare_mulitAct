<?php
/**
 * 确认收货
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/2/19
 * Time: 21:26
 */
require_once('../../index.php');
require_once('../../tools.php');
try {
    $orderNo = $_GET["orderNo"];
    $supplierId =  $_GET["supplierId"];

    $res = $leSharePayment->reciptSupplierOrder($orderNo,$supplierId);
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}


