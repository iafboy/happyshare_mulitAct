<?php
class ModelCashreportsList extends MyModel {

    private function mainQuerySql($columns , $data=array()){
        $sql = 'select '.$columns.' from '
            .getTable('cash_report').' a , '
            .getTable('customer').' b '
            .' where 1=1 and a.customer_id = b.customer_id';

        if(is_valid($data['filter_'.'customer_name'])){
            $sql .= " and b.fullname like '%" . $this->db->escape($data['filter_'.'customer_name']) . "%' ";
        }
        if(is_valid($data['filter_'.'cash_apply_id'])){
            $sql .= " and a.cash_apply_id like '%" . $this->db->escape($data['filter_'.'cash_apply_id']) . "%' ";
        }
        if(is_valid($data['filter_'.'cash_pay_no'])){
            $sql .= " and a.cash_pay_no like '%" . $this->db->escape($data['filter_'.'cash_pay_no']) . "%' ";
        }
        if(is_valid($data['filter_'.'cash_pay_status']) && $data['filter_'.'cash_pay_status'] != '*'){
            $sql .= " and a.cash_pay_status = " . to_db_int($data['filter_'.'cash_pay_status']);
        }

        if(is_valid($data['filter_'.'cash_apply_time_start'])){
            $sql .= " and a.cash_apply_time >= '" .$data['filter_'.'cash_apply_time_start'] . "' ";
        }
        if(is_valid($data['filter_'.'cash_apply_time_end'])){
            $sql .= " and a.cash_apply_time <= '" .$data['filter_'.'cash_apply_time_end'] . "' ";
        }
        return $sql;
    }

    public function queryCashreports($data=array()){
        $sql = $this->mainQuerySql('a.*,b.fullname as customer_name ',$data);
        $sql .= ' order by a.cash_apply_time asc';
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
    public function  queryCashreportsCount($data=array()){
        $sql = $this->mainQuerySql('count(1) as count ',$data);
        return parent::queryCount($sql);
    }

}