<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2015/12/8
 * Time: 0:05
 */
require_once('../../index.php');

try {
    $num = $_GET['num'];
    if(!is_valid($num)){
        $num = 10;
    }
    $res = $brandGroupController->getBrandGroupInfo($num);

    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
