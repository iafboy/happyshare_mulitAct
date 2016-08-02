<?php
/**
 * 请购物车删除选择的商品
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/26
 * Time: 10:51
 */
require_once('../../index.php');
require_once('./cartCache.php');
require_once('./ShoppingCart.php');
try {
    $productId = $_GET["productId"];

    if($productId == null){
        throw new exception("请选择要删除的商品");
    }

    //添加到购物车
    $shoppingCart->removeProduct($productId);
    //设置到cache
    $cache->set("shoppingcart" . $shoppingCart->getCustomerId()
        , $shoppingCart);


    $msg = new \leshare\json\message($shoppingCart, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
