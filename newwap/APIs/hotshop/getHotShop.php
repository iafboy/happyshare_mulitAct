<?php
/**
 *
 * 获取热门商品
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/11/15
 * Time: 10:55
 */
require_once('../../index.php');

try {
    //按照推荐 销量 新品来切换展示
    //recommended buyNum newArrival
    //TODO
    $show = $_GET["show"];
    $num =  $_GET["num"];
    $ztg=  $_GET["ztg"];

    if($num == null){
        $num = 10;
    }

    $res = $productController->getHotShopProductList($show,$ztg,$num);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}

