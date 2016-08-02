<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/1/24
 * Time: 13:44
 */
require_once('../../index.php');
try {

    $limit = $_GET["limit"];
    $res = $sharingController->getShareList($limit);
    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message(null, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
