<?php

class ModelUserUser extends MyModel
{
    public function addSubUser($data)
    {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "supplier` SET company_contacter = '" . $this->db->escape($data['company_contacter']) . "', supplier_name = '" . $this->db->escape($data['company_contacter']) . "', supplier_group_id = '" . (int)$data['supplier_group_id'] . "',supplier_company = '" . $this->db->escape($data['supplier_company']) . "', prov = '" . $this->db->escape($data['prov']) . "', city = '" . $this->db->escape($data['city']) . "', distic = '" . $this->db->escape($data['distic']) . "', street = '" . $this->db->escape($data['street']) . "', company_contacter_phone = '" . $this->db->escape($data['company_contacter_phone']) . "', status = '" . (int)$data['status'] . "', create_date = NOW()" . ", salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "',parent_id=" . $supplier_id = $this->session->data['supplier_id'] . ", username = '" . $this->db->escape($data['username']) . "'");


    }

    public function addUser($data)
    {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "supplier` SET company_contacter = '" . $this->db->escape($data['company_contacter']) . "', supplier_name = '" . $this->db->escape($data['company_contacter']) . "', supplier_group_id = '" . (int)$data['supplier_group_id'] . "',supplier_company = '" . $this->db->escape($data['supplier_company']) . "', prov = '" . $this->db->escape($data['prov']) . "', city = '" . $this->db->escape($data['city']) . "', distic = '" . $this->db->escape($data['distic']) . "', street = '" . $this->db->escape($data['street']) . "', company_contacter_phone = '" . $this->db->escape($data['company_contacter_phone']) . "', status = '" . (int)$data['status'] . "', create_date = NOW()" . ", salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', username = '" . $this->db->escape($data['username']) . "'");


    }

    public function addUserReg($data)
    {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "supplier_reg` SET company_contacter = '" . $this->db->escape($data['company_contacter']) . "', supplier_name = '" . $this->db->escape($data['company_contacter']) . "',supplier_company = '" . $this->db->escape($data['supplier_company']) . "', prov = '" . $this->db->escape($data['prov']) . "', city = '" . $this->db->escape($data['city']) . "', distic = '" . $this->db->escape($data['distic']) . "', street = '" . $this->db->escape($data['street']) . "', company_contacter_phone = '" . $this->db->escape($data['company_contacter_phone']) . "', email = '" . $this->db->escape($data['company_contacter_email']) . "', create_date = NOW()");
    }

    public function editUser($user_id, $data)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "supplier` SET username = '" . $this->db->escape($data['username']) . "', supplier_group_id = '" . (int)$data['supplier_group_id'] . "',supplier_company = '" . $this->db->escape($data['supplier_company']) . "', supplier_name = '" . $this->db->escape($data['company_contacter']) . "', company_contacter = '" . $this->db->escape($data['company_contacter']) . "', prov = '" . $this->db->escape($data['prov']) . "', city = '" . $this->db->escape($data['city']) . "', distic = '" . $this->db->escape($data['distic']) . "', street = '" . $this->db->escape($data['street']) . "', company_contacter_phone = '" . $this->db->escape($data['company_contacter_phone']) . "', status = '" . (int)$data['status'] . "' WHERE supplier_id = '" . (int)$user_id . "'");

        if ($data['password']) {
            $this->db->query("UPDATE `" . DB_PREFIX . "supplier` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE supplier_id = '" . (int)$user_id . "'");
        }
    }

    public function editPassword($user_id, $password)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "supplier` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE supplier_id = '" . (int)$user_id . "'");
    }

    public function editCode($mobile, $code, $sms_pin)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "supplier` SET code = '" . $this->db->escape($code) . "', sms_pin = '" . (int)$sms_pin . "' WHERE LCASE(company_contacter_phone) = '" . $this->db->escape(utf8_strtolower($mobile)) . "'");
    }

    public function deleteUser($user_id)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "supplier` WHERE supplier_id = '" . (int)$user_id . "'");
    }

    public function getUser($user_id)
    {
        $query = $this->db->query("SELECT *, (SELECT ug.name FROM `" . DB_PREFIX . "supplier_group` ug WHERE ug.supplier_group_id = u.supplier_group_id) AS user_group FROM `" . DB_PREFIX . "supplier` u WHERE u.supplier_id = '" . (int)$user_id . "'");

        return $query->row;
    }

    public function getUserByUsername($username)
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "supplier` WHERE username = '" . $this->db->escape($username) . "'");

        return $query->row;
    }

    public function getUserByCode($code)
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "supplier` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

        return $query->row;
    }

    public function getUsers($data = array())
    {
        $supplier_id = $this->session->data['supplier_id'];
        $sub_supplier_id = $this->session->data['cur_supplier_id'];

        $sql = "SELECT a.*,b.name as groupname,a.supplier_group_id  FROM `" . DB_PREFIX
            . "supplier` a,".getTable('supplier_group')
            . " b where a.supplier_group_id = b.supplier_group_id and ( a.supplier_id = "
            . $supplier_id . " or a.parent_id = " . $supplier_id . ")";
        if($supplier_id != $sub_supplier_id){
            $sql .= ' and a.supplier_group_id != 1 ';
        }

        if (isset($data['username'])) {
            $sql .= " AND company_contacter_phone LIKE '%" . $this->db->escape($data['username']) . "%'";
        } else {
            $sql .= "";
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

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalUsers($filter_user_name)
    {
        $supplier_id = $this->session->data['supplier_id'];
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "supplier` where (supplier_id = " . $supplier_id . " or parent_id = " . $supplier_id . ") AND company_contacter_phone LIKE '%" . $this->db->escape($filter_user_name) . "%'");

        return $query->row['total'];
    }

    public function getTotalUsersByGroupId($supplier_group_id)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "supplier` WHERE supplier_group_id = '" . (int)$supplier_group_id . "'");

        return $query->row['total'];
    }

    public function getTotalUsersByMobile($mobile)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "supplier` WHERE LCASE(company_contacter_phone) = '" . $this->db->escape(utf8_strtolower($mobile)) . "'");

        return $query->row['total'];
    }


}
