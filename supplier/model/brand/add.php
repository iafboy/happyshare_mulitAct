<?php
class ModelBrandAdd extends Model {

  public function addBanner($data){

				$this->db->query("INSERT INTO " . DB_PREFIX . "brandbanner_image SET image = '" . $data['img_src'] . "',  link = '" . $data['input_url'] .  "',  sort_order = '" . $data['input_seq'] . "',  supplier_id = '" . $data['supplier_id'] . "',  enable_status = '" . 1 . "'");
  }
 
  public function delBanner($banner_id){
    
    $this->db->query("DELETE FROM " . DB_PREFIX . "brandbanner_image WHERE brandbanner_id = '" . (int)$banner_id . "'");
  }

  public function enableBanner($banner_id){

			$this->db->query("UPDATE " . DB_PREFIX . "brandbanner_image SET enable_status = '1' WHERE brandbanner_id = '" . (int)$banner_id . "'");
  }

  public function disableBanner($banner_id){

			$this->db->query("UPDATE " . DB_PREFIX . "brandbanner_image SET enable_status = '0' WHERE brandbanner_id = '" . (int)$banner_id . "'");
  }

  public function getBanners($data){

    $sql = "SELECT * FROM " . DB_PREFIX . "brandbanner_image bb ";
		$sql .= " WHERE bb.supplier_id = '" . (int)$data['supplier_id'] . "'";
		$query = $this->db->query($sql);

		return $query->rows;
  }

  public function getTotalBanners($data){

 		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "brandbanner_image bb ";
		$sql .= " WHERE bb.supplier_id = '" . (int)$data['supplier_id'] . "'";
		$query = $this->db->query($sql);

		return $query->row['total'];
 }

}
