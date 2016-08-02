<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/1/16
 * Time: 19:45
 */
require_once('../../index.php');
try {

    $customerId = $_GET["customerId"];
    $date = $_GET["date"];
    if ($customerId == null) {
        throw new exception("用户id不能为空");
    }
    $res = $creditController->getCreditInDiffLevel($customerId,$date);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}