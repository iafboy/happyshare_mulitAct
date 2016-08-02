<?php
/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2015/12/20
 * Time: 20:02
 */
require_once('../index.php');
$supplierId = '2';
$products = Array();
$products[0]['id'] = '28';
$products[0]['num'] = '1';
var_dump($products) ;
$receiveAddressId = '3';
$data=$registry->get("ProductController")->getFreight($supplierId, $products, $receiveAddressId);
$msg = new \leshare\json\message($data, 0, " success");
$msg->writeJson();