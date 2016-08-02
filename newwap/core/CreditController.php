<?php

/**
 * Created by PhpStorm.
 * User: ZENGJIPENG
 * Date: 2015/12/20
 * Time: 20:23
 */
class CreditController
{
    private $db;
    private $log;
    private $registry;

    public $creditToCashTransferPercent =10;
    public $cashTaxPercent = 80;

    public $TYPE_REVOKE_CREDIT = -1;
    public $TYPE_BUY_CREDIT = 0;
    public $TYPE_BONUS_CREDIT = 1;
    public $TYPE_SHARE_CREDIT = 2;
    public $TYPE_SPEND_CREDIT = 3;
    public $TYPE_DEVELOP_CREDIT = 4;

    public function __construct($registry)
    {
        $this->db = $registry->get('db');
        $this->log = $registry->get('log');
        $this->registry = $registry;
    }

    public function getCreditConfigure()
    {
        //查询mcc_config_credit表，列出所有记录	
        $sql = "SELECT * FROM mcc_config_credit";
        $res = $this->db->getAll($sql);
        return $res;
    }

    // 记录积分收支情况到mcc_credithistory表
    /*
     * 积分收支类型：
     * 0 - 消费获得积分
     * 1 - 赠与获得积分
     * 2 - 分享获得积分
     * 3 - 支出积分
     * 4 - 活动积分
     */
    public function recordCredit($type,$ref_id,$credit,$customerId,$productId,$comment,$istatus = -1,$order_id=-1)
    {
        $this->log->debug('[CreditController][recordCredit] type = '.$type.' ref_id = '.$ref_id.' credit = '.$credit.
            ' customerId = '.$customerId.' productId = '.$productId.' comment = '.$comment.' istatus = '.$istatus);
        $adddate = date('Y-m-d H:i:s');
        if($istatus == -1){
            if($type == $this->TYPE_SPEND_CREDIT){
                $status = 1;
            }else{
                $status = 0;
            }
        }else{
            $status = $istatus;
            //需要对增长积分做反馈上级操作
            if($type != $this->TYPE_SPEND_CREDIT) {
                $this->feedbackCredit($customerId, $type, $credit);
            }
        }

        // 消费积分中 一个订单只能积分一次
        if($type == $this->TYPE_BUY_CREDIT){
            $sql = "select * from `mcc_credithistory` where type = $this->TYPE_BUY_CREDIT and ref_id = $ref_id and customerid = $customerId";
            $res = $this->db->getAll($sql);
            if (count($res) > 0){
                $this->log->debug('[CreditController][recordCredit] credit history already exist, id = '.$res[0]['ch_id']);
                return $res[0]['ch_id'];
            }
            $order_id=$ref_id;
        }

        $sql = "INSERT INTO `mcc_credithistory` (`type`, `ref_id`,`order_id`, `adddate`, `credit`, `customerid`, `productId`, `comment`, `status`) VALUES ($type,'$ref_id',$order_id,'$adddate',$credit,$customerId,'$productId','$comment',$status)";
        $res = $this->db->query($sql);
        $id = $this->db->getLink()->insert_id;
        $this->log->debug('[CreditController][recordCredit] new credit history inserted, id = '.$id);
        return $id;
    }

    public function updateCreditConfigure($request)
    {
        //更新mcc_config_credit表
        $name = $request['name'];
        if ($name == '') {
            $name = 'default';
        }
        $filter = Array();
        $filter[name] = $name;
        $table = "mcc_config_credit";
        $res = $this->db->update($table, $request, $filter);
        if (!$res) {
            $msg = new \leshare\json\message($res, 1, " failed");
        } else {
            $msg = new \leshare\json\message($res, 0, " success");
        }
        return $msg;
    }

    public function getCreditRules($request)
    {
        //查询mcc_config_credit_rules表，列出所有记录
        $sql = "SELECT * FROM mcc_config_credit_rules";
        $res = $this->db->getAll($sql);
        if (!$res) {
            $msg = new \leshare\json\message($res, 1, " failed");
        } else {
            $msg = new \leshare\json\message($res, 0, " success");
        }
        return $msg;
    }

    public function addCreditRules($request)
    {
        //增加一条记录到mcc_config_credit_rules表
        $table = "mcc_config_credit_rules";
        $res = $this->db->insert($table, $request);
        if (!$res) {
            $msg = new \leshare\json\message($res, 1, " failed");
        } else {
            $msg = new \leshare\json\message($res, 0, " success");
        }
        return $msg;
    }

    // 积分入账入口
    // 尝试为所有用户入账当月积分
    public function applyCreditEntry()
    {
        $this->log->debug('[CreditController][applyCreditEntry] start to apply credit for this month');
        //获取所有用户
        $currentMonth = date("Ym");
        $currentMonthStart = $currentMonth . "01";
        $currentMonthEnd = date('Ymd', strtotime("$currentMonthStart +1 month"));
        $sql = "SELECT DISTINCT(a.customerid) as customerid FROM `mcc_credithistory` a inner join `mcc_order` b on (a.order_id=b.order_id and b.order_status =10)  WHERE a.adddate >= DATE($currentMonthStart) and a.adddate <= DATE($currentMonthEnd) and a.status = 0 ";
        $this->log->debug('[CreditController][applyCreditEntry] get all users '.$sql);
        $res = $this->db->getAll($sql);

        //分别入账
        for ($i = 0; $i < count($res); $i++) {
            $this->log->debug('[CreditController][applyCreditEntry] start to apply credit for customer = '.$res[$i]['customerid']);
            $current_customer_id = $res[$i]['customerid'];
            $this->applyCredit($current_customer_id);
        }

        //再次尝试入账分享积分 （缺陷 #436）
        for ($i = 0; $i < count($res); $i++) {
            $current_customer_id = $res[$i]['customerid'];
            $this->applyCredit($current_customer_id);
        }
    }

