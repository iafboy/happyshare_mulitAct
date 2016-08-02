<?php
class ModelCommonCustomer extends MyModel {

    public function queryCustomerAddressList($customerId){
        $sql = 'select a.*,b.name as city,c.name as district,d.name as province from '.getTable('address').' a ,'
            .getTable('addressbook_china_city').' b ,'
            .getTable('addressbook_china_district').' c ,'
            .getTable('addressbook_china_province').' d '
            .'where a.customer_id = '.to_db_int($customerId)
            .' and a.province_id = d.id and a.city_id = b.id and a.district_id = c.id';
        return parent::queryRows($sql);
    }
}