<?php
/**
 * 获取活动
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/23
 * Time: 0:07
 */
require_once('../../index.php');
try {

    $promotionType = $_GET["promotionType"];
    if($promotionType == null){
        $promotionType = 0;
    }
    //取表mcc_promotion_products中产品列表，获取特价/积分翻倍活动的产品信息列表，需要结合mcc_product表组合查询
    if ($promotionType == 0) {
        $res = $promotionController->getCreditsPromotionList();

    }
    //TODO
    //积分翻倍和特价活动需要区分
    if ($promotionType == 1) {
        $res = $promotionController->getCreditsPromotionList();

    }

    if($promotionType == 2){
        $res2 = $promotionController->getFreeProductFPromotionList();
        $res3 = $promotionController->getFreeProductNFPromotionList();
        $res = array_merge( $res2, $res3);

    }

    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($data, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}