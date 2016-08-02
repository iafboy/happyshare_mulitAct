<?php
require_once('../../index.php');
try {
	
    $productIds = $_POST["productIds"];
    $productNums = $_POST["productNums"];
    $orderId  = $_POST["orderId"];
    $supplierId =  $_POST["supplierId"];
    $receiveAddressId =  $_POST["receiveAddressId"];
	
    $idArray = explode(",", $productIds);

    $numArray = explode(",",$productNums);

    $products = [];
    for($i = 0;$i<sizeof($idArray); $i ++){
        $products[] = ['num'=>$numArray[$i],'id'=>$idArray[$i]];
    }
    $res = $productController->getFreight($supplierId, $products, $receiveAddressId, $orderId);
    $msg = new \leshare\json\message($res, 0);
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally{
	$msg->writeJson();
}


