<?php
class ModelProductAdd extends MyModel {
    public function checkCreditSettingPower($userid){
        $sql='select can_edit_credit from '.getTable('supplier').' where supplier_id='.$userid.'';
        $isOk = parent::querySingleRow($sql);
        return $isOk['can_edit_credit'];
    }
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

    public function getParentSupplier($supplier_id){
        $sql = "SELECT parent_id FROM " . DB_PREFIX . "supplier WHERE supplier_id =". $supplier_id;

        $query = $this->db->query($sql);

        return ($query->rows[0]['parent_id'] == null)?$supplier_id:$query->rows[0]['parent_id'];
    }

    public function addProduct($data=array()){

        if(!is_valid($data['return_limit'])){
            $data['return_limit'] = 0;
        }

        $supplier_id = $this->getParentSupplier($this->session->data['supplier_id']);
        $credit_percent=to_db_int($data['credit_percent']);
        if(is_null($credit_percent)||!isset($credit_percent)||ctype_space($credit_percent)){
            $credit_percent=0;
        }
        $sql = 'insert into '.getTable('product')
            .' (`product_no`,`supplier_id`,`quantity`,`image`,`img_1`,`img_2`,`img_3`,`price`,`market_price`,`storeprice`,`interest_price`,`credit_percent`,`weight`,`volume`,'
            .'`date_added`,`fromwhere`,`refoundlimit`,`status`,`date_modified`,`shareLevel`,`product_type_id`,`origin_place_id`,`charge_type`,`tax_charge`,`credit`,`express_template`,`return_limit`) '
            .' values ( '
            .to_db_str($data['product_no']).' , '
            .to_db_int($supplier_id).' , '
            .to_db_int($data['quantity']).' , '
            .to_db_str('').' , '.to_db_str('').' , '.to_db_str('').' , '.to_db_str('').' , '
            .to_db_int($data['price'] ).' , '
            .to_db_int($data['market_price']).' , '
            .to_db_int($data['store_price']).' , '
            .to_db_int($data['interest_price']).' , '
            .$credit_percent.' , '
            .to_db_int($data['weight']).' , '
            .to_db_int($data['volume']).' , '
            .to_db_int('now()').' , '
            .to_db_str(1).' , '
            .to_db_int($data['return_limit']).' , '
            .to_db_str(0).' , '
            .to_db_int('now()').' , '
            .to_db_int($data['shareLevel']).' , '
            .to_db_int($data['product_type']).' , '
            .to_db_int($data['origin_place_id']).' , '
            .to_db_int(1).' , '
            .to_db_str(0).' , '
            .to_db_str($data['credit']).' , '
            .to_db_str($data['express_template']).' , '
            .to_db_int($data['return_limit']).' '
            .')';
            //echo $sql;
            parent::startTransaction();
        try{
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('first insert failed');
            }else{
                $product_id = parent::getLastId();
            }

            $sql = 'insert '.getTable('product_description').' (`product_id`,`language_id`,`name`)'
                .' values ( '
                .to_db_int($product_id).' , '
                .to_db_int(1).' , '
                .to_db_str($data['product_name']).'  '
                .')';
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('insert product_desc failed');
            }

