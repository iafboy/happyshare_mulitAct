<?php

require_once('../index.php');
try {
    //取表mcc_promotion_products中产品列表，获取特价/积分翻倍活动的产品信息列表，需要结合mcc_product表组合查询

    //取表mcc_fp_refound中产品列表，获取需要回退的免费体验活动的产品信息列表，需要结合mcc_product表组合查询

    //取表mcc_fp_norefound中产品列表，获取不需要回退的免费体验活动的信息产品列表，需要结合mcc_product表组合查询

    $res1 = $promotionController->getCreditsPromotionList(1);
    $res2 = $promotionController->getFreeProductFPromotionList(1);
    $res3 = $promotionController->getFreeProductNFPromotionList(1);
    $reso = array_merge($res1, $res2, $res3);
    $msg = new \leshare\json\message($reso, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($data, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}