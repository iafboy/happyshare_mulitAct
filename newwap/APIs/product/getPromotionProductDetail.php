<?php
/**
 * 获取活动商品详情
 * Created by PhpStorm.
 * User: liutao
 * Date: 11/12/2015
 * Time: 12:36 AM
 */
require_once('../../index.php');
//require_once('../data/fakeData.php');
//$res=$fake_getProducDetail;
//$msg = new \leshare\json\message($res,0," success");
//$msg->writeJson();
try {
    $productId = $_GET["product_id"];
    $promotionType = $_GET["promotionType"];
    if ($promotionType == null) {
        $promotionType = 0;
    }

    // 产品信息
    $product = $productController->getProductInfo($productId);
    $res = $product[0];

    //分享次数
    $shareNum = $sharingController->showShareNums($productId);

    //评价次数
    $commentNum = $commentsController->showCommentNum($productId);

    //收藏次数
    $collectNum = $collectionController->showCollectionNums($productId);

    //购买次数
    $buyNum = $productController->showBuyNums($productId);

    $res = array_merge($res, array("shareNum" => $shareNum));
    $res = array_merge($res, array("commentNum" => $commentNum));
    $res = array_merge($res, array("collectNum" => $collectNum));
    $res = array_merge($res, array("xiaoliang" => $buyNum));
    //产品图片
    $productImg = $productController->getProductImgs($productId);
    $res = array_merge($res, array("imgs" => $productImg));

    //积分信息
    $jifen = $productController->getProductCreditByDate($productId, date("Ymd"));
    $res = array_merge($res, array("jifen" => round($jifen)));
    //TODO
    $res = array_merge($res, array("gm_link" => "#"));
    $res = array_merge($res, array("gwc_link" => "#"));


    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message(null, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
