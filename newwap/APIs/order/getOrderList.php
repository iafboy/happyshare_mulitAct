<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/11/14
 * Time: 18:00
 */
require_once('../../index.php');
//$order011=array("orderId"=>"order001","orderCreateTime"=>"2015-11-03","orderPrice"=>"99","credits"=>"9");
//$orderLs = array(0=>$order011);
//$fake_myorders = array("orderLs" => $orderLs, "mycredits" => "30", "shareCode" => "12x23xe");
try {
    $customer_id = $_GET["customerId"];
    $status_id = $_GET["statusId"];
    if ($customer_id != null) {
        $res = $leSharePayment->showOrderListV3($customer_id,$status_id);
        $msg = new \leshare\json\message($res, 0, " success");

    } else {
        throw new exception("customerId不能为空");
    }
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}


