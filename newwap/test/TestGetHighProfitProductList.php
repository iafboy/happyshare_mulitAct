<?php
/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2016/1/17
 * Time: 0:40
 */
require_once('../index.php');
$type='健康馆';
$data=$registry->get("ProductController")->getHighProfitProductList($type);
$msg = new \leshare\json\message($data, 0, " success");
$msg->writeJson();