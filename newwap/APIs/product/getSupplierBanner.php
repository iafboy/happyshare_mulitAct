<?php
require_once('../../index.php');

try {

    $supplierId = $_GET["supplierId"];
    if($supplierId == null){
        throw new exception("品牌供货商id不能为空");
    }
    $res = $productController->getSupplierBanner($supplierId);

    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message(null, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
