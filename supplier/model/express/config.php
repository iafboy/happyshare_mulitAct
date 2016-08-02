<?php

class ModelExpressConfig extends MyModel{

    public function getConfigById($config_id){
        $sql = 'select * from '.getTable('express_price').' where exppr_id = '.to_db_int($config_id);
        return parent::querySingleRow($sql);
    }
    public function getSetting(){
        $sql = 'select * from '.getTable('express_setting').' where supplier_id = '.to_db_int($this->session->data['supplier_id']);
        return parent::querySingleRow($sql);
    }
    public function getTemplates(){
        $sql = 'select a.*,b.place_name as fromwhere from '.getTable('express_template').' a, '
            .getTable('fromwhere').' b where a.fromwhere_id = b.fromwhere_id and a.supplier_id = '.to_db_int($this->session->data['supplier_id']);
        return parent::queryRows($sql);
    }
    public function getReturn(){
        $sql = 'select * from '.getTable('express_salesreturn').' where supplier_id = '.to_db_int($this->session->data['supplier_id']);
        return parent::querySingleRow($sql);
    }

    public function delTemplate($templateId){
        $sql = 'delete from '.getTable('express_template').' where express_template_id = '.to_db_int($templateId);
        $success = parent::executeSql($sql);
        return ['success'=>$success,'errMsg'=>'操作失败！'];
    }
    public function getAllConfigs(){
        $sql = 'select a.*,'
            .'(select place_name from '.getTable('fromwhere').' d where d.fromwhere_id = a.place_origin ) as fromwhere,'
            .'(select name from '.getTable('addressbook_china_province').' b where b.id = a.place_dest_prov ) as priv_name,'
            .'(select name from '.getTable('addressbook_china_city').' c where c.id = a.place_dest_city ) as city_name from '.getTable('express_price').' a where supplier_id = '.to_db_int($this->session->data['supplier_id']);
        return parent::queryRows($sql);
    }
    public function addConfig($data=array()){
        $sql = 'select count(1) as count from '.getTable('express_price')
            .' where place_origin = '.to_db_int($data['place_origin'])
            .' and charge_type = '.to_db_int($data['charge_type'])
            .' and place_dest_prov = (select id from '.getTable('addressbook_china_province').' where region_code = '.to_db_str($data['place_dest_prov']).' ) '
            .' and place_dest_city = (select id from '.getTable('addressbook_china_city').' where region_code = '.to_db_str($data['place_dest_city']).' ) ';
        if(parent::queryCount($sql) > 0){
            return ['success'=>false,'errMsg'=>'配置已存在'];
        }
        $sql = 'insert into '.getTable('express_price')
            .' set expco = '.to_db_int($data['expco']).','
            .'  charge_type = '.to_db_int($data['charge_type']).','
            .'  place_origin = '.to_db_int($data['place_origin']).','
            .'  place_dest_prov = (select id from '.getTable('addressbook_china_province').' where region_code = '.to_db_str($data['place_dest_prov']).') ,'
            .'  place_dest_city = (select id from '.getTable('addressbook_china_city').' where region_code = '.to_db_str($data['place_dest_city']).') ,';
            if(is_valid(to_db_int($data['weight_start_weight']))){
                $sql .=' weight_start_weight = '.(to_db_int($data['weight_start_weight'])).',';
            }
            if(is_valid(to_db_int($data['weight_start_price']))){
                $sql .=' weight_start_price = '.(to_db_int($data['weight_start_price'])).',';
            }
            if(is_valid(to_db_int($data['weight_add_weight']))){
                $sql .= '  weight_add_weight = '.(to_db_int($data['weight_add_weight'])).',';
            }
            if(is_valid(to_db_int($data['weight_add_price']))){
                $sql .= '  weight_add_price = '.(to_db_int($data['weight_add_price'])).',';
            }
            if(is_valid(to_db_int($data['piece_start_price']))){
                $sql .= '  piece_start_price = '.(to_db_int($data['piece_start_price'])).',';
            }
            if(is_valid(to_db_int($data['piece_add_price']))){
                $sql .= '  piece_add_price = '.(to_db_int($data['piece_add_price'])).',';
            }
            if(is_valid(to_db_int($data['volume_start_price']))){
                $sql .= '  volume_start_price = '.(to_db_int($data['volume_start_price'])).',';
            }
            $sql .= '  supplier_id = '.to_db_int($this->session->data['supplier_id']);
        $success = parent::executeSql($sql);
        return ['success'=>$success,'errMsg'=>'操作失败！'];
    }

