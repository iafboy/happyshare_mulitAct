<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/1/6
 * Time: 23:43
 */
require_once('../../index.php');
try {

    function printf_info($log,$data)
    {
        $log->info("=====================start to print request str ===================");
        foreach($data as $key=>$value){
            $log->info($key." ====>  "."$value ");
        }
        $log->info("=====================end to print request str ===================");
    }
    printf_info($log,$_POST);


//    $orderNo = $_GET["orderNo"];
//    $transactionId = $_GET["queryId"];
//
//    $isSuccess  =  $_GET["isSuccess"];
//    //在加一个isSuccess = 0/1, respMsg='错误信息'
//    $respMsg = $_GET['respMsg'];
    // 实际上是 orderPaymentNo
    $orderNo = $_POST["orderNo"];
    $transactionId = $_POST["queryId"];

    $isSuccess  =  $_POST["isSuccess"];
    //在加一个isSuccess = 0/1, respMsg='错误信息'
    $respMsg = $_POST['respMsg'];
    $res = $leSharePayment->confirmPayOrder($orderNo,$isSuccess,$respMsg,$transactionId);
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}


