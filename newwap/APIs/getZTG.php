<?php
/**
 * Created by PhpStorm.
 * User: xiju
 * Date: 11/8/2015
 * Time: 2:52 PM
 */
require_once('../index.php');
//require_once('../data/fakeData.php');
$postStr = file_get_contents("php://input");;
//$userId=json_decode($postStr, true)['userId'];
try {
//    $sql = "select src,link,text from " . getTable('ztg_view');
    $sql ="SELECT a.dkey AS buylink, CONCAT('ztg=',a.dkey ) AS linktxt ,  a.dvalue AS TEXT, b.dvalue AS src FROM  ".getTable('dict')." a ,".getTable('dict')." b WHERE a.dkey = b.dkey AND a.group_id = 0 AND b.group_id = 1 ";
    $res = $db->getAll($sql);
    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}