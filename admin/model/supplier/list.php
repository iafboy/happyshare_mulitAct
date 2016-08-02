<?php
class ModelSupplierList extends MyModel {

    public function getSuppliers($data=array()){
        $sql = "SELECT distinct v.*,c.status as brand_status FROM ( " . getTable('supplier')." v left join "
            .getTable('supplier_to_brand')." b on v.supplier_id = b.supplier_id ) "
            .' left join '.getTable('brandgroup').' c on c.bg_id = b.brand_id '
            ." where v.parent_id is null ";

        if (is_valid($data['filter_supplier_name'])) {
            $sql .= " AND v.supplier_name LIKE '%" . $this->db->escape($data['filter_supplier_name']) . "%'";
        }
        if (is_valid($data['filter_supplier_no']) ) {
            $sql .= " AND v.supplier_no LIKE '%" . $this->db->escape($data['filter_supplier_no']) . "%'";
        }
        if (is_valid($data['filter_is_in_brand_display']) && $data['filter_is_in_brand_display'] != '*' ) {
            if($data['filter_is_in_brand_display'] == 1){
                $sql .= " AND v.own_brand = 1 ";
            }else if($data['filter_is_in_brand_display'] == 0){
                $sql .= " AND ( v.own_brand = 0 or ifnull(v.own_brand,0) = 0 ) ";
            }
        }
        if (is_valid($data['filter_is_self_set_score']) && $data['filter_is_self_set_score'] != '*' ) {
            if($data['filter_is_self_set_score'] == 1){
                $sql .= " AND v.can_edit_credit = 1 ";
            }else if($data['filter_is_self_set_score'] == 0){
                $sql .= " AND ( v.can_edit_credit = 0 or ifnull(v.can_edit_credit,0) = 0 ) ";
            }
        }
        if(is_valid($data['filter_supplier_time_start'])){
            $sql .= " and v.create_date >= '" .$data['filter_supplier_time_start'] . "' ";
        }
        if(is_valid($data['filter_supplier_time_end'])){
            $sql .= " and v.create_date <= '" .$data['filter_supplier_time_end'] . "' ";
        }
        $sql .= ' order by v.supplier_no asc, v.supplier_name';
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $suppliers = parent::queryRows($sql);

        foreach($suppliers as &$supplier){

            $sql = 'select count(1) as count from '.getTable('product')
                .' where status in (2,3,4) and supplier_id = '.to_db_int($supplier['supplier_id']);
            $count = parent::queryCount($sql);
            if(!isset($count)){
                $count = 0;
            }
            $supplier['supplier_git_count'] = $count;


            $sql = 'select count(1) as count from '.getTable('product')
                .' where status in (3) and supplier_id = '.to_db_int($supplier['supplier_id']);
            $count = parent::queryCount($sql);
            if(!isset($count)){
                $count = 0;
            }
            $supplier['supplier_online_count'] = $count;

            $sql = 'select sum(quantity) as count from '.getTable('order_product')
                .' where supplier_id = '.to_db_int($supplier['supplier_id']);
            $count = parent::queryCount($sql);
            if(!isset($count)){
                $count = 0;
            }
            $supplier['supplier_sold_count'] = $count;

            $sql = 'select sum(a.quantity*a.supplier_price) as count from '.getTable('order_product').' a, '.getTable('order').' b '
                .' where a.order_id = b.order_id and b.order_status in (10,11) and a.supplier_id = '.to_db_int($supplier['supplier_id']);
            $count = parent::queryCount($sql);
            if(!isset($count)){
                $count = 0;
            }
            $supplier['supplier_supply_amount'] = $count;

            $sql = 'select sum(a.quantity*a.price) as count from '.getTable('order_product').' a, '.getTable('order').' b '
                .' where a.order_id = b.order_id and b.order_status in (10,11)   and  a.supplier_id = '.to_db_int($supplier['supplier_id']);
            $count = parent::queryCount($sql);
            if(!isset($count)){
                $count = 0;
            }
            $supplier['supplier_sold_amount'] = $count;

            $sql = 'select sum(a.quantity*a.price-a.quantity*a.supplier_price) as count from '.getTable('order_product').' a, '.getTable('order').' b '
                .' where a.order_id = b.order_id and b.order_status in (10,11)   and  a.supplier_id = '.to_db_int($supplier['supplier_id']);
            $count = parent::queryCount($sql);
            if(!isset($count)){
                $count = 0;
            }
            $supplier['supplier_interest_amount'] = $count;

        }
        unset($supplier);

        foreach($suppliers as $key=>$supplier){

            $count = $supplier['supplier_git_count'];
            if (is_valid($data['filter_supplier_git_count_start']) ) {
                if($count < $data['filter_supplier_git_count_start']){
                    unset($suppliers[$key]);
                    continue;
                }
            }
            if (is_valid($data['filter_supplier_git_count_end'])) {
                if($count > $data['filter_supplier_git_count_end']){
                    unset($suppliers[$key]);
                    continue;
                }
            }

            $count = $supplier['supplier_online_count'];

            if (is_valid($data['filter_supplier_online_count_start'])  ) {
                if($count < $data['filter_supplier_online_count_start']){
                    unset($suppliers[$key]);
                    continue;
                }
            }
            if (is_valid($data['filter_supplier_online_count_end']) ) {
                if($count > $data['filter_supplier_online_count_end']){
                    unset($suppliers[$key]);
                    continue;
                }
            }


            $count = $supplier['supplier_sold_count'];
            if (is_valid($data['filter_supplier_sold_count_start'])  ) {
                if($count < $data['filter_supplier_sold_count_start']){
                    unset($suppliers[$key]);
                    continue;
                }
            }
            if (is_valid($data['filter_supplier_sold_count_end'])  ) {
                if($count > $data['filter_supplier_sold_count_end']){
                    unset($suppliers[$key]);
                    continue;
                }
            }

            $count = $supplier['supplier_supply_amount'];
            if (is_valid($data['filter_supplier_supply_amount_start'])  ) {
                if($count < $data['filter_supplier_supply_amount_start']){
                    unset($suppliers[$key]);
                    continue;
                }
            }
            if (is_valid($data['filter_supplier_supply_amount_end'])  ) {
                if($count > $data['filter_supplier_supply_amount_end']){
                    unset($suppliers[$key]);
                    continue;
                }
            }

            $count = $supplier['supplier_sold_amount'];
            if (is_valid($data['filter_supplier_sold_amount_start'])  ) {
                if($count < $data['filter_supplier_sold_amount_start']){
                    unset($suppliers[$key]);
                    continue;
                }
            }
            if (is_valid($data['filter_supplier_sold_amount_end'])  ) {
                if($count > $data['filter_supplier_sold_amount_end']){
                    unset($suppliers[$key]);
                    continue;
                }
            }


            $count = $supplier['supplier_interest_amount'];
            if (is_valid($data['filter_supplier_interest_amount_start'])  ) {
                if($count < $data['filter_supplier_interest_amount_start']){
                    unset($suppliers[$key]);
                    continue;
                }
            }
            if (is_valid($data['filter_supplier_interest_amount_end']) ) {
                if($count > $data['filter_supplier_interest_amount_end']){
                    unset($suppliers[$key]);
                    continue;
                }
            }
        }
        return $suppliers;
    }

