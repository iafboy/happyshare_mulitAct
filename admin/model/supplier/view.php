<?php
class ModelSupplierView extends MyModel {

    public function getBanks(){
       return parent::getBanks();
    }

    public function querySupplier($supplier_id){
        $sql = 'select * from '.getTable('supplier').' where supplier_id = '.to_db_int($supplier_id);
        $supplier = parent::querySingleRow($sql);
        $supplier['images'] = $this->querySupplierImages($supplier_id);
        return $supplier;
    }

    public function querySupplierImages($supplier_id){
        $sql = 'select * from '.getTable('supplier_imgs').' where supplier_id = '.to_db_int($supplier_id)
            .' order by seq asc';
        return parent::queryRows($sql);
    }
    public function querySupplierImage($vis_id){
        $sql = 'select * from '.getTable('supplier_imgs').' where vis_id = '.to_db_int($vis_id);
        return parent::querySingleRow($sql);
    }

    public function setSupplierImage($vis_id,$file_path){
        $sql = 'update '.getTable('supplier_imgs').' set imgurl = '.to_db_str($file_path).' where vis_id = '.to_db_int($vis_id);
        return parent::wrapTransaction($sql);
    }
    public function addSupplierImage($supplier_id, $file_path,$seq){
        parent::startTransaction();
        try{
            $sql = 'insert into '.getTable('supplier_imgs').' ( `supplier_id`,`imgurl`,`seq` ) '
                .'values ( '.to_db_int($supplier_id).' , '.to_db_str($file_path).', '.to_db_int($seq).')';
            parent::executeSql($sql);
            $last_id = parent::getLastId();
        }catch (Exception $e){
            parent::rollbackTransaction();
        }
        parent::commitTransaction();
        return $last_id;
    }

    public function modSupplier($data){
        $sql = 'update '.getTable('supplier')
            .' set '
            .'supplier_name = '.to_db_str($data['supplier_name']).', '
            .'username = '.to_db_str($data['username']).', '
            .'bankid = '.to_db_str($data['bankid']).', '
            .'bankcard = '.to_db_str($data['bankcard']).', '
            .'can_edit_credit = '.to_db_int($data['can_edit_credit']).', '
            .'service_phone = '.to_db_str($data['service_phone']);
        if(is_valid($data['password'])){
            $sql .= ' , password = '.to_db_str($this->db->escape(md5($data['password'])));
        }
        $sql .=
            ' where supplier_id = '.$data['supplier_id'];
        return parent::wrapTransaction($sql);
    }

    public function del_image($supplierId,$seq){
        $sql = 'select * from '.getTable('supplier_imgs').' where supplier_id = '.to_db_int($supplierId).' and seq = '.to_db_int($seq);
        $row = parent::querySingleRow($sql);
        if(isset($row)){
            $sql = 'delete from '.getTable('supplier_imgs').' where supplier_id = '.to_db_int($supplierId).' and seq = '.to_db_int($seq);
            $success = parent::executeSql($sql);
            if($success){
                if(is_file(DIR_IMAGE.$row['imgurl'])){
                    FileUtil::unlinkFile(is_file(DIR_IMAGE.$row['imgurl']));
                }
                return ['success'=>true,'errMsg'=>''];
            }else{
                return ['success'=>false,'errMsg'=>'删除失败'];
            }
        }else{
            return ['success'=>false,'errMsg'=>'图片不存在'];
        }


    }

}