    //积分入账
    public function applyCredit($customerId)
    {
        if ($customerId == null) {
            echo "no customer id specified!";
            $this->log->error('[CreditController][applyCredit] customer = '.$customerId.' not found!');
            return new \leshare\json\message(null, 1, " failed");
        }
        $creditConfig = $this->getCreditConfigure();
        $buyCreditLimitLastMonth = $creditConfig[0]["buyCreditLimitLastMonth"];
        $buyCreditLimitThisMonth = $creditConfig[0]["buyCreditLimitThisMonth"];
        $bonusCreditLimitLastMonth = $creditConfig[0]["bonusCreditLimitLastMonth"];
        $bonusCreditLimitThisMonth = $creditConfig[0]["bonusCreditLimitThisMonth"];
        $shareCreditLimitLastMonth = $creditConfig[0]["shareCreditLimitLastMonth"];
        $shareCreditLimitThisMonth = $creditConfig[0]["shareCreditLimitThisMonth"];

        $currentMonth = date("Ym");
        $currentMonthStart = $currentMonth . "01";
        $currentMonthEnd = date('Ymd', strtotime("$currentMonthStart +1 month"));

        $lastMonth = date("Ym") - 1;
        $lastMonthStart = $lastMonth . "01";
        $lastMonthEnd = date('Ymd', strtotime("$lastMonthStart +1 month"));

        //获取上月消费积分+奖励积分总和（简称上月积分总和）
//        if ($month != null) {
            //$sql = "SELECT sum(a.credit) as sumLastMonth FROM mcc_credithistory a left join mcc_order b on (a.order_id=b.order_id and b.order_status =10 and a.type=0 or a.type=1 or a.type=2 or a.type=4) WHERE (a.type <> 3) and a.customerid = $customerId  and a.adddate >= DATE($lastMonthStart) and a.adddate <= DATE($lastMonthEnd)";
            $sql="SELECT sum(credit) FROM mcc_credithistory where customerid = $customerId and (type in (1,2,4) or type=0 and ifnull(order_id,-99)<>-99 and order_id in (select order_id from mcc_order where order_status=10 and date_added>= DATE($lastMonthStart) and date_added <= DATE($lastMonthEnd))) and status=0 and adddate >= DATE($lastMonthStart) and adddate <= DATE($lastMonthEnd)";
            $res = $this->db->getAll($sql);
            $sumLastMonth = $res[0]["sumLastMonth"] == null?0:$res[0]["sumLastMonth"];
//        } else {
//            // 如果没有指定月份，则获取所有本月之前的未入账的记录
//            $sql = "SELECT sum(credit) as sumLastMonth FROM mcc_credithistory WHERE (type = 0 or type = 1) and `customerid` = $customerId  and `adddate` <= DATE($currentMonthStart) and status = 0";
//            $res = $this->db->getAll($sql);
//            $sumLastMonth = $res[0]["sumLastMonth"];
//        }

        //获取当月消费积分+奖励积分总和（简称当月积分总和）
        //$sql = "SELECT sum(a.credit) as sumThisMonth FROM mcc_credithistory a left join mcc_order b on (a.order_id=b.order_id and b.order_status =10) WHERE (a.type = 0 or a.type = 1) and a.customerid` = $customerId  and a.adddate >= DATE($currentMonthStart) and a.adddate <= DATE($currentMonthEnd)";
        $sql="SELECT sum(credit) FROM mcc_credithistory where customerid = $customerId and (type in (1,2,4) or type=0 and ifnull(order_id,-99)<>-99 and order_id in (select order_id from mcc_order where order_status=10 and date_added>= DATE($currentMonthStart) and date_added <= DATE($currentMonthEnd))) and status=0 and adddate >= DATE($currentMonthStart) and adddate <= DATE($currentMonthEnd)";
        $res = $this->db->getAll($sql);
        $sumThisMonth = $res[0]["sumThisMonth"]==null?0:$res[0]["sumThisMonth"];
        $this->log->debug('[CreditController][applyCredit] sumLastMonth = '.$sumLastMonth);
        $this->log->debug('[CreditController][applyCredit] sumThisMonth = '.$sumThisMonth);
        $this->log->debug('[CreditController][applyCredit] buyCreditLimitLastMonth = '.$buyCreditLimitLastMonth);
        $this->log->debug('[CreditController][applyCredit] buyCreditLimitThisMonth = '.$buyCreditLimitThisMonth);
        $this->log->debug('[CreditController][applyCredit] bonusCreditLimitLastMonth = '.$bonusCreditLimitLastMonth);
        $this->log->debug('[CreditController][applyCredit] bonusCreditLimitThisMonth = '.$bonusCreditLimitThisMonth);
        $this->log->debug('[CreditController][applyCredit] shareCreditLimitLastMonth = '.$shareCreditLimitLastMonth);
        $this->log->debug('[CreditController][applyCredit] shareCreditLimitThisMonth = '.$shareCreditLimitThisMonth);
        //如果上月积分总和满足规则中的阈值，则入账
        //如果上月积分总和不满足规则中的阈值，则获取本月消费积分+奖励积分总和（简称本月积分总和）
        //如果本月积分总和满足规则中的阈值，则入账
        //如果本月积分总和不满足规则中的阈值，不入账
        if ($sumLastMonth >= $buyCreditLimitLastMonth || $sumThisMonth >= $buyCreditLimitThisMonth) {
            $this->addCredit($customerId, $this->TYPE_BUY_CREDIT);
        }else{
            $this->log->debug('[CreditController][applyCredit] buy credit denied due to the rule! ');
        }

        if ($sumLastMonth >= $bonusCreditLimitLastMonth || $sumThisMonth >= $bonusCreditLimitThisMonth) {
            $this->addCredit($customerId, $this->TYPE_BONUS_CREDIT);
        }else{
            $this->log->debug('[CreditController][applyCredit] bonus credit denied due to the rule! ');
        }

        if ($sumLastMonth >= $shareCreditLimitLastMonth || $sumThisMonth >= $shareCreditLimitThisMonth) {
            $this->addCredit($customerId, $this->TYPE_SHARE_CREDIT);
        }else{
            $this->log->debug('[CreditController][applyCredit] share credit denied due to the rule! ');
        }
        $this->addCredit($customerId, $this->TYPE_DEVELOP_CREDIT);
//        return $msg;
    }

