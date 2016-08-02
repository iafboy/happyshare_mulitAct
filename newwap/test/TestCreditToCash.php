<?php
/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2015/12/20
 * Time: 20:02
 */
require_once('../index.php');
$customerId = '16';
$amount = '150';
$bankId = '1';
$cardId = '6222020200054098097';
$cardHolder = '测试员';
$cardHolderId = '360731198612090011';
$receiverEmail = 'test@163.com';
$data=$registry->get("CreditController")->creditToCash($customerId, $amount,$bankId, $cardId, $cardHolder, $cardHolderId, $receiverEmail);
$msg = new \leshare\json\message($data, 0, " success");
$msg->writeJson();