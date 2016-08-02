<?php
class ModelSupplierGallery extends MyModel {

    public function getSupplier($supplier_id){
        $sql = 'select a.*,c.bg_name as `name`,c.bg_intro,c.img as imgurl from '.getTable('supplier').' a '
            .'left join '.getTable('supplier_to_brand').' b on a.supplier_id = b.supplier_id '
            .'left join '.getTable('brandgroup').' c on b.brand_id = c.bg_id where a.supplier_id = '.to_db_int($supplier_id)
        ;
        return parent::querySingleRow($sql);
    }

    public function saveOrUpdateBrand($data){

        $sql = 'select count(1) as count from '.getTable('supplier_to_brand')
            .' a, '.getTable('brandgroup').' b '
            .' where a.brand_id = b.bg_id and a.supplier_id = '.to_db_int($data['supplier_id']);
        $count = parent::queryCount($sql);
        if($count==0){
            if(!is_valid($data['desc'])){
                $data['desc'] = '';
            }
            if(!is_valid($data['imgurl'])){
                $data['imgurl'] = '';
            }
            if(!is_valid($data['status'])){
                $data['status'] = 0;
            }
            $sql = 'insert into '.getTable('brandgroup').'(`bg_name`,`bg_intro`,`status`,`img`)'
                .'values ('
                .to_db_str($data['name']).' ,'
                .to_db_str($data['desc']).' ,'
                .to_db_int($data['status']).' ,'
                .to_db_str($data['imgurl'])
                .' ) ';
            parent::startTransaction();
            try{
                $success = parent::executeSql($sql);
                if(!$success){
                    throw new Exception('');
                }
                $brand_id = parent::getLastId();
                $sql = 'insert into '.getTable('supplier_to_brand').' (`supplier_id`,`brand_id`) values '
                    .'('.to_db_int($data['supplier_id']).','.to_db_int($brand_id).')';
                $success = parent::executeSql($sql);
                if(!$success){
                    throw new Exception('');
                }
                $sql = 'update '.getTable('supplier').' set own_brand = '.to_db_int($data['status']).' where supplier_id = '.to_db_int($data['supplier_id']);
                $success = parent::executeSql($sql);
                if(!$success){
                    throw new Exception('');
                }
            }catch (Exception $e){
                parent::rollbackTransaction();
                return false;
            }
            parent::commitTransaction();
        }else if($count ==1){

            try {
                $sql = 'update ' . getTable('brandgroup') . ' a set bg_name = ' . to_db_str($data['name']);
                if (is_valid($data['desc'])) {
                    $sql .= ' , `bg_intro` = ' . to_db_str($data['desc']);
                }
                if (is_valid($data['status'])) {
                    $sql .= ' , `status` = ' . to_db_int($data['status']);
                }
                if (is_valid($data['imgurl'])) {
                    $sql .= ' , `img` = ' . to_db_str($data['imgurl']);
                }
                $sql .= ' where a.bg_id in (select c.brand_id from '
                    . getTable('supplier_to_brand') . ' c where c.supplier_id = ' . to_db_int($data['supplier_id']) . ') ';
                $success = parent::executeSql($sql);
                if (!$success) {
                    throw new Exception('');
                }
                $sql = 'update ' . getTable('supplier') . ' set own_brand = ' . to_db_int($data['status']) . ' where supplier_id = ' . to_db_int($data['supplier_id']);
                $success = parent::executeSql($sql);
                if (!$success) {
                    throw new Exception('');
                }
            } catch (Exception $e) {
                parent::rollbackTransaction();
                return false;
            }
            parent::commitTransaction();
        }

        return true;
    }


}