<?php
/**
 * Created by PhpStorm.
 * User: liutao
 * Date: 11/12/2015
 * Time: 12:36 AM
 */
require_once('../../index.php');
//require_once('../data/fakeData.php');
//$res=$fake_getProducDetail;
//$msg = new \leshare\json\message($res,0," success");
//$msg->writeJson();
try {
   // $sqldomain="select dvalue from ".getTable('dict')." where dkey='getProductURL'";
    $sqldomain="select dvalue from ".getTable('dict')." where dkey='domainURL'";
    $res = $db->getAll($sqldomain);
    $domain=$res[0]['dvalue'];
    $productId = $_GET["product_id"];
//    SELECT a.image AS topic,b.name AS title, a.price AS money ,a.market_price AS yuanjia FROM mcc_product a, mcc_product_description b
//WHERE a.product_id = b.product_id AND b.language_id = 1 AND a.product_id = 28
    $sqlProduct = "SELECT  CONCAT('".$domain."/',a.image) AS topic,b.name AS title, a.storeprice  AS money ,a.price AS yuanjia ,a.market_price AS scj, a.storeprice*a.credit_percent/100 AS minge  FROM " . getTable('product') . " a, " . getTable('product_description') . " b where a.product_id = b.product_id AND b.language_id = 1 AND a.product_id =" . $productId . " limit 1";
    $shareNumSql = "SELECT sharenum as shareNum from " . getTable('sharehistory_view') . " where product_id =" . $productId . " limit 1";
    $commentNummSql = "SELECT commentnum as commentNum from " . getTable('commenthistory_view') . " where product_id =" . $productId . " limit 1";
    $collectNumSql = "SELECT collect as collectNum from " . getTable('collecthistory_view') . " where product_id =" . $productId . " limit 1";
    // 产品信息
    $product = $db->getAll($sqlProduct);
    if (count($product) == 0) {
        throw new exception("产品不存在,id=" . $productId);
    }

    //分享次数
    $shareNum = $db->getAll($shareNumSql);

    //评价次数
    $commentNum = $db->getAll($commentNummSql);

    //收藏次数
    $collectNum = $db->getAll($collectNumSql);

//    echo(count($product));
//    foreach ($product[0] as $x => $x_value) {
//        echo "Key=" . $x . ", Value=" . $x_value;
//        echo "<br>";
//    }
//    echo "<br>";


    $res = $product[0];
    if (count($shareNum) != 0) {
        $res = array_merge($res, $shareNum[0]);
    } else {
        $res = array_merge($res, array("shareNum" => "0"));
    }
    if (count($commentNum) != 0) {
        $res = array_merge($res, $commentNum[0]);
    } else {
        $res = array_merge($res, array("commentNum" => "0"));
    }

    if (count($collectNum) != 0) {
        $res = array_merge($res, $collectNum[0]);
    } else {
        $res = array_merge($res, array("collectNum" => "0"));
    }
    $msg = new \leshare\json\message($res, 0, " success");

} catch (Exception $e) {
    $msg = new \leshare\json\message(null, 1, $e->getMessage());
} finally {
    $msg->writeJson();
}
