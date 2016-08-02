<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/1/19
 * Time: 22:44
 */
require_once('../../index.php');
try {

    $shareId = $_GET["shareId"];
    if($shareId==null){
        throw new exception("请指定要查询的分享记录");
    }

    $res = $sharingController->queryProductShareDetail($shareId);

    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message(null, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
