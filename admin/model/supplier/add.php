<?php
class ModelSupplierAdd extends MyModel {

    public function getBanks(){
       return parent::getBanks();
    }

    public function getSupplierNo(){
        //return parent::queryNextNo('supplier');
        $date = new DateTime();
        $tp=$date->getTimestamp();
        $length=4;
        $pattern = '1234567890';
        $key='';
        for($i=0;$i<$length;$i++)
        {
            $key .= $pattern{mt_rand(0,9)};    //生成php随机数
        }
        return 'SP'.$tp.$key;
    }

    public function addSupplier($data=array()){
        parent::startTransaction();
        $this->load->language('default');
        $supplier_no = $this->getSupplierNo();
        $pwd='123456';
        if(isset($data['password'])){
            $pwd=$this->db->escape(md5($data['password']));
        }
        $sql = 'insert into '.getTable('supplier')
            .' (`status`,`supplier_no`,`salt`,`supplier_name`,`username`,`password`,`bankid`,`bankcard`,`service_phone`,`can_edit_credit`) values '
            .'( '
            .to_db_int(1).' ,'
            .to_db_str($supplier_no).' ,'
            .to_db_str($this->language->default_permission_salt).' ,'
            .to_db_str($data['supplier_name']).' ,'
            .to_db_str($data['username']).' ,'
            .to_db_str($pwd).' ,'
            .to_db_str($data['bankid']).' ,'
            .to_db_str($data['bankcard']).' ,'
            .to_db_str($data['service_phone']).' ,'
            .to_db_str($data['can_edit_credit']).' '
            .' )';
        try{
            if(parent::executeSql($sql)!=true){
                throw new Exception('用户信息重复');
            }

            $supplier_id = parent::getLastId();

            $sql = 'select * from '.getTable('supplier_reg').' where supplier_reg_id = '.to_db_int($data['supplier_reg_id']);
            $supplierreg = parent::querySingleRow($sql);
            if(!isset($supplierreg)){
                throw new Exception('注册记录不存在！');
            }
            if($supplierreg['is_registered'] == 1){
                throw new Exception('账号已创建！');
            }
            $sql = 'update '.getTable('supplier_reg').' set is_registered = 1 ,approve_status = 2 where supplier_reg_id = '.to_db_int($data['supplier_reg_id']);
            if(parent::executeSql($sql)!=true){
                throw new Exception('');
            }
            $sql = 'update '.getTable('supplier').' set '
                .' supplier_company = '.to_db_str($supplierreg['supplier_company']).' , '
                .' company_contacter = '.to_db_str($supplierreg['company_contacter']).' , '
                .' company_contacter_phone = '.to_db_str($supplierreg['company_contacter_phone']).' , '
                .' company_address = '.to_db_str($supplierreg['prov'].$supplierreg['city'].$supplierreg['distic'].$supplierreg['street']).' , '
                .' prov = '.to_db_str($supplierreg['prov']).' , '
                .' city = '.to_db_str($supplierreg['city']).' , '
                .' distic = '.to_db_str($supplierreg['distic']).' , '
                .' street = '.to_db_str($supplierreg['street']).' '
                .' where supplier_id = '.to_db_int($supplier_id);
            if(parent::executeSql($sql)!=true){
                throw new Exception('');
            }
            $imgs = $data['images'];
            $i = 0;

            $success = true;
            foreach($imgs as $img){
                $i++;
                $new_path = DIR_IMAGE.'supplier/desc/'.str_replace('temp','',basename($img));
                $store_path = 'supplier/desc/'.str_replace('temp','',basename($img));
                if(is_file($new_path)){
                    FileUtil::unlinkFile($new_path);
                }
                if(FileUtil::moveFile(DIR_IMAGE.$img,$new_path)){
                    $sql2 = 'insert into '.getTable('supplier_imgs').' ( `supplier_id`,`imgurl`,`seq` ) '
                        .'values ( '.to_db_int($supplier_id).' , '.to_db_str($store_path).', '.to_db_int($i).')';
                    parent::executeSql($sql2);
                }else{
                    $success = false;
                }
            }
            if(!$success){
                throw new Exception('');
            }
        }catch (Exception $e){
            parent::rollbackTransaction();
            return $e->getMessage();
        }
        parent::commitTransaction();
        return true;
    }

}