<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/1/17
 * Time: 19:14
 */
require_once('../../index.php');
try {
    $ztg = $_GET["ztg"];
    $limit =  $_GET["limit"];
    $page =  $_GET["page"];
    if($limit == null){
        $limit = 6;
    }
    if($page == null){
        $page = 1;
    }
    if($ztg == null){
        throw new exception("主题馆不能为空" );
    }
    $res = $productController->getHighProfitProductList($ztg,$limit,$page);

    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
