<?php
/**
 * Created by PhpStorm.
 * User: xiju
 * Date: 11/4/2015
 * Time: 12:56 AM
 */
require_once('../../index.php');
try {
    //http://localhost/leshare/newwap/APIs/publishComments.php?customerId=1&productId=2&comment=不错
    //request中取得用户customerid
    //request中取得productId
    //request中取得评价内容
    $productId = $_GET["productId"];
    $customerId = $_GET["customerId"];
    $commentText = $_GET["comment"];

    if($productId == null){
        throw new exception("参数产品编码为空" );
    }
    if($customerId == null){
        throw new exception("参数用户编码为空" );
    }
    if($commentText == null){
        throw new exception("参数产品评价为空" );
    }

    //$picPaths =  array('pic1','pic2','pic3','pic4','pic5','pic6');
    $commentsController->publishCommentsV2($commentText,$productId,$customerId);
    //将数据写入表mcc_customer_ophistory，其中参数operation_type为 2
//    $sql = "INSERT INTO " . getTable('customer_ophistory') . " SET product_id = '" . (int)$productId . "', operation_type = 2, customer_id='" . (int)$customerId . "', comments = '" . $db->escape($commentText) . "', createTime =  NOW()";
//    $db->query($sql);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}