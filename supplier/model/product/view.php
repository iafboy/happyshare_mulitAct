<?php
class ModelProductView extends MyModel {

    public function queryProduct($product_id){
        $sql = 'select distinct a.*, b.name as product_name,'
            .' c.supplier_name as supplier_name, d.place_name,e.type_name as product_type from '
            .getTable('product').' a '
            .' inner join '.getTable('product_description').' b on a.product_id = b.product_id '
            .' inner join '.getTable('supplier').' c on a.supplier_id = c.supplier_id '
            .' inner join '.getTable('origin_place').' d on a.origin_place_id = d.origin_place_id '
            .' inner join '.getTable('product_type').' e on a.product_type_id = e.product_type_id '
            .' where a.product_id = '.to_db_int($product_id);
        $product = parent::querySingleRow($sql);
        $product['sub_images'] = $this->queryProductSubImages($product_id);
        $product['share_cases'] = $this->queryProductShareCases($product_id);
        return $product;
    }
    public function queryMainImage($product_id){
        $sql = 'select image from '.getTable('product').' where product_id = '.to_db_int($product_id);
        $p = parent::querySingleRow($sql);
        if(isset($p) && !empty($p)){
            return $p['image'];
        }
        return '';
    }
    public function querySubImage($product_id,$seq){
        $sql = 'select a.image from '
            .getTable('product_image').' a '
            .' where a.product_id = '.to_db_int($product_id)
            .' and a.sort_order = '.to_db_int($seq);
        $p = parent::querySingleRow($sql);
        if(isset($p) && !empty($p)){
            return $p['image'];
        }
        return '';
    }
    public function delSubImage($product_id,$sort_order){
        $sql = 'select a.image from '
            .getTable('product_image').' a '
            .' where a.product_id = '.to_db_int($product_id)
            .' and sort_order = '.to_db_int($sort_order);
        $image = parent::querySingleRow($sql);
        $sql = 'delete from '.getTable('product_image')
            .' where product_id = '.to_db_int($product_id)
            .' and sort_order = '.to_db_int($sort_order);
        try{
            parent::startTransaction();
            $success = parent::executeSql($sql);
        }catch (Exception $e){
            parent::rollbackTransaction();
            $success = false;
        }
        if($success){
            parent::commitTransaction();
        }
        if(!$success){
            return ['success'=>false,'errMsg'=>'删除失败！'];
        }
        if(isset($image)){
            if(file_exists(DIR_IMAGE.$image['image'])){
                FileUtil::unlinkFile(DIR_IMAGE.$image['image']);
            }
        }
        return ['success'=>true,'errMsg'=>''];
    }
    public function delShareCase($product_id,$seq){
        $sql = 'select a* from '
            .getTable('product_share').' a '
            .' where a.product_id = '.to_db_int($product_id)
            .' and seq = '.to_db_int($seq);
        $share = parent::querySingleRow($sql);
        $sql = 'delete from '.getTable('product_share')
            .' where product_id = '.to_db_int($product_id)
            .' and seq = '.to_db_int($seq);
        try{
            parent::startTransaction();
            $success = parent::executeSql($sql);
        }catch (Exception $e){
            parent::rollbackTransaction();
            $success = false;
        }
        if($success){
            parent::commitTransaction();
        }
        if(!$success){
            return ['success'=>false,'errMsg'=>'删除失败！'];
        }
        if(isset($share)){
            foreach([1,2,3,4,5,6,7,8,9] as $i){
                if(isset($share['imgurl'.$i])){
                    if(file_exists(DIR_IMAGE.$share['imgurl'.$i])){
                        FileUtil::unlinkFile(DIR_IMAGE.$share['imgurl'.$i]);
                    }
                }
            }
        }
        return ['success'=>true,'errMsg'=>''];
    }

    public function queryProductShareCases($product_id){
        $sql = 'select b.* from '
            .getTable('product').' a , '
            .getTable('product_share').' b '
            .' where a.product_id = '.to_db_int($product_id).' and a.product_id = b.product_id order by seq asc';
        return parent::queryRows($sql);
    }


