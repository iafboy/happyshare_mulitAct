<?php
require_once('../../index.php');
try {

    $key = $_GET["key"];
    if ($key == null) {
        throw new exception("搜索关键字不能为空");
    }
    $res = $productController->search($key);
    if(count($res) == 0){
        throw new exception("无匹配结果");
    }
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}