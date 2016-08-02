<?php
class ModelReportsAdd extends Model {

    public function queryOrder($data=array()){
        /*$sql = "SELECT supplier_id,supplier_company,company_address,company_contactor,company_contactor_phone,approve_status FROM "
            . getTable('supplier')." v where status = '0' ";
        if (isset($data['filter_supplier_company']) && !is_null($data['filter_supplier_company'])) {
            $sql .= " AND v.supplier_company_name LIKE '" . $this->db->escape($data['filter_supplier_company']) . "%'";
        }
        if (isset($data['filter_supplier_create_date_start']) && !is_null($data['filter_supplier_create_date_start'])) {
            $sql .= " AND v.create_date >= " . $data['filter_supplier_create_date_start'];
        }
        if (isset($data['filter_supplier_create_date_end']) && !is_null($data['filter_supplier_create_date_end'])) {
            $sql .= " AND v.create_date <= " . $data['filter_supplier_create_date_end'];
        }
        if (isset($data['filter_supplier_approve_status']) && !is_null($data['filter_supplier_approve_status'])) {
            $sql .= " AND v.approve_status <= " . $data['filter_supplier_approve_status'];
        }
        $sort_data = array(
            'v.supplier_no',
            'v.supplier_name'
        );
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY v.supplier_no";
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
        $query = $this->db->query($sql);*/
        return [];
    }
    public function  queryOrderCount($data=array()){
        return 0;
    }

}