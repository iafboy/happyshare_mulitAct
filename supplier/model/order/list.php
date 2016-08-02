<?php
class ModelOrderList extends MyModel {

    public function queryOrder($data=array()){
        $sql = "select a.*, d.order_type_name, b.fullname from "
            .getTable('order').' a , '
            .getTable('customer').' b , '
            .getTable('order_type').' d '
            .'where 1=1 and a.order_type_id = d.order_type_id and a.customer_id = b.customer_id and exists ( select 1 from '
            .getTable('order_product').' x where x.order_id = a.order_id and x.supplier_id = '.to_db_int($this->session->data['supplier_id']).')';
        if(is_valid($data['filter_'.'order_no'])){
            $sql .= " and a.order_no = " . to_db_str($data['filter_'.'order_no']);
        }

        if(is_valid($data['filter_'.'buyer_account'])){
            $sql .= " and a.fullname like '%" . $this->db->escape($data['filter_'.'buyer_account']) . "%' ";
        }
        if(is_valid($data['filter_'.'receiver_name'])){
            $sql .= " and a.receiver_fullname like '%" . $this->db->escape($data['filter_'.'receiver_name']) . "%' ";
        }
        if(is_valid($data['filter_'.'receiver_phone'])){
            $sql .= " and a.receiver_phone like '%" . $this->db->escape($data['filter_'.'receiver_phone']) . "%' ";
        }
        if(is_valid($data['filter_'.'order_type']) && $data['filter_'.'order_type'] != '*'){
            //$sql .= " and d.order_type_name  like '%" . $this->db->escape($data['filter_'.'order_type']). "%' ";
            $sql .= " and d.order_type_id = '" . $this->db->escape($data['filter_'.'order_type']). "' ";
        }
        if(is_valid($data['filter_'.'order_status']) && $data['filter_'.'order_status'] != '*'){
            $sql .= " and a.order_status = " . to_db_str($data['filter_'.'order_status']);
        }

        if(is_valid($data['filter_'.'order_create_time_start'])){
            $sql .= " and a.date_added >= '" .$data['filter_'.'order_create_time_start'] . "' ";
        }
        if(is_valid($data['filter_'.'order_create_time_end'])){
            $sql .= " and a.date_added <= '" .$data['filter_'.'order_create_time_end'] . "' ";
        }
        $sql .= ' order by a.date_added desc,a.date_modified, a.order_no';
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
    public function  queryOrderCount($data=array()){
        $sql = "select count(1) as count from "
            .getTable('order').' a , '
            .getTable('customer').' b , '
            .getTable('order_type').' d '
            .'where 1=1 and a.order_type_id = d.order_type_id and a.customer_id = b.customer_id and exists ( select 1 from '
            .getTable('order_product').' x where x.order_id = a.order_id and x.supplier_id = '.to_db_int($this->session->data['supplier_id']).')';
        if(is_valid($data['filter_'.'order_no'])){
            $sql .= " and a.order_no = " . to_db_str($data['filter_'.'order_no']);
        }

        if(is_valid($data['filter_'.'buyer_account'])){
            $sql .= " and a.fullname like '%" . $this->db->escape($data['filter_'.'buyer_account']) . "%' ";
        }
        if(is_valid($data['filter_'.'receiver_name'])){
            $sql .= " and a.receiver_name like '%" . $this->db->escape($data['filter_'.'receiver_name']) . "%' ";
        }
        if(is_valid($data['filter_'.'receiver_phone'])){
            $sql .= " and a.receiver_phone like '%" . $this->db->escape($data['filter_'.'receiver_phone']) . "%' ";
        }
        //if(is_valid($data['filter_'.'order_type'])){
        if(is_valid($data['filter_'.'order_type']) && $data['filter_'.'order_type'] != '*'){
            //$sql .= " and d.order_type_name  like '%" . $this->db->escape($data['filter_'.'order_type']). "%' ";
            $sql .= " and d.order_type_id = '" . $this->db->escape($data['filter_'.'order_type']). "' ";
        }
        if(is_valid($data['filter_'.'order_status']) && $data['filter_'.'order_status'] != '*'){
            $sql .= " and a.order_status = " . to_db_str($data['filter_'.'order_status']);
        }

        if(is_valid($data['filter_'.'order_create_time_start'])){
            $sql .= " and a.date_added >= '" .$data['filter_'.'order_create_time_start'] . "' ";
        }
        if(is_valid($data['filter_'.'order_create_time_end'])){
            $sql .= " and a.date_added <= '" .$data['filter_'.'order_create_time_end'] . "' ";
        }
        return parent::queryCount($sql);
    }


    public function queryOrderShipments($order_id){
        $sql = 'select a.shipments_id,b.supplier_name from '.getTable('shipments_orderproduct').' a , '.getTable('order_product').' b '
            .'where a.order_product_id = b.order_product_id and  a.order_id = '.to_db_str($order_id)
            .' group by a.shipments_id';
        return parent::queryRows($sql);
    }

    public function getShipmentProcesses($shipments_id){
        $sql = 'select * from '.getTable('shipments').' a, '.getTable('shipments_process').' b '
            .' where 1=1 and a.shipments_id = '.to_db_str($shipments_id).' and a.shipments_id = b.shipments_id order by b.time desc ';
        return parent::queryRows($sql);
    }
    public function getShipmentProducts($shipments_id){
        $sql = 'select b.* from '.getTable('shipments_orderproduct').' a, '.getTable('order_product').' b '
            .' where 1=1 and a.shipments_id = '.to_db_str($shipments_id).' and a.order_product_id = b.order_product_id ';
        return parent::queryRows($sql);
    }
    public function getTotalOrders($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " .getTable('order');
        $sql .= " WHERE order_status <> 1";

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND fullname LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND total = '" . (float)$data['filter_total'] . "'";
        }
        //printf($sql);
        $query = $this->db->query($sql);

        return $query->row['total'];
    }
    public function getOrders($data = array()) {
        $sql = "SELECT o.order_id,o.order_no as order_no, o.fullname AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";

        $sql .= " WHERE o.order_status <> 1 ";

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND o.fullname LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
        }

        $sort_data = array(
            'o.order_id',
            'order_no',
            'customer',
            'status',
            'o.date_added',
            'o.date_modified',
            'o.total'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY o.order_id";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        //printf($sql);
        $query = $this->db->query($sql);

        return $query->rows;
    }



    function confirmReturnGoods($orderId){
        $sql = 'select count(1) as count from '.getTable('order').' where order_id = '.to_db_int($orderId);
        $count = parent::queryCount($sql);
        if($count == 0){
            return ['success'=>false,'errMsg'=>'订单不存在'];
        }
        $sql = 'update '.getTable('order').' set order_status = 6 , order_status_id = 6 '.' where order_id = '.to_db_int($orderId);
        $success = parent::executeSql($sql);
        if($success){
            return ['success'=>true];
        }else{
            return ['success'=>false,'errMsg'=>'操作失败'];
        }
    }
}
