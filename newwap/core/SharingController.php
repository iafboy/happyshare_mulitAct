<?php

class SharingController
{
    private $db;
    private $log;

    private $registry;

    public function __construct($registry)
    {
        $this->db = $registry->get('db');
        $this->log = $registry->get('log');
        $this->registry = $registry;
    }

    public function queryProdutShareList($productId, $limit)
    {
        //TODO
        $domainUrl = $this->getDomain();
        $sql = "SELECT a.coh_id as shareId, b.customer_id AS customerId, b.fullname AS customerName,CONCAT('" . $domainUrl . "/image/', e.imgurl) AS avatar,a.comments AS comments,
a.createTime AS createTime,
   CASE ISNULL(a.sharePic1) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',a.sharePic1) WHEN 1 THEN '' END AS sharePic1,
CASE ISNULL(a.sharePic2) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',a.sharePic1) WHEN 1 THEN '' END AS sharePic2,
CASE ISNULL(a.sharePic3) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',a.sharePic1) WHEN 1 THEN '' END AS sharePic3,
CASE ISNULL(a.sharePic4) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',a.sharePic1) WHEN 1 THEN '' END AS sharePic4,
CASE ISNULL(a.sharePic5) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',a.sharePic1) WHEN 1 THEN '' END AS sharePic5
 FROM " . getTable('customer_ophistory') . " a, " . getTable('customer') . " b," . getTable('customer_avatar') . "  c WHERE b.avatar_id= c.avatar_id and a.customer_id = b.customer_id AND a.operation_type =1  and a.product_id =" . $productId . " limit " . $limit;
        $res = $this->db->getAll($sql);
        return $res;
    }

    public function queryProductShareDetail($shareId)
    {
        $domainUrl = $this->getDomain();
        $sql = "SELECT a.coh_id as shareId, b.customer_id AS customerId, b.fullname AS customerName,CONCAT('" . $domainUrl . "/image/', e.imgurl) AS avatar,a.comments AS comments,c.product_id as productId,d.name as productName,
a.createTime AS createTime,
  CASE ISNULL(a.sharePic1) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',a.sharePic1) WHEN 1 THEN '' END AS sharePic1,
CASE ISNULL(a.sharePic2) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',a.sharePic1) WHEN 1 THEN '' END AS sharePic2,
CASE ISNULL(a.sharePic3) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',a.sharePic1) WHEN 1 THEN '' END AS sharePic3,
CASE ISNULL(a.sharePic4) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',a.sharePic1) WHEN 1 THEN '' END AS sharePic4,
CASE ISNULL(a.sharePic5) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',a.sharePic1) WHEN 1 THEN '' END AS sharePic5
 FROM " . getTable('customer_ophistory') . " a, " . getTable('customer') . " b," . getTable('product') . "c," . getTable('product_description') . " d," . getTable('customer_avatar') . " e
 WHERE  b.avatar_id= e.avatar_id and c.product_id = d.product_id and d.language_id=1 and a.customer_id = b.customer_id AND a.operation_type =1  and a.coh_id =" . $shareId . " limit 1";
        $res = $this->db->getAll($sql);
        return $res;
    }

    //返回发展人数数量
    public function getSharedPeopleAmount($referee)
    {
        $counter = 1;
        $stop = false;
        while (!$stop) {
            //1.根据$referee，查询mcc_customer表对应的shareCode值

            //2.根据查询到的shareCode值，查询mcc_customer表是否有相应的referee值与其对应

            //3.1 如果有值则$counter+1

            //3.2 如果没有值则$stop=true退出循环

        }

        return $this->getRegNum($referee);
    }

