<?php
/**
 * 确认下单
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/28
 * Time: 22:02
 */
require_once('../../index.php');
require_once('./cartCache.php');
require_once('./ShoppingCart.php');
try {
    $productIds = $_GET["productIds"];
    $productNum = $_GET["nums"];
    $customerId = $_GET['customerId'];
    $addressId = $_GET["addressId"];

    //from ：‘cart/product/null’
    $from = $_GET["from"];
    $supplierIds = $_GET["supplierIds"];
    $orderMsg = $_GET["orderMsg"];

    //增加推荐分享码
    $shareId = $_SESSION['shareId'];
    $shareProductId = $_SESSION['shareProductId'];
    $referenceCode = $_SESSION['shareCode'];

    $res = $customerController->placeOrder($productIds, $productNum, $customerId, $addressId, $orderMsg, $supplierIds, $_GET,$shareId,$shareProductId, $referenceCode);

    if ($from == "cart" && $shoppingCart != null) {
        $idArray = explode(",", $productIds);
        foreach ($idArray as $productId) {
            $shoppingCart->removeProduct($productId);
        }
        //设置到cache
        $cache->set("shoppingcart" . $shoppingCart->getCustomerId()
            , $shoppingCart);
    }

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}

