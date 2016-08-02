<?php
/**
 * Created by PhpStorm.
 * User: xiju
 * Date: 11/4/2015
 * Time: 12:48 AM
 */
require_once('../../index.php');
try {

//    $productId = $_GET["productId"];
    $customerId = $_GET["customerId"];

    $res = $commentsController->queryMyCommentOfProd( $customerId);

    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
