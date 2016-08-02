<?php
require_once('../../index.php');
require_once('./ShoppingCart.php');
try {
    $productIds = $_GET["productIds"];
    $productNum = $_GET["nums"];
    $customerId = $_GET['customerId'];
    $addressId = $_GET["addressId"];

    $shoppingCart = $customerController->shoppingProduct($productIds,$productNum,$customerId,$addressId);

    $res = $shoppingCart->showCartDetail($productController);

    //add by 20160215 增加验证码
//    $verifyCode = $commonController->generateRegCode($mobile);
//    $_SESSION['orderCode'] = $verifyCode;
//    $message = "您的校验码是".$verifyCode."";
//    $smsController->pushSMS2SoapClient($mobile,$message);
    //20160301 祖凯要求，jifenAmount改为用户积分能兑换的金额
    $customerCredit = $customerController->queryCustomerCredit($customerId);
    $res = array_merge(array(
        "hasOfflineProduct" => $shoppingCart->getHasOfflineProduct(),
        "totalAmount" => $shoppingCart->getTotalAmount(),
        "totalExpressAmount" => $shoppingCart->getTotalExpressAmount(),
        "jifenAmount" =>$customerCredit,
        "jifenMoney" =>$customerCredit/CREDIT_EXCHANGE_PERCENT
    ),
        array("suppliers" => $res));


    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