            $suf = parseExtension($data['main_image']);
            $main_image = 'products/main/products_'.$product_id.'_main'.'.'.$suf;
            $success = FileUtil::copyFile(DIR_IMAGE.$data['main_image'],DIR_IMAGE.$main_image,true);
            if(!$success){
                throw new Exception('copy image failed');
            }
            $sql = 'update '.getTable('product').' set image = '.to_db_str($main_image).' where product_id = '.to_db_int($product_id);
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('update Img URL failed');
            }
            $suf = parseExtension($data['main_image1']);
            $main_image1 = 'products/main/products_'.$product_id.'_main_1'.'.'.$suf;
            $success = FileUtil::copyFile(DIR_IMAGE.$data['main_image1'],DIR_IMAGE.$main_image1,true);
            if(!$success){
                throw new Exception('copy image1 failed');
            }
            $sql = 'update '.getTable('product').' set img_1 = '.to_db_str($main_image1).' where product_id = '.to_db_int($product_id);
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('update Img1 URL failed');
            }
            $suf = parseExtension($data['main_image2']);
            $main_image2 = 'products/main/products_'.$product_id.'_main_2'.'.'.$suf;
            $success = FileUtil::copyFile(DIR_IMAGE.$data['main_image2'],DIR_IMAGE.$main_image2,true);
            if(!$success){
                throw new Exception('copy image2 failed');
            }
            $sql = 'update '.getTable('product').' set img_2 = '.to_db_str($main_image2).' where product_id = '.to_db_int($product_id);
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('update Img2 URL failed');
            }
            $suf = parseExtension($data['main_image3']);
            $main_image3 = 'products/main/products_'.$product_id.'_main_3'.'.'.$suf;
            $success = FileUtil::copyFile(DIR_IMAGE.$data['main_image3'],DIR_IMAGE.$main_image3,true);
            if(!$success){
                throw new Exception('copy image3 failed');
            }
            $sql = 'update '.getTable('product').' set img_3 = '.to_db_str($main_image3).' where product_id = '.to_db_int($product_id);
            $success = parent::executeSql($sql);
            if(!$success){
                throw new Exception('update Img3 URL failed');
            }

            $subs = explode(',',$data['subs']);

            foreach($subs as $i){
                $suf = parseExtension($data['sub_image_'.$i]);
                $sub_image = 'products/sub/products_'.$product_id.'_sub_'.$i.'.'.$suf;
                $success = FileUtil::copyFile(DIR_IMAGE.$data['sub_image_'.$i],DIR_IMAGE.$sub_image,true);
                if(!$success){
                    throw new Exception('upload sub_image failed');
                }
                $sql = ' insert into '.getTable('product_image')
                    .' (`product_id`,`sort_order`,`image`) '
                    .' values ( '
                    .to_db_int($product_id).','
                    .to_db_int($i).','
                    .to_db_str($sub_image)
                    .' ) ';
                $success = parent::executeSql($sql);
                if(!$success){
                    throw new Exception('insert product_img failed');
                }
            }

            if(is_valid($data['cases'])){
                $cases = explode(',',$data['cases']);
                foreach($cases as $i){
                    $sql =
                        'insert into '.getTable('product_share')
                        .' set title = '.to_db_str($data['case_'.$i.'_title']).','
                        .'  memo = '.to_db_str($data['case_'.$i.'_memo']).','
                        .'  audit = '.to_db_str($data['case_'.$i.'_audit']).','
                        .'  seq = '.to_db_int($i).','
                        .'  product_id = '.to_db_int($product_id).',';

                    for($xx = 1; $xx < 10;$xx ++){
                        $suf = parseExtension($data['share_image_'.$i.'_'.$xx]);
                        $sub_image = 'products/sharecases/products_'.$product_id.'_case_'.$i.'_img_'.$xx.'.'.$suf;
                        if(is_file(DIR_IMAGE.$data['share_image_'.$i.'_'.$xx])){
                            $success = FileUtil::copyFile(DIR_IMAGE.$data['share_image_'.$i.'_'.$xx],DIR_IMAGE.$sub_image,true);
                            if(!$success){
                                throw new Exception('insert share img failed');
                            }
                            $sql .= 'imgurl'.$xx.' = '.to_db_str($sub_image).',';
                        }

                    }
                    $sql = substr($sql,0,strlen($sql)-1);
                    $success = parent::executeSql($sql);
                    if(!$success){
                        throw new Exception('insert share info failed');
                    }
                }
            }
            $success = true;
        }catch (Exception $e){
            $success = false;
            //echo $e->getMessage();
            parent::rollbackTransaction();
        }
        if(!$success){
            parent::rollbackTransaction();
            return false;
        }else{
            parent::commitTransaction();
            return true;
        }
    }





    public function unpassProduct($product_id){
        $sql = 'update '.getTable('product').' set status = '.to_db_int(1).' where product_id = '.to_db_int($product_id);
        return parent::wrapTransaction($sql);
    }
}