    // 根据类型入账当月积分
    public function addCredit($customerId, $type)
    {
        $this->log->debug('[CreditController][addCredit] customerId = '.$customerId. '$type = '.$type);
        $currentMonth = date("Ym");
        $currentMonthStart = $currentMonth . "01";
        $currentMonthEnd = date('Ymd', strtotime("$currentMonthStart +1 month"));
        //获得指定用户指定类型的当月未入账积分总值
        //$sql = "SELECT sum(a.credit) as sumThisMonth FROM mcc_credithistory a left join mcc_order b on (a.order_id=b.order_id and b.order_status =10) WHERE a.type = $type and a.customerid = $customerId  and  a.adddate >= DATE($currentMonthStart) and a.adddate <= DATE($currentMonthEnd) and status = 0";
        if($type==0)
            $sql="SELECT sum(credit) FROM mcc_credithistory where customerid = $customerId and type=$type and order_id in (select order_id from mcc_order where order_status=10 and date_added>= DATE($currentMonthStart) and date_added <= DATE($currentMonthEnd)))and status=0 and adddate >= DATE($currentMonthStart) and adddate <= DATE($currentMonthEnd)";
        else
            $sql="SELECT sum(credit) FROM mcc_credithistory where customerid = $customerId and type=$type and status=0  and adddate >= DATE($currentMonthStart) and adddate <= DATE($currentMonthEnd)";
        $res = $this->db->getAll($sql);
        $sumThisMonth = $res[0]["sumThisMonth"];
        $this->log->debug('[CreditController][addCredit] sumThisMonth = '.$sumThisMonth);
        //将未入账积分总值增加到用户表的总积分和对应类型积分字段
        $this->addCreditDetail($customerId, $type, $sumThisMonth);
        //反馈积分给上级
        $this->feedbackCredit($customerId, $type, $sumThisMonth);
        //将指定用户指定类型的当月未入账积分置成已入账
        $sql = "UPDATE `mcc_credithistory` SET status = 1 WHERE type = $type and `customerid`= $customerId and status = 0 and `adddate` >= DATE($currentMonthStart) and `adddate` <= DATE($currentMonthEnd) and order_id in (select order_id from mcc_order where order_status=10)";
        $this->db->getAll($sql);

    }

    // 根据类型入账当月积分
    public function addCreditDetail($customerId, $type, $amount){
        $sql = "UPDATE `mcc_customer` SET credit = credit + $amount WHERE `customer_id`= $customerId";
        $this->db->getAll($sql);
        if ($type == $this->TYPE_BUY_CREDIT) {
            $sql = "UPDATE `mcc_customer` SET buyCredit = buyCredit + $amount WHERE `customer_id`= $customerId";
            $this->db->getAll($sql);
        } else if ($type == $this->TYPE_BONUS_CREDIT) {
            $sql = "UPDATE `mcc_customer` SET rewardCredit = rewardCredit + $amount WHERE `customer_id`= $customerId";
            $this->db->getAll($sql);
        } else if ($type == $this->TYPE_SHARE_CREDIT) {
            $sql = "UPDATE `mcc_customer` SET shareCredit = shareCredit + $amount WHERE `customer_id`= $customerId";
            $this->db->getAll($sql);
        } else if ($type == $this->TYPE_DEVELOP_CREDIT) {
            $sql = "UPDATE `mcc_customer` SET developCredit = developCredit + $amount WHERE `customer_id`= $customerId";
            $this->db->getAll($sql);
        }

    }

