<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/1/16
 * Time: 16:12
 */
require_once('../../index.php');

try {

    $supplierId = $_GET["supplierId"];

    $res = $productController->getProductListBySupplier($supplierId);

    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message(null, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
