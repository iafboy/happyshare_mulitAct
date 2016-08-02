<?php
class ModelActivityList extends MyModel {

	private function mainQuerySql($data,$col){
		$sql='select '.$col.' from '.getTable('combine_promotion_view').' a where 1=1 ';

		if(is_valid($data['filter_'.'act_name'])){
			$sql .= " and promotion_name like %" . $this->db->escape($data['filter_'.'act_name']) . "%' ";
		}
		if(is_valid($data['filter_'.'act_product_name'])){
			$sql .= " and product_name like %" . $this->db->escape($data['filter_'.'act_product_name']) . "%' ";
		}
		if(is_valid($data['filter_'.'act_type']) && $data['filter_'.'act_type'] != '*'){
			$sql .= " and type = " . to_db_int($data['filter_'.'act_type']);
		}
		if(is_valid($data['filter_'.'act_expire_date_start'])){
			$sql .= " and enddate >= " .to_db_str($data['filter_'.'act_expire_date_start']);
		}
		if(is_valid($data['filter_'.'act_expire_date_end'])){
			$sql .= " and enddate <= " .to_db_str($data['filter_'.'act_expire_date_end'] );
		}
		if(is_valid($data['filter_'.'act_joiner_count_start'])){
			$sql .= " and usernumber >= " .to_db_int($data['filter_'.'act_joiner_count_start']);
		}
		if(is_valid($data['filter_'.'act_joiner_count_end'])){
			$sql .= " and usernumber <= " .to_db_int($data['filter_'.'act_joiner_count_end']);
		}
		if(is_valid($data['filter_'.'act_status']) && $data['filter_'.'act_status'] != '*'){
			$sql .= " and status = " . to_db_int($data['filter_'.'act_status']);
		}
		return $sql;
	}

	public function queryActs($data=array()){
		$sql = $this->mainQuerySql($data," distinct pid, subpromotionid,type,status,startdate,enddate,usernumber,promotion_name ");
		$sql .= ' order by enddate desc,promotion_name';
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
	public function  queryActsCount($data=array()){
		$sql = $this->mainQuerySql($data,' count(distinct pid) as count ');
		//$sql .= ' group by pid';
		return parent::queryCount($sql);
	}

}