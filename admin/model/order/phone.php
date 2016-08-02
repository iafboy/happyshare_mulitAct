<?php
class ModelOrderPhone extends MyModel {

    public function queryUnpaidCloseHour(){
        return parent::queryDict('config','unpaid_order_close_hour')['dvalue'];
    }

    public function updateUnpaidCloseHour($hour){
        return parent::updateDict('config','unpaid_order_close_hour',$hour);
    }

    public function queryOriginPlaces(){
        $sql = 'select * from '.getTable('origin_place');
        $sql .= ' order by place_code asc';
        return parent::queryRows($sql);
    }
    public function queryProductTypes(){
        $sql = 'select * from '.getTable('product_type');
        $sql .= ' order by product_type_no asc';
        return parent::queryRows($sql);
    }

    public function changePlaceStatus($place_id,$status){
        $sql = 'update '.getTable('origin_place').' set status = '.to_db_str($status).' where origin_place_id = '.to_db_str($place_id);
        return parent::wrapTransaction($sql);
    }
    public function changeProducttypeStatus($producttype_id,$status){
        $sql = 'update '.getTable('product_type').' set status = '.to_db_str($status).' where product_type_id = '.to_db_str($producttype_id);
        return parent::wrapTransaction($sql);
    }

    public function addPlace($place_code,$place_name){
        $sql = 'insert into  '.getTable('origin_place').' ( `place_name`,`place_code`,`status`) VALUES '
            .'( '.to_db_str($place_name).','.to_db_str($place_code).','.to_db_str('0').')';
        return parent::wrapTransaction($sql);
    }

    public function addProducttype($producttype_code,$producttype_name){
        $sql = 'insert into  '.getTable('product_type').' ( `product_type_no`,`type_name`,`status`) VALUES '
            .'( '.to_db_str($producttype_code).','.to_db_str($producttype_name).','.to_db_str('0').')';
        return parent::wrapTransaction($sql);
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