    public function getSupplierTotalCount($data=array()){
        /*$sql = "SELECT count(1) as count FROM " . getTable('supplier')." v where v.parent_id is null ";
        if (is_valid($data['filter_supplier_name'])) {
            $sql .= " AND v.supplier_name LIKE '" . $this->db->escape($data['filter_supplier_name']) . "%'";
        }
        if (is_valid($data['filter_supplier_no']) ) {
            $sql .= " AND v.supplier_no = " . $data['filter_supplier_no'];
        }
        return parent::queryCount($sql);*/

        $sql = "SELECT distinct v.*,c.status as brand_status FROM ( " . getTable('supplier')." v left join "
            .getTable('supplier_to_brand')." b on v.supplier_id = b.supplier_id ) "
            .' left join '.getTable('brandgroup').' c on c.bg_id = b.brand_id '
            ." where v.parent_id is null";

        if (is_valid($data['filter_supplier_name'])) {
            $sql .= " AND v.supplier_name LIKE '%" . $this->db->escape($data['filter_supplier_name']) . "%'";
        }
        if (is_valid($data['filter_supplier_no']) ) {
            $sql .= " AND v.supplier_no = " . $data['filter_supplier_no'];
        }
        if (is_valid($data['filter_is_in_brand_display']) && $data['filter_is_in_brand_display'] != '*' ) {
            if($data['filter_is_in_brand_display'] == 1){
                $sql .= " AND v.own_brand = 1 ";
            }else if($data['filter_is_in_brand_display'] == 0){
                $sql .= " AND ( v.own_brand = 0 or ifnull(v.own_brand,0) = 0 ) ";
            }
        }
        if (is_valid($data['filter_is_self_set_score']) && $data['filter_is_self_set_score'] != '*' ) {
            if($data['filter_is_self_set_score'] == 1){
                $sql .= " AND v.can_edit_credit = 1 ";
            }else if($data['filter_is_self_set_score'] == 0){
                $sql .= " AND ( v.can_edit_credit = 0 or ifnull(v.can_edit_credit,0) = 0 ) ";
            }
        }

        $suppliers = parent::queryRows($sql);
        $len= sizeof($suppliers);
        foreach($suppliers as &$supplier){
            $sql = 'select count(1) as count from '.getTable('product')
                .' where status in (2,3,4) and supplier_id = '.to_db_int($supplier['supplier_id']);
            $count = parent::queryCount($sql);
            if(!isset($count)){
                $count = 0;
            }
            $supplier['supplier_git_count'] = $count;



            $sql = 'select count(1) as count from '.getTable('product')
                .' where status in (3) and supplier_id = '.to_db_int($supplier['supplier_id']);
            $count = parent::queryCount($sql);
            if(!isset($count)){
                $count = 0;
            }
            $supplier['supplier_online_count'] = $count;

            $sql = 'select sum(quantity) as count from '.getTable('order_product')
                .' where supplier_id = '.to_db_int($supplier['supplier_id']);
            $count = parent::queryCount($sql);
            if(!isset($count)){
                $count = 0;
            }
            $supplier['supplier_sold_count'] = $count;

            $sql = 'select sum(a.quantity*a.supplier_price) as count from '.getTable('order_product').' a, '.getTable('order').' b '
                .' where a.order_id = b.order_id and b.order_status in (10,11) and a.supplier_id = '.to_db_int($supplier['supplier_id']);
            $count = parent::queryCount($sql);
            if(!isset($count)){
                $count = 0;
            }
            $supplier['supplier_supply_amount'] = $count;

            $sql = 'select sum(a.quantity*a.price) as count from '.getTable('order_product').' a, '.getTable('order').' b '
                .' where a.order_id = b.order_id and b.order_status in (10,11)   and  a.supplier_id = '.to_db_int($supplier['supplier_id']);
            $count = parent::queryCount($sql);
            if(!isset($count)){
                $count = 0;
            }
            $supplier['supplier_sold_amount'] = $count;

            $sql = 'select sum(a.quantity*a.price-a.quantity*a.supplier_price) as count from '.getTable('order_product').' a, '.getTable('order').' b '
                .' where a.order_id = b.order_id and b.order_status in (10,11)   and  a.supplier_id = '.to_db_int($supplier['supplier_id']);
            $count = parent::queryCount($sql);
            if(!isset($count)){
                $count = 0;
            }
            $supplier['supplier_interest_amount'] = $count;
        }
        foreach($suppliers as $key=>$supplier){
            $count = $supplier['supplier_git_count'];
            if (is_valid($data['filter_supplier_git_count_start']) ) {
                if($count < $data['filter_supplier_git_count_start']){
                    unset($suppliers[$key]);
                    $len--;
                    continue;
                }
            }
            if (is_valid($data['filter_supplier_git_count_end'])) {
                if($count > $data['filter_supplier_git_count_end']){
                    unset($suppliers[$key]);
                    $len--;
                    continue;
                }
            }

            $count = $supplier['supplier_online_count'];

            if (is_valid($data['filter_supplier_online_count_start'])  ) {
                if($count < $data['filter_supplier_online_count_start']){
                    unset($suppliers[$key]);
                    $len--;
                    continue;
                }
            }
            if (is_valid($data['filter_supplier_online_count_end']) ) {
                if($count > $data['filter_supplier_online_count_end']){
                    unset($suppliers[$key]);
                    $len--;
                    continue;
                }
            }


            $count = $supplier['supplier_sold_count'];
            if (is_valid($data['filter_supplier_sold_count_start'])  ) {
                if($count < $data['filter_supplier_sold_count_start']){
                    unset($suppliers[$key]);
                    continue;
                }
            }
            if (is_valid($data['filter_supplier_sold_count_end'])  ) {
                if($count > $data['filter_supplier_sold_count_end']){
                    unset($suppliers[$key]);
                    $len--;
                    continue;
                }
            }

            $count = $supplier['supplier_supply_amount'];
            if (is_valid($data['filter_supplier_supply_amount_start'])  ) {
                if($count < $data['filter_supplier_supply_amount_start']){
                    unset($suppliers[$key]);
                    $len--;
                    continue;
                }
            }
            if (is_valid($data['filter_supplier_supply_amount_end'])  ) {
                if($count > $data['filter_supplier_supply_amount_end']){
                    unset($suppliers[$key]);
                    $len--;
                    continue;
                }
            }

            $count = $supplier['supplier_sold_amount'];
            if (is_valid($data['filter_supplier_sold_amount_start'])  ) {
                if($count < $data['filter_supplier_sold_amount_start']){
                    unset($suppliers[$key]);
                    $len--;
                    continue;
                }
            }
            if (is_valid($data['filter_supplier_sold_amount_end'])  ) {
                if($count > $data['filter_supplier_sold_amount_end']){
                    unset($suppliers[$key]);
                    $len--;
                    continue;
                }
            }


            $count = $supplier['supplier_interest_amount'];
            if (is_valid($data['filter_supplier_interest_amount_start'])  ) {
                if($count < $data['filter_supplier_interest_amount_start']){
                    unset($suppliers[$key]);
                    $len--;
                    continue;
                }
            }
            if (is_valid($data['filter_supplier_interest_amount_end']) ) {
                if($count > $data['filter_supplier_interest_amount_end']){
                    unset($suppliers[$key]);
                    $len--;
                    continue;
                }
            }
        }
        return $len;

    }

    public function changeStatus($supplier_id,$status){
        $sql = 'update '.getTable('supplier').' set status = '.to_db_int($status).' where supplier_id = '.to_db_int($supplier_id);
        return parent::executeSql($sql);
    }
    public function changeRelatedProductStatus($supplier_id){
        $sql = 'update '.getTable('product').' set status = 4 where supplier_id = '.to_db_int($supplier_id);
        return parent::executeSql($sql);
    }
}