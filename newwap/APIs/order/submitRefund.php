<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/11/16
 * Time: 22:22
 */
require_once('../../index.php');
try {

    $orderNoProductId = $_GET['order_product_id'];
    $shippmentCompany = $_GET['shippment_company'];
    $shippmentNo = $_GET['shippment_no'];

    $bankId   =  $_GET['bankId'];
    $cardId = $_GET["cardId"];
    $cardHolder = $_GET["cardHolder"];


    $res = $leSharePayment->submitRefund($orderNoProductId,$shippmentCompany,$shippmentNo,$bankId,$cardId,$cardHolder);
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
