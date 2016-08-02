<?php
class ModelActivitySpecial extends MyModel {

    public function queryNextSpecialId(){
        return parent::queryNextId('special_promotion','promotion_id');
    }
    public function querySpecialById($sub_id){
        $sql = 'select a.* from '.getTable('special_promotion').' a where promotion_id = '.to_db_int($sub_id);
        $special = parent::querySingleRow($sql);
        $sql3 = 'select distinct a.*,b.name as product_name ,c.act_price, c.act_credit_percent, c.act_credit from '
            .getTable('product').' a , '
            .getTable('product_description').' b , '
            .getTable('special_promotion_products').' c '
            .' where a.product_id = b.product_id '
            .' and a.product_id = c.product_id '
            .' and c.promotion_id = '.to_db_int($sub_id)
        ;
        $products = parent::queryRows($sql3);
        $special['products'] = $products;
        return $special;
    }
    public function queryProductTypes(){
        return parent::queryProductTypeList();
    }
    public function createSpecial($data){
        parent::startTransaction();
        try{
            $sql = 'insert into '.getTable('special_promotion')
                .' (`imgurl`,`promotion_name`,`starttime`,`endtime`,`memo`,`special_type`) values ('
                .to_db_str($data['imgurl']).','
                .to_db_str($data['act_name']).','
                .to_db_str($data['act_start_date']).','
                .to_db_str($data['act_end_date']).','
                .to_db_str($data['act_memo']).','
                .to_db_int($data['special_type'])
                .')';
            parent::executeSql($sql);
            $sub_id = parent::getLastId();
            $act_product_ids = $data['act_product_ids'];
            $index = 0;
            foreach($act_product_ids as $product_id){
                $index ++;
                $sql2 = 'insert into '.getTable('special_promotion_products')
                    .' (`promotion_id`,`product_id`,`sort_order`,`act_price`,`act_credit`)'
                    .'values ( '
                    .to_db_int($sub_id).' , '
                    .to_db_int($product_id).' , '
                    .to_db_int($index).' , '
                    .to_db_int($data['p_'.$product_id.'_act_price']).' , '
                    .to_db_int($data['p_'.$product_id.'_act_credit'])
                    .')';
                parent::executeSql($sql2);
            }
            $sql3 = 'insert into '.getTable('promotions')
                .' (`subpromotionid`,`type`,`status`,`startdate`,`enddate`) '
                .' values ('
                .to_db_int($sub_id).' , '
                .to_db_int(0).' , '
                .to_db_int($data['act_status']).' , '
                .to_db_str($data['act_start_date']).' , '
                .to_db_str($data['act_end_date'])
                .')';
            parent::executeSql($sql3);
        }catch (Exception $e){
            parent::rollbackTransaction();
        }
        parent::commitTransaction();
        return true;
    }
    public function modifySpecial($data){
        parent::startTransaction();
        try{
            $sql = 'update '.getTable('special_promotion').' set promotion_id = promotion_id ';
                if(is_valid($data['imgurl'])){
                    $sql .= ' , imgurl = '.to_db_str($data['imgurl']);
                }
                $sql.= ', promotion_name = '.to_db_str($data['act_name']);
                $sql.= ', starttime = '.to_db_str($data['act_start_date']);
                $sql.= ', endtime = '.to_db_str($data['act_end_date']);
                $sql.= ', memo = '.to_db_str($data['act_memo']);
                $sql.= ', special_type = '.to_db_str($data['special_type']);
                $sql .= ' where promotion_id = '.to_db_int($data['act_id']);
            parent::executeSql($sql);
            $sub_id = $data['act_id'];
            $sql4 = 'delete from '.getTable('special_promotion_products').' where promotion_id = '.to_db_int($sub_id);
            parent::executeSql($sql4);
            $act_product_ids = $data['act_product_ids'];
            $index = 0;
            foreach($act_product_ids as $product_id){
                $index ++;
                $sql2 = 'insert into '.getTable('special_promotion_products')
                    .' (`promotion_id`,`product_id`,`sort_order`,`act_price`,`act_credit`)'
                    .'values ( '
                    .to_db_int($sub_id).' , '
                    .to_db_int($product_id).' , '
                    .to_db_int($index).' , '
                    .to_db_int($data['p_'.$product_id.'_act_price']).' , '
                    .to_db_int($data['p_'.$product_id.'_act_credit'])
                    .')';
                parent::executeSql($sql2);
            }
            $sql3 = 'update '.getTable('promotions').' set status = '
                .to_db_int($data['act_status'])
                .' , startdate = '.to_db_str($data['act_start_date'])
                .' , enddate = '.to_db_str($data['act_end_date'])
                .' where subpromotionid = '.to_db_int($sub_id).' and type = '.to_db_int(0);
            parent::executeSql($sql3);
        }catch (Exception $e){
            parent::rollbackTransaction();
        }
        parent::commitTransaction();
        return true;
    }

    public function isAlreadyInSpecial($product_id,$limit,$act_id){
        $sql = 'select count(1) as count from '.getTable('special_promotion').' a,'.getTable('special_promotion_products').' b , '
            .getTable('promotions').' c '
        .'where a.promotion_id = b.promotion_id and a.promotion_id = c.subpromotionid and c.status = 0 and a.starttime < current_timestamp and a.endtime > current_timestamp '
        .' and b.product_id = '.to_db_int($product_id);
        if(is_valid($act_id)){
            $sql .= ' and a.promotion_id != '.to_db_int($act_id);
        }
        $count = parent::queryCount($sql);
        return $count > $limit;
    }

    public function queryAllSpecial(){
        $sql = 'select a.* from '.getTable('special_promotion').' a ';
        $special = parent::queryRows($sql);
        return $special;
    }

}