    //反馈积分给上级，上上级，上上上级....(除了反馈积分，其他类型积分需要反馈).
    public function feedbackCredit($customerId, $type, $amount){
        if($type != $this->TYPE_SHARE_CREDIT) {
            $creditConfig = $this->getCreditConfigure();
            $earnCreditLv1 = $creditConfig[0]["earnCreditLv1"];
            $earnCreditLv2 = $creditConfig[0]["earnCreditLv2"];
            $earnCreditLv3 = $creditConfig[0]["earnCreditLv3"];
            $parentCustomerV1 = $this->getParentCustomer($customerId);
            if ($parentCustomerV1 != null && $parentCustomerV1 != "") {
                $lv1FeedbackCredit = intval($amount * $earnCreditLv1 / 100);
                if ($lv1FeedbackCredit > 0) {
                    $this->log->debug('[CreditController][addCredit] feedback to level 1 customer = ' . $parentCustomerV1 . ' with credit = ' . $lv1FeedbackCredit);
                    $this->feedbackCreditToCustomer($customerId, $lv1FeedbackCredit, $this->TYPE_SHARE_CREDIT, $parentCustomerV1, "一级用户入账");
                }
                $parentCustomerV2 = $this->getParentCustomer($parentCustomerV1);
                if ($parentCustomerV2 != null && $parentCustomerV2 != "") {
                    $lv2FeedbackCredit = intval($amount * $earnCreditLv2 / 100);
                    if ($lv2FeedbackCredit > 0) {
                        $this->log->debug('[CreditController][addCredit] feedback to level 2 customer = ' . $parentCustomerV2 . ' with credit = ' . $lv2FeedbackCredit);
                        $this->feedbackCreditToCustomer($customerId, $lv2FeedbackCredit, $this->TYPE_SHARE_CREDIT, $parentCustomerV2, "二级用户入账");
                    }
                    $parentCustomerV3 = $this->getParentCustomer($parentCustomerV2);
                    if ($parentCustomerV3 != null && $parentCustomerV3 != "") {
                        $lv3FeedbackCredit = intval($amount * $earnCreditLv3 / 100);
                        if ($lv3FeedbackCredit > 0) {
                            $this->log->debug('[CreditController][addCredit] feedback to level 3 customer = ' . $parentCustomerV3 . ' with credit = ' . $lv3FeedbackCredit);
                            $this->feedbackCreditToCustomer($customerId, $lv3FeedbackCredit, $this->TYPE_SHARE_CREDIT, $parentCustomerV3, "三级用户入账");
                        }
                    }
                }
            }
        }
    }

    /*
     * 获取父用户（只可能有一位）
     */
    public function getParentCustomer($customerId)
    {
        $sql="SELECT customer_id FROM `mcc_customer` WHERE shareCode = (SELECT referee from `mcc_customer` WHERE customer_id = $customerId)";
        $result = $this->db->getAll($sql);
        return $result[0]['customer_id'];
    }

    /*
     * 反馈积分到指定用户
     */
    public function feedbackCreditToCustomer($ref_id, $amount, $type, $customerId,$comment)
    {
        $this->recordCredit($this->TYPE_SHARE_CREDIT,$ref_id,$amount,$customerId,null,$comment);
    }

    public function queryCreditHistory($customerId, $type)
    {
        if ($type == $this->TYPE_BUY_CREDIT) {//消费积分
            $sql = "
            SELECT a.order_no AS orderNo ,date_added AS orderDate, a.total AS money,b.credit AS credit
            FROM " . getTable('order') . " a," . getTable('credithistory') . " b  WHERE b.ref_id= a.order_id AND b.type=$this->TYPE_BUY_CREDIT AND b.status = 1 AND b.customerid = " . $customerId." order by date_added desc";
            $result = $this->db->getAll($sql);
            $this->log->debug($sql);
            return $result;
        }
        if ($type == $this->TYPE_BONUS_CREDIT){//赠与积分
            $sql = "
            SELECT ref_id AS refId,adddate AS addDate,credit AS credit
            FROM " . getTable('credithistory') ." WHERE customerid = " . $customerId . " AND type = $this->TYPE_BONUS_CREDIT"." order by adddate desc";
            $this->log->debug($sql);
            $result = $this->db->getAll($sql);
            for($i = 0; $i < count($result); $i++){
                //活动ID转换成活动名字,尚未实现，暂时返回测试数据
                if($result[$i]['refId'] == 1){
                    $result[$i]['actName'] = '首单积分奖';
                }else{
                    $result[$i]['actName'] = '获赠积分';
                }
            }
            return $result;
        }
        if ($type == $this->TYPE_SPEND_CREDIT){//支出积分
            $sql = "
            SELECT ref_id AS refId,adddate AS addDate,credit AS credit, comment AS comment
            FROM " . getTable('credithistory') ." WHERE customerid = " . $customerId . " AND type = $this->TYPE_SPEND_CREDIT"." order by adddate desc";
            $this->log->debug($sql);

            $result = $this->db->getAll($sql);
            for($i = 0; $i < count($result); $i++){
                //ref_id转化为支付方式
                if($result[$i]['refId'] == 1){
                    $result[$i]['way'] = '提现支出';
                }else if ($result[$i]['refId'] == 2) {
                    $result[$i]['way'] = '积分赠送';
                }else{
                    $result[$i]['way'] = '消费抵扣';
                }

            }
            return $result;
        }
        if ($type == $this->TYPE_DEVELOP_CREDIT){//活动积分
            $sql = "
            SELECT ref_id AS refId,adddate AS addDate,credit AS credit
            FROM " . getTable('credithistory') ." WHERE customerid = " . $customerId . " AND type = $this->TYPE_DEVELOP_CREDIT"." order by adddate desc";
            $this->log->debug($sql);
            $result = $this->db->getAll($sql);
            for($i = 0; $i < count($result); $i++){
                $curRefId = $result[$i]['refId'];
                $sql = "SELECT a.promotion_name FROM `mcc_special_promotion` a, mcc_promotions b WHERE a.promotion_id = b.subpromotionid and b.pid = $curRefId";
                $res = $this->db->getAll($sql);
                $result[$i]['actName'] = $res[0]['promotion_name'];
            }
            return $result;
        }
    }

