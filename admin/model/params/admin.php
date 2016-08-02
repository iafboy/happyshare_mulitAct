<?php
class ModelParamsAdmin extends MyModel {

    public function queryUnpaidCloseHour(){
        return parent::queryDict('config','unpaid_order_close_hour')['dvalue'];
    }

    public function updateUnpaidCloseHour($hour){
        return parent::updateDict('config','unpaid_order_close_hour',$hour);
    }

    public function queryOriginPlaces($data=array()){

        $sql = 'select * from '.getTable('origin_place');
        $sql .= ' order by place_code asc';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            if ($data['limit'] < 1) {
                $data['limit'] = 10;
            }
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }else{
            $data['start'] = 0;
            $data['limit'] = 10;
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        return parent::queryRows($sql);
    }
    public function queryExpressPlaces($data=array()){

        $sql = 'select * from '.getTable('fromwhere');
        $sql .= ' order by place_code asc';

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            if ($data['limit'] < 1) {
                $data['limit'] = 10;
            }
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }else{
            $data['start'] = 0;
            $data['limit'] = 10;
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        return parent::queryRows($sql);
    }
    public function queryOriginPlacesCount(){
        $sql = 'select count(1) as count from '.getTable('origin_place');
        return parent::queryCount($sql);
    }
    public function queryExpressPlacesCount(){
        $sql = 'select count(1) as count from '.getTable('fromwhere');
        return parent::queryCount($sql);
    }
    public function queryProductTypes(){
        $sql = 'select * from '.getTable('product_type');
        $sql .= ' order by product_type_no asc';
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            if ($data['limit'] < 1) {
                $data['limit'] = 10;
            }
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }else{
            $data['start'] = 0;
            $data['limit'] = 10;
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        return parent::queryRows($sql);
    }
    public function queryProductTypesCount(){
        $sql = 'select count(1) as count from '.getTable('product_type');
        $sql .= ' order by product_type_no asc';
        return parent::queryCount($sql);
    }

    public function changePlaceStatus($place_id,$status){
        $sql= 'select count(1) as count from '.getTable('product').' where status in (3) and origin_place_id = '.to_db_int($place_id);
        $count = parent::queryCount($sql);
        if($count > 0){
            return ['success'=>false,'errMsg'=>'该来源地已被使用'];
        }
        $sql = 'update '.getTable('origin_place').' set status = '.to_db_str($status).' where origin_place_id = '.to_db_str($place_id);
        $success = parent::wrapTransaction($sql);
        return getReturnMsg($success);
    }
    public function changeExpressPlaceStatus($place_id,$status){
        $sql= 'select count(1) as count from '.getTable('product').' a '
            .' where a.status in (3) and a.fromwhere = '.to_db_str($place_id);
        $count = parent::queryCount($sql);
        if($count > 0){
            return ['success'=>false,'errMsg'=>'该发货地已被使用'];
        }
        $sql = 'update '.getTable('fromwhere').' set status = '.to_db_str($status).' where fromwhere_id = '.to_db_str($place_id);
        $success = parent::wrapTransaction($sql);
        return getReturnMsg($success);
    }
    public function changeProducttypeStatus($producttype_id,$status){
        $sql= 'select count(1) as count from '.getTable('product').' a '
            .' where a.status in (3) and a.product_type_id = '.to_db_str($producttype_id);
        $count = parent::queryCount($sql);
        if($count > 0){
            return ['success'=>false,'errMsg'=>'该产品类型已被使用'];
        }
        $sql = 'update '.getTable('product_type').' set status = '.to_db_str($status).' where product_type_id = '.to_db_str($producttype_id);
        $success = parent::wrapTransaction($sql);
        return getReturnMsg($success);
    }

    public function addPlace($place_code,$place_name){
        $sql = 'insert into  '.getTable('origin_place').' ( `place_name`,`place_code`,`status`) VALUES '
            .'( '.to_db_str($place_name).','.to_db_str($place_code).','.to_db_str('0').')';
        return parent::wrapTransaction($sql);
    }
    public function addExpressPlace($place_code,$place_name){
        $sql = 'insert into  '.getTable('fromwhere').' ( `place_name`,`place_code`,`status`) VALUES '
            .'( '.to_db_str($place_name).','.to_db_str($place_code).','.to_db_str('0').')';
        return parent::wrapTransaction($sql);
    }

    public function checkExpressPlaceCode($place_code){
        $sql = 'select count(1) as count from '.getTable('fromwhere').' where place_code = '.to_db_str($place_code);
        $count = parent::queryCount($sql);
        return $count > 0;
    }
    public function checkPlaceCode($place_code){
        $sql = 'select count(1) as count from '.getTable('origin_place').' where place_code = '.to_db_str($place_code);
        $count = parent::queryCount($sql);
        return $count > 0;
    }

    public function checkProducttypeCode($producttype_code){
        $sql = 'select count(1) as count from '.getTable('product_type').' where product_type_no = '.to_db_str($producttype_code);
        $count = parent::queryCount($sql);
        return $count > 0;
    }

    public function addProducttype($producttype_code,$producttype_name){
        $sql = 'insert into  '.getTable('product_type').' ( `product_type_no`,`type_name`,`status`) VALUES '
            .'( '.to_db_str($producttype_code).','.to_db_str($producttype_name).','.to_db_str('0').')';
        return parent::wrapTransaction($sql);
    }

    public function queryExpressPlaceById($place_id){
        $sql = 'select * from '.getTable('fromwhere').' where fromwhere_id = '.to_db_int($place_id);
        return parent::querySingleRow($sql);
    }
    public function queryPlaceById($place_id){
        $sql = 'select * from '.getTable('origin_place').' where origin_place_id = '.to_db_int($place_id);
        return parent::querySingleRow($sql);
    }
    public function queryProducttypeById($product_type_id){
        $sql = 'select * from '.getTable('product_type').' where product_type_id = '.to_db_int($product_type_id);
        return parent::querySingleRow($sql);
    }
}