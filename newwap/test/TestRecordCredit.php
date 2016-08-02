<?php
/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2015/12/20
 * Time: 20:02
 */
require_once('../index.php');
$type=0;
$ref_id=132;
$credit=22;
$customerId=23;
$productId=0;
$comment=null;
$data=$registry->get("CreditController")->recordCredit($type,$ref_id,$credit,$customerId,$productId,$comment);
$msg = new \leshare\json\message($data, 0, " success");
$msg->writeJson();