    // 获取用户某类积分的分级下级记录
    // $customerId ：客户ID，例如，1
    // $type ：积分类型，例如，2
    // $date ：时间区间，月份，例如，201511
    public function getCreditInDiffLevel($customerId, $date)
    {
        $res = array();
        if (strlen($date) != 6) {
            echo "month format invalid!";
            return new \leshare\json\message(null, 1, " failed");
        }
        $monthStart = $date . "01";
        $monthEnd = date('Ymd', strtotime("$monthStart +1 month"));
        // customerId 组合 '1','2','3'
        $customerIdLv1Filter = '';
        // 根据customerId在表mcc_customer中分别查询出'直接下级', '二级下级', '三级下级'用户
        $customerLv1 = $this->getChildCustomer($customerId);
        if ($customerLv1) {
            for ($i = 0; $i < count($customerLv1); $i++) {
                $currentLv1Id = $customerLv1[$i]['customer_id'];
                $customerLv2 = $this->getChildCustomer($currentLv1Id);
                $customerIdLv2Filter = '';
                if ($customerLv2) {
                    for ($j = 0; $j < count($customerLv2); $j++) {
                        $currentLv2Id = $customerLv2[$j]['customer_id'];
                        $customerLv3 = $this->getChildCustomer($currentLv2Id);
                        if ($customerLv3) {
                            $customerIdLv3Filter = '';
                            for ($k = 0; $k < count($customerLv3); $k++) {
                                $currentLv3Id = $customerLv3[$k]['customer_id'];
                                if ($customerIdLv3Filter == '') {
                                    $customerIdLv3Filter = "'$currentLv3Id'";
                                } else {
                                    $customerIdLv3Filter = $customerIdLv3Filter . ",'$currentLv3Id'";
                                }

//                                $res[$currentLv3Id]['name'] = $customerLv3[$k]['fullname'];
//                                $res[$currentLv3Id]['level'] = '三级下级';
                            }
                            $sql = "SELECT ref_id as customerid,'三级下级' as level,credit,status  FROM `mcc_credithistory` WHERE `ref_id` in ($customerIdLv3Filter) and customerid = $customerId and  `adddate` >= DATE($monthStart) and `adddate` <= DATE($monthEnd) and type = 2";
                            $result = $this->db->getAll($sql);
                            $res = array_merge($res,$result);
                        }
                        if ($customerIdLv2Filter == '') {
                            $customerIdLv2Filter = "'$currentLv2Id'";
                        } else {
                            $customerIdLv2Filter = $customerIdLv2Filter . ",'$currentLv2Id'";
                        }
//                        $res[$currentLv2Id]['name'] = $customerLv2[$j]['fullname'];
//                        $res[$currentLv2Id]['level'] = '二级下级';
                    }
                    $sql = "SELECT ref_id as customerid,'二级下级' as level,credit,status  FROM `mcc_credithistory` WHERE `ref_id` in ($customerIdLv2Filter) and customerid = $customerId  and `adddate` >= DATE($monthStart) and `adddate` <= DATE($monthEnd) and type = 2";
                    $result = $this->db->getAll($sql);
                    $res = array_merge($res,$result);
                }
                if ($customerIdLv1Filter == '') {
                    $customerIdLv1Filter = "'$currentLv1Id'";
                } else {
                    $customerIdLv1Filter = $customerIdLv1Filter . ",'$currentLv1Id'";
                }
//                $res[$currentLv1Id]['name'] = $customerLv1[$i]['fullname'];
//                $res[$currentLv1Id]['level'] = '直接下级';
            }
            $sql = "SELECT ref_id as customerid,'直接下级' as level,credit,status  FROM `mcc_credithistory` WHERE `ref_id` in ($customerIdLv1Filter) and customerid = $customerId  and `adddate` >= DATE($monthStart) and `adddate` <= DATE($monthEnd) and type = 2";
            $result = $this->db->getAll($sql);
            $res = array_merge($res,$result);
        } else {
            // 没有任何下级用户， 直接返回
            return $res;
        }
        // 通过不同的下级用户ID在表mcc_credithistory表中获取在指定时间内所产生的积分  需要得到'积分值'和'积分状态'


//        $sql = "SELECT * FROM `mcc_credithistory` WHERE `customerid` in ($customerIdFilter) and `adddate` >= DATE($monthStart) and `adddate` <= DATE($monthEnd) and type = 2";
//        $result = $this->db->getAll($sql);
        for ($j = 0; $j < count($res); $j++) {
            $res[$j]['status'] = $this->getStatus($result[$j]['status']);
        }
        return $res;
    }

    public function getStatus($status)
    {
        if ($status == 0) {
            return "未入账";
        }
        if ($status == 1) {
            return "已入账";
        }
        return "未入账";
    }

    // 获取“直接下级”用户
    public function getChildCustomer($customerId)
    {
        $sql = "SELECT `fullname`, `customer_id`  FROM `mcc_customer` WHERE `referee` = (SELECT `shareCode` from `mcc_customer` where `customer_id` = $customerId)";
        return $this->db->getAll($sql);
    }

    public  function queryCreditToCash($customerId){
        $sql = "select credit as jifen,".(100-$this->cashTaxPercent)." as taxPercent from ".getTable('customer')." where customer_id= ".$customerId;
        $res = $this->db->getAll($sql);
        return $res[0];
    }