    //注册推广链接相关信息展示
    public function showRegShareInfo($customerId)
    {
        //根据customerId查询mcc_customer表，查询shareCode字段
        //根据shareCode统计mcc_customer表中refereeCode字段的值与shareCode一致的数据量，从而得到链接转化注册人数，点击数量，积分收益

        $shareCode = $this->getCustomerShareCode($customerId);
        if ($shareCode != null) {
            $regNum = $this->getRegNum($shareCode);
            $domain = $this->getDomain();
            $sql = "select '" . $shareCode . "' as shareCode, a.telephone as phone, a.fullname as userName,a.clickShareCode as clickNum, a.credit as credit, a.regCredit as regCredit , " . $regNum . " as regNum , CONCAT('" . $domain . "/image/', b.imgurl) as img
            from " . getTable('customer') . " a," . getTable('customer_avatar') . " b where a.avatar_id=b.avatar_id and  a.customer_id=" . $customerId;
            $res = $this->db->getAll($sql);
//            $res = array_merge($res, $regNum);
            return $res;

        } else {
            throw new exception("用户分享码为空");
        }
    }

    //查询分享产品转化注册的数量
    public function showProductShareInfo($customerId)
    {
        $domainUrl = $this->getDomain();
        $sqlProduct = "SELECT c.coh_id as coh_id,a.product_id as productId, CONCAT('" . $domainUrl . "/image/',a.img_3) AS topic,b.name AS title,'0' as clickNum,'0' as regNum,  '0' as regCredit,
        d.shareCode as shareCode
        FROM " .
            getTable('product') . " a, " . getTable('product_description') . " b," . getTable('customer_ophistory') .
            " c,".getTable('customer')." d where d.customer_id = c.customer_id and a.product_id = c.product_id and a.product_id = b.product_id AND b.language_id = 1 AND c.operation_type = 1 and c.status=0 and c.customer_id =" . $customerId . " limit 10";
        $res = $this->db->getAll($sqlProduct);
        if(count($res)>0){
            for($index =0;$index<count($res);$index++){
                $coh_id = $res[$index]['coh_id'];
                $sql = "select totalNum,sucNum,credit from ".getTable('share_statistic')." where cohId = ".$coh_id;
                $resProductShare =  $this->db->getAll($sql);
                if(count($resProductShare)>0){
                    $res[$index]['clickNum'] = $resProductShare[0]['totalNum'] ;
                    $res[$index]['regNum'] = $resProductShare[0]['sucNum'] ;
                    $res[$index]['regCredit'] =$resProductShare[0]['credit'] ;
                }
            }
        }

        return $res;

    }


    private function getRegNum($shareCode)
    {
        $sql = "SELECT COUNT(1) AS num FROM  " . getTable('customer') . " WHERE referee='b38bd5c1-85cc-11e5-a541-5494c9df8a0e'";
        $res = $this->db->getAll($sql);
        $num = $res[0]['num'];
        return $num;
    }

    private function  getCustomerShareCode($customerId)
    {
        $sql = "select shareCode from " . getTable('customer') . " where customer_id=" . $customerId;
        $res = $this->db->getAll($sql);
        $shareCode = $res[0]['shareCode'];
        return $shareCode;
    }

    //发布使用报告
    public function publishTrialReport($title, $content, $picPaths, $productId, $customerId)
    {
        //检查数组picPaths,中数量，最多5个
        //将数据写入mcc_customer_ophistory，其中operation_type=4
        $sql = "INSERT INTO " . getTable('customer_ophistory') . " SET product_id = '" . (int)$productId . "', operation_type = 4, customer_id='" . (int)$customerId . "', comments = '" . $this->db->escape($content) . "', createTime =  NOW()";
        if (count($picPaths) > 0) {
            $index = 1;
            foreach ($picPaths as $pic) {
                $sql = $sql . ",sharePic" . $index . " = '" . $this->db->escape($pic) . "'";
                $index++;
                if ($index > 5) {
                    break;
                }
            }
        }
        //echo $sql;
        $this->db->query($sql);

    }

