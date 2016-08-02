<?php
/**
 * Created by PhpStorm.
 * User: xiju
 * Date: 11/2/2015
 * Time: 10:02 PM
 */
require_once('../index.php');
//require_once('../data/fakeData.php');
$postStr = file_get_contents("php://input");;
//$userId=json_decode($postStr, true)['userId'];
try {
    //mcc_picwall_setting表里面有关于热门商品的设置`
    //这个pws_id对应的是3
    //然后在mcc_picwallbanner_image表中找到bs_id对应的图片列表就是了
    $pws_id  = $_GET["pws_id"];
    if($pws_id == null){
        $pws_id = 1;
    }

    $sqldomain="select dvalue from ".getTable('dict')." where dkey='domainURL'";
    $res = $db->getAll($sqldomain);
    $domain=$res[0]['dvalue'];
    $sql= "select CONCAT('".$domain."/image/',a.image) as src,a.link as link  from " . getTable("picwallbanner_image") ." a, ".getTable("picwall_setting")." b where a.enable_status=1 and a.bs_id=b.pws_id and b.pic_type=0 and a.bs_id=".$pws_id.
    " order by a.sort_order";
    $res = $db->getAll($sql);

    $msg = new \leshare\json\message($res, 0, " success");
} catch (Exception $e) {
    $msg = new \leshare\json\message($res, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}