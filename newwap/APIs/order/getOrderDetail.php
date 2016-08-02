<?php
/**
 * Created by PhpStorm.
 * User: xiju
 * Date: 11/4/2015
 * Time: 1:16 AM
 */
require_once('../../index.php');
try {
    $orderNo  = $_GET["orderNo"];
    $supplierId =  $_GET["supplierId"];
    $res = $leSharePayment->showOrderDetailV2($orderNo,$supplierId);
    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}


