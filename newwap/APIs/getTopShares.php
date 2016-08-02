<?php
/**
 * Created by PhpStorm.
 * User: xiju
 * Date: 11/2/2015
 * Time: 11:27 PM
 */
require_once('../index.php');
try {
    //查询视图mcc_sharehistory_view，倒序查出列表
    $num = $_GET["limit"];

    //根据视图中的productId,再分别查出对应的产品信息，然后组合成数组返回，(此处为了效率原因考虑，如果是视图+原表操作估计数据库效率太差,所以分开做)
    // hotshare/getHotShare.php
    $res  =  $sharingController->getTopShare($num);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($data, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}