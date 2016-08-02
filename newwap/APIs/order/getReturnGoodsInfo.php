<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/11/16
 * Time: 22:22
 */
require_once('../../index.php');
try {

    $supplierId = $_GET['supplierId'];
    $orderProductId = $_GET['order_product_id'];
    $res = $leSharePayment->queryReturnGoodsInfo($supplierId,$orderProductId);
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