    /* 积分提现
    $customerId : 1
    $amount : 20
    */
    public function creditToCash($customerId, $amount, $bankId, $cardId, $cardHolder, $cardHolderId, $receiverEmail)
    {
        $this->log->debug('[CreditController][creditToCash] customerId = '.$customerId.
            ' amount = '.$amount.
            ' bankId = '.$bankId.
            ' cardId = '.$cardId.
            ' cardHolder = '.$cardHolder.
            ' cardHolderId = '.$cardHolderId.
            ' receiverEmail = '.$receiverEmail
        );
        $sql = "select credit from ".getTable('customer')." where customer_id = ".$customerId;
        $res = $this->db->getAll($sql);
        if(count($res)==0){
            throw new exception("用户不存在:" . $customerId);
        }
        if($res[0]['credit']<$amount){
            throw new exception("用户当前积分小于" . $amount);
        }

        // 获取当前积分提现规则
        $now = date('Y-m-d G:i:s');
        $sql = "select * from `mcc_config_credit_rules` where '$now' <= creditRuleValidDateEnd and '$now' >= creditRuleValidDateStart";
        $res = $this->db->getAll($sql);
        if(count($res)>0){
            $creditForBuyOnCreditEnabled = $res[0]['creditForBuyOnCreditEnabled'];
            $creditForBuyThresholdOnCredit = $res[0]['creditForBuyThresholdOnCredit'];
            $creditForBuyOnUserEnabled = $res[0]['creditForBuyOnUserEnabled'];
            $creditForBuyThresholdOnUser = $res[0]['creditForBuyThresholdOnUser'];
            $creditForWithdrawOnCreditEnabled = $res[0]['creditForWithdrawOnCreditEnabled'];
            $creditForWithdrawThresholdOnCredit = $res[0]['creditForWithdrawThresholdOnCredit'];
            $creditForWithdrawOnUserEnabled = $res[0]['creditForWithdrawOnUserEnabled'];
            $creditForWithdrawThresholdOnUser = $res[0]['creditForWithdrawThresholdOnUser'];
            $creditToManeyRate = $res[0]['creditToManeyRate'];
            $creditRuleValidDateStart = $res[0]['creditRuleValidDateStart'];
            $creditRuleValidDateEnd = $res[0]['creditRuleValidDateEnd'];
        }else{
            $creditForBuyOnCreditEnabled = 0;
            $creditForBuyThresholdOnCredit = 0;
            $creditForBuyOnUserEnabled = 0;
            $creditForBuyThresholdOnUser = 0;
            $creditForWithdrawOnCreditEnabled = 0;
            $creditForWithdrawThresholdOnCredit = 0;
            $creditForWithdrawOnUserEnabled = 0;
            $creditForWithdrawThresholdOnUser = 0;
            $creditToManeyRate = 80;
            $creditRuleValidDateStart = 0;
            $creditRuleValidDateEnd = 0;
        }
        if($creditForWithdrawOnCreditEnabled == 1){
            $totalMoney = $this->getSpentMoney($customerId);
            if ($totalMoney < $creditForWithdrawThresholdOnCredit){
                throw new exception("用户历史消费金额小于阈值" . $creditForWithdrawThresholdOnCredit."，提现失败");
            }
        }

        if($creditForWithdrawOnUserEnabled == 1){
            $totalSubUser = $this->getAllChildCustomer($customerId);
            if (count($totalSubUser) < $creditForWithdrawThresholdOnUser){
                throw new exception("用户发展用户数量小于阈值" . $creditForWithdrawThresholdOnUser."，提现失败");
            }
        }
        $this->cashTaxPercent = $creditToManeyRate;
        $cash_apply_id = $this->getCashApplyId();
        $cash_pay_no = $this->getCashPayNo();
        $credit_amount = $this->getCreditAmount($amount);
        $cash_amount = $this->getCashAmount($credit_amount);

        // 插入提现申请到cash_report表
        $sql = "INSERT INTO `mcc_cash_report`(`customer_id`, `cash_apply_id`, `cash_apply_time`, `cash_pay_status`, `cash_pay_no`, `credit_amount`, `cash_amount`, `bankId`, `cardId`, `cardHolder`, `cardHolderId`, `receiverEmail`) VALUES ($customerId,$cash_apply_id,'$now',0,$cash_pay_no, '$amount', '$cash_amount', '$bankId', '$cardId', '$cardHolder', '$cardHolderId', '$receiverEmail') ";
        $this->db->getAll($sql);
        // 更新customer表
        $customer = $this->getCustomer($customerId);
        if ($customer == null) {
            throw new exception("用户不存在,id=" . $customerId);
        }
        $customer[0]['credit'] = $customer[0]['credit'] - $amount;
        $this->db->update('mcc_customer', $customer[0], array("customer_id" => $customerId));
        $msg = "扣除".(100-$this->cashTaxPercent)."%个人所得税后提现金额为".$cash_amount."元，我们将在7个工作日内为您进行转账，请注意查收！";
        $this->recordCredit($this->TYPE_SPEND_CREDIT,1,$amount,$customerId,null,null);
        $res = array();
        $res['percent']=100-$this->cashTaxPercent;
        $res['money']=$cash_amount;
        return $res;

    }

    /*
     * 获取客户消费总金额
     */
    public function getSpentMoney($customerId)
    {
        $sql = "select sum(total) as totalMoney from mcc_order where customer_id = $customerId and (order_status = 10 or order_status = 11)";
        $res = $this->db->getAll($sql);
        return $res[0]['totalMoney'];
    }

    public function getCashAmount($amount)
    {
        // 提现金额扣除个人所得税
        return round($amount*($this->cashTaxPercent)/100,2);
    }

    public function getCreditAmount($amount)
    {
        // 积分和现金默认按10:1兑换
        return round($amount/($this->creditToCashTransferPercent),2);
    }

    public function cash2Credit($amount)
    {
        // 积分和现金默认按10:1兑换
        return round($amount * ($this->creditToCashTransferPercent),2);
    }

    public function credit2Cash($amount)
    {
        // 积分和现金默认按10:1兑换
        return round($amount / ($this->creditToCashTransferPercent),2);
    }

    public function getCashApplyId()
    {
        return 1;
    }

    public function getCashPayNo()
    {
        return 1;
    }