    public function pulishShare($productId, $customerId)
    {
        //删除历史分享
        $sql = "delete from " . getTable('customer_ophistory') . " where  operation_type = 1 and  product_id=" . $productId . " and customer_id =" . $customerId;
        $this->db->query($sql);


        $sql = 'select count(1) as count from ' . getTable('product_share') . ' where audit = 1 and product_id = ' . $productId;
        $count = $this->db->getAll($sql);
        if ($count[0]['count'] > 0) {
            $sql = 'select title,memo,imgurl1,imgurl2,imgurl3,imgurl4,imgurl5 from ' . getTable('product_share') . ' where audit = 1 and product_id = ' . $productId;
        } else {
            $sql = 'select b.name as title,b.name as memo,image as imgurl1 from ' . getTable('product') . ' a , ' . getTable('product_description') . ' b where a.product_id = b.product_id '
                . ' and a.product_id = ' . $productId . ' and b.language_id = 1 ';
        }
        $res = $this->db->getAll($sql);

        $content = $res[0]['memo'];
        $title = $res[0]['title'];
        $sql = "INSERT INTO " . getTable('customer_ophistory') . " SET product_id = '" . (int)$productId . "', operation_type = 1, customer_id='" . (int)$customerId . "', comments = '" . $this->db->escape($content) . "', createTime =  NOW(),title='" . $this->db->escape($title) . "'";

        for ($i = 1; $i < 6; $i++) {
            $index = "imgurl" . $i;
            $img = $res[0][$index];
            if (isset($img)) {
                $srcImg = DIR_IMAGE . $img;
                $imgDir = dirname($srcImg);
                $imgFileName = str_ireplace($imgDir."/",'',$srcImg);
                $targetImg = DIR_IMAGE . "customer/sharecases/" . $imgFileName;

                FileUtil:: copyFile($srcImg, $targetImg, true);
                $sql = $sql . ",sharePic" . $i . " = '" . $this->db->escape("customer/" . $img) . "'";
            }
        }

        $this->db->query($sql);
    }


    //发布分享
    public function publishShareReport($title, $content, $picPaths, $productId, $customerId)
    {

        //TODO
        //检查数组picPaths,中数量，最多5个
        //将数据写入mcc_customer_ophistory，其中operation_type=1
        $sql = "INSERT INTO " . getTable('customer_ophistory') . " SET product_id = '" . (int)$productId . "', operation_type = 1, customer_id='" . (int)$customerId . "', comments = '" . $this->db->escape($content) . "', createTime =  NOW()";
        if (count($picPaths) > 0) {
            $index = 1;
            foreach ($picPaths as $pic) {
                $targetImg = DIR_IMAGE . "/products/sharecases/111.jpg";


                $sql = $sql . ",sharePic" . $index . " = '" . $this->db->escape($pic) . "'";
                $index++;
                if ($index > 5) {
                    break;
                }
            }
        }
        //echo $sql;
        $this->db->query($sql);

    }

    public function queryCustomerShareList($customerLimit, $limit)
    {
        if ($customerLimit == null) {
            $customerLimit = 5;
        }
        if ($limit == null) {
            $limit = 5;
        }
        $resArray = array();

        $customerSql = "SELECT DISTINCT customer_id   as customerId FROM " . getTable('customer_ophistory') . " c WHERE    c.operation_type = 1
          AND c.status = 0  ORDER BY c.createTime DESC LIMIT " . $customerLimit;
        $customerArray = $this->db->getAll($customerSql);

        $domainUrl = $this->getDomain();

        foreach ($customerArray as $idArray) {

            $sqlProduct = "SELECT c.coh_id as shareId,a.product_id as product_id, CONCAT('" . $domainUrl . "/image/',a.image) AS topic,b.name AS title, IFNULL(c.createTime,NOW()) as sharetime,d.fullname as username,
          CASE ISNULL(c.sharePic1) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',c.sharePic1) WHEN 1 THEN '' END AS sharePic1,
            CASE ISNULL(c.sharePic2) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',c.sharePic1) WHEN 1 THEN '' END AS sharePic2,
            CASE ISNULL(c.sharePic3) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',c.sharePic1) WHEN 1 THEN '' END AS sharePic3,
            CASE ISNULL(c.sharePic4) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',c.sharePic1) WHEN 1 THEN '' END AS sharePic4,
            CASE ISNULL(c.sharePic5) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',c.sharePic1) WHEN 1 THEN '' END AS sharePic5
        FROM " . getTable('product') . " a, " . getTable('product_description') . " b," . getTable('customer_ophistory') . " c ," . getTable('customer') . "d
         where d.customer_id = c.customer_id and  a.product_id = c.product_id and a.product_id = b.product_id AND b.language_id = 1 AND c.operation_type = 1
         and c.status=0 and c.customer_id =" . $idArray['customerId'] . " order by c.createTime desc   limit " . $limit;

            $shareArray = $this->db->getAll($sqlProduct);
            array_push($resArray, array_merge($idArray, array("shareList" => $shareArray)));
        }


        return $resArray;
    }


