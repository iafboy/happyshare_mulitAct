<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/11/16
 * Time: 22:22
 */
require_once('../../index.php');
try {

    $orderNo = $_POST['orderNo'];
    $productId = $_POST['productId'];
    $mode = $_POST['mode'];
    $reason = $_POST['reason'];
    $phone = $_POST['phone'];
    $base64_str = $_POST['imageBase64'];
    $returnnum=$_POST['num'];
    $imgurl = '';
    if(is_valid($base64_str)){
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_str, $result)) {
            $type = $result[2];
            $imgurl = "returngoods/".time().".{$type}";
            $new_file =DIR_IMAGE .$imgurl;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_str)))) {
            }
        }
    }
    $res = $leSharePayment->applyRefond($orderNo,$productId,$reason,$mode,$phone,$imgurl);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