    /* 积分赠送 ($customerId1赠送给$customerId2)
    $customerId1 : 1
    $customerId2 : 2
    $amount : 20
    扣除customer1的积分 但不能直接将积分增加到customer2的账户中，需要按照积分入账规则入账。
    */
    public function giveCredit($customerId1, $customerId2, $amount)
    {
        $this->log->debug('[CreditController][giveCredit] customerId1 = '.$customerId1.' customerId2 = '.$customerId2.' amount = '.$amount);
        // 获取$customerId1
        $customer1 = $this->getCustomer($customerId1);
        $customer2 = $this->getCustomer($customerId2);
        if ($customer1 == null) {
            throw new exception("用户不存在,id=" . $customerId1);
        }
        if ($customer2 == null) {
            throw new exception("用户不存在,id=" . $customerId2);
        }
        if ($customer1[0]['credit'] < $amount) {
            throw new exception("用户积分小于赠送积分");
        }
        $customer1[0]['credit'] = $customer1[0]['credit'] - $amount;
//        $customer2[0]['credit'] = $customer2[0]['credit'] + $amount;
        $this->recordCredit($this->TYPE_SPEND_CREDIT,2,$amount,$customerId1,null,null);
        $this->db->update('mcc_customer', $customer1[0], array("customer_id" => $customerId1));
        $this->recordCredit($this->TYPE_BONUS_CREDIT,0,$amount,$customerId2,null,null);
//        $this->db->update('mcc_customer', $customer2[0], array("customer_id" => $customerId2));
    }

    public function getCustomer($customerId)
    {
        $sql = "select * from mcc_customer where customer_id = $customerId";
        return $this->db->getAll($sql);
    }
	
	/*
		我的销售体系
		$customerId : 用户ID
		$month : 月份（201601）空表示所有
	*/
	public function getSaleStructure($customerId, $month){
        $result = array();
		$res = $this->getChildCustomer($customerId);
//        $customerIdFilter = '\''.$res[0]['customer_id'].'\'';
        for ($i = 0; $i < count($res); $i++) {
//            $customerIdFilter = $customerIdFilter. ',\''.$res[$i]['customer_id'].'\'';
            $customerId = $res[$i]['customer_id'];
            if ($month == null){
                $sql = "SELECT b.fullname as fullname, a.type as type, SUM(a.credit) as credit
FROM `mcc_credithistory` as a, `mcc_customer` as b
WHERE a.customerid = $customerId
AND a.customerid = b.customer_id
GROUP BY a.customerid, a.type";
            }else{
                $monthStart = $month . "01";
                $monthEnd = date('Ymd', strtotime("$monthStart +1 month"));
                $sql = "SELECT b.fullname as fullname, a.type as type, SUM(a.credit) as credit
FROM `mcc_credithistory` as a, `mcc_customer` as b
WHERE a.adddate >= DATE($monthStart)
and a.adddate <= DATE($monthEnd)
AND a.customerid = b.customer_id
and a.customerid = $customerId
GROUP BY a.customerid, a.type";
            }
            $curRes = $this->db->getAll($sql);
            $result[$i]['fullname'] = $res[$i]['fullname'];
            $result[$i]['buyCredit'] = 0;
            $result[$i]['shareCredit'] = 0;
            $result[$i]['rewardCredit'] = 0;
            $result[$i]['developCredit'] = 0;
            for ($j = 0; $j < count($curRes); $j++) {
                if($curRes[$j]['type'] == 0){
                    $result[$i]['buyCredit'] = $curRes[$j]['credit'];
                }else if ($curRes[$j]['type'] == 1){
                    $result[$i]['rewardCredit'] = $curRes[$j]['credit'];
                }else if ($curRes[$j]['type'] == 2){
                    $result[$i]['shareCredit'] = $curRes[$j]['credit'];
                }else if ($curRes[$j]['type'] == 4){
                    $result[$i]['developCredit'] = $curRes[$j]['credit'];
                }
            }
        }

        return $result;
	}

    public function getCreditType($type)
    {
        if ($type == $this->TYPE_BUY_CREDIT) {
            return "消费积分";
        }
        else if ($type == $this->TYPE_BONUS_CREDIT) {
            return "奖励积分";
        }
        else if ($type == $this->TYPE_SHARE_CREDITT) {
            return "分享积分";
        }
        else if ($type == $this->TYPE_DEVELOP_CREDIT) {
            return "发展积分";
        }
        return "消费积分";
    }
	
	public function getAllChildCustomer($customerId)
	{
		$res=$this->getChildCustomer($customerId);
		if ($res == null || count($res) == 0){
			return null;
		}
		$result=array();
        $result = array_merge($result, $res);
		for($j=0;$j<count($res);$j++)
		{
			$curRes=$this->getAllChildCustomer($res[$j]['customer_id']);
			if ($curRes != null){
				$result = array_merge($result, $curRes);
			}
		}
		return $result;
	}

