<?php
class ModelSupplierApplyList extends Model {

    public function getApplySuppliers($data=array()){
        $sql = "SELECT supplier_reg_id,supplier_company,company_address,company_contacter,prov,city,distic,street,company_contacter_phone,approve_status,email,is_registered FROM "
            . getTable('supplier_reg')." v where v.approve_status<>3 ";
        if (is_valid($data['filter_supplier_company_name'])) {
            $sql = $sql." AND v.supplier_company LIKE '%" . $this->db->escape($data['filter_supplier_company_name']) . "%'";
        }
        if (is_valid($data['filter_supplier_create_date_start'])) {
            $sql = $sql." AND v.create_date >= " . to_db_str($data['filter_supplier_create_date_start']);
        }
        if (is_valid($data['filter_supplier_create_date_end'])) {
            $sql = $sql." AND v.create_date <= " . to_db_str($data['filter_supplier_create_date_end']);
        }
        if (is_valid($data['filter_supplier_approve_status'])&& $data['filter_supplier_approve_status']!=='*') {
            $sql = $sql." AND v.approve_status = " . to_db_str($data['filter_supplier_approve_status']);
        }
        $sql .= ' ORDER BY create_date desc, supplier_reg_id asc';
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getApplySupplierTotalCount($data=array()){
        $sql = "SELECT count(1) as count FROM " . getTable('supplier_reg')." s where 1=1 ";
        if (is_valid($data['filter_supplier_company'])) {
            $sql = $sql." AND v.supplier_company LIKE '" . $this->db->escape($data['filter_supplier_company']) . "%'";
        }
        if (is_valid($data['filter_supplier_create_date_start'])) {
            $sql = $sql." AND v.create_date >= " . to_db_str($data['filter_supplier_create_date_start']);
        }
        if (is_valid($data['filter_supplier_create_date_end'])) {
            $sql = $sql." AND v.create_date <= " . to_db_str($data['filter_supplier_create_date_end']);
        }
        if (is_valid($data['filter_supplier_approve_status']) && $data['filter_supplier_approve_status']!=='*') {
            $sql = $sql." AND v.approve_status = " . to_db_str($data['filter_supplier_approve_status']);
        }
        $query = $this->db->query($sql);
        return $query->row['count'];
    }

    public function changeStatus($approve_status,$supplier_id){
        $this->db->startTransaction();
        try {
            $sql = "update " . getTable('supplier_reg') . " s set approve_status = " . to_db_str($approve_status)
                . ' where supplier_reg_id = ' . to_db_int($supplier_id);
            $this->db->query($sql);
        } catch (Exception $e) {
            $this->db->rollbackTransaction();
            return false;
        }
        $this->db->commitTransaction();
        return true;
    }

}