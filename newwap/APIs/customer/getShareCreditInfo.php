<?php
/**
 *
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/3/10
 * Time: 21:48
 */
require_once('../../index.php');

try {
   $customerId = $_GET['customerId'];
    $unappliedCredit = $creditController->getUnappliedCredit($customerId);
    $unappliedCreditValue=round($unappliedCredit/10,0);
	//TODO
    $creditRule = $creditController->getCreditThresholdLastMonth();
    $creditNeed = $creditController->getCreditThresholdThisMonth();

    if($creditRule <$creditRule ){
        $resMsg = "亲：您". date("m")."
        月获得的分享积分为".$unappliedCredit."
        （价值".$unappliedCredit."
        元），但是您". date("m")."
        月的“消费积分+活动奖励积分”不足".$creditRule."
        ，因此该分享积分不能入账。假如您本月的“消费积分+活动奖励积分”达到".$creditNeed."
        ，该".$unappliedCredit."
        分享积分则可重新入账；否则".$unappliedCredit."
        积分将作废。积分可用于购物现金抵用或体现。";
    }else{
        $resMsg="亲：您". date("m")."
        月获得的分享积分为".$unappliedCredit."
        （价值".$unappliedCreditValue."
        元）";
    }
    $res = array("mgs"=>$resMsg);
    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message(null, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}

