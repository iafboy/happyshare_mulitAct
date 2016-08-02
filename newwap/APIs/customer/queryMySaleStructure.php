<?php
require_once('../../index.php');
try {

    $customerId = $_GET["customerId"];
    $type = $_GET["type"];

    if ($customerId == null) {
        throw new exception("用户id不能为空");
    }
    if ($type == "currentMonth") {
        $month = date("Ym");
    } elseif ($type == "lastMonth") {
//        $y = date("Y");
//        $m = date("m");
//        if (($m - 1) == "00") {
//            $month = ($y - 1) . "12";
//        } else {
            $month = date("Ym") - 1;
//        }
    }

    $resArray = $creditController->getSaleStructure($customerId, $month);
    $num = count($resArray);
    $res = array("num" => $num);
    $res["list"] = $resArray;
//    $res = array_merge($res, $resArray);
//    if(count($res) == 0){
//        throw new exception("暂无下级客户");
//    }

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}