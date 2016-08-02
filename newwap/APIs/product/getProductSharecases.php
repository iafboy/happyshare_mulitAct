<?php
require_once('../../index.php');
require_once('../../tools.php');
try {

    $productId = $_GET["product_id"];

    if($productId == null){
        throw new exception("商品id不能为空");
    }

    $res = $productController->getProductShareCasesInfo($productId);
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message(null, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