    public function modConfig($data=array()){
        $sql = 'select count(1) as count from '.getTable('express_price')
            .' where place_origin = '.to_db_int($data['place_origin'])
            .' and charge_type = '.to_db_int($data['charge_type'])
            .' and place_dest_prov = (select id from '.getTable('addressbook_china_province').' where region_code = '.to_db_str($data['place_dest_prov']).' ) '
            .' and place_dest_city = (select id from '.getTable('addressbook_china_city').' where region_code = '.to_db_str($data['place_dest_city']).' ) '
            .' and exppr_id != '.to_db_int($data['exppr_id']);
        if(parent::queryCount($sql) > 0){
            return ['success'=>false,'errMsg'=>'配置已存在'];
        }
        $sql = 'update '.getTable('express_price')
            .' set expco = '.to_db_int($data['expco']).','
            .'  charge_type = '.to_db_int($data['charge_type']).','
            .'  place_origin = '.to_db_int($data['place_origin']).','
            .'  place_dest_prov = (select id from '.getTable('addressbook_china_province').' where region_code = '.to_db_str($data['place_dest_prov']).') ,'
            .'  place_dest_city = (select id from '.getTable('addressbook_china_city').' where region_code = '.to_db_str($data['place_dest_city']).') ,';
            if(is_valid(to_db_int($data['weight_start_weight']))){
                $sql .=' weight_start_weight = '.(to_db_int($data['weight_start_weight'])).',';
            }else{
                $sql .=' weight_start_weight = 0 ,';
            }
            if(is_valid(to_db_int($data['weight_start_price']))){
                $sql .=' weight_start_price = '.(to_db_int($data['weight_start_price'])).',';
            }else{
                $sql .=' weight_start_price = 0 ,';
            }
            if(is_valid(to_db_int($data['weight_add_weight']))){
                $sql .= '  weight_add_weight = '.(to_db_int($data['weight_add_weight'])).',';
            }else{
                $sql .= '  weight_add_weight = 0 ,';
            }
            if(is_valid(to_db_int($data['weight_add_price']))){
                $sql .= '  weight_add_price = '.(to_db_int($data['weight_add_price'])).',';
            }else{
                $sql .= '  weight_add_price = 0 ,';
            }
            if(is_valid(to_db_int($data['piece_start_price']))){
                $sql .= '  piece_start_price = '.(to_db_int($data['piece_start_price'])).',';
            }else{
                $sql .= '  piece_start_price = 0 ,';
            }
            if(is_valid(to_db_int($data['piece_add_price']))){
                $sql .= '  piece_add_price = '.(to_db_int($data['piece_add_price'])).',';
            }else{
                $sql .= '  piece_add_price = 0 ,';
            }
            if(is_valid(to_db_int($data['volume_start_price']))){
                $sql .= '  volume_start_price = '.(to_db_int($data['volume_start_price'])).',';
            }else{
                $sql .= '  volume_start_price = 0 ,';
            }
            $sql = substr($sql,0,strlen($sql) - 1);
            $sql .= ' where exppr_id = '.to_db_int($data['exppr_id']);
        $success = parent::executeSql($sql);
        return ['success'=>$success,'errMsg'=>'操作失败！'];
    }

