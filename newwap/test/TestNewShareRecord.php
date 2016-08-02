<?php
/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2015/12/20
 * Time: 20:02
 */
require_once('../index.php');
$type=1;
$customerId=16;
$productId=100  ;
$referenceCode="ildvyqtnim75o57j";
$data=$registry->get("SharingController")->newShareRecord($type, $customerId, $productId, $referenceCode);
$msg = new \leshare\json\message($data, 0, " success");
$msg->writeJson();