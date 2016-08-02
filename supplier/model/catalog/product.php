<?php
class ModelCatalogProduct extends MyModel {




	public function  modQuantity($productId,$quantity){
		$sql = 'update '.getTable('product').' set quantity = '.to_db_int($quantity).' where product_id = '.to_db_int($productId);
		return parent::executeSql($sql);
	}
	public function  putOffShell($productId){
		$sql = 'update '.getTable('product').' set status = 4 where product_id = '.to_db_int($productId);
		return parent::executeSql($sql);
	}
	public function addProduct($data) {
		$this->event->trigger('pre.admin.product.add', $data);

		//$this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) ."', product_no='".$this->db->escape($data['input_product_code']). "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");
		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) ."', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");

		$product_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$product_reward['points'] . "'");
			}
		}

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		if (isset($data['product_recurrings'])) {
			foreach ($data['product_recurrings'] as $recurring) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$recurring['customer_group_id'] . ", `recurring_id` = " . (int)$recurring['recurring_id']);
			}
		}

		$this->cache->delete('product');

		$this->event->trigger('post.admin.product.add', $product_id);

		return $product_id;
	}


  public function addProductNew($supplier_id,$data) {
		$this->event->trigger('pre.supplier.product.add', $data);

	  // 供应商提供的是供货价而不是平台价  所以把storeprice改成了price
	  $credit_percent = (int)($data['input_product_reward'] / $data['input_product_price_market'] * 100);
	  $this->db->query("INSERT INTO " . DB_PREFIX . "product SET product_type_id = '" . $this->db->escape($data['input_model']) . "', product_no='".$this->db->escape($data['input_product_code']). "', fromwhere = '" . $this->db->escape($data['input_product_place']) . "', origin_place_id = '" . $this->db->escape($data['input_product_place_shipment']) . "', quantity = '" . $data['input_product_stock'] . "', supplier_id = '" . (int)$supplier_id . "', status = '0', weight = '" . $this->db->escape($data['input_weight']) . "', market_price = '" . $data['input_product_price_market'] .  "', price = '" . $data['input_product_price_store'] . "', credit_percent = '" . $credit_percent . "', shareLevel = '" . $data['input_product_recommand_index'] . "', volume = '" . $data['input_volume']  . "', tax_charge = '" . $data['input_product_tax'] . "', charge_type = '" . $this->db->escape($data['input_charge_mode']) . "', return_limit = '" . (int)$data['input_product_return_deadline']  . "', feedback_reward = '" . $data['input_product_reward']  . "', date_added = NOW()");

		$product_id = $this->db->getLastId();

		if (isset($data['input_upload_img_title']) && ($data['input_upload_img_title'] != '')) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['input_upload_img_title']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
    if (isset($data['input_name'])){
      $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', name = '" . $this->db->escape($data['input_name']) . "', language_id = '" . (int)$this->config->get('config_language_id') . "'");
    } 


    for($i = 1; $i <= 5; $i++){
      if (isset($data['input_product_img_sub_'.$i]) && (!empty($data['input_product_img_sub_'.$i]))) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($data['input_product_img_sub_'.$i]) . "', sort_order = '" . $i . "'");
      }
    }

    if (isset($data['total_sharedocs'])){
      $total_sharedocs = $data['total_sharedocs'];
      for($j=1;$j<=$total_sharedocs;$j++){

        if ( isset($data['input_sharedoc_title_'.$j]) && isset($data['input_sharedoc_memo_'.$j]) && !empty($data['input_sharedoc_title_'.$j]) && !empty($data['input_sharedoc_memo_'.$j]) ){
          $this->db->query("INSERT INTO " . DB_PREFIX . "product_share SET product_id = '" . (int)$product_id . "', title = '" . $this->db->escape($data['input_sharedoc_title_'.$j]) . "', memo = '" . $this->db->escape($data['input_sharedoc_memo_'.$j]) . "'");
          
          $prdshare_id = $this->db->getLastId();
          
          for($i = 1; $i <= 9; $i++){
            if (isset($data['input_share_doc_img_'.$j.'_'.$i])) {
              $this->db->query("UPDATE " . DB_PREFIX . "product_share SET imgurl" .$i ." = '" . $this->db->escape($data['input_share_doc_img_'.$j.'_'.$i]) . "' WHERE prdshare_id = '" . $prdshare_id . "'");
            }
          }
        }

      }
    }

		$this->cache->delete('product');

		$this->event->trigger('post.supplier.product.add', $product_id);

		return $product_id;
}

	public function editProductNew($product_id, $data) {
		$this->event->trigger('pre.supplier.product.edit', $product_id);

		$this->db->query("UPDATE " . DB_PREFIX . "product SET product_type_id = '" . $this->db->escape($data['input_model']) . "', fromwhere = '" . $this->db->escape($data['input_product_place']) . "', origin_place_id = '" . $this->db->escape($data['input_product_place_shipment']) . "', quantity = '" . $data['input_product_stock'] . "', status = '0', weight = '" . $this->db->escape($data['input_weight']) . "', market_price = '" . $data['input_product_price_market'] .  "', storeprice = '" . $data['input_product_price_store'] . "', shareLevel = '" . $data['input_product_recommand_index'] . "', volume = '" . $data['input_volume']  . "', tax_charge = '" . $data['input_product_tax'] . "', charge_type = '" . (int)$data['input_charge_mode'] . "', return_limit = '" . (int)$data['input_product_return_deadline']  . "', feedback_reward = '" . $data['input_product_reward']  . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['input_upload_img_title'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['input_upload_img_title']) . "' WHERE product_id = '" . (int)$product_id . "'");
    }

    if (isset($data['input_name'])){
      $this->db->query("UPDATE " . DB_PREFIX . "product_description SET name = '" . $this->db->escape($data['input_name']) . "' WHERE product_id = '" . (int)$product_id . "'" );
    } 



		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$img_total = $query->row['total'];

		$sql = "SELECT pi.product_image_id FROM " . DB_PREFIX . "product_image pi WHERE pi.product_id = '" . (int)$product_id . "'";

		$query = $this->db->query($sql);
		$imgs = $query->rows;

    if ( $img_total >= 5 ) {
      for($i = 1; $i <= 5; $i++){
        if (isset($data['input_product_img_sub_'.$i]) && (!empty($data['input_product_img_sub_'.$i]))) {
        //if (isset($data['input_product_img_sub_'.$i])) {
          $this->db->query("UPDATE " . DB_PREFIX . "product_image SET image = '" . $this->db->escape($data['input_product_img_sub_'.$i]) . "', sort_order = '" . $i . "' WHERE product_id = '" . (int)$product_id . "' AND product_image_id = '" . $imgs[$i-1]['product_image_id'] . "'");
        } else {
          //TODO delete the record, don't forget to delete file
        }
      }
    } else {
       for($i = 1; $i <= $img_total; $i++){
        if (isset($data['input_product_img_sub_'.$i]) && (!empty($data['input_product_img_sub_'.$i]))) {
        //if (isset($data['input_product_img_sub_'.$i])) {
          $this->db->query("UPDATE " . DB_PREFIX . "product_image SET image = '" . $this->db->escape($data['input_product_img_sub_'.$i]) . "', sort_order = '" . $i . "' WHERE product_id = '" . (int)$product_id . "' AND product_image_id = '" . $imgs[$i-1]['product_image_id'] . "'");
        } else {
        //TODO delete related record
        }
       }
       for($i = ($img_total+1); $i <= 5; $i++){
        if (isset($data['input_product_img_sub_'.$i]) && (!empty($data['input_product_img_sub_'.$i]))) {
          $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($data['input_product_img_sub_'.$i]) . "', sort_order = '" . $i . "'");
        } else {
        //TODO delete related record
        } 
       }
    }



    if (isset($data['total_sharedocs'])){
      $total_sharedocs = $data['total_sharedocs'];
      for($j=1;$j<=$total_sharedocs;$j++){

        if (isset($data['input_share_doc_'.$j]) && !empty($data['input_share_doc_'.$j]) ) { 
          if ( isset($data['input_sharedoc_title_'.$j]) && isset($data['input_sharedoc_memo_'.$j])){
            $this->db->query("UPDATE " . DB_PREFIX . "product_share SET product_id = '" . (int)$product_id . "', title = '" . $this->db->escape($data['input_sharedoc_title_'.$j]) . "', memo = '" . $this->db->escape($data['input_sharedoc_memo_'.$j]) . "' WHERE prdshare_id = '" . $this->db->escape($data['input_share_doc_'.$j]) . "'");
            
            
            for($i = 1; $i <= 9; $i++){
              if (isset($data['input_share_doc_img_'.$j.'_'.$i])) {
                $this->db->query("UPDATE " . DB_PREFIX . "product_share SET imgurl" .$i ." = '" . $this->db->escape($data['input_share_doc_img_'.$j.'_'.$i]) . "' WHERE prdshare_id = '" . $this->db->escape($data['input_share_doc_'.$j]) . "'");
              }
            }
          }
        } else {
           if ( isset($data['input_sharedoc_title_'.$j]) && isset($data['input_sharedoc_memo_'.$j]) && !empty($data['input_sharedoc_title_'.$j]) && !empty($data['input_sharedoc_memo_'.$j]) ){
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_share SET product_id = '" . (int)$product_id . "', title = '" . $this->db->escape($data['input_sharedoc_title_'.$j]) . "', memo = '" . $this->db->escape($data['input_sharedoc_memo_'.$j]) . "'");
            
            $prdshare_id = $this->db->getLastId();
            
            for($i = 1; $i <= 9; $i++){
              if (isset($data['input_share_doc_img_'.$j.'_'.$i])) {
                $this->db->query("UPDATE " . DB_PREFIX . "product_share SET imgurl" .$i ." = '" . $this->db->escape($data['input_share_doc_img_'.$j.'_'.$i]) . "' WHERE prdshare_id = '" . $prdshare_id . "'");
              }
            }
          }
        }

      }
    }




		$this->cache->delete('product');

		$this->event->trigger('post.supplier.product.edit', $product_id);

}















	public function editProduct($product_id, $data) {
		$this->event->trigger('pre.admin.product.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");

		if (!empty($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = " . (int)$product_id);

		if (isset($data['product_recurrings'])) {
			foreach ($data['product_recurrings'] as $recurring) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int)$product_id . ", customer_group_id = " . (int)$recurring['customer_group_id'] . ", `recurring_id` = " . (int)$recurring['recurring_id']);
			}
		}

		$this->cache->delete('product');

		$this->event->trigger('post.admin.product.edit', $product_id);
	}

	public function copyProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		if ($query->num_rows) {
			$data = array();

			$data = $query->row;

			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';

			$data = array_merge($data, array('product_attribute' => $this->getProductAttributes($product_id)));
			$data = array_merge($data, array('product_description' => $this->getProductDescriptions($product_id)));
			$data = array_merge($data, array('product_discount' => $this->getProductDiscounts($product_id)));
			$data = array_merge($data, array('product_filter' => $this->getProductFilters($product_id)));
			$data = array_merge($data, array('product_image' => $this->getProductImages($product_id)));
			$data = array_merge($data, array('product_option' => $this->getProductOptions($product_id)));
			$data = array_merge($data, array('product_related' => $this->getProductRelated($product_id)));
			$data = array_merge($data, array('product_reward' => $this->getProductRewards($product_id)));
			$data = array_merge($data, array('product_special' => $this->getProductSpecials($product_id)));
			$data = array_merge($data, array('product_category' => $this->getProductCategories($product_id)));
			$data = array_merge($data, array('product_download' => $this->getProductDownloads($product_id)));
			$data = array_merge($data, array('product_layout' => $this->getProductLayouts($product_id)));
			$data = array_merge($data, array('product_store' => $this->getProductStores($product_id)));
			$data = array_merge($data, array('product_recurrings' => $this->getRecurrings($product_id)));

			$this->addProduct($data);
		}
	}

  public function deleteProductNew($product_id) {
		$this->event->trigger('pre.admin.product.delete', $product_id);
		$this->cache->delete('product');
    
    //$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
    //liuhang: a temp solution for product delete: only set supplier_id to a invaild value 
    $this->db->query("UPDATE " . DB_PREFIX . "product SET supplier_id = '0' WHERE product_id = '" . (int)$product_id . "'");
  
    $this->event->trigger('post.admin.product.delete', $product_id);
  }











	public function deleteProduct($product_id) {
		$this->event->trigger('pre.admin.product.delete', $product_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_recurring WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");

		$this->cache->delete('product');

		$this->event->trigger('post.admin.product.delete', $product_id);
	}

	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category ptc ON (p.product_id = ptc.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getProducts($data = array()) {
    
    $sql = 'SELECT p.product_id,p.product_no, pd.name, p.market_price, p.quantity, p.fromwhere, p.storeprice, p.price,'.
		' ( select count(1) from '. DB_PREFIX .'customer_ophistory t where t.operation_type=0 and t.product_id=p.product_id ) as sales,'.
		 '( select count(1) from '.getTable('customer_ophistory').' n where n.product_id = p.product_id and n.operation_type = 1 ) document,'.
		'( select count(1) from '.getTable('review').' m where m.product_id = p.product_id )  comments, p.shareLevel, p.status, p.product_type_id,p.return_limit, p.feedback_reward, p.image, p.origin_place_id FROM ' . DB_PREFIX . 'product p';

    //if (!empty($data['filter_product_category'])) {
      //$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category ptc ON (p.product_id = ptc.product_id) ";
    //}

    $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

/*
		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}
    
    if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}
 */
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

    /****** liuhang added  **/
		if (!empty($data['filter_product_no'])) {
			$sql .= " AND p.product_no = '" . $this->db->escape($data['filter_product_no']) . "'";
		}
  
    if (!empty($data['filter_product_category'])) {
			$sql .= " AND p.product_type_id = '" . $this->db->escape($data['filter_product_category']) . "'";
    }

    if (!empty($data['filter_quantity_min'])) {
			$sql .= " AND p.quantity >= '" . $this->db->escape($data['filter_quantity_min']) . "'";
		}
    
    if (!empty($data['filter_quantity_max'])) {
			$sql .= " AND p.quantity <= '" . $this->db->escape($data['filter_quantity_max']) . "'";
		}
    
    if (!empty($data['filter_product_price_market_min'])) {
			$sql .= " AND p.market_price >= '" . $this->db->escape($data['filter_product_price_market_min']) . "'";
		}
    
    if (!empty($data['filter_product_price_market_max'])) {
			$sql .= " AND p.market_price <= '" . $this->db->escape($data['filter_product_price_market_max']) . "'";
		}
  
    if (!empty($data['filter_product_sales_min'])) {
			$sql .= " AND p.sales >= '" . $this->db->escape($data['filter_product_sales_min']) . "'";
		}

    if (!empty($data['filter_product_sales_max'])) {
			$sql .= " AND p.sales <= '" . $this->db->escape($data['filter_product_sales_max']) . "'";
		}
 
    if (!empty($data['filter_origin'])) {
			$sql .= " AND p.fromwhere = '" . $this->db->escape($data['filter_origin']) . "'";
		}
    
    if (!empty($data['filter_product_price_supplier_min'])) {
			$sql .= " AND p.storeprice >= '" . $this->db->escape($data['filter_product_price_supplier_min']) . "'";
		}
  
    if (!empty($data['filter_product_price_supplier_max'])) {
			$sql .= " AND p.storeprice <= '" . $this->db->escape($data['filter_product_price_supplier_max']) . "'";
		}
  
    if (!empty($data['filter_product_comments_min'])) {
			$sql .= " AND p.comments >= '" . $this->db->escape($data['filter_product_comments_min']) . "'";
		}

    if (!empty($data['filter_product_comments_max'])) {
			$sql .= " AND p.comments <= '" . $this->db->escape($data['filter_product_comments_max']) . "'";
		}

    if (!empty($data['filter_product_recommend_index_min'])) {
			$sql .= " AND p.shareLevel >= '" . $this->db->escape($data['filter_product_recommend_index_min']) . "'";
		}

    if (!empty($data['filter_product_recommend_index_max'])) {
			$sql .= " AND p.shareLevel <= '" . $this->db->escape($data['filter_product_recommend_index_max']) . "'";
		}

    if (!empty($data['supplier_id'])) {
			$sql .= " AND p.supplier_id = '" . $this->db->escape($data['supplier_id']) . "'";
		}



		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
      'pd.name',
      'p.product_id',
			'p.product_no',
      //'p.model',
      'ptc.category_id',
      //'p.price',
      'p.market_price',
			'p.quantity',
			'p.status',
			'p.sort_order',
	    'p.fromwhere',       
      'p.storeprice',
      'p.sales',        
      'p.document',     
      'p.comments',
      'p.shareLevel'     
	);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
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
		//echo $sql;
		$query = $this->db->query($sql);

		return $query->rows;
	}


	public function getProductsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getProductDescriptions($product_id) {
		$product_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $product_description_data;
	}

	public function getProductCategories($product_id) {
		$product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}

	public function getProductFilters($product_id) {
		$product_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}

		return $product_filter_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_data = array();

		$product_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' GROUP BY attribute_id");

		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();

			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}

			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}

		return $product_attribute_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "'");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity, priority, price");

		return $query->rows;
	}

	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");

		return $query->rows;
	}

	public function getProductRewards($product_id) {
		$product_reward_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $product_reward_data;
	}

	public function getProductDownloads($product_id) {
		$product_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}

		return $product_download_data;
	}

	public function getProductStores($product_id) {
		$product_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}

		return $product_store_data;
	}

	public function getProductLayouts($product_id) {
		$product_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $product_layout_data;
	}

	public function getProductRelated($product_id) {
		$product_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}

	public function getRecurrings($product_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getLastProductId() {

    $sql = "SELECT * FROM " . DB_PREFIX . "product p ";

		$product_id = $this->db->getLastId();
 
    return ($product_id + 1);

  }

	public function getTotalProducts($data = array()) {

    $sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

    //$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category ptc ON (p.product_id = ptc.product_id) ";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}
/*
		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}
 */

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

    /****** liuhang added  **/
		if (!empty($data['filter_id'])) {
			$sql .= " AND p.product_id = '" . $this->db->escape($data['filter_id']) . "'";
		}
  
    if (!empty($data['filter_product_category'])) {
			//$sql .= " AND ptc.category_id = '" . $this->db->escape($data['filter_product_category']) . "'";
			$sql .= " AND p.product_type_id = '" . $this->db->escape($data['filter_product_category']) . "'";
    }

    if (!empty($data['filter_quantity_min'])) {
			$sql .= " AND p.quantity >= '" . $this->db->escape($data['filter_quantity_min']) . "'";
		}
    
    if (!empty($data['filter_quantity_max'])) {
			$sql .= " AND p.quantity <= '" . $this->db->escape($data['filter_quantity_max']) . "'";
		}
    
    if (!empty($data['filter_product_price_market_min'])) {
			$sql .= " AND p.market_price >= '" . $this->db->escape($data['filter_product_price_market_min']) . "'";
		}
    
    if (!empty($data['filter_product_price_market_max'])) {
			$sql .= " AND p.market_price <= '" . $this->db->escape($data['filter_product_price_market_max']) . "'";
		}
  
    if (!empty($data['filter_product_sales_min'])) {
			$sql .= " AND p.sales >= '" . $this->db->escape($data['filter_product_sales_min']) . "'";
		}

    if (!empty($data['filter_product_sales_max'])) {
			$sql .= " AND p.sales <= '" . $this->db->escape($data['filter_product_sales_max']) . "'";
		}
 
    if (!empty($data['filter_origin'])) {
			$sql .= " AND p.fromwhere = '" . $this->db->escape($data['filter_origin']) . "'";
		}
    
    if (!empty($data['filter_product_price_supplier_min'])) {
			$sql .= " AND p.storeprice >= '" . $this->db->escape($data['filter_product_price_supplier_min']) . "'";
		}
  
    if (!empty($data['filter_product_price_supplier_max'])) {
			$sql .= " AND p.storeprice <= '" . $this->db->escape($data['filter_product_price_supplier_max']) . "'";
		}
  
    if (!empty($data['filter_product_comments_min'])) {
			$sql .= " AND p.comments >= '" . $this->db->escape($data['filter_product_comments_min']) . "'";
		}

    if (!empty($data['filter_product_comments_max'])) {
			$sql .= " AND p.comments <= '" . $this->db->escape($data['filter_product_comments_max']) . "'";
		}

    if (!empty($data['filter_product_recommend_index_min'])) {
			$sql .= " AND p.shareLevel >= '" . $this->db->escape($data['filter_product_recommend_index_min']) . "'";
		}

    if (!empty($data['filter_product_recommend_index_max'])) {
			$sql .= " AND p.shareLevel <= '" . $this->db->escape($data['filter_product_recommend_index_max']) . "'";
		}

    if (!empty($data['supplier_id'])) {
			$sql .= " AND p.supplier_id = '" . $this->db->escape($data['supplier_id']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalProductsByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByProfileId($recurring_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_recurring WHERE recurring_id = '" . (int)$recurring_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
  }

	public function getTotalProductsById($product_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");

		return $query->row['total'];
  }

  public function getCategories(){

		$sql = "SELECT c.category_id, cd.name FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$query = $this->db->query($sql);

		return $query->rows;
  }

  public function getCategoryNameByCId($category_id){

		$sql = "SELECT cd.name FROM " . DB_PREFIX . "category_description cd WHERE cd.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$query = $this->db->query($sql);

		return $query->row['name'];
  }

  public function getCountries(){

		$sql = "SELECT c.country_id, c.name FROM " . DB_PREFIX . "country c ";

		$query = $this->db->query($sql);

		return $query->rows;
  }

  public function getOriginPlaces(){

		$sql = "SELECT c.origin_place_id, c.place_name FROM " . DB_PREFIX . "origin_place c ";

		$query = $this->db->query($sql);

		return $query->rows;
  }

  public function getFromwherePlaces(){

		$sql = "SELECT c.fromwhere_id, c.place_name FROM " . DB_PREFIX . "fromwhere c ";

		$query = $this->db->query($sql);

		return $query->rows;
  }


  public function getOriginalplaceById($origin_place_id){

		$sql = "SELECT op.place_name FROM " . DB_PREFIX . "origin_place op WHERE op.origin_place_id = '" . (int)$origin_place_id . "'";

		$query = $this->db->query($sql);

		return $query->row['place_name'];
  }

  public function getCountryById($country_id){

		$sql = "SELECT c.name FROM " . DB_PREFIX . "country c WHERE c.country_id = '" . (int)$country_id . "'";

		$query = $this->db->query($sql);

		return $query->row['name'];
  }

  public function getProductStatus(){

		$sql = "SELECT ps.pstatus_id, ps.name FROM " . DB_PREFIX . "product_status ps ";

		$query = $this->db->query($sql);

		return $query->rows;
  }

  public function getProductTypes(){

		$sql = "SELECT pt.product_type_id, pt.type_name FROM " . DB_PREFIX . "product_type pt WHERE status = 1";

		$query = $this->db->query($sql);

		return $query->rows;
  }

  public function getProductTypeByTId($product_type_id){

		$sql = "SELECT pt.type_name FROM " . DB_PREFIX . "product_type pt WHERE pt.product_type_id = '" . (int)$product_type_id . "'";

		$query = $this->db->query($sql);

		return $query->row['type_name'];
  }

  public function getProductImgById($product_id){

		$sql = "SELECT pi.image FROM " . DB_PREFIX . "product_image pi WHERE pi.product_id = '" . (int)$product_id . "'";

		$query = $this->db->query($sql);

		return $query->rows;
  }

  public function getProductSharedocsById($product_id){

		$sql = "SELECT * FROM " . DB_PREFIX . "product_share ps WHERE ps.product_id = '" . (int)$product_id . "'";

		$query = $this->db->query($sql);

		return $query->rows;
  }

  public function getLastProduct(){
		//$sql = "SELECT * FROM (SELECT * FROM " . DB_PREFIX . "product ORDER BY product_id desc) WHERE rownum=1 ";
		//$sql = "SELECT top 1 * FROM " . DB_PREFIX . "product ORDER BY product_id desc";
		$sql = "SELECT * FROM " . DB_PREFIX . "product ORDER BY product_id desc";

		$query = $this->db->query($sql);

		return $query->rows[0]['product_id'];
  }

  public function addShareDocs($data) {

    $this->event->trigger('pre.supplier.product.addsharedoc', $data);

    if ( isset($data['title']) && isset($data['memo']) && isset($data['product_id']) ){
      $this->db->query("INSERT INTO " . DB_PREFIX . "product_share SET product_id = '" . (int)$data['product_id'] . "', title = '" . $this->db->escape($data['title']) . "', memo = '" . $this->db->escape($data['memo']) . "'");
      
      $prdshare_id = $this->db->getLastId();
      
      for($i = 1; $i <= 9; $i++){
        if (isset($data['img'.$i])) {
          $this->db->query("UPDATE " . DB_PREFIX . "product_share SET imgurl" . $i ." = '" . $this->db->escape($data['img'.$i]) . "' WHERE prdshare_id = '" . $prdshare_id . "'");
        } else {
          $this->db->query("UPDATE " . DB_PREFIX . "product_share SET imgurl" . $i ." = '' WHERE prdshare_id = '" . $prdshare_id . "'");
        }
      }
    }

		$this->cache->delete('product');
		$this->event->trigger('post.supplier.product.addsharedoc', $data);

		return $prdshare_id;
	}
	
	public function getParentSupplier($supplier_id){
		$sql = "SELECT parent_id FROM " . DB_PREFIX . "supplier WHERE supplier_id =". $supplier_id;

		$query = $this->db->query($sql);

		return ($query->rows[0]['parent_id'] == null)?$supplier_id:$query->rows[0]['parent_id'];
  }




















}