    public function getShareList($num)
    {
        if ($num == null) {
            $num = 3;
        }
        $domainUrl = $this->getDomain();
        $sqlProduct = "SELECT c.coh_id as shareId,a.product_id as product_id, CONCAT('" . $domainUrl . "/image/',a.image) AS topic,b.name AS title, IFNULL(c.createTime,NOW()) as sharetime,d.fullname as username,
          CASE ISNULL(c.sharePic1) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',c.sharePic1) WHEN 1 THEN '' END AS sharePic1,
            CASE ISNULL(c.sharePic2) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',c.sharePic1) WHEN 1 THEN '' END AS sharePic2,
            CASE ISNULL(c.sharePic3) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',c.sharePic1) WHEN 1 THEN '' END AS sharePic3,
            CASE ISNULL(c.sharePic4) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',c.sharePic1) WHEN 1 THEN '' END AS sharePic4,
            CASE ISNULL(c.sharePic5) WHEN 0 THEN CONCAT('" . $domainUrl . "/image/',c.sharePic1) WHEN 1 THEN '' END AS sharePic5
        FROM " . getTable('product') . " a, " . getTable('product_description') . " b," . getTable('customer_ophistory') . " c ," . getTable('customer') . "d
         where d.customer_id = c.customer_id and  a.product_id = c.product_id and a.product_id = b.product_id AND b.language_id = 1 AND c.operation_type = 1
         and c.status=0 order by c.createTime desc   limit " . $num;
        $res = $this->db->getAll($sqlProduct);
        return $res;
    }

    //获取最新的分享
    public function  getTopShare($num)
    {
        if ($num == null) {
            $num = 3;
        }
        $domainUrl = $this->getDomain();
        //$sqlProduct = "SELECT c.coh_id as shareId,a.product_id as product_id, CONCAT('" . $domainUrl . "/image/',IFNULL(a.img_2,'')) AS topic,b.name AS title, IFNULL(c.createTime,NOW()) as sharetime,CONCAT(CONCAT(substring(d.fullname,1,3),'****'),substring(d.fullname,-4)) as username   FROM " . getTable('product') . " a, " . getTable('product_description') . " b," . getTable('customer_ophistory') . " c ," . getTable('customer') . "d where d.customer_id = c.customer_id and  a.product_id = c.product_id and a.product_id = b.product_id AND b.language_id = 1 AND c.operation_type = 1 and c.status=0 order by c.createTime desc limit " . $num;
         $sqlProduct = "SELECT '' as shareId,a.product_id as product_id, CONCAT('" . $domainUrl . "/image/',IFNULL(a.img_2,'')) AS topic,b.name AS title, IFNULL(null,NOW()) as sharetime,'' as username  FROM " . getTable('product') . " a, " . getTable('product_description') . " b," . getTable('sharehistory_view') . " c where   a.product_id = c.product_id and a.product_id = b.product_id AND b.language_id = 1 order by c.collectnum desc limit " . $num;
		//echo $sqlProduct;
		$res = $this->db->getAll($sqlProduct);
        foreach($res as &$product){
            $product = array_merge($product,getActInfo($product['product_id']));
        }
        return $res;
    }

    //查询分享产品的用户数(包含风格化显示)
    public function showShareNums($productId)
    {
        //根据productId查询mcc_product表中start_sharenum字段以及incr_sharenum字段订的值
        $sql = "select start_sharenum ,incr_sharenum from " . getTable('product') . " where product_id =" . $productId . " limit 1";
        $res = $this->db->getAll($sql);
        $start_sharenum = $res[0]['start_sharenum'];
        $incr_sharenum = $res[0]['incr_sharenum'];

        //统计mcc_customer_ophistory，对应productid,operation_type=1的数量
        $shareNumSql = "SELECT collectnum as shareNum from " . getTable('sharehistory_view') . " where product_id =" . $productId . " limit 1";
        $res = $this->db->getAll($shareNumSql);

        if (count($res) == 0) {
            $shareNum = 0;
        } else {
            $shareNum = $res[0]['shareNum'];
        }

        //检查值是否大于start_sharenum，如果大于则 round(查询的值*（100+incr_sharenum)/100）否则按照正常返回
        if ($shareNum > $start_sharenum) {
//            return $shareNum;
            return round($shareNum * (100 + $incr_sharenum) / 100);
        } else {
            return $shareNum;
        }
    }