    public function saveOrModReturn($data=array()){
        $sql = 'select count(1) as count from '.getTable('express_salesreturn').' where supplier_id = '.to_db_int($this->session->data['supplier_id']);
        $count = parent::queryCount($sql);
        if($count >0){
            $sql = 'update ';
        }else{
            $sql = 'insert into ';
        }
        $sql .= getTable('express_salesreturn')
            .' set supplier_id = '.to_db_int($this->session->data['supplier_id']).',';
        if(is_valid(to_db_str($data['name']))){
            $sql .= '  name = '.(to_db_str($data['name'])).',';
        }else{
            $sql .= '  name = '.to_db_str('').' ,';
        }
        if(is_valid(to_db_str($data['telephone']))){
            $sql .= '  telephone = '.(to_db_str($data['telephone'])).',';
        }else{
            $sql .= '  telephone = '.to_db_str('').' ,';
        }
        if(is_valid(to_db_str($data['addr_info']))){
            $sql .= '  addr_info = '.(to_db_str($data['addr_info'])).',';
        }else{
            $sql .= '  addr_info = '.to_db_str('').' ,';
        }
        $sql .= ' addr_prov = (select id from '.getTable('addressbook_china_province').' where region_code = '.to_db_str($data['addr_prov']).') ,';
        $sql .= ' addr_city = (select id from '.getTable('addressbook_china_city').' where region_code = '.to_db_str($data['addr_city']).') ,';
        $sql .= ' addr_dist = (select id from '.getTable('addressbook_china_district').' where region_code = '.to_db_str($data['addr_dist']).') ';
        if($count > 0){
            $sql .= ' where supplier_id = '.to_db_int($this->session->data['supplier_id']);
        }
        $success = parent::executeSql($sql);
        return ['success'=>$success,'errMsg'=>'操作失败！'];
    }
    public function saveOrModSetting($data=array()){
        $sql = 'select count(1) as count from '.getTable('express_setting').' where supplier_id = '.to_db_int($this->session->data['supplier_id']);
        $count = parent::queryCount($sql);
        if($count >0){
            $sql = 'update ';
        }else{
            $sql = 'insert into ';
        }
        $sql .= getTable('express_setting')
            .' set supplier_id = '.to_db_int($this->session->data['supplier_id']).','
            .' expressname = '.to_db_str('').','
            .' startweight = '.to_db_int(0).','
            .' startprice = '.to_db_int(0).','
            .' order_charge = '.to_db_int(1).','
        ;
        if(is_valid(to_db_int($data['free_shipping']))){
            $sql .= '  free_shipping = '.(to_db_int($data['free_shipping'])).',';
        }else{
            $sql .= '  free_shipping = '.to_db_int('').' ,';
        }
        if(is_valid(to_db_int($data['free_tax_min']))){
            $sql .= '  free_tax_min = '.(to_db_int($data['free_tax_min'])).',';
        }else{
            $sql .= '  free_tax_min = '.to_db_int('').' ,';
        }
        if(is_valid(to_db_int($data['free_tax_max']))){
            $sql .= '  free_tax_max = '.(to_db_int($data['free_tax_max'])).',';
        }else{
            $sql .= '  free_tax_max = '.to_db_int('').' ,';
        }
        $sql = substr($sql,0,strlen($sql) - 1);
        if($count > 0){
            $sql .= ' where supplier_id = '.to_db_int($this->session->data['supplier_id']);
        }
        $success = parent::executeSql($sql);
        return ['success'=>$success,'errMsg'=>'操作失败！'];
    }

    public function setTemplateStatus($templateId,$status)
    {
        //set others to be inactive
        if ($status == 1) {
            $sql = 'update '.getTable('express_template').' set status = 0 where supplier_id = '.to_db_int($this->session->data['supplier_id']);
            $success = parent::executeSql($sql);
            return ['success'=>$success,'errMsg'=>'禁用其他规则失败！'];
        }
        $sql = 'update '.getTable('express_template').' set status = '.to_db_int($status).' where express_template_id = '.to_db_int($templateId);
        $success = parent::executeSql($sql);
        return ['success'=>$success,'errMsg'=>'操作失败！'];
    }

}
