<?php
require_once('../../index.php');
require_once('../../tools.php');
try {

    $productId = $_GET["productId"];
    $customerId =  $_GET['customerId'];

    if($productId == null){
        throw new exception("商品id不能为空");
    }
    if($customerId == null){
        $customerId =  $_SESSION['customerId'];
    }

    $res = $productController->getProductShareCase($productId,$customerId);
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message(null, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
