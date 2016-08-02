<?php
class ModelReportsList extends Model {

    public function queryOrder($data=array()){
      
      
      
      
        $sql = "select a.order_id,a.order_no,a.order_status,a.repay_status,a.supplier_price,a.finish_time from "
            .getTable('order').' a , '
            .getTable('repay').' c '
            .getTable('customer').' d '
            .'where 1=1 and a.repay_id = c.repay_id and a.customer_id = d.customer_id';

        if(is_valid($data['filter_'.'order_no'])){
            $sql .= " and a.order_no = " . to_db_str($data['filter_'.'order_no']);
        }

        if(is_valid($data['filter_'.'buyer_name'])){
            //$sql .= " and a.customer_id like '%" . $this->db->escape($data['filter_'.'buyer_name']) . "%' ";
            //$sql .= " and a.customer_id = " . $customer_id ;
            $sql .= " and a.customer_id = " . $data['filter_'.'buyer_name'] ;
        }
        if(is_valid($data['filter_'.'receiver_name'])){
            $sql .= " and a.receiver_fullname like '%" . $this->db->escape($data['filter_'.'receiver_name']) . "%' ";
        }
        if(is_valid($data['filter_'.'receiver_phone'])){
            $sql .= " and a.receiver_phone like '%" . $this->db->escape($data['filter_'.'receiver_phone']) . "%' ";
        }
        if(is_valid($data['filter_'.'order_type']) && $data['filer_'.'order_type'] != '*'){
            $sql .= " and a.order_type_id = " . to_db_str($data['filter_'.'order_type']);
        }
        if(is_valid($data['filter_'.'order_status']) && $data['filer_'.'order_status'] != '*'){
            $sql .= " and a.order_status = " . to_db_str($data['filter_'.'order_status']);
        }
        if(is_valid($data['filter_'.'supplier_id'])){
            $sql .= " and a.supplier_id like '%" . $this->db->escape($data['filter_'.'supplier_id'])."%'";
        }
        if(is_valid($data['filter_'.'repay_status']) && $data['filer_'.'repay_status'] != '*'){
             $sql .= " and a.repay_status = " . to_db_str($data['filter_'.'repay_status']);
        }
        if(is_valid($data['filter_'.'repay_no']) && $data['filer_'.'repay_no'] != '*'){
             $sql .= " and a.repay_no = like '%" . $this->db->escape($data['filter_'.'repay_no'])."%'";
        }
        if(is_valid($data['filter_order_finishtime_start'])){
            $sql .= " and a.finish_time >= '" .$data['filter_order_finishtime_start'] . "' ";
        }
        if(is_valid($data['filter_order_finishtime_end'])){
            $sql .= " and a.finish_time <= '" .$data['filter_order_finishtime_end'] . "' ";
        }
        $sql .= ' order by a.order_no';
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
            .getTable('repay').' c '
            .'where 1=1 and a.repay_id = c.repay_id';

        if(is_valid($data['filter_'.'order_no'])){
            $sql .= " and a.order_no = " . to_db_str($data['filter_'.'order_no']);
        }

        if(is_valid($data['filter_'.'buyer_name'])){
            $sql .= " and a.fullname like '%" . $this->db->escape($data['filter_'.'buyer_name']) . "%' ";
        }
        if(is_valid($data['filter_'.'receiver_name'])){
            $sql .= " and a.receiver_fullname like '%" . $this->db->escape($data['filter_'.'receiver_name']) . "%' ";
        }
        if(is_valid($data['filter_'.'receiver_phone'])){
            $sql .= " and a.receiver_phone like '%" . $this->db->escape($data['filter_'.'receiver_phone']) . "%' ";
        }
        if(is_valid($data['filter_'.'order_type']) && $data['filer_'.'order_type'] != '*'){
            $sql .= " and a.order_type_id = " . to_db_str($data['filter_'.'order_type']);
        }
        if(is_valid($data['filter_'.'order_status']) && $data['filer_'.'order_status'] != '*'){
            $sql .= " and a.order_status = " . to_db_str($data['filter_'.'order_status']);
        }
        if(is_valid($data['filter_'.'supplier_id'])){
            $sql .= " and a.supplier_id like '%" . $this->db->escape($data['filter_'.'supplier_id'])."%'";
        }
        if(is_valid($data['filter_'.'repay_status']) && $data['filer_'.'repay_status'] != '*'){
            $sql .= " and a.repay_status = " . to_db_str($data['filter_'.'repay_status']);
        }
        if(is_valid($data['filter_'.'repay_no']) && $data['filer_'.'repay_no'] != '*'){
            $sql .= " and a.repay_no = like '%" . $this->db->escape($data['filter_'.'repay_no'])."%'";
        }
        if(is_valid($data['filter_order_finishtime_start'])){
            $sql .= " and a.finish_time >= '" .$data['filter_order_finishtime_start'] . "' ";
        }
        if(is_valid($data['filter_order_finishtime_end'])){
            $sql .= " and a.finish_time <= '" .$data['filter_order_finishtime_end'] . "' ";
        }
        return parent::queryCount($sql);
    }





	public function getTotalOrders($data = array()) {



    $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order o INNER JOIN " . DB_PREFIX . "repay r on (r.repay_id = o.repay_id)" . " INNER JOIN " . DB_PREFIX ."order_product op ON (op.order_id = o.order_id)";

    //$sql .= " , " . DB_PREFIX . "repay r WHERE o.repay_id = r.repay_id and o.customer_id = c.customer_id";
    
    /**************
     * 1. repay_id will not be checked any more!
     * 2. add supplier_id check
     *
     * ************/

    $supplier_id = $this->session->data['supplier_id'];
    $sql .= " INNER JOIN " . DB_PREFIX . "customer c ON ( o.customer_id = c.customer_id ) WHERE op.supplier_id = '" . $supplier_id . "' ";


		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_no = '" . $this->db->escape($data['filter_order_id']) . "'";
		}

