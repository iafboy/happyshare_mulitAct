<?php
require_once('../../index.php');
try {
    //取表mcc_promotion_products中产品列表，获取特价/积分翻倍活动的产品信息列表，需要结合mcc_product表组合查询


    $res  = $productController->getSpecialActProductList();
//    if(count($res) == 0){
//        throw new exception("暂无特价商品" );
//
//    }
    $msg = new \leshare\json\message($res1, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($data, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}