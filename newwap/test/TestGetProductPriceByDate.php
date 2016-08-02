<?php
/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2015/12/20
 * Time: 20:02
 */
require_once('../index.php');
$productId = '163';
$date = '20160306';
$data=$registry->get("ProductController")->getProductPriceByDate($productId, $date);
$msg = new \leshare\json\message($data, 0, " success");
$msg->writeJson();