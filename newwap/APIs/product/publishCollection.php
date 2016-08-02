<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/8
 * Time: 22:22
 */
require_once('../../index.php');
try {
    $productId = $_GET["productId"];
    $customerId = $_GET["customerId"];

    if($productId == null){
        throw new exception("参数产品编码为空" );
    }
    if($customerId == null){
        throw new exception("参数客户编码为空" );
    }

    $collectionController->publishCollection($productId,$customerId);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}