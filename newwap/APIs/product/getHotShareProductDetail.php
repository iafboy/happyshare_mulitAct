<?php
/**
 * 获取热门分享商品详情
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

//    $shareNumSql = "SELECT sharenum as shareNum from " . getTable('sharehistory_view') . " where product_id =" . $productId . " limit 1";
//    $commentNummSql = "SELECT commentnum as commentNum from " . getTable('commenthistory_view') . " where product_id =" . $productId . " limit 1";
//    $collectNumSql = "SELECT collect as collectNum from " . getTable('collecthistory_view') . " where product_id =" . $productId . " limit 1";
    $buyNumSql = "select count(1) as xiaoliang from ".getTable('customer_ophistory'). " where product_id =" . $productId;

//    $res = array_merge($res,array("content" =>htmlspecialchars_decode($content[0]['content'])));


     // 产品信息
    $product = $productController->getProductInfo($productId);
    $res = $product[0];

// CONCAT('".$domain."/',a.image) AS topic,
    //分享次数
    $shareNum = $sharingController->showShareNums($productId);

    //评价次数
    $commentNum = $commentsController->showCommentNum($productId);

    //收藏次数
    $collectNum = $collectionController->showCollectionNums($productId);

    //购买次数
    $buyNum =  $productController->showBuyNums($productId);

    $res = array_merge($res, array("shareNum" =>$shareNum));
    $res = array_merge($res, array("commentNum" => $commentNum));
    $res = array_merge($res, array("collectNum" => $collectNum));
    $res = array_merge($res, array("xiaoliang" => $buyNum));

    $hasCollented = 0;
    if(isset($_SESSION['customerId'])){
        $customerId =$_SESSION['customerId'];
        //祖凯要求，增加字段判断客户是否有收藏当前商品
        $collectRes = $collectionController->queryMyCollection($customerId,$productId);
        if(count ($collectRes)>0){
            $hasCollented = 1;
        }
    }
    $res = array_merge($res, array("hasCollented" => $hasCollented));

    //test
    $customerId = 1;


    //产品图片
    $productImg = $productController->getProductImgs($productId);
    $res = array_merge($res, array("imgs" => $productImg));

    //积分信息
    $jifen = $productController->getProductCreditByDate($productId,date("Ymd"));
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
