<?php
/**
 * Created by PhpStorm.
 * User: xiju
 * Date: 11/2/2015
 * Time: 10:05 PM
 */
$domain = "http://localhost/leshare";
//
$fake_login = array("userId" => "54321", "shareCode" => "12x23xe");
//index
$indexFocus01 = array("imgURL" => $domain . "/pic/pic01.jpg", "link" => "#");
$indexFocus02 = array("imgURL" => $domain . "/pic/pic01.jpg", "link" => "#");
$indexFocus03 = array("imgURL" => $domain . "/pic/pic01.jpg", "link" => "#");
$index_Focus = array(0 => $indexFocus01, 1 => $indexFocus02, 2 => $indexFocus03);

$index_promotionList01 = array("imgURL" => $domain . "/pic/pic02.jpg", "link" => "#");
$index_promotionList02 = array("imgURL" => $domain . "/pic/pic02.jpg", "link" => "#");
$index_promotionList03 = array("imgURL" => $domain . "/pic/pic02.jpg", "link" => "#");
$index_promotionList = array(0 => $index_promotionList01, 1 => $index_promotionList02, 2 => $index_promotionList03);

$index_getTopShares01 = array("imgURL" => $domain . "/pic/pic04.jpg", "link" => "#");
$index_getTopShares02 = array("imgURL" => $domain . "/pic/pic04.jpg", "link" => "#");
$index_getTopShares03 = array("imgURL" => $domain . "/pic/pic04.jpg", "link" => "#");
$index_getTopShares = array(0 => $index_getTopShares01, 1 => $index_getTopShares02, 2 => $index_getTopShares03);

$product01 = array("supplierId" => "123", "supplierName" => "name11", "shipingType" => "1", "productId" => "12345", "productName" => "product1", "orgPrice" => "122.2", "newPrice" => "120", "credit" => "12", "buyNum" => "12", "shareNum" => "10", "commentNum" => "12", "collectNum" => "100");
$product02 = array("supplierId" => "122", "supplierName" => "name11", "shipingType" => "1", "productId" => "12344", "productName" => "product2", "orgPrice" => "122.2", "newPrice" => "120", "credit" => "12", "buyNum" => "12", "shareNum" => "10", "commentNum" => "12", "collectNum" => "101");
$fake_getProductList = array(0 => $product01, 1 => $product02);

$fake_getProducDetail = array("supplierId" => "123","supplierName" => "sfewexxxxxxx", "productId" => "12345", "productName" => "product1", "orgPrice" => "122.2", "newPrice" => "120", "credit" => "12", "buyNum" => "10","shareNum" => "12","commentsNum" => "10","collectNum" => "10", "memo" => "fafasfadsfdsafasd");

$comment01 = array("userId" => "54321", "username" => "testuser1", "comment" => "comments1");
$comment02 = array("userId" => "54322", "username" => "testuser2", "comment" => "comments2");
$comment03 = array("userId" => "54323", "username" => "testuser3", "comment" => "comments3");
$fake_getComments = array(0 => $comment01, 1 => $comment02, 2 => $comment03);

$shares01 = array("userId" => "54321", "userName" => "testuser1", "imgURL" => $domain . "/pic/pic02.jpg", "sharedComments" => "comments1");
$shares02 = array("userId" => "54322", "userName" => "testuser2", "imgURL" => $domain . "/pic/pic02.jpg", "sharedComments" => "comments2");
$fake_getSharedList = array(0 => $shares01, 1 => $shares02);
$order011=array("orderId"=>"order001","orderCreateTime"=>"2015-11-03","orderPrice"=>"99","credits"=>"9");
$orderLs = array(0=>$order011);
$fake_myorders = array("orderLs" => $orderLs, "mycredits" => "30", "shareCode" => "12x23xe");

$products01 = array("imgUrl" => $domain . "/pic/pic03.jpg","productId" => "12345","title" => "product1",    "amount" => "1",    "price" => "40",    "credits" => "10", "refoundStatus" => "0");
$products02 = array("imgUrl" => $domain . "/pic/pic04.jpg","productId" => "12344","title" => "product2","amount" => "2","price" => "20", "credits" => "5","refoundStatus" => "0");
$order01 = array( "shipping_address" => "shanghai","user_name" => "haisi","zip_code" => "102133","shippingId" => "132423424224","act_pay" => "100","credits" => "20","productsInOrder" => array(0 => $product01, 1 => $product02));
$fake_orderDetail = array(0 => $order01);