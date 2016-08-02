<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/1/16
 * Time: 15:19
 */
require_once('../../index.php');
try {
    $bg_id = $_GET["bg_id"];
    if($bg_id == null){
        throw new exception("入参品牌id必须" );
    }
    $res = $brandGroupController->getBrandSupplier($bg_id);

    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
