<?php
class ModelProductEdit extends MyModel {

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
    public function queryMainTitleImage($product_id,$index){
        $sql = 'select img_'.$index.' as image from '.getTable('product').' where product_id = '.to_db_int($product_id);
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
    public function setProductTitleImage($product_id,$main_image,$index){
        $sql = 'update '.getTable('product').' set img_'.$index.' = '.to_db_str($main_image).' where product_id = '.to_db_int($product_id);
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
        if(is_valid($data['store_price'])){
            $sql .= ' storeprice = '.to_db_int($data['store_price']).' ,';
        }
        if(is_valid($data['price'])){
            $sql .= ' price = '.to_db_int($data['price']).' ,';
        }
        if(is_valid($data['credit_percent'])){
            $sql .= ' credit_percent = '.to_db_int($data['credit_percent']).' ,';
        }
        if(is_valid($data['credit'])){
            $sql .= ' credit = '.to_db_int($data['credit']).' ,';
        }
        if(is_valid($data['shareLevel'])){
            $sql .= ' shareLevel = '.to_db_int($data['shareLevel']).' ,';
        }
        if(is_valid($data['start_buynum'])){
            $sql .= ' start_buynum = '.to_db_int($data['start_buynum']).' ,';
        }
        if(is_valid($data['incr_buynum'])){
            $sql .= ' incr_buynum = '.to_db_int($data['incr_buynum']).' ,';
        }
        if(is_valid($data['start_sharenum'])){
            $sql .= ' start_sharenum = '.to_db_int($data['start_sharenum']).' ,';
        }
        if(is_valid($data['incr_sharenum'])){
            $sql .= ' incr_sharenum = '.to_db_int($data['incr_sharenum']).' ,';
        }
        if(is_valid($data['start_collectnum'])){
            $sql .= ' start_collectnum = '.to_db_int($data['start_collectnum']).' ,';
        }
        if(is_valid($data['incr_collectnum'])){
            $sql .= ' incr_collectnum = '.to_db_int($data['incr_collectnum']).' ,';
        }
        if(is_valid($data['status'])){
            $sql .= ' status = '.to_db_int($data['status']).' ,';
            if($data['status'] ==3){
                $sql  .= ' date_available = current_timestamp , ';
            }
        }
        $sql  .= ' date_modified = current_timestamp ';
        $sql = substr($sql,0,strlen($sql)-1);
        $sql .= ' where product_id = '.to_db_int($data['product_id']);
        $sqls[] = $sql;
        //$sql = 'update '.getTable('product').' set price = storeprice - interest_price where product_id = '.to_db_int($data['product_id']);

        //$sqls[] = $sql;
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

    public  function delSharecaseImage($product_id,$case_index,$img_index){
        $sql = 'select * from '.getTable('product_share').' where product_id = '.to_db_int($product_id)
            .' and seq = '.to_db_int($case_index);
        $case = parent::querySingleRow($sql);
        $image_url = $case['imgurl'.$img_index];
        if(is_file(DIR_IMAGE.$image_url)){
            FileUtil::unlinkFile(DIR_IMAGE.$image_url);
        }
        $sql = 'update '.getTable('product_share')
            .' set imgurl'.$img_index.' = NULL where product_id = '.to_db_int($product_id)
            .' and seq = '.to_db_int($case_index);
        $success = parent::executeSql($sql);
        return ['success'=>$success,errMsg=>'操作失败'];
    }

    public function unpassProduct($product_id){
        $sql = 'update '.getTable('product').' set status = '.to_db_int(1).' where product_id = '.to_db_int($product_id);
        return parent::wrapTransaction($sql);
    }
}