    /*
     * 只删除积分历史记录mcc_credithistory，每个订单在表中只会对应一条记录
     */
    public function removeCreditByOrder($orderId)
    {
//        $sql = "select customer_id from mcc_order where order_id = $orderId";
//        $res = $this->db->getAll($sql);
//        if ($res == null || count($res) == 0){
//            return null;
//        }
//        $credit = $this->registry->get('ProductController')->getProductCreditByOrder($orderId);
//        $customerId = $res[0]['customer_id'];
//        $this->removeCredit($customerId,$credit,$this->TYPE_BUY_CREDIT);



        $sql = "update mcc_credithistory set type = $this->TYPE_REVOKE_CREDIT where type = $this->TYPE_BUY_CREDIT and ref_id = $orderId";
        $this->db->getAll($sql);
    }
    public function removeCreditByOrderProducts($order_id,$productId,$return_num)
    {
        //$sql = 'update mcc_credithistory set type ='. $this->TYPE_REVOKE_CREDIT.',credit=credit-(select credit*'.$return_num.' from mcc_product where product_id='.$productId.')  where status=0 and type = '.$this->TYPE_BUY_CREDIT.' and ref_id = '.$order_id.' and productId='.$productId;
//        $sql='select * from mcc_credithistory where status=0 and type='.$this->TYPE_BUY_CREDIT.' and ref_id = '.$order_id;
//        $res = $this->db->getAll($sql);
//        if (count($res) > 0)
        //$sql = 'update mcc_credithistory set credit=credit-(select credit*' . $return_num . ' from mcc_product where product_id=' . $productId . ')  where status=0 and type = ' . $this->TYPE_BUY_CREDIT . ' and ref_id = ' . $order_id;
        $this->log->debug('[CreditController][removeCreditByOrderProducts] begin to remove credit : order_id '.$order_id);
        if ($order_id>0) {
            $this->log->debug('[CreditController][removeCreditByOrderProducts] begin:');
            $res = $this->registry->get('ProductController')->getProductCreditByOrderAndType($order_id);
            $this->log->debug('[CreditController][removeCreditByOrderProducts] total buy credit = ' . $res['total_buy_credit']);
            $this->log->debug('[CreditController][removeCreditByOrderProducts] number of activity credit = ' . count($res['total_activity_credit_list']));
            $normalcnt=0;
            if (count($res['total_activity_credit_list']) > 0) {
                for ($i = 0; $i < count($res['total_activity_credit_list']); $i++) {
                    if($res['total_activity_credit_list'][$i]['product_id']==$productId) {
                        $sql = 'update mcc_credithistory set credit=credit-'. $res['total_activity_credit_list'][$i]['s_credit'] .'*'.$return_num.' where type=4 and ref_id=' . $res['total_activity_credit_list'][$i]['ref_id'] . ' and order_id=' . $order_id;
                        $this->log->debug('[CreditController][removeCreditByOrderProducts] update credit sql: ' . $sql);
                        $this->db->getAll($sql);
                        $normalcnt++;
                    }
                }
                if (($normalcnt==0)&&($res['total_buy_credit'] > 0)) {
                    $sql = 'update mcc_credithistory set credit=credit-(select credit*' . $return_num . ' from mcc_product where product_id=' . $productId . ')  where status=0 and type =0 and order_id = ' . $order_id;
                    $this->log->debug('[CreditController][removeCreditByOrderProducts] update credit sql: ' . $sql);
                    $this->db->getAll($sql);
                }
                $sql = 'update mcc_credithistory set type=-1 where  credit<1 and order_id=' . $order_id;
                $this->log->debug('[CreditController][removeCreditByOrderProducts] delete sql: ' . $sql);
                $this->db->getAll($sql);
                return;
            }else
            if ($res['total_buy_credit'] > 0) {
                $sql = 'update mcc_credithistory set credit=credit-(select credit*' . $return_num . ' from mcc_product where product_id=' . $productId . ')  where status=0 and type =0 and order_id = ' . $order_id;
                $this->log->debug('[CreditController][removeCreditByOrderProducts] update credit sql: ' . $sql);
                $this->db->getAll($sql);
                $sql = 'update mcc_credithistory set type=-1 where credit<1 and order_id=' . $order_id;
                $this->log->debug('[CreditController][removeCreditByOrderProducts] delete sql: ' . $sql);
                $this->db->getAll($sql);
                return;
            }
        }
        $this->log->debug('[CreditController][removeCreditByOrderProducts] begin to remove credit finished ');
    }

    public function removeCredit($customerId, $credit, $type)
    {
        if ($type == $this->TYPE_BUY_CREDIT) {
            $sql = "update mcc_customer set credit = credit - $credit, buyCredit = buyCredit - $credit where customer_id = $customerId";
        }
        else if ($type == $this->TYPE_BONUS_CREDIT) {
            $sql = "update mcc_customer set credit = credit - $credit, rewardCredit = rewardCredit - $credit where customer_id = $customerId";
        }
        else if ($type == $this->TYPE_SHARE_CREDITT) {
            $sql = "update mcc_customer set credit = credit - $credit, shareCredit = shareCredit - $credit where customer_id = $customerId";
        }
        else if ($type == $this->TYPE_DEVELOP_CREDIT) {
            $sql = "update mcc_customer set credit = credit - $credit, developCredit = developCredit - $credit where customer_id = $customerId";
        }
        $this->db->getAll($sql);
    }

    /*
     * 获取本月所有未入账积分
     */
    function getUnappliedCredit($customerId)
    {
        $monthStart = date('Ym') . "01";
        $monthEnd = date('Ymd', strtotime("$monthStart +1 month"));
        $sql = "select sum(credit) as total from mcc_credithistory where (type in (1,2,4) or (type=0 and ifnull(order_id,-99)!=-99)) and customerid = $customerId and status = 0 and adddate <= $monthEnd and adddate >= $monthStart";
        return $this->db->getAll($sql)[0]['total'] == null?0:$this->db->getAll($sql)[0]['total'];
    }

    /*
     * 获取上月分享积分入账阈值
     */
    function getCreditThresholdLastMonth()
    {
        $creditConfig = $this->getCreditConfigure();
        return $shareCreditLimitLastMonth = $creditConfig[0]["shareCreditLimitLastMonth"];
    }

    /*
     * 获取本月分享积分入账阈值
     */
    function getCreditThresholdThisMonth()
    {
        $creditConfig = $this->getCreditConfigure();
        return $shareCreditLimitThisMonth = $creditConfig[0]["shareCreditLimitThisMonth"];
    }
	
}	