    public function getMyShareList($customerId)
    {
        //组合查询mcc_customer_ophistory表以及mcc_product表，其中operation_type=3,stastus=0
        $domainUrl = $this->getDomain();
        $sqlProduct = "SELECT  CONCAT('" . $domainUrl . "/image/',a.image) AS topic,b.name AS name, a.storeprice  AS money ,a.price AS yuanjia ,a.market_price AS scj,c.createTime as shareDate  FROM " . getTable('product') . " a, " . getTable('product_description') . " b," . getTable('customer_ophistory') . " c where a.product_id = c.product_id and a.product_id = b.product_id AND b.language_id = 1 AND c.operation_type = 1 and c.status=0 and c.customer_id =" . $customerId . " limit 10";
        $res = $this->db->getAll($sqlProduct);
        return $res;
    }


    private function getDomain()
    {
        $sqldomain = "select dvalue from mcc_dict where dkey='domainURL'";
        $res = $this->db->getAll($sqldomain);
        $domain = $res[0]['dvalue'];
        return $domain;
    }

    public function getRegisterCredit(){
        return 2;
    }

    public function newShareRecord($type, $customerId, $productId, $referenceCode)
    {
        // 通过referenceCode获取用户
        $sql = "select customer_id from mcc_customer where shareCode='$referenceCode'";
        $res = $this->db->getAll($sql);
        $curCustomerId = $res[0]['customer_id'];
        // 查询mcc_customer_ophistory表获取shareId
        $sql = "select a.coh_id from mcc_customer_ophistory a where a.customer_id = $curCustomerId";
        if($productId!=null){
            $sql = $sql. " and a.product_id = $productId ";
        }else{
            $sql = $sql. " and a.operation_type = 5 ";
        }
        $res = $this->db->getAll($sql);
        $cohId = $res[0]['coh_id'];
        if ($cohId != null){
            $sql = "select * from mcc_share_statistic a where a.cohId = $cohId ";
            $res = $this->db->getAll($sql);
            if (count($res) > 0){
                $sql = "update `mcc_share_statistic` SET totalNum = totalNum + 1 where cohId = $cohId";
                $this->db->query($sql);
                return $cohId;
            }else{
                $sql = "insert into `mcc_share_statistic` (`cohId`, `totalNum`) VALUES ($cohId,1)";
                $this->db->query($sql);
                $_SESSION['shareId'] = $cohId;
                return $cohId;
            }
        }else{
            //throw exception
        }
    }

    public function activeShareRecord($cohId)
    {
        $this->log->debug('[SharingController][activeShareRecord] cohId = '.$cohId);
        $credit = $this->getRegisterCredit();
        // 分享链接成功次数增加
        $sql = "update `mcc_share_statistic` SET sucNum = sucNum + 1, credit = credit + $credit where cohId = $cohId";
        $this->db->query($sql);
        $sql = "select customer_id from `mcc_customer_ophistory` where coh_id = $cohId";
        $res = $this->db->getAll($sql);
//        $this->registry->get('CreditController')->recordCredit(
//            $this->shareController->TYPE_SHARE_CREDIT,
//            null,2,$res[0]['customer_id'],null,null);
        //直接增加两个积分到对应用户的记录中
        $sql = "update mcc_customer set credit = credit + $credit, regCredit = regCredit + $credit where customer_id = ". $res[0]['customer_id'];
        $res = $this->db->getAll($sql);
        $this->registry->get('CreditController')->recordCredit($this->registry->get('CreditController')->TYPE_SHARE_CREDIT, null, $credit, $res[0]['customer_id'], null, null,1);

    }
}
