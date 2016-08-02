<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/25
 * Time: 22:09
 */
require_once('../../index.php');
require_once('./cartCache.php');
require_once('./ShoppingCart.php');
try {

    $productArray = $shoppingCart->getProductArray();
    $addressId = $customerController->queryCustomerDefalutAddressId($customerId);
    if($addressId != null){
        $address = $customerController->queryCustomerAddress($addressId);
        $addStr = $address[0]['provinceName'];
    }else{
        $addStr = "";
    }

    $ghsArray = array();
    $productDetailArray = array();
    foreach ($productArray as $productInfo) {
        if(is_null($productInfo['productId'])||!isset($productInfo['productId'])||($productInfo['productId']=='null')){
            $log->debug('found a null productid');
            continue;
        }
        $productDetail = $productController->getProductDetail($productInfo);
        $productCredit = $productController->getProductCreditByDate($productInfo['productId'], date("Ymd"));
        $productDetail = array_merge($productDetail, array("jifen" => round($productCredit)));

        $ghsId = $productDetail[0]['supplierId'];
        $resSupplierExpress = $productController->getSupplierExpress($ghsId);
//        $tips1 = "已包邮 ".$addStr."（供货商包邮包税标准：满".$resSupplierExpress[0]['free_shipping']."元）";
        $tips1 = "包邮标准：满".$resSupplierExpress[0]['free_shipping']."元";
        $tips2 = "";
        $address = $addStr;
        $isChk = "";
        $ghsInfo = $shoppingCart->newGhs($ghsId, $productDetail[0]['supplierName'], $tips1, $tips2, $address, $isChk);
//        array_push($ghsArray, array("ghsId" => $productDetail[0]['supplierId'],
//            "ghsName" => $productDetail[0]['supplierName'],"tips1"=>"已包邮（供货商包邮包税标准：满200元）",
//            "tips2"=>"","address"=>"",
//            "isChk" => ""));
        //array_push($ghsArray, $ghsInfo);
        $shoppingCart->addGhs($ghsInfo);

        $src = $productDetail[0]['image'];
        $title = $productDetail[0]['productName'];
        $money = $productDetail[0]['newPrice'];
        $jifen = $productDetail[0]['jifen'];
        $num = $productInfo['amt'];
        $baoyou = "直邮 现货包邮";
        $productId = $productInfo['productId'];
        $isChk = $productInfo['isChk'];
        $productDetail2Array =  $shoppingCart->newProductDetail($ghsId, $src, $title, $money, $productCredit, $num, $baoyou, $productId, $isChk);
//        array_push($productDetailArray, array("ghsId" => $productDetail[0]['supplierId'], "src" => $productDetail[0]['image'],
//            "title" => $productDetail[0]['productName'], "money" => $productDetail[0]['newPrice'],
//            "jifen" => $productDetail[0]['jifen'], "num" => $productInfo['amt'],
//            "id" => $productInfo['productId'],
//            "baoyou" => "直邮 现货包邮",
//            "buylink" => "productId=" . $productInfo['productId'], "isChk" => ""
//        ));
        array_push($productDetailArray, $productDetail2Array);
        //print_r($productDetailArray);
    }

    //$shoppingCart->setGhsArray($ghsArray);
    $shoppingCart->setProductDetailArray($productDetailArray);

    //设置到cache
    $cache->set("shoppingcart" . $shoppingCart->getCustomerId()
        , $shoppingCart);

    $res = $shoppingCart->showCartDetail($productController);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}

