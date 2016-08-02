<?php
class ModelLocalisationCnAddressbook extends Model {
	public function addCountry($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "country SET name = '" . $this->db->escape($data['name']) . "', iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "', address_format = '" . $this->db->escape($data['address_format']) . "', postcode_required = '" . (int)$data['postcode_required'] . "', status = '" . (int)$data['status'] . "'");

		$this->cache->delete('country');
	}

	public function editCountry($country_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "country SET name = '" . $this->db->escape($data['name']) . "', iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "', address_format = '" . $this->db->escape($data['address_format']) . "', postcode_required = '" . (int)$data['postcode_required'] . "', status = '" . (int)$data['status'] . "' WHERE country_id = '" . (int)$country_id . "'");

		$this->cache->delete('country');
	}

	public function deleteCountry($country_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");

		$this->cache->delete('country');
	}

	public function getCountry($country_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");

		return $query->row;
	}

	public function getCountries($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "country";

			$sort_data = array(
				'name',
				'iso_code_2',
				'iso_code_3'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY name";
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

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			$country_data = $this->cache->get('country');

			if (!$country_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country ORDER BY name ASC");

				$country_data = $query->rows;

				$this->cache->set('country', $country_data);
			}

			return $country_data;
		}
	}

	public function getTotalCountries() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "country");

		return $query->row['total'];
  }


  /* *************************************
   * liuhang : add for new vendor's registration page
   *  
   *  1. for province : get getTotal 
   *  2. for city : get
   *  3. for county : get 
   *
   *
   * ********************************************/
	public function getProvinces() {

    $province_data = $this->cache->get('country');

		if (!$province_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "addressbook_china_province ORDER BY id ASC");

			$province_data = $query->rows;

			$this->cache->set('country', $province_data);
		}

		return $province_data;
	}

	public function getTotalProvinces() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "addressbook_china_province");

		return $query->row['total'];
  }

  public function getProvinceById($province_id) {

    if ($province_id == 0){
      return null;
    }  
    if(isset($province_id)){
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "addressbook_china_province WHERE id = '" . (int)$province_id . "'" );
      return $query->row;
    }
  
    return null;
  }

  public function getCityById($city_id) {

    if ($city_id == 0){
      return null;
    }  
    if(isset($city_id)){
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "addressbook_china_city WHERE id = '" . (int)$city_id . "'" );
      return $query->row;
    }
  
    return null;
  }

  public function getDistrictById($district_id) {

    if ($district_id == 0){
      return null;
    }  
    if(isset($district_id)){
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "addressbook_china_district WHERE id = '" . (int)$district_id . "'" );
      return $query->row;
    }
  
    return null;
  }

/*
	public function getZone($zone_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "' AND status = '1'");

		return $query->row;
	}
 */
  
  public function getCityByProviceId($region_code) {
		$city_data = $this->cache->get('city.' . $region_code);

		if (!$city_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "addressbook_china_city WHERE parent_code = '" . $region_code . "' ORDER BY id");

			$city_data = $query->rows;

			$this->cache->set('city.' . $region_code, $city_data);
		}

		return $city_data;
	}


  public function getDistrictByCityId($region_code) {
		$district_data = $this->cache->get('district.' . $region_code);

		if (!$district_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "addressbook_china_district WHERE parent_code = '" . $region_code . "' ORDER BY id");

			$district_data = $query->rows;

			$this->cache->set('city.' . $region_code, $district_data);
		}

		return $district_data;
	}





























}
