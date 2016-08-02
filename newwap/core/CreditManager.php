<?php
//积分相关逻辑处理
//用户购买商品后要调用rewwardCredits增加相关积分
//用户
class CreditManager
{
    private $db;
    private $log;
    public function __construct($registry) {
        $this->db=$registry->get('db');
        $this->log=$registry->get('log');
    }
    //积分回馈规则
    public function rewardCredits($productId,$customerId){
            $sqlProductCredit = "select a_setting,b_setting,c_setting from ".getTable('giftcredit_setting')." where starttime>= now() and now()<=endtime order by cs_id desc limit 1";
            try {
                $res = $this->db->getAll($sqlProductCredit);
                if (size($res) > 0) {
                    //TODO

                    $a_setting = $res[0]['a_setting'];
                    $b_setting = $res[0]['b_setting'];
                    $c_setting = $res[0]['c_setting'];
                    //根据productId找到产品平台价格，并得出积分值

                    //根据custmerid在mcc_customer表找到推荐人shareCode字段referee（值为shareCode的值），然后根据referree向前找从此倒数第一级推荐人customerid

                    //倒数第一级customerid客户的credit字段按照c_setting设置的百分数增加积分

                    //根据倒数第一级customer，查看是否有存相关referee值，如果有根据referee值向前找倒数第二级推荐人customerid

                    //倒数第二级customerid客户的credit字段按照b_setting设置的百分数增加积分

                    //根据倒数第二级customer，查看是否有存相关referee值，如果有根据referee值向前找倒数第三级推荐人customerid

                    //倒数第三级customerid客户的credit字段按照a_setting设置的百分数增加积分

                }
            } catch (Exception $e) {
                $this->log->error($e->getMessage());
            }
        }
}