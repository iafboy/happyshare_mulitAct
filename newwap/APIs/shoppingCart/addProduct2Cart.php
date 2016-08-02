<?php
/**
 * 向购物车中添加商品
 * * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/25
 * Time: 22:06
 */
require_once('../../index.php');
require_once('./cartCache.php');
require_once('./ShoppingCart.php');
try {
    $productId = $_GET["productId"];

    if ($productId == null) {
        throw new exception("请选择要添加的商品");
    }

    //校验产品是否存在
    $request = array("productId"=>$productId);
    $productController->getProductDetail($request);


    //添加到购物车
    $shoppingCart->addProdcut($customerId, $productId);

    $cache->set("shoppingcart" . $shoppingCart->getCustomerId()
        , $shoppingCart);


    $msg = new \leshare\json\message($shoppingCart, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}