    public function queryProductSubImages($product_id){
        $sql = 'select a.image,a.sort_order from '
            .getTable('product_image').' a '
            .' where a.product_id = '.to_db_int($product_id)
            .' order by sort_order asc';
        return parent::queryRows($sql);
    }
    public function queryCaseImage($product_id,$case_index,$image_index){
        if(!in_array($image_index,[1,2,3,4,5,6,7,8,9])){
            return '';
        }
        $sql = 'select a.imgurl'.$image_index.'  as image  from '
            .getTable('product_share').' a '
            .' where a.product_id = '.to_db_int($product_id)
            .' and a.seq = '.to_db_int($case_index);
        $p = parent::querySingleRow($sql);
        if(isset($p) && !empty($p)){
            return $p['image'];
        }
        return '';
    }
    public function setProductImage($product_id,$main_image){
        $sql = 'update '.getTable('product').' set image = '.to_db_str($main_image).' where product_id = '.to_db_int($product_id);
        return parent::wrapTransaction($sql);
    }
    public function setProductSubImage($product_id,$seq,$sub_image){
        $sql = 'select count(1) as count from '
            .getTable('product_image').' a '
            .' where a.product_id = '.to_db_int($product_id)
            .' and a.sort_order = '.to_db_int($seq);
        $count = parent::queryCount($sql);
        if($count == 0){
            $sql_ = ' insert into '.getTable('product_image').' (`product_id`,`image`,`sort_order`) values ('
                .to_db_int($product_id).' , '
                .to_db_str($sub_image).' , '
                .to_db_int($seq).' '
                .') ';
        }else if($count==1){
            $sql_ = 'update '.getTable('product_image').' a set a.image = '.to_db_str($sub_image)
                .' where a.product_id = '.to_db_int($product_id)
                .' and a.sort_order = '.to_db_int($seq);
        }else{
            return false;
        }
        return parent::wrapTransaction($sql_);
    }
    public function setCaseImage($product_id,$case_index,$image_index,$case_image){
        $sql = 'select count(1) as count from '
            .getTable('product_share').' a '
            .' where a.product_id = '.to_db_int($product_id)
            .' and a.seq = '.to_db_int($case_index);
        $count = parent::queryCount($sql);
        if($count == 0){
            $sql_ = ' insert into '.getTable('product_share').' (`product_id`,`imgurl'.$image_index.'`,`seq`) values ('
                .to_db_int($product_id).' , '
                .to_db_str($case_image).' , '
                .to_db_int($case_index).' '
                .') ';
        }else if($count==1){
            $sql_ = 'update '.getTable('product_share').' a set a.imgurl'.$image_index.' = '.to_db_str($case_image)
                .' where a.product_id = '.to_db_int($product_id)
                .' and a.seq = '.to_db_int($case_index);
        }else{
            return false;
        }
        return parent::wrapTransaction($sql_);
    }

    public function saveProduct($data=array()){
        $sqls = array();
        $sql = 'update '.getTable('product').' set product_id = product_id ,';
        if(is_valid($data['product_type'])){
            $sql .= ' product_type_id = '.to_db_int($data['product_type']).' ,';
        }
        if(is_valid($data['origin_place_id'])){
            $sql .= ' origin_place_id = '.to_db_int($data['origin_place_id']).' ,';
        }
        if(is_valid($data['fromwhere_id'])){
            $sql .= ' fromwhere = '.to_db_int($data['fromwhere_id']).' ,';
        }
        if(is_valid($data['charge_type'])){
            $sql .= ' charge_type = '.to_db_int($data['charge_type']).' ,';
        }
        if(is_valid($data['quantity'])){
            $sql .= ' quantity = '.to_db_int($data['quantity']).' ,';
        }
        if(is_valid($data['weight'])){
            $sql .= ' weight = '.to_db_int($data['weight']).' ,';
        }
        if(is_valid($data['volume'])){
            $sql .= ' volume = '.to_db_int($data['volume']).' ,';
        }
        if(is_valid($data['market_price'])){
            $sql .= ' market_price = '.to_db_int($data['market_price']).' ,';
        }
        if(is_valid($data['store_price'])){
            $sql .= ' storeprice = '.to_db_int($data['store_price']).' ,';
        }
        if(is_valid($data['shareLevel'])){
            $sql .= ' shareLevel = '.to_db_int($data['shareLevel']).' ,';
        }
        if(is_valid($data['return_limit'])){
            $sql .= ' return_limit = '.to_db_int($data['return_limit']).' ,';
        }
        if(is_valid($data['credit_percent'])){
            $sql .= ' credit_percent = '.to_db_int($data['credit_percent']).' ,';
        }
        if(is_valid($data['interest_price'])){
            $sql .= ' interest_price = '.to_db_int($data['interest_price']).' ,';
        }
        if(is_valid($data['status']) || $data['status'] == 0){
            $sql .= ' status = '.to_db_int($data['status']).' ,';
        }
        $sql = substr($sql,0,strlen($sql)-1);
        $sql .= ' where product_id = '.to_db_int($data['product_id']);
        $sqls[] = $sql;
        $sql = 'update '.getTable('product').' set price = storeprice - interest_price where product_id = '.to_db_int($data['product_id']);
        $sqls[] = $sql;
        if(is_valid($data['product_name'])){
            $sql2 = 'update '.getTable('product_description').' set name = '.to_db_str($data['product_name'])
                .' where product_id = '.to_db_int($data['product_id']);
            $sqls[] = $sql2;
        }
        if(is_valid($data['cases'])){
            $cases = explode(',',$data['cases']);
            foreach($cases as $i){
                $countSql = 'select count(1) as count from '.getTable('product_share')
                    .' where product_id = '.to_db_int($data['product_id'])
                    .'and seq = '.to_db_int($i);
                $count = parent::queryCount($countSql);
                if($count == 1){
                    $sql3 = 'update '.getTable('product_share')
                        .' set title = '.to_db_str($data['case_'.$i.'_title']).', '
                        .'  memo = '.to_db_str($data['case_'.$i.'_memo']).', '
                        .'  audit = '.to_db_str($data['case_'.$i.'_audit']).' '
                        .' where product_id = '.to_db_int($data['product_id'])
                        .'and seq = '.to_db_int($i);
                }else if($count == 0){
                    $sql3 =
                        'insert '.getTable('product_share')
                        .' (`title`,`memo`,`audit`,`seq`,`product_id`) '
                        .' values ('
                        .to_db_str($data['case_'.$i.'_title']).','
                        .to_db_str($data['case_'.$i.'_memo']).','
                        .to_db_str($data['case_'.$i.'_audit']).','
                        .to_db_int($i).','
                        .to_db_int($data['product_id']).''
                        .') ';
                }
                $sqls[] = $sql3;
            }
        }
        parent::addTransactionBatch($sqls);
        return parent::executeTransactionBatch();
    }

