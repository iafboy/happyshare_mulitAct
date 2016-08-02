<?php
class ModelProductList extends MyModel {

    private function mainQuerySql($data,$column_str){
        $sql = 'SELECT '.$column_str
            .' FROM ' . getTable('product').' a '
            .'left join '.getTable('review').' b on a.product_id = b.product_id '
            .'left join '.getTable('customer_ophistory').' d on a.product_id = d.product_id '
            .'inner join '.getTable('origin_place').' c on a.origin_place_id = c.origin_place_id '
            .'inner join '.getTable('product_description').' e on a.product_id = e.product_id and e.language_id = 1 '
            .'inner join '.getTable('supplier').' f on a.supplier_id = f.supplier_id '
            .'left join '.getTable('product_type').' h on a.product_type_id = h.product_type_id '
            .'where 1=1 ';
        if (is_valid($data['filter_supplier_name'])) {
            $sql .= " AND f.supplier_name LIKE '%" . $this->db->escape($data['filter_supplier_name']) . "%'";
        }

        if (is_valid($data['filter_product_supplier_id']) && $data['filter_product_supplier_id'] != '*') {
            $sql .= " AND f.supplier_id = " . $data['filter_product_supplier_id'];
        }

        if (is_valid($data['filter_product_no']) ) {
            $sql .= " AND a.product_no = '" . $data['filter_product_no']."'";
        }

        if (is_valid($data['filter_product_name']) ) {
            $sql .= " AND e.name like  '%" . $data['filter_product_name'] . "%' ";
        }

        if (is_valid($data['filter_product_place']) && $data['filter_product_place'] != '*') {
            $sql .= " AND a.origin_place_id = " . $data['filter_product_place'];
        }

        if (is_valid($data['filter_product_type']) && $data['filter_product_type'] != '*') {
            $sql .= " AND a.product_type_id = " . $data['filter_product_type'];
        }

        if (is_valid($data['filter_supplier_price_start'])) {
            $sql .= " AND a.price >= " . $data['filter_supplier_price_start'];
        }

        if (is_valid($data['filter_supplier_price_end'])) {
            $sql .= " AND a.price <= " . $data['filter_supplier_price_end'];
        }

        if (is_valid($data['filter_product_price_start'])) {
            $sql .= " AND a.storeprice >= " . $data['filter_product_price_start'];
        }

        if (is_valid($data['filter_product_price_end'])) {
            $sql .= " AND a.storeprice <= " . $data['filter_product_price_end'];
        }

        if (is_valid($data['filter_score_feedback_rate_start'])) {
            $sql .= " AND a.credit_percent >= " . $data['filter_score_feedback_rate_start'];
        }

        if (is_valid($data['filter_score_feedback_rate_end'])) {
            $sql .= " AND a.credit_percent <= " . $data['filter_score_feedback_rate_end'];
        }

        if (is_valid($data['filter_product_count_start'])) {
            $sql .= " AND a.quantity >= " . $data['filter_product_count_start'];
        }

        if (is_valid($data['filter_product_count_end'])) {
            $sql .= " AND a.quantity <= " . $data['filter_product_count_end'];
        }

        if (is_valid($data['filter_product_status']) && $data['filter_product_status'] != '*') {
            $sql .= " AND a.status = " . $data['filter_product_status'];
        }

        if (is_valid($data['filter_product_submit_time_start'])) {
            $sql .= " AND a.date_modified >= " . $data['filter_product_submit_time_start'];
        }

        if (is_valid($data['filter_product_submit_time_end'])) {
            $sql .= " AND a.date_modified <= " . $data['filter_product_submit_time_end'];
        }

        if (is_valid($data['filter_product_sold_count_start'])) {
            $sql .= " AND a.sales >= " . $data['filter_product_sold_count_start'];
        }

        if (is_valid($data['filter_product_sold_count_end'])) {
            $sql .= " AND a.sales <= " . $data['filter_product_sold_count_end'];
        }

        if (is_valid($data['filter_product_share_count_start'])) {
            $sql .= " AND a.document >= " . $data['filter_product_share_count_start'];
        }

        if (is_valid($data['filter_product_share_count_end'])) {
            $sql .= " AND a.document <= " . $data['filter_product_share_count_end'];
        }

        if (is_valid($data['filter_product_comment_count_start'])) {
            $sql .= " AND a.comments >= " . $data['filter_product_comment_count_start'];
        }

        if (is_valid($data['filter_product_comment_count_end'])) {
            $sql .= " AND a.comments <= " . $data['filter_product_comment_count_end'];
        }

        if (is_valid($data['filter_product_shareLevel_start'])) {
            $sql .= " AND a.shareLevel >= " . $data['filter_product_shareLevel_start'];
        }

        if (is_valid($data['filter_product_shareLevel_end'])) {
            $sql .= " AND a.shareLevel <= " . $data['filter_product_shareLevel_end'];
        }
        return $sql;
    }


    public function queryProducts($data=array()){
        $sql = $this->mainQuerySql($data,' distinct a.*,e.name as product_name,f.supplier_name,c.place_name,( select group_concat( distinct h.type_name) )  type_name , '
            .'( select count(1) from '.getTable('review').' m where m.product_id = a.product_id ) commentCount , '
            .'( select count(1) from '.getTable('customer_ophistory')
            .' n where n.product_id = a.product_id and n.operation_type = 1 ) shareCount ');
        $sql .= ' group by a.product_id order by a.product_id asc ';
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        return parent::queryRows($sql);
    }
    public function  queryProductsCount($data=array()){
        $sql = $this->mainQuerySql($data,' distinct a.* ');
        $sql = 'select count(1) as count from ('.$sql.') ttt';
        return parent::queryCount($sql);
    }

    public function queryProductList(){
        return parent::queryProductTypeList();
    }

    public function querySupplierList(){
        $sql = "SELECT * FROM " . getTable('supplier')." v where status != '0' order by supplier_no";
        return parent::queryRows($sql);
    }
    public function queryOriginPlaces(){
        $sql = 'select * from '.getTable('origin_place');
        $sql .= ' where status = '.to_db_str('1').' order by place_code asc';
        return parent::queryRows($sql);
    }
}
