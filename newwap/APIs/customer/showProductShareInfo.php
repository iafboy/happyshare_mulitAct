<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/27
 * Time: 0:50
 */
require_once('../../index.php');
try {

    $customerId = $_GET["customerId"];
    $res = $sharingController->showProductShareInfo($customerId);

    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
