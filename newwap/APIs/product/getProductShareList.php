<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 2016/1/18
 * Time: 16:09
 */
require_once('../../index.php');
//require_once('../data/fakeData.php');
//$res=$fake_getProducDetail;
//$msg = new \leshare\json\message($res,0," success");
//$msg->writeJson();
try {

    $productId = $_GET["productId"];
    $limit = $_GET["limit"];
    if ($limit == null) {
        $limit = 10;
    }
    if ($productId == null) {
        throw new exception("产品id不能为空");
    }
    $res = $sharingController->queryProdutShareList($productId,$limit);

    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message(null, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
