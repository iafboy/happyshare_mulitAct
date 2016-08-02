<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/13
 * Time: 13:28
 */
require_once('../../index.php');
require_once('../../core/phpqrcode/phpqrcode.php');

try {

    $customerId = $_GET["customerId"];
    $res = $sharingController->showRegShareInfo($customerId);

    $imgDir = DIR_IMAGE . 'customer/qrcode/';
    $qrCode = $customerController->getCustomerQR($customerId, $imgDir, $res['shareCode']);
    $registerUrl = $customerController->getRegisterUrl($customerId, $res['shareCode']);
    $unappliedCredit = $creditController->getUnappliedCredit($customerId);
//    $res = array_merge($res, array("shareUrl" => $registerUrl));
//    $res = array_merge($res, array("qrCode" => $qrCode));
    $res = array_merge($res[0], array("shareUrl" => $registerUrl));
    $res = array_merge($res, array("qrCode" => $qrCode));
    $res = array_merge($res, array("unappliedCredit" => $unappliedCredit));
    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
