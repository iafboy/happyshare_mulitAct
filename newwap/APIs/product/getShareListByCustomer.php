<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/1/24
 * Time: 13:44
 */
require_once('../../index.php');
try {
    $customerLimit = $_GET["num1"];
    $limit = $_GET["num2"];
    $res = $sharingController->queryCustomerShareList($customerLimit,$limit);
    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message(null, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
