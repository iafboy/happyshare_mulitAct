<?php
class ModelActivityGift extends MyModel {

    public function queryNextGiftId(){
        return parent::queryNextId('gift_promotion','gp_id');
    }
    public function queryGiftById($sub_id){
        $sql = 'select a.* from '.getTable('gift_promotion').' a where gp_id = '.to_db_int($sub_id);
        $gift = parent::querySingleRow($sql);
        return $gift;
    }
    public function createGift($data){
        parent::startTransaction();
        try{
            if(!is_valid($data['gift_credit'])){$data['gift_credit'] = 0;}
            if(!is_valid($data['is_gift_credit'])){$data['is_gift_credit'] = 1;}
            if(!is_valid($data['description'])){$data['description'] = '';}
            $sql = 'insert into '.getTable('gift_promotion')
                .' (`imgurl`,`gp_name`,`gift_trans_time`,`memo`,`gift_credit`,`is_gift_credit`,`description`) values ('
                .to_db_str($data['imgurl']).','
                .to_db_str($data['act_name']).','
                .to_db_str($data['gift_trans_time']).','
                .to_db_str($data['act_memo']).','
                .to_db_int($data['gift_credit']).','
                .to_db_int($data['is_gift_credit']).','
                .to_db_str($data['description'])
                .')';
            parent::executeSql($sql);
            $sub_id = parent::getLastId();
            $sql3 = 'insert into '.getTable('promotions')
                .' (`subpromotionid`,`type`,`status`,`startdate`,`enddate`) '
                .' values ('
                .to_db_int($sub_id).' , '
                .to_db_int(3).' , '
                .to_db_int($data['act_status']).' , '
                .to_db_str('1111-01-01 00:00:00').' , '
                .to_db_str('9999-12-12 00:00:00')
                .')';
            parent::executeSql($sql3);
        }catch (Exception $e){
            parent::rollbackTransaction();
        }
        parent::commitTransaction();
        return true;
    }
    public function modifyGift($data){
        parent::startTransaction();
        try{
            if(!is_valid($data['gift_credit'])){$data['gift_credit'] = 0;}
            if(!is_valid($data['is_gift_credit'])){$data['is_gift_credit'] = 1;}
            if(!is_valid($data['description'])){$data['description'] = '';}
            $sql = 'update '.getTable('gift_promotion').' set gp_id = gp_id ';
            if(is_valid($data['imgurl'])){
                $sql .= ' , imgurl = '.to_db_str($data['imgurl']);
            }
            $sql.= ', gp_name = '.to_db_str($data['act_name']);
            $sql.= ', gift_trans_time = '.to_db_str($data['gift_trans_time']);
            $sql.= ', memo = '.to_db_str($data['act_memo']);
            $sql.= ', gift_credit = '.to_db_int($data['gift_credit']);
            $sql.= ', is_gift_credit = '.to_db_int($data['is_gift_credit']);
            $sql.= ', description = '.to_db_str($data['description']);
            $sql .= ' where gp_id = '.to_db_int($data['act_id']);
            parent::executeSql($sql);
            $sub_id = $data['act_id'];
            $sql3 = 'update '.getTable('promotions').' set status = '
                .to_db_int($data['act_status'])
                .' where subpromotionid = '.to_db_int($sub_id).' and type = '.to_db_int(3);
            parent::executeSql($sql3);
        }catch (Exception $e){
            parent::rollbackTransaction();
        }
        parent::commitTransaction();
        return true;
    }

}