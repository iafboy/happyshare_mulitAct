<?php

class CommentsController
{
    private $db;
    private $log;

    public function __construct($registry)
    {
        $this->db = $registry->get('db');
        $this->log = $registry->get('log');
    }




    //发布评价
    public function publishComments($title, $content, $picPaths, $productId, $customerId)
    {
        //检查数组picPaths,中数量，最多5个

        //将数据写入mcc_customer_ophistory，其中operation_type=2
        $sql = "INSERT INTO " . getTable('customer_ophistory') . " SET product_id = '" . (int)$productId . "', operation_type = 2, customer_id='" . (int)$customerId . "', comments = '" . $this->db->escape($content) . "', createTime =  NOW()";
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

    //查询产品评价列表
    public function queryCommentsOfProd($productId, $num)
    {
        $domain = $this->getDomain();

        $sql = "SELECT CONCAT('" . $domain . "/image/', coalesce(c.imgurl,'avatar/avatar_1.jpg')) as userImg, coalesce(CONCAT(CONCAT(substring(b.fullname,1,3),'****'),substring(b.fullname,-4)),'平台管理员') AS userName, b.customer_id AS userId, a.comments AS comment,a.createtime as createTime,a.reply as reply, a.admin_review as admin_review
        FROM " . getTable('comment_view') . " a left join " . getTable('customer') . " b on (a.customer_id = b.customer_id) left join ".getTable('customer_avatar')." c on (b.avatar_id =c.avatar_id) where a.product_id = " . $productId . " order by a.createtime desc limit " . $num;
        $res = $this->db->getAll($sql);

        return $res;
    }

    public function queryMyCommentOfProd( $customerId)
    {
        if ($customerId == null) {
            throw new exception("用户id不能为空");
        }
        //组合查询mcc_customer_ophistory表以及mcc_product表，其中operation_type=2,stastus=0
        $sql = "SELECT  b.fullname AS userName, b.customer_id AS userId, a.comments AS comment,a.createtime as createTime FROM " . getTable('comment_view') . " a," . getTable('customer') . " b  WHERE a.customer_id = b.customer_id  AND  a.customer_id=" . $customerId . " limit 1";
        $res = $this->db->getAll($sql);

        return $res;
    }

    public function publishCommentsV2($content, $productId, $customerId)
    {
        //检查数组picPaths,中数量，最多5个

        //将数据写入mcc_customer_ophistory，其中operation_type=2
        $sql = "INSERT INTO " . getTable('review') . " SET product_id = '" . (int)$productId . "', status = 0, customer_id='" . (int)$customerId . "', text = '" . $this->db->escape($content) . "', date_added =  NOW()";
        //echo $sql;
        $this->db->query($sql);
    }


    public function showCommentNum($productId)
    {
        $commentNummSql = "SELECT commentnum as commentNum from " . getTable('commenthistory_view') . " where product_id =" . $productId . " limit 1";
        $res = $this->db->getAll($commentNummSql);
        if (count($res) == 0) {
            $num = 0;
        } else {
            $num = $res[0]['commentNum'];
        }
        if($num == null){
            $num = 0;
        }

        return $num;
    }

    private function getDomain()
    {
        $sqldomain = "select dvalue from mcc_dict where dkey='domainURL'";
        $res = $this->db->getAll($sqldomain);
        $domain = $res[0]['dvalue'];
        return $domain;
    }
}