<?php
class ModelReportsList extends MyModel {

    public function queryOrder($data=array()){
        $sql = "select distinct c.repay_id,a.order_id,a.order_no,a.order_status,a.repay_status, c.transfer_amount,c.repay_time,c.transfer_no as transfer_no,c.supplier_id from "
            .getTable('order').' a , '
            .getTable('order_product').' b , '
            .getTable('repay').' c ,'
	 .getTable('supplier').' d '
            .'where 1=1 and b.supplier_id = c.supplier_id and b.supplier_id=d.supplier_id and a.order_id = c.order_id';
        if(is_valid($data['filter_'.'order_no'])){
            $sql .= " and a.order_no = " . to_db_str($data['filter_'.'order_no']);
        }

        if(is_valid($data['filter_'.'buyer_name'])){
            $sql .= " and a.fullname like '%" . $this->db->escape($data['filter_'.'buyer_name']) . "%' ";
        }
        if(is_valid($data['filter_'.'receiver_name'])){
            $sql .= " and a.receiver_name like '%" . $this->db->escape($data['filter_'.'receiver_name']) . "%' ";
        }
        if(is_valid($data['filter_'.'receiver_phone'])){
            $sql .= " and a.receiver_phone like '%" . $this->db->escape($data['filter_'.'receiver_phone']) . "%' ";
        }
        if(is_valid($data['filter_'.'order_type']) && $data['filter_'.'order_type'] != '*'){
            $sql .= " and a.order_type_id = " . to_db_str($data['filter_'.'order_type']);
        }
        if(is_valid($data['filter_'.'order_status']) && $data['filter_'.'order_status'] != '*'){
            $sql .= " and a.order_status = " . to_db_str($data['filter_'.'order_status']);
        }
        if(is_valid($data['filter_'.'supplier_id'])){
            $sql .= " and d.supplier_name like '%" . $this->db->escape($data['filter_'.'supplier_id'])."%'";
        }
        if(is_valid($data['filter_'.'repay_status']) && $data['filter_'.'repay_status'] != '*'){
             $sql .= " and a.repay_status = " . to_db_str($data['filter_'.'repay_status']);
        }
        if(is_valid($data['filter_'.'repay_no']) && $data['filter_'.'repay_no'] != '*'){
             $sql .= " and a.repay_no = like '%" . $this->db->escape($data['filter_'.'repay_no'])."%'";
        }
        if(is_valid($data['filter_order_finishtime_start'])){
            $sql .= " and a.date_modified >= '" .$data['filter_order_finishtime_start'] . "' ";
        }
        if(is_valid($data['filter_order_finishtime_end'])){
            $sql .= " and a.date_modified <= '" .$data['filter_order_finishtime_end'] . "' ";
        }
        $sql .= ' order by a.order_no desc';
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
       // echo $sql;
        return parent::queryRows($sql);
    }
    public function  queryOrderCount($data=array()){
        $sql = 'select count(1) as count from ( ';
        $sql .= " select distinct c.repay_id from "
            .getTable('order').' a , '
            .getTable('order_product').' b , '
            .getTable('repay').' c '
            .'where 1=1 and a.order_id = c.order_id and b.supplier_id = c.supplier_id ';

        if(is_valid($data['filter_'.'order_no'])){
            $sql .= " and a.order_no = " . to_db_str($data['filter_'.'order_no']);
        }

        if(is_valid($data['filter_'.'buyer_name'])){
            $sql .= " and a.fullname like '%" . $this->db->escape($data['filter_'.'buyer_name']) . "%' ";
        }
        if(is_valid($data['filter_'.'receiver_name'])){
            $sql .= " and a.receiver_name like '%" . $this->db->escape($data['filter_'.'receiver_name']) . "%' ";
        }
        if(is_valid($data['filter_'.'receiver_phone'])){
            $sql .= " and a.receiver_phone like '%" . $this->db->escape($data['filter_'.'receiver_phone']) . "%' ";
        }
        if(is_valid($data['filter_'.'order_type']) && $data['filter_'.'order_type'] != '*'){
            $sql .= " and a.order_type_id = " . to_db_str($data['filter_'.'order_type']);
        }
        if(is_valid($data['filter_'.'order_status']) && $data['filter_'.'order_status'] != '*'){
            $sql .= " and a.order_status = " . to_db_str($data['filter_'.'order_status']);
        }
        if(is_valid($data['filter_'.'supplier_id'])){
            $sql .= " and a.supplier_id like '%" . $this->db->escape($data['filter_'.'supplier_id'])."%'";
        }
        if(is_valid($data['filter_'.'repay_status']) && $data['filter_'.'repay_status'] != '*'){
            $sql .= " and a.repay_status = " . to_db_str($data['filter_'.'repay_status']);
        }
        if(is_valid($data['filter_'.'repay_no']) && $data['filter_'.'repay_no'] != '*'){
            $sql .= " and a.repay_no = like '%" . $this->db->escape($data['filter_'.'repay_no'])."%'";
        }
        if(is_valid($data['filter_order_finishtime_start'])){
            $sql .= " and a.finish_time >= '" .$data['filter_order_finishtime_start'] . "' ";
        }
        if(is_valid($data['filter_order_finishtime_end'])){
            $sql .= " and a.finish_time <= '" .$data['filter_order_finishtime_end'] . "' ";
        }
        $sql .=' ) count_tb ';
        return parent::queryCount($sql);
    }

}
