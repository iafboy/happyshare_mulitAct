<?php
class ModelActivityCredit extends MyModel {

    public function queryNextCreditId(){
        return parent::queryNextId('credit_promotion','cp_id');
    }
    public function queryCreditById($sub_id){
        $sql = 'select a.* from '.getTable('credit_promotion').' a where cp_id = '.to_db_int($sub_id);
        $credit = parent::querySingleRow($sql);
        return $credit;
    }

    public function createCredit($data){
        parent::startTransaction();
        try{
            if(!is_valid($data['gift_credit'])){$data['gift_credit'] = 0;}
            if(!is_valid($data['ext_gift'])){$data['ext_gift'] = 0;}
            if(!is_valid($data['reducecash'])){$data['reducecash'] = 0;}
            if(!is_valid($data['consumelimit'])){$data['consumelimit'] = 0;}
            if(!is_valid($data['consume_setting'])){$data['consume_setting'] = 0;}
            if(!is_valid($data['firstorder'])){$data['firstorder'] = 0;}
            $sql = 'insert into '.getTable('credit_promotion')
                .' (`imgurl`,`cp_name`,`starttime`,`endtime`,`memo`,`giftcredit`,`extgift`,`reducecash`,`consumelimit`,`consume_setting`,`firstorder`) values ('
                .to_db_str($data['imgurl']).','
                .to_db_str($data['act_name']).','
                .to_db_str($data['act_start_date']).','
                .to_db_str($data['act_end_date']).','
                .to_db_str($data['act_memo']).','
                .to_db_int($data['gift_credit']).','
                .to_db_int($data['ext_gift']).','
                .to_db_int($data['reducecash']).','
                .to_db_int($data['consumelimit']).','
                .to_db_int($data['consume_setting']).','
                .to_db_int($data['firstorder'])
                .')';
            parent::executeSql($sql);
            $sub_id = parent::getLastId();
            $sql3 = 'insert into '.getTable('promotions')
                .' (`subpromotionid`,`type`,`status`,`startdate`,`enddate`) '
                .' values ('
                .to_db_int($sub_id).' , '
                .to_db_int(2).' , '
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
    public function modifyCredit($data){
        parent::startTransaction();
        try{
            if(!is_valid($data['gift_credit'])){$data['gift_credit'] = 0;}
            if(!is_valid($data['ext_gift'])){$data['ext_gift'] = 0;}
            if(!is_valid($data['reducecash'])){$data['reducecash'] = 0;}
            if(!is_valid($data['consumelimit'])){$data['consumelimit'] = 0;}
            if(!is_valid($data['consume_setting'])){$data['consume_setting'] = 0;}
            if(!is_valid($data['firstorder'])){$data['firstorder'] = 0;}
            $sql = 'update '.getTable('credit_promotion').' set cp_id = cp_id ';
            if(is_valid($data['imgurl'])){
                $sql .= ' , imgurl = '.to_db_str($data['imgurl']);
            }
            $sql.= ', cp_name = '.to_db_str($data['act_name']);
            $sql.= ', starttime = '.to_db_str($data['act_start_date']);
            $sql.= ', endtime = '.to_db_str($data['act_end_date']);
            $sql.= ', memo = '.to_db_str($data['act_memo']);
            $sql.= ', giftcredit = '.to_db_int($data['gift_credit']);
            $sql.= ', extgift = '.to_db_int($data['ext_gift']);
            $sql.= ', reducecash = '.to_db_int($data['reducecash']);
            $sql.= ', consumelimit = '.to_db_int($data['consumelimit']);
            $sql.= ', consume_setting = '.to_db_int($data['consume_setting']);
            $sql.= ', firstorder = '.to_db_int($data['firstorder']);
            $sql .= ' where cp_id = '.to_db_int($data['act_id']);
            parent::executeSql($sql);
            $sub_id = $data['act_id'];
            $sql3 = 'update '.getTable('promotions').' set status = '
                .to_db_int($data['act_status'])
                .' , startdate = '.to_db_str($data['act_start_date'])
                .' , enddate = '.to_db_str($data['act_end_date'])
                .' where subpromotionid = '.to_db_int($sub_id).' and type = '.to_db_int(2);
            parent::executeSql($sql3);
        }catch (Exception $e){
            parent::rollbackTransaction();
        }
        parent::commitTransaction();
        return true;
    }

}