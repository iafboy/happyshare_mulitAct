<?php
class Supplier {
	private $user_id;
	private $username;
	private $t_permission = array();
	private $log;

	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
		$this->log= $registry->get('log');

		//unset($this->session->data['supplier_id']);
		//$this->log->write("current user id: ".serialize($this->session));
		if (isset($this->session->data['cur_supplier_id'])) {
			$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "supplier WHERE supplier_id = '" . (int)$this->session->data['cur_supplier_id'] . "' AND status = '1'");
			$this->log->write("SELECT * FROM " . DB_PREFIX . "supplier WHERE supplier_id = '" . (int)$this->session->data['cur_supplier_id'] . "' AND status = '1'");
			if ($user_query->num_rows) {
				$this->user_id = $user_query->row['supplier_id'];
				$this->username = $user_query->row['username'];
				$this->user_group_id = $user_query->row['supplier_group_id'];
				$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "supplier_group WHERE supplier_group_id = '" . (int)$user_query->row['supplier_group_id'] . "'");

				$permissions = unserialize($user_group_query->row['permission']);

				if (is_array($permissions)) {
					foreach ($permissions as $key => $value) {
						$this->t_permission[$key] = $value;
						$this->log->write("load permission ".$key." | ".serialize($value));
					}
					$this->log->write("permission construct is ".serialize($this->t_permission));
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login($username, $password) {
		$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "supplier WHERE username = '" . $this->db->escape($username) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') ");
		if ($user_query->num_rows) {
			$supplierId = $user_query->row['supplier_id'];
			$parentId = $user_query->row['parent_id'];
			$sup_status=$user_query->row['status'];
			if($sup_status>0) {
				if (is_valid($parentId)) {
					$this->session->data['supplier_id'] = $parentId;
				} else {
					$this->session->data['supplier_id'] = $supplierId;
				}
				$this->session->data['cur_supplier_id'] = $supplierId;

				$this->user_id = $user_query->row['supplier_id'];
				$this->username = $user_query->row['username'];
				$this->user_group_id = $user_query->row['supplier_group_id'];

				$user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "supplier_group WHERE supplier_group_id = '" . (int)$user_query->row['supplier_group_id'] . "'");
				$permissions = unserialize($user_group_query->row['permission']);
				if (is_array($permissions)) {
					foreach ($permissions as $key => $value) {
						$this->t_permission[$key] = $value;
					}
				}
				return 1;
			}else{
				return 2;
			}
		} else {
			return 0;
		}
	}

	public function logout() {
		unset($this->session->data['supplier_id']);

		$this->user_id = '';
		$this->username = '';
		$this->t_permission=array();
	}

	public function hasPermission($key, $value) {
		if (isset($this->t_permission[$key])) {
			$this->log->write("has permission ".$key." | ".serialize($value)." in_array ".serialize($this->t_permission[$key])." ");
			return in_array($value, $this->t_permission[$key]);
		} else {
			return false;
		}
	}

	public function isLogged() {
		return $this->user_id;
	}

	public function getId() {
		return $this->user_id;
	}

	public function getUserName() {
		return $this->username;
	}

	public function getGroupId() {
		return $this->user_group_id;
	}
}
