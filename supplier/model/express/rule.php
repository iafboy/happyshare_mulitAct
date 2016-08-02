<?php

class ModelExpressRule extends MyModel{

    public function addTemplate($data=array()){
        $sql = 'insert into '.getTable('express_template')
            .' set express_template_name = '.to_db_str($data['express_template_name']).' ,'
            .'  status = '.to_db_int(0).','
            .'  express_mode = '.to_db_int($data['charge_type']).' ,'
            .'  baoyou_type = '.to_db_int($data['baoyou_type']).' ,'
            .'  default_unit = '.to_db_int($data['defaultUnit']).' ,'
            .'  default_price = '.to_db_int($data['defaultPrice']).' ,'
            .'  default_add_unit = '.to_db_int($data['defaultAddUnit']).' ,'
            .'  default_add_price = '.to_db_int($data['defaultAddPrice']).' ,'
            .'  supplier_id = '.to_db_int($this->session->data['supplier_id']).' ,'
            .'  fromwhere_id = '.to_db_int($data['fromwhere_id'])
        ;
        parent::startTransaction();
        try{
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('');
            }
            $templateId = parent::getLastId();
            if(!isset($templateId)){
                throw new Exception('');
            }
            $seqs = $data['seqs'];
            if(is_valid($seqs)){
                $seqArray = explode(',',$seqs);
                foreach($seqArray as $seq){
                    $sql = 'insert into '.getTable('express_rule')
                        .' set unit = '.to_db_int($data['unit_'.$seq]).' ,'
                        .'  price = '.to_db_int($data['price_'.$seq]).' ,'
                        .'  add_unit = '.to_db_int($data['addUnit_'.$seq]).' ,'
                        .'  add_price = '.to_db_int($data['addPrice_'.$seq]).' ,'
                        .'  template_id = '.to_db_int($templateId).' ,'
                        .'  seq = '.to_db_int($seq);
                    $success = parent::executeSql($sql);
                    if(!$success){
                        throw new Exception('');
                    }
                    $ruleId = parent::getLastId();
                    if(!isset($ruleId)){
                        throw new Exception('');
                    }
                    $cities = $data['cities_'.$seq];
                    $privs = $data['privs_'.$seq];
                    $areas = $data['areas_'.$seq];
                    if(is_valid($cities)){
                        $cityArray = explode(',',$cities);
                        foreach($cityArray as $city){
                            $sql = 'insert into '.getTable('express_district')
                                .' set district_type = '.to_db_int(2).' ,'
                                .'  relate_name = (select name from '.getTable('addressbook_china_city').' where region_code = '.to_db_str($city).' ) ,'
                                .'  relate_code = '.to_db_str($city).' ,'
                                .'  rule_id = '.to_db_int($ruleId);
                            $success = parent::executeSql($sql);
                            if(!$success){
                                throw new Exception('');
                            }
                        }
                    }
                    if(is_valid($privs)){
                        $privArray = explode(',',$privs);
                        foreach($privArray as $priv){
                            $sql = 'insert into '.getTable('express_district')
                                .' set district_type = '.to_db_int(1).' ,'
                                .'  relate_name = (select name from '.getTable('addressbook_china_province').' where region_code = '.to_db_str($priv).' ) ,'
                                .'  relate_code = '.to_db_str($priv).' ,'
                                .'  rule_id = '.to_db_int($ruleId);
                            $success = parent::executeSql($sql);
                            if(!$success){
                                throw new Exception('');
                            }
                        }

                    }
                    if(is_valid($areas)){
                        $areaArray = explode(',',$areas);
                        foreach($areaArray as $area){
                            $sql = 'insert into '.getTable('express_district')
                                .' set district_type = '.to_db_int(3).' ,'
                                .'  relate_name = (select name from '.getTable('addressbook_china_area').' where region_code = '.to_db_str($area).' ) ,'
                                .'  relate_code = '.to_db_str($area).' ,'
                                .'  rule_id = '.to_db_int($ruleId);
                            $success = parent::executeSql($sql);
                            if(!$success){
                                throw new Exception('');
                            }
                        }
                    }
                }
            }

            $success = true;
        }catch (Exception $e){
            parent::rollbackTransaction();
            $success = false;
        }
        if($success){
            parent::commitTransaction();
        }else{
            parent::rollbackTransaction();
        }
        return ['success'=>$success,'errMsg'=>'操作失败！'];
    }
    public function modTemplate($data=array()){
        $templateId = $data['template_id'];

        $sql = 'update '.getTable('express_template')
            .' set express_template_name = '.to_db_str($data['express_template_name']).' ,'
            .'  express_mode = '.to_db_int($data['charge_type']).' ,'
            .'  baoyou_type = '.to_db_int($data['baoyou_type']).' ,'
            .'  default_unit = '.to_db_int($data['defaultUnit']).' ,'
            .'  default_price = '.to_db_int($data['defaultPrice']).' ,'
            .'  default_add_unit = '.to_db_int($data['defaultAddUnit']).' ,'
            .'  default_add_price = '.to_db_int($data['defaultAddPrice']).' ,'
            .'  fromwhere_id = '.to_db_int($data['fromwhere_id'])
            .' where express_template_id = '.to_db_int($templateId)
        ;
        parent::startTransaction();
        try{
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('');
            }
            $sql = 'delete from '.getTable('express_district').' where rule_id in ( select rule_id from '.getTable('express_rule').' where template_id  = '.to_db_int($templateId).' ) ';
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('');
            }
            $sql = 'delete from '.getTable('express_rule').' where template_id = '.to_db_int($templateId);
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('');
            }
            $seqs = $data['seqs'];
            if(is_valid($seqs)){
                $seqArray = explode(',',$seqs);
                foreach($seqArray as $seq){
                    $sql = 'insert into '.getTable('express_rule')
                        .' set unit = '.to_db_int($data['unit_'.$seq]).' ,'
                        .'  price = '.to_db_int($data['price_'.$seq]).' ,'
                        .'  add_unit = '.to_db_int($data['addUnit_'.$seq]).' ,'
                        .'  add_price = '.to_db_int($data['addPrice_'.$seq]).' ,'
                        .'  template_id = '.to_db_int($templateId).' ,'
                        .'  seq = '.to_db_int($seq);
                    $success = parent::executeSql($sql);
                    if(!$success){
                        throw new Exception('');
                    }
                    $ruleId = parent::getLastId();
                    if(!isset($ruleId)){
                        throw new Exception('');
                    }
                    $cities = $data['cities_'.$seq];
                    $privs = $data['privs_'.$seq];
                    $areas = $data['areas_'.$seq];
                    if(is_valid($cities)){
                        $cityArray = explode(',',$cities);
                        foreach($cityArray as $city){
                            $sql = 'insert into '.getTable('express_district')
                                .' set district_type = '.to_db_int(2).' ,'
                                .'  relate_name = (select name from '.getTable('addressbook_china_city').' where region_code = '.to_db_str($city).' ) ,'
                                .'  relate_code = '.to_db_str($city).' ,'
                                .'  rule_id = '.to_db_int($ruleId);
                            $success = parent::executeSql($sql);
                            if(!$success){
                                throw new Exception('');
                            }
                        }
                    }
                    if(is_valid($privs)){
                        $privArray = explode(',',$privs);
                        foreach($privArray as $priv){
                            $sql = 'insert into '.getTable('express_district')
                                .' set district_type = '.to_db_int(1).' ,'
                                .'  relate_name = (select name from '.getTable('addressbook_china_province').' where region_code = '.to_db_str($priv).' ) ,'
                                .'  relate_code = '.to_db_str($priv).' ,'
                                .'  rule_id = '.to_db_int($ruleId);
                            $success = parent::executeSql($sql);
                            if(!$success){
                                throw new Exception('');
                            }
                        }

                    }
                    if(is_valid($areas)){
                        $areaArray = explode(',',$areas);
                        foreach($areaArray as $area){
                            $sql = 'insert into '.getTable('express_district')
                                .' set district_type = '.to_db_int(3).' ,'
                                .'  relate_name = (select name from '.getTable('addressbook_china_area').' where region_code = '.to_db_str($area).' ) ,'
                                .'  relate_code = '.to_db_str($area).' ,'
                                .'  rule_id = '.to_db_int($ruleId);
                            $success = parent::executeSql($sql);
                            if(!$success){
                                throw new Exception('');
                            }
                        }
                    }
                }
            }

            $success = true;
        }catch (Exception $e){
            parent::rollbackTransaction();
            $success = false;
        }
        if($success){
            parent::commitTransaction();
        }else{
            parent::rollbackTransaction();
        }
        return ['success'=>$success,'errMsg'=>'操作失败！'];
    }

    public function queryTemplateById($templateId){
        $sql = 'select a.* from '.getTable('express_template').' a where a.express_template_id = '.to_db_int($templateId);
        $template = parent::querySingleRow($sql);
        $sql = 'select * from '.getTable('express_rule').' where template_id = '.to_db_int($templateId);
        $rules = parent::queryRows($sql);
        foreach($rules as &$rule){
            $privs = [];
            $areas = [];
            $cities = [];
            $sql = 'select * from '.getTable('express_district').' where rule_id = '.to_db_int($rule['rule_id']);
            $dists = parent::queryRows($sql);
            foreach($dists as $dist){
                if($dist['district_type'] == 1){
                    $privs[] = $dist['relate_code'];
                }
                else if($dist['district_type'] == 2){
                    $cities[] = $dist['relate_code'];
                }
                else if($dist['district_type'] == 3){
                    $areas[] = $dist['relate_code'];
                }
            }
            $rule['privs'] = stringfyArray($privs);
            $rule['areas'] = stringfyArray($areas);
            $rule['cities'] = stringfyArray($cities);
        }
        $template['rules'] = $rules;
        return $template;
    }

}
