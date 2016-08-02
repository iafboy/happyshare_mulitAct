<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/27
 * Time: 18:28
 */
require_once('../../index.php');
require_once('./cartCache.php');
require_once('./ShoppingCart.php');

try {
    $productId = $_GET["productId"];
    $num= $_GET["num"];
    if($productId == null){
        throw new exception("请选择要添加的商品");
    }

    //添加到购物车
    $shoppingCart->addProdcut($customerId, $productId,$num);
    //设置到cache
    $cache->set("shoppingcart" . $shoppingCart->getCustomerId()
        , $shoppingCart);

//    $res = $shoppingCart->showCartDetail();
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}