    if (!empty($data['filter_order_type'])) {
			$sql .= " AND o.order_type_id = '" . $this->db->escape($data['filter_order_type']) . "'";
		}

    if (!empty($data['filter_repay_status'])) {
			$sql .= " AND o.repay_status = '" . $this->db->escape($data['filter_repay_status']) . "'";
		}

    if (!empty($data['filter_buyer_name'])) {
			$sql .= " AND o.fullname LIKE '" . $this->db->escape($data['filter_buyer_name']) . "%'";
		}

    if (!empty($data['filter_order_status'])) {
			$sql .= " AND o.order_status = '" . $this->db->escape($data['filter_order_status']) . "'";
		}

    if (!empty($data['filter_repay_no'])) {
			$sql .= " AND r.repay_no = '" . $this->db->escape($data['filter_repay_no']) . "'";
		}

    if (!empty($data['filter_receiver_name'])) {
			$sql .= " AND o.receiver_fullname = '" . $this->db->escape($data['filter_receiver_name']) . "'";
		}

    if (!empty($data['filter_receiver_phone'])) {
			$sql .= " AND o.receiver_phone = '" . $this->db->escape($data['filter_receiver_phone']) . "'";
		}

    if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_start']) . "')";
		}

    if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= DATE('" . $this->db->escape($data['filter_date_end']) . "')";
		}


		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getOrders ($data = array()) {


    if (!empty($data['filter_buyer_name'])) {
    $sql = "select c.customer_id from "
      .DB_PREFIX . "customer c " 
      ."where  c.fullname  = '" . $this->db->escape($data['filter_'.'buyer_name']) . "'";
    $query = $this->db->query($sql);
    $customer_id = $query->row['customer_id'];
		}

    $sql = "SELECT o.order_id,o.order_no,o.customer_id,o.order_status,o.repay_status,o.supplier_price,o.finish_time, r.repay_time, r.transfer_no FROM " . DB_PREFIX . "repay r left JOIN " . DB_PREFIX . "order o on (r.order_id = o.order_id)" . " left JOIN " . DB_PREFIX ."order_product op ON (op.order_id = o.order_id)";

    //$sql .= " , " . DB_PREFIX . "repay r WHERE o.repay_id = r.repay_id and o.customer_id = c.customer_id";
    
    /**************
     * 1. repay_id will not be checked any more!
     * 2. add supplier_id check
     *
     * ************/

    $supplier_id = $this->session->data['supplier_id'];
    $sql .= " left JOIN " . DB_PREFIX . "customer c ON ( o.customer_id = c.customer_id ) WHERE op.supplier_id = '" . $supplier_id . "' ";

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_no = '" . $this->db->escape($data['filter_order_id']) . "'";
		}

    if (!empty($data['filter_order_type'])) {
			$sql .= " AND o.order_type_id = '" . $this->db->escape($data['filter_order_type']) . "'";
		}

    if (!empty($data['filter_repay_status'])) {
			$sql .= " AND o.repay_status = '" . $this->db->escape($data['filter_repay_status']) . "'";
		}

    if (!empty($data['filter_buyer_name'])) {
			//$sql .= " AND o.fullname LIKE '" . $this->db->escape($data['filter_buyer_name']) . "%'";
      $sql .= " and o.customer_id = " . $customer_id ;
		}

    if (!empty($data['filter_order_status'])) {
			$sql .= " AND o.order_status = '" . $this->db->escape($data['filter_order_status']) . "'";
		}

    if (!empty($data['filter_repay_no'])) {
			$sql .= " AND r.repay_no = '" . $this->db->escape($data['filter_repay_no']) . "'";
		}

    if (!empty($data['filter_receiver_name'])) {
			$sql .= " AND o.receiver_fullname = '" . $this->db->escape($data['filter_receiver_name']) . "'";
		}

    if (!empty($data['filter_receiver_phone'])) {
			$sql .= " AND o.receiver_phone = '" . $this->db->escape($data['filter_receiver_phone']) . "'";
		}

    if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($data['filter_date_start']) . "')";
		}

    if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= DATE('" . $this->db->escape($data['filter_date_end']) . "')";
		}


		$sort_data = array(
			'o.order_no',
			'o.order_status',
			'o.repay_status',
			'o.supplier_price',
			'r.repay_time',
			'r.transfer_no'
		);
    
    
    if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.date_added";
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
        //echo $sql;
		$query = $this->db->query($sql);

		return $query->rows;
	}


	public function getOrderTypes () {

		$sql = "SELECT ot.order_type_id,ot.order_type_no,ot.order_type_name FROM " . DB_PREFIX . "order_type ot ";

    //$sql .= " WHERE ot.language_id = (int)$this->config->get('config_language_id') ";

		$query = $this->db->query($sql);

		return $query->rows;
	}


	public function getOrderStatus () {

		$sql = "SELECT os.order_status_id,os.name FROM " . DB_PREFIX . "order_status os where order_status_id <5 or order_status_id>9 ";

    $sql .= " and os.language_id = '" . (int)$this->config->get('config_language_id') . "' ";

		$query = $this->db->query($sql);
        //echo $sql;
		return $query->rows;
	}










}
