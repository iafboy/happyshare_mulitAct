<?php
/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2015/12/20
 * Time: 20:02
 */
require_once('../index.php');
$customerId = '16';
$date = '201602';
$data=$registry->get("CreditController")->getCreditInDiffLevel($customerId,$date);
$msg = new \leshare\json\message($data, 0, " success");
$msg->writeJson();