<?php
/**
 *
 * 获取具体产品评价
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/11/15
 * Time: 11:03
 */
require_once('../../index.php');

try {

    $productId = $_GET["product_id"];
    $num = $_GET["num"];
    if($num == null){
        $num = 5;
    }

//SELECT b.fullname AS username, b.customer_id AS userId, a.comments AS COMMENT FROM `mcc_customer_ophistory` a ,`mcc_customer` b WHERE a.customer_id = b.customer_id AND a.operation_type = 2 AND a.product_id = 28;
    //$sql = "SELECT b.fullname AS username, b.customer_id AS userId, a.comments AS COMMENT FROM ".getTable('customer_ophistory')." a,".getTable('customer')." b  WHERE a.customer_id = b.customer_id AND a.operation_type = 2 AND a.product_id = ".$productId;
    //$res = $db->getAll($sql);
    $res  = $commentsController->queryCommentsOfProd($productId,$num);

    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
