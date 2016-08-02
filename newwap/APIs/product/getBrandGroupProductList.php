<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/8
 * Time: 22:07
 */
//Deprecated
require_once('../../index.php');

try {
    $bg_id = $_GET["bg_id"];
    if($bg_id == null){
        throw new exception("入参品牌id必须" );
    }
    $res = $brandGroupController->getBrandGroupProductList($bg_id);

    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
