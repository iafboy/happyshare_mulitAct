<?php

class ApiController
{
    public function queryAddressById($addressId){
        if(!is_valid($addressId)){
            return null;
        }
        $sql = "select a.address_id as addressId,a.fullname as name,a.phone as mobile, a.address as address ,c.name as city ,a.city_id as cityId, a.province_id as provinceId, c.name as provinceName,
        a.district_id as  districtId ,d.name as districtName, a.seq as seq
              from " . getTable('address') . " a ," . getTable('addressbook_china_province') .
            " c ," . getTable('addressbook_china_district') . " d, " . getTable('addressbook_china_city')
            . "e where d.id=a.district_id and e.id=a.city_id  and c.id = a.province_id and  a.address_id = " . $addressId;

        return $this->db->querySingleRow($sql);

    }

}