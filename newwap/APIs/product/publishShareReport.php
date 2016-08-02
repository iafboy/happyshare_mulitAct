<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/9
 * Time: 21:56
 */
require_once('../../index.php');
require_once('../../core/FileUtil.php');

try {
    //http://localhost/leshare/newwap/APIs/publishComments.php?customerId=1&productId=2&comment=不错
    //request中取得用户customerid
    //request中取得productId
    $productId = $_GET["productId"];
    $customerId = $_GET["customerId"];

    if($productId == null){
        throw new exception("参数产品编码为空" );
    }
    if($customerId == null){
        throw new exception("参数用户编码为空" );
    }




//    $pic1 = $_GET["pic1"];
//    $pic2 = $_GET["pic2"];
//    $pic3 = $_GET["pic3"];
//    $pic4 = $_GET["pic4"];
//    $pic5 = $_GET["pic5"];
//
//
//    //TODO
////    $picPaths =  array('pic1','pic2','pic3','pic4','pic5','pic6');
//    $picPaths =  array($pic1,$pic2,$pic3,$pic4,$pic5);
//    foreach ($picPaths as $pic) {
//        $imgFile = str_ireplace(HTTP_SERVER,DIR_IMAGE,$pic);
//        $targetImg = DIR_IMAGE."products/sharecases/111.jpg";
//        copy($imgFile,$targetImg);
//
//
//        $sql = $sql . ",sharePic" . $index . " = '" . $this->db->escape($pic) . "'";
//        $index++;
//        if ($index > 5) {
//            break;
//        }
//    }

    $sharingController->pulishShare($productId, $customerId);
    //将数据写入表mcc_customer_ophistory，其中参数operation_type为 2
//    $sql = "INSERT INTO " . getTable('customer_ophistory') . " SET product_id = '" . (int)$productId . "', operation_type = 2, customer_id='" . (int)$customerId . "', comments = '" . $db->escape($commentText) . "', createTime =  NOW()";
//    $db->query($sql);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}