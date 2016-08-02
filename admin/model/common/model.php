<?php
class ModelCommonModel extends MyModel {

    public function getBanks(){
        return parent::getBanks();
    }

    public function getSupplierCompanies(){
        $sql = "SELECT supplier_id,supplier_company FROM " . getTable('supplier')." v where status != '0' and parent_id is null";
        return  parent::queryRows($sql);
    }

    public function getSuppliers($data=array()){
        $sql = "SELECT * FROM " . getTable('supplier')." v where status != '0' ";

        if (is_valid($data['filter_supplier_name'])) {
            $sql .= " AND v.supplier_name LIKE '%" . $this->db->escape($data['filter_supplier_name']) . "%'";
        }
        if (is_valid($data['filter_supplier_no']) ) {
            $sql .= " AND v.supplier_no = " . $data['filter_supplier_no'];
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

        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getSupplierTotalCount($data=array()){
        $sql = "SELECT count(1) as count FROM " . getTable('supplier').' v';
        if (is_valid($data['filter_supplier_name'])) {
            $sql .= " AND v.supplier_name LIKE '" . $this->db->escape($data['filter_supplier_name']) . "%'";
        }
        if (is_valid($data['filter_supplier_no']) ) {
            $sql .= " AND v.supplier_no = " . $data['filter_supplier_no'];
        }
        $query = $this->db->query($sql);
        return $query->row['count'];
    }

}