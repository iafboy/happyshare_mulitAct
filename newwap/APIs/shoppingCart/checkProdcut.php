<?php
/**
 * 勾选商品
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/27
 * Time: 18:21
 */
require_once('../../index.php');
require_once('./cartCache.php');
require_once('./ShoppingCart.php');
try {
    $productId = $_GET["productId"];
    $ghsId = $_GET["ghsId"];
    $isChk =$_GET["isChk"];
    if ($productId == null && $ghsId == null) {
        throw new exception("参数商品id或供货商id必须");
    }

    if ($isChk == null) {
        $isChk = "";
    }
    if ($ghsId != null) {
        $shoppingCart->checkGhs($isChk, $ghsId);
    }
    if ($productId != null) {
        $shoppingCart->checkProduct($isChk, $productId);
    }
    //设置到cache
    $cache->set("shoppingcart" . $shoppingCart->getCustomerId()
        , $shoppingCart);


//echo $shoppingCart->getCustomerId();
//    print_r($shoppingCart->getProductArray());
    $msg = new \leshare\json\message($shoppingCart, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}


