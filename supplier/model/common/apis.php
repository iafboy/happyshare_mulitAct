<?php
class ModelCommonApis extends MyModel {

    public function getAllExpressCompanies(){
        $sql = 'select * from '.getTable('express_company');
        return parent::queryRows($sql);
    }

    public function setAreaPrivs($areaId,$privCodes){
        foreach($privCodes as $privCode){
            $sql = 'update '.getTable('addressbook_china_province').' set parent_code = '.to_db_str($areaId).' where region_code = '.to_db_str($privCode);
            parent::executeSql($sql);
        }
    }

    public function getAllPrivs($areaCode,$areaId){
        $sql = 'select * from '.getTable('addressbook_china_province').' where 1=1 ';
        if(is_valid($areaCode)){
            $sql .=' and parent_code = '.to_db_str($areaCode);
        }
        if(is_valid($areaId)){
            $sql .=' and parent_code = ( select region_code from '.getTable('addressbook_china_area').' where id = '.to_db_int($areaId).' )';
        }
        return parent::queryRows($sql);
    }

    public function getAreas(){
        $sql = 'select * from '.getTable('addressbook_china_area');
        return parent::queryRows($sql);
    }

    public function getCities($privCode,$priv_id){
        $sql = 'select * from '.getTable('addressbook_china_city').' where 1=1 ';
        if(is_valid($privCode)){
            $sql .=' and parent_code = '.to_db_str($privCode);
        }
        if(is_valid($priv_id)){
            $sql .=' and parent_code = ( select region_code from '.getTable('addressbook_china_province').' where id = '.to_db_int($priv_id).' )';
        }

        return parent::queryRows($sql);
    }
    public function getDists($cityCode,$cityId){
        $sql = 'select * from '.getTable('addressbook_china_district').' where 1 = 1 ';
        if(is_valid($cityCode)){
            $sql .=' and parent_code = '.to_db_str($cityCode);
        }
        if(is_valid($cityId)){
            $sql .=' and parent_code = ( select region_code from '.getTable('addressbook_china_city').' where id = '.to_db_int($cityId).' )';
        }
        return parent::queryRows($sql);
    }

    public function getFromWheres(){
        $sql = 'select * from '.getTable('fromwhere').' where status = '.to_db_str('1');
        return parent::queryRows($sql);
    }
}