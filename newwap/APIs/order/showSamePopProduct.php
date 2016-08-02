<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/12
 * Time: 23:25
 */
//购买成功后显示订单同类产品top10
require_once('../../index.php');
try {
    $productId = $_GET["productId"];
    if ($productId != null) {
        $res = $leSharePayment->showSamePopProduct($productId);

        $msg = new \leshare\json\message($res, 0, " success");

    } else {
        throw new exception("productId不能为空");
    }
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
