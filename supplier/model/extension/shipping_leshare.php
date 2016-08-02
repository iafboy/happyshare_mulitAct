<?php

class ModelExtensionShippingLeshare extends Model
{
    public function getInstalled($type)
    {
        $extension_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' ORDER BY code");

        foreach ($query->rows as $result) {
            $extension_data[] = $result['code'];
        }

        return $extension_data;
    }

    public function install($type, $code)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "extension SET `type` = '" . $this->db->escape($type) . "', `code` = '" . $this->db->escape($code) . "'");
    }


    public function getExpressCompany()
    {

        $sql = "SELECT e.expco_id, e.name FROM " . DB_PREFIX . "express_company e ";
        $query = $this->db->query($sql);

        return $query->rows;
    }


    public function getPlaceFromwhere()
    {

        $sql = "SELECT c.fromwhere_id, c.place_name FROM " . DB_PREFIX . "fromwhere c ";
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getPlaceDest()
    {

        $sql = "SELECT c.id, c.name FROM " . DB_PREFIX . "addressbook_china_city c ";
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getProvinces()
    {

        $sql = "SELECT p.id, p.name FROM " . DB_PREFIX . "addressbook_china_province p ";
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getPlaceFromwhereById($id)
    {

        $sql = "SELECT c.place_name FROM " . DB_PREFIX . "fromwhere c WHERE c.fromwhere_id = '" . $id . "'";
        $query = $this->db->query($sql);

        return $query->row['place_name'];
    }

    public function getExpressReturnInfo($supplier_id)
    {
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "express_salesreturn WHERE supplier_id = '" . (int)$supplier_id . "'");
      return $query->row;

    }
    public function getExpressGeneralInfo($supplier_id)
    {
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "express_setting WHERE supplier_id = '" . (int)$supplier_id . "'");
      return $query->row;
    }
    public function addExpressGeneralInfo($data)
    {

        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "express_setting WHERE supplier_id = '" . (int)$data['supplier_id'] . "'");

        if ($query->row['total'] == 0) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "express_setting SET supplier_id = '" . (int)$data['supplier_id'] . "', free_shipping = '" . (int)$data['free_shipping'] . "', free_tax_min = '" . (int)$data['free_tax_min'] . "', free_tax_max = '" . (int)$data['free_tax_max'] . "', order_charge = '" . (int)$data['order_charge'] . "'");
        } else {
            $this->db->query("UPDATE " . DB_PREFIX . "express_setting SET free_shipping = '" . (int)$data['free_shipping'] . "', free_tax_min = '" . (int)$data['free_tax_min'] . "', free_tax_max = '" . (int)$data['free_tax_max'] . "', order_charge = '" . (int)$data['order_charge'] . "' WHERE supplier_id = '" . $data['supplier_id'] . "'");
        }

        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "express_salesreturn WHERE supplier_id = '" . (int)$data['supplier_id'] . "'");

        if ($query->row['total'] == 0) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "express_salesreturn SET supplier_id = '" . (int)$data['supplier_id'] . "', name = '" . $data['name'] . "', telephone = '" . $data['telephone'] . "', addr_prov = '" . (int)$data['addr_prov'] . "', addr_city = '" . (int)$data['addr_city'] . "', addr_dist = '" . (int)$data['addr_dist'] . "', addr_info = '" . $data['addr_info'] . "'");
        } else {
            $this->db->query("UPDATE " . DB_PREFIX . "express_salesreturn SET name = '" . $data['name'] . "', telephone = '" . $data['telephone'] . "', addr_prov = '" . (int)$data['addr_prov'] . "', addr_city = '" . (int)$data['addr_city'] . "', addr_dist = '" . (int)$data['addr_dist'] . "', addr_info = '" . $data['addr_info'] . "' WHERE supplier_id = '" . $data['supplier_id'] . "'");
        }

    }

    public function addExpressPrice($data)
    {

        foreach ($data['expco'] as $expco) {
            $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "express_price WHERE supplier_id = '" . (int)$data['supplier_id'] . "' AND expco = '" . $expco . "' AND place_origin = '" . $data['place_origin'] . "' AND place_dest_city = '" . $data['place_dest_city'] . "' AND place_dest_prov = '" . $data['place_dest_prov'] . "'");

            if ($query->num_rows == 0) {

                $query_info = "INSERT INTO " . DB_PREFIX . "express_price SET supplier_id = '" . (int)$data['supplier_id'] . "', expco = '" . $expco . "', place_origin = '" . $data['place_origin'] . "', place_dest_prov = '" . $data['place_dest_prov'] . "', place_dest_city = '" . $data['place_dest_city'];

                if ($data['charge_type'] == 1) {
                    $query_info .= "', piece_start_price = '" . $data['piece_start_price'] . "', piece_add_price = '" . $data['piece_add_price'];
                } else if ($data['charge_type'] == 2) {
                    $query_info .= "', weight_start_weight = '" . $data['weight_start_weight'] . "', weight_start_price = '" . $data['weight_start_price'] . "', weight_add_weight = '" . $data['weight_add_weight'] . "', weight_add_price = '" . $data['weight_add_price'];
                } else if ($data['charge_type'] == 3) {
                    $query_info .= "', volume_start_price = '" . $data['volume_start_price'];
                }
                $query_info .= "'";

                $result = $this->db->query($query_info);
            } else if ($query->num_rows == 1) {

                $query_info = "UPDATE " . DB_PREFIX . "express_price";

                if ($data['charge_type'] == 1) {
                    $query_info .= " SET piece_start_price = '" . $data['piece_start_price'] . "', piece_add_price = '" . $data['piece_add_price'] . "' ";
                } else if ($data['charge_type'] == 2) {
                    $query_info .= " SET weight_start_weight = '" . $data['weight_start_weight'] . "', weight_start_price = '" . $data['weight_start_price'] . "', weight_add_weight = '" . $data['weight_add_weight'] . "', weight_add_price = '" . $data['weight_add_price'] . "' ";
                } else if ($data['charge_type'] == 3) {
                    $query_info .= " SET volume_start_price = '" . $data['volume_start_price'] . "' ";
                }
                $query_info .= " WHERE exppr_id = '" . $query->row['exppr_id'] . "'";

                $result = $this->db->query($query_info);
            }
        }
    }

    public function getExpressPrice($data)
    {

        //$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "express_price WHERE supplier_id = '" . (int)$data['supplier_id'] . "' AND place_origin = '" .$data['place_origin'] . "'");
        $query = $this->db->query("SELECT ep.exppr_id, ep.expco, ep.place_origin, ep.place_dest_prov, ep.place_dest_city, ep.weight_start_price, ep.weight_add_price, ep.piece_start_price, ep.piece_add_price, ep.volume_start_price, acp.name as pname, acc.name as cname, ec.name as expconame FROM " . DB_PREFIX . "express_price ep LEFT JOIN " . DB_PREFIX . "addressbook_china_province acp ON (ep.place_dest_prov = acp.id) LEFT JOIN " . DB_PREFIX . "addressbook_china_city acc ON (ep.place_dest_city = acc.id) LEFT JOIN " . DB_PREFIX . "express_company ec ON (ec.expco_id = ep.expco) WHERE supplier_id = '" . (int)$data['supplier_id'] . "' AND place_origin = '" . $data['place_origin'] . "'");

        return $query->rows;

    }

    public function getExpressPrice_new($data)
    {

        //$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "express_price WHERE supplier_id = '" . (int)$data['supplier_id'] . "' AND place_origin = '" .$data['place_origin'] . "'");
        $query = $this->db->query("SELECT ep.exppr_id, ep.expco, ep.place_origin,fw.place_name as fwname, ep.place_dest_prov, ep.place_dest_city, ep.weight_start_price, ep.weight_add_price, ep.piece_start_price, ep.piece_add_price, ep.volume_start_price, acp.name as pname, acc.name as cname, ec.name as expconame FROM " . DB_PREFIX . "express_price ep LEFT JOIN " . DB_PREFIX . "addressbook_china_province acp ON (ep.place_dest_prov = acp.id) LEFT JOIN " . DB_PREFIX . "addressbook_china_city acc ON (ep.place_dest_city = acc.id) LEFT JOIN " . DB_PREFIX . "express_company ec ON (ec.expco_id = ep.expco) left join ".DB_PREFIX."fromwhere fw on (ep.place_origin=fw.fromwhere_id) WHERE supplier_id = '" . (int)$data['supplier_id'] . "'");

        return $query->rows;

    }
    public function getExpcoByAddress($data){
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "express_price WHERE supplier_id = '" . $data['supplier_id'] . "' AND place_origin = '" . $data['place_origin'] . "' AND place_dest_prov = '" . $data['province_id'] . "' AND place_dest_city = '" . $data['city_id'] . "'");   
    
        return $query->row;
    }


}
