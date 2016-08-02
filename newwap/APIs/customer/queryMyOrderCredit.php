<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/1/18
 * Time: 16:28
 */
require_once('../../index.php');
try {

    $customerId = $_GET["customerId"];
    $type = $_GET["type"];

    if ($customerId == null) {
        throw new exception("用户id不能为空");
    }


    $res = $creditController-> queryCreditHistory($customerId, 0);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}