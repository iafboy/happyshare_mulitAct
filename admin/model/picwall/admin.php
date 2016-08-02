<?php
class ModelPicwallAdmin extends MyModel {

    public function queryPicwalls($data=array()){

        $sql = 'select * from '.getTable('picwall_setting')
            .' where 1=1 and pic_type = 1 ';
        $isFirst = true;
        if(is_valid($data['filter_'.'cascade_1'])){
            $isFirst = false;
            $sql  = $sql. " and category_level1 like '%" . $this->db->escape($data['filter_'.'cascade_1']) . "%' ";
        }
        if(is_valid($data['filter_'.'cascade_2'])){
            $isFirst = false;
            $sql  = $sql. " and category_level2 like '%" . $this->db->escape($data['filter_'.'cascade_2']) . "%' ";
        }
        if(is_valid($data['filter_'.'cascade_3'])){
            $isFirst = false;
            $sql  = $sql. " and category_level3 like '%" . $this->db->escape($data['filter_'.'cascade_3']) . "%' ";
        }
        if(is_valid($data['filter_'.'picwall_address_or_code'])){
            $isFirst = false;
            $sql .= " and location like '%" . $this->db->escape($data['filter_'.'picwall_address_or_code']) . "%' ";
        }
        if($isFirst===true){
            $sql .= " and category_level1 like '%".$this->db->escape('首页')."%' ";
        }
        $picwalls =  parent::queryRows($sql);
        $picwalls2 = [];
        foreach($picwalls as $picwall){
            $sql2 = "select * from "
                .getTable('picwallbanner_image')
                .' where bs_id = '.to_db_int($picwall['pws_id']);
            $list = parent::queryRows($sql2);
            $picwall['list'] = dealImageWithRows($list,'image');
            $picwall['size'] = sizeof($list);
            $picwalls2[] = $picwall;
        }
        return $picwalls2;
    }
    public function hasPicwallImage($picwall_id,$sort_order){
        $sql_select = ' select count(1) as count from '.getTable('picwallbanner_image')
            .' where bs_id = '.to_db_int($picwall_id).' and sort_order = '.to_db_int($sort_order);
        if(parent::queryCount($sql_select)>0){
            return true;
        }
        return false;
    }

    public function queryPicwallImage($picwall_image_id){
        $sql = ' select * from '.getTable('picwallbanner_image')
            .' where picwallbanner_id = '.to_db_int($picwall_image_id);
        return parent::querySingleRow($sql);
    }


    public function addPicwallImage($picwall_id,$image_path,$sort_order,$link){

        $sql = 'insert into '.getTable('picwallbanner_image')." ( `bs_id`,`sort_order`,`image`,`link` ) "
            ." values ("
            .to_db_int($picwall_id)." , "
            .to_db_int($sort_order)." , "
            .to_db_str($image_path)." , "
            .to_db_str($link)."  "
            .")";
        return parent::wrapTransaction($sql);
    }

    public function delPicwallImage($picwall_image_id){
        $sql = ' delete from '.getTable('picwallbanner_image')
            .' where picwallbanner_id = '.to_db_int($picwall_image_id);
        return parent::wrapTransaction($sql);
    }
    public function setPicwallImageStatus($picwall_image_id,$status){
        $sql = ' update '.getTable('picwallbanner_image')
            .' set enable_status = '.to_db_int($status).' where picwallbanner_id = '.to_db_int($picwall_image_id);
        return parent::wrapTransaction($sql);
    }

}