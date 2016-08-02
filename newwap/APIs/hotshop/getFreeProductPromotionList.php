<?php

require_once('../../index.php');
try {

    //取表mcc_fp_refound中产品列表，获取需要回退的免费体验活动的产品信息列表，需要结合mcc_product表组合查询

    //取表mcc_fp_norefound中产品列表，获取不需要回退的免费体验活动的信息产品列表，需要结合mcc_product表组合查询

    $res  = $productController->getFreeTrialProductList();
//    if(count($res) == 0){
//        throw new exception("暂无免费体验商品" );
//
//    }
    $msg = new \leshare\json\message($reso, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($data, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}