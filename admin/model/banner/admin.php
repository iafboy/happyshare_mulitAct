<?php
class ModelBannerAdmin extends MyModel {


    public function queryBanners($data=array()){
        $sql = 'select * from '.getTable('picwall_setting')
            .' where 1=1 and pic_type = 0 ';
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
        if(is_valid($data['filter_'.'banner_address_or_code'])){
            $isFirst = false;
            $sql .= " and location like '%" . $this->db->escape($data['filter_'.'banner_address_or_code']) . "%' ";
        }
        if($isFirst===true){
            $sql .= " and category_level1 like '%".$this->db->escape('首页')."%' ";
        }
        $banners =  parent::queryRows($sql);
        $banners2 = [];
        foreach($banners as $banner){
            $sql2 = "select * from "
                .getTable('picwallbanner_image')
                .' where bs_id = '.to_db_int($banner['pws_id']);
            $list = parent::queryRows($sql2);
            $banner['list'] = dealImageWithRows($list,'image');
            $banner['size'] = sizeof($list);
            $banners2[] = $banner;
        }
        return $banners2;
    }
    public function hasBannerImage($banner_id,$sort_order){
        $sql_select = ' select count(1) as count from '.getTable('picwallbanner_image')
            .' where bs_id = '.to_db_int($banner_id).' and sort_order = '.to_db_int($sort_order);
        if(parent::queryCount($sql_select)>0){
            return true;
        }
        return false;
    }

    public function queryBannerImage($banner_image_id){
        $sql = ' select * from '.getTable('picwallbanner_image')
            .' where picwallbanner_id = '.to_db_int($banner_image_id);
        return parent::querySingleRow($sql);
    }

    public function setBannerImage($bannerId,$image){
        $sql = 'update '.getTable('picwallbanner_image')
            ." set image = "
            .to_db_str($image)
            ." where picwallbanner_id = ".to_db_int($bannerId);;
        return parent::wrapTransaction($sql);
    }

    public function addBannerImage($banner_id,$image_path,$sort_order,$link){

        $sql = 'insert into '.getTable('picwallbanner_image')." ( `bs_id`,`sort_order`,`image`,`link` ) "
        ." values ("
            .to_db_int($banner_id)." , "
            .to_db_int($sort_order)." , "
            .to_db_str($image_path)." , "
            .to_db_str($link)."  "
            .")";
        return parent::wrapTransaction($sql);
    }
    public function updateBannerImage($banner_id,$image_path,$sort_order,$link){

        $sql = 'update '.getTable('picwallbanner_image')." ( `bs_id`,`sort_order`,`image`,`link` ) "
        ." values ("
            .to_db_int($banner_id)." , "
            .to_db_int($sort_order)." , "
            .to_db_str($image_path)." , "
            .to_db_str($link)."  "
            .")";
        return parent::wrapTransaction($sql);
    }

    public function delBannerImage($banner_image_id){
        $sql = ' delete from '.getTable('picwallbanner_image')
            .' where picwallbanner_id = '.to_db_int($banner_image_id);
        return parent::wrapTransaction($sql);
    }
    public function setBannerImageStatus($banner_image_id,$status){
        $sql = ' update '.getTable('picwallbanner_image')
            .' set enable_status = '.to_db_int($status).' where picwallbanner_id = '.to_db_int($banner_image_id);
        return parent::wrapTransaction($sql);
    }
    public function updateBannerImageSetting($banner_image_id,$link,$seq){
        $sql = ' update '.getTable('picwallbanner_image')
            .' set link = '.to_db_str($link).', sort_order = '.to_db_int($seq).' where picwallbanner_id = '.to_db_int($banner_image_id);
        return parent::wrapTransaction($sql);
    }

}