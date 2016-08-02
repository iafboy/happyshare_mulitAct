<?php
//推广链接专用
require_once('../index.php');
//注册推广链接http://cybershare.php/mfasdf12
//产品推广链接http://cybershare.php/mfasdf12&productId=134243
try {
    $shareCode =   $_GET["shareCode"];
    $productId =  $_GET["productId"];
    $type =  $_GET["type"];
    if($shareCode == null){
        throw new exception("分享码不能为空");
    }

//    newShareRecord($type, $customerId, $productId, $referenceCode)
//    type:   0表示产品购买分享，1表示注册分享
//    customerId:  点击链接的用户ID，如果是注册分享，这个值为null
//    productId:  产品购买分享中的产品ID，如果是注册分享，这个值为null
//    referenceCode: 推荐码


    if($type == 0){
        $customerId = $_SESSION['customerId'];
        if($productId == null){
            throw new exception("分享产品不能为空");
        }
    }

    $id = $sharingController->newShareRecord($type,$customerId,$productId,$shareCode);
    $res = array("shareId"=>$id);
    //设置session,后期使用
    $_SESSION['shareId'] = $id;
    $_SESSION['shareCode'] = $shareCode;
    $_SESSION['shareProductId'] = $productId;

    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($data, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