    public function getParentSupplier($supplier_id){
        $sql = "SELECT parent_id FROM " . DB_PREFIX . "supplier WHERE supplier_id =". $supplier_id;

        $query = $this->db->query($sql);

        return ($query->rows[0]['parent_id'] == null)?$supplier_id:$query->rows[0]['parent_id'];
    }

    public function addProduct($data=array()){
        $supplier_id = getParentSupplier($this->session->data['supplier_id']);

        $sql = 'insert '.getTable('product')
            .' (`product_no`,`supplier_id`,`quantity`,`image`,`price`,`market_price`,`storeprice`,`interest_price`,`credit_percent`,`weight`,`volume`,'
            .'`date_added`,`fromwhere`,`refoundlimit`,`status`,`date_modified`,`shareLevel`,`product_type_id`,`origin_place_id`,`charge_type`,`tax_charge`,`return_limit`) '
            .' values ( '
            .to_db_str($data['product_no']).' , '
            .to_db_int($supplier_id).' , '
            .to_db_str($data['quantity']).' , '
            .to_db_str($data['main_image']).' , '
            .to_db_str($data['storeprice'] - $data['interest_price']).' , '
            .to_db_str($data['market_price']).' , '
            .to_db_str($data['store_price']).' , '
            .to_db_str($data['interest_price']).' , '
            .to_db_str($data['credit_percent']).' , '
            .to_db_str($data['weight']).' , '
            .to_db_str($data['volume']).' , '
            .to_db_int('now()').' , '
            .to_db_str($data['fromwhere_id']).' , '
            .to_db_str($data['return_limit']).' , '
            .to_db_str(0).' , '
            .to_db_str('now()').' , '
            .to_db_str($data['shareLevel']).' , '
            .to_db_str($data['product_type']).' , '
            .to_db_str($data['origin_place_id']).' , '
            .to_db_str($data['charge_type']).' , '
            .to_db_str(0).' , '
            .to_db_str($data['return_limit']).' '
            .')';
            parent::startTransaction();
        try{
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('');
            }else{
                $product_id = parent::getLastId();
            }
            $sql = 'insert '.getTable('product_description').' (`product_id`,`language_id`,`name`)'
                .' values ( '
                .to_db_str($product_id).' , '
                .to_db_str(1).' , '
                .to_db_str($data['product_name']).'  '
                .')';
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('');
            }
            if(is_valid($data['cases'])){
                $cases = explode(',',$data['cases']);
                foreach($cases as $i){
                    $countSql = 'select count(1) as count from '.getTable('product_share')
                        .' where product_id = '.to_db_int($data['product_id'])
                        .'and seq = '.to_db_int($i);
                    $count = parent::queryCount($countSql);
                    if($count == 1){
                        $sql = 'update '.getTable('product_share')
                            .' set title = '.to_db_str($data['case_'.$i.'_title']).', '
                            .'  memo = '.to_db_str($data['case_'.$i.'_memo']).', '
                            .'  audit = '.to_db_str($data['case_'.$i.'_audit']).' '
                            .' where product_id = '.to_db_int($data['product_id'])
                            .'and seq = '.to_db_int($i);
                        $success = parent::executeSql($sql);
                        if(!$success){
                            throw new Exception('');
                        }
                    }else if($count == 0){
                        $sql =
                            'insert '.getTable('product_share')
                            .' (`title`,`memo`,`audit`,`seq`,`product_id`) '
                            .' values ('
                            .to_db_str($data['case_'.$i.'_title']).','
                            .to_db_str($data['case_'.$i.'_memo']).','
                            .to_db_str($data['case_'.$i.'_audit']).','
                            .to_db_int($i).','
                            .to_db_int($product_id).''
                            .') ';
                        $success = parent::executeSql($sql);
                        if(!$success){
                            throw new Exception('');
                        }
                    }
                }
            }
        }catch (Exception $e){
            parent::rollbackTransaction();
        }

        return parent::executeTransactionBatch();
    }





    public function unpassProduct($product_id){
        $sql = 'update '.getTable('product').' set status = '.to_db_int(1).' where product_id = '.to_db_int($product_id);
        return parent::wrapTransaction($sql);
    }
}