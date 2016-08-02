<?php
/**
 * 清空购物车
 * * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/25
 * Time: 22:06
 */
require_once('../../index.php');
require_once('./cartCache.php');
require_once('./ShoppingCart.php');
try {

    //设置到cache
    $cache->delete("shoppingcart" .$shoppingCart->getCustomerId());


//echo $shoppingCart->getCustomerId();
//print_r($shoppingCart->getProductArray());
    $msg = new \leshare\json\message($shoppingCart, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}


