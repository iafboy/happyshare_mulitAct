<?php
class ModelCommentList extends MyModel {

    private function mainQuerySql($data,$column_str){
        $sql = 'select '.$column_str.' from '
            .'( '
            .'( '
    		.'( '
			.'( '
            .'( '.getTable('review').' d inner join `mcc_product` b on b.product_id = d.product_id '
            .') '
 			    .'left join '.getTable('order_product').' a on a.product_id = b.product_id '
            .') '
 		        .'left join '.getTable('order').' c on a.order_id = c.order_id '
            .') '
	            .'inner join '.getTable('customer').' e on d.customer_id = e.customer_id '
            .') '
                .'inner join '.getTable('product_description').' g on g.product_id = d.product_id '
            .') ';


        $sql .= "where g.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 
        //$sql .= "where 1=1 ";


        if(is_valid($data['filter_'.'product_no'])){
            $sql .= " and b.product_no like '%" . $this->db->escape($data['filter_'.'product_no'])."%' ";
        }
        if(is_valid($data['filter_'.'product_name'])){
            $sql .= " and g.name like '%" . $this->db->escape($data['filter_'.'product_name']) . "%' ";
        }
        if(is_valid($data['filter_'.'product_type']) && $data['filter_'.'product_type'] != '*'){
            $sql .= " and b.product_type_id = ".$data['filter_'.'product_type'];
        }
        if(is_valid($data['filter_'.'buyer_account']) ){
            $sql .= " and e.fullname like '%" . $this->db->escape($data['filter_'.'buyer_account']) . "%' ";
        }
        if(is_valid($data['filter_'.'comment_key']) ){
            $sql .= " and d.text like '%" . $this->db->escape($data['filter_'.'comment_key']) . "%' ";
        }
        if($data['filter_'.'contain_comment_key']===1 || $data['filter_'.'contain_comment_key'] ===true || $data['filter_'.'contain_comment_key'] ==='on' ){
            $count = parent::queryCount('select count(1) as count from '.getTable('comment_key'));
            if($count > 0){
                $sql .= " and  exists ( select 1 from ".getTable('comment_key')." f where d.text like concat('%',f.key_name,'%')) ";
            }
        }
        if(is_valid($data['filter_'.'comment_time_start'])){
            $sql .= " and d.date_added >= '" .$data['filter_'.'comment_time_start'] . "' ";
        }
        if(is_valid($data['filter_'.'comment_time_end'])){
            $sql .= " and d.date_added <= '" .$data['filter_'.'comment_time_end'] . "' ";
        }
        return $sql;
    }

    public function queryComments($data=array()){
        $sql = $this->mainQuerySql($data,' distinct d.*,g.name as product_name, e.fullname,b.product_no ');
        $sql .= ' order by d.date_added desc';
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        return parent::queryRows($sql);
    }
    public function  queryCommentsCount($data=array()){
        $sql = $this->mainQuerySql($data,' distinct d.*,g.name as product_name, e.fullname ');
        $sql = 'select count(1) as count  from ( '.$sql.') m';
        return parent::queryCount($sql);
    }

    public function getProductTypes(){
        return parent::queryProductTypeList();
    }

    public function delComment($comment_id){
        $sql = 'delete from '.getTable('review').' where review_id = '.to_db_int($comment_id);
        return parent::wrapTransaction($sql);
    }

    public function addReply($comment_id,$user_id,$text){
        $sql = 'select * from '.getTable('review').' where review_id = '.to_db_int($comment_id);
        $comment = parent::querySingleRow($sql);
        $reply_json = [];
        if(isset($comment) &&  is_valid($comment['reply'])){
            $reply = $comment['reply'];
            $reply = json_decode($reply);
            $time = parent::getCurrentTime();
            $reply[] = ['user_id'=>$user_id,
                'text' =>$text,
                'date_added'=>$time,
                'type'=>'1'];
            $reply_json = $reply;
        }else{
            $array = array();
            $time = parent::getCurrentTime();
            $array[] = ['user_id'=>$user_id,
            'text' =>$text,
            'date_added'=>$time,
            'type'=>'1'
            ];
            $reply_json = $array;
        }
        $sql_update = 'update '.getTable('review').' set';
        if(isset($comment) && $comment['status'].''==='0'){
            $sql_update = $sql_update .' status = '.to_db_int(1).' , ';
        }
        $reply_json_text = json_encode($reply_json);
        $sql_update .= ' reply = '.to_db_str($reply_json_text).' where review_id = '.to_db_int($comment_id);
        return parent::wrapTransaction($sql_update);
    }
    public function addComment($product_id,$user_id,$text){
        $sql='insert into '.getTable('review')." ( `product_id`,`customer_id`,`text`,`status`,`admin_review` ) values(".to_db_str($product_id).",".to_db_str($user_id).",".to_db_str($text).",0,1)";
        return parent::wrapTransaction($sql);
    }
}
