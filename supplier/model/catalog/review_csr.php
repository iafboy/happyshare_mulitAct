<?php
class ModelCatalogReviewCsr extends Model {

	public function getReview($review_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT pd.name FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = r.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS product FROM " . DB_PREFIX . "review r WHERE r.review_id = '" . (int)$review_id . "'");

		return $query->row;
	}

	public function getReviews($data = array()) {
    
    $sql = "SELECT co.coh_id, co.product_id, co.status, pd.name, p.product_type_id, co.customer_id, co.comments, co.createTime, c.fullname FROM " . DB_PREFIX . "customer_ophistory co";

    $sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (co.product_id = p.product_id) ";
    $sql .= " LEFT JOIN " . DB_PREFIX . "customer c ON (co.customer_id = c.customer_id) ";
    $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (co.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'" ;


		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND co.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_product_code'])) {
			$sql .= " AND co.product_id = '" . $this->db->escape($data['filter_product_code']) . "'";
		}

    if (!empty($data['filter_user_account'])) {
			//$sql .= " AND co.customer_id = '" . $this->db->escape($data['filter_user_account']) . "'";
			$sql .= " AND c.fullname LIKE '%" . $this->db->escape($data['filter_user_account']) . "%'";
		}

    if (!empty($data['filter_product_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_product_name']) . "%'";
		}

    if (!empty($data['filter_product_type'])) {
      $sql .= " AND p.product_type_id = '" . $this->db->escape($data['filter_product_type']) . "'";
    }

    if (!empty($data['filter_start_time'])) {
			$sql .= " AND DATE(co.createTime) >= DATE('" . $this->db->escape($data['filter_start_time']) . "')";
		}

    if (!empty($data['filter_end_time'])) {
			$sql .= " AND DATE(co.createTime) <= DATE('" . $this->db->escape($data['filter_end_time']) . "')";
		}

    if (!empty($data['filter_key_word'])) {
      $sql .= " AND co.comments LIKE '%" . $this->db->escape($data['filter_key_word']) . "%'";
      if($data['filter_key_word_checkbox'] == 1){
        $tquery = $this->db->query('select count(1) as count from '. DB_PREFIX .'comment_key');
        $count = $tquery->row['count'];
        if($count > 0){
          $sql .= " and  exists ( select 1 from ". DB_PREFIX . "comment_key f where co.comments like concat('%',f.key_name,'%')) ";
        }
      }
    }



		$sort_data = array(
			//'pd.name',
			//'r.author',
			//'r.rating',
			'co.status',
			'co.product_id',
			'pd.name',
			'co.customer_id',
			'co.createTime'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY co.createTime";
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
	}

	public function getTotalReviews($data = array()) {

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_ophistory co ";

    /*
    if (!empty($data['filter_product_category'])) {
      $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category ptc ON (co.product_id = ptc.product_id) ";
    }
     */

    $sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (co.product_id = p.product_id) ";
    $sql .= " LEFT JOIN " . DB_PREFIX . "customer c ON (co.customer_id = c.customer_id) ";
    $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (co.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'" ;

/*    
		if (!empty($data['filter_product'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product']) . "%'";
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND r.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}
 */

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND co.status = '" . (int)$data['filter_status'] . "'";
		}

/*    
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
 */
		if (!empty($data['filter_product_code'])) {
			$sql .= " AND co.product_id = '" . $this->db->escape($data['filter_product_code']) . "'";
		}

    if (!empty($data['filter_user_account'])) {
			//$sql .= " AND co.customer_id = '" . $this->db->escape($data['filter_user_account']) . "'";
			$sql .= " AND c.fullname LIKE '%" . $this->db->escape($data['filter_user_account']) . "%'";
		}

    if (!empty($data['filter_product_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_product_name']) . "%'";
		}

    if (!empty($data['filter_product_type'])) {
      $sql .= " AND p.product_type_id = '" . $this->db->escape($data['filter_product_type']) . "'";
    }

    if (!empty($data['filter_start_time'])) {
			$sql .= " AND DATE(co.createTime) >= DATE('" . $this->db->escape($data['filter_start_time']) . "')";
		}

    if (!empty($data['filter_end_time'])) {
			$sql .= " AND DATE(co.createTime) <= DATE('" . $this->db->escape($data['filter_end_time']) . "')";
		}

    if (!empty($data['filter_key_word'])) {
      $sql .= " AND co.comments LIKE '%" . $this->db->escape($data['filter_key_word']) . "%'";
      if($data['filter_key_word_checkbox'] == 1){
        $tquery = $this->db->query('select count(1) as count from '. DB_PREFIX .'comment_key');
        $count = $tquery->row['count'];
        if($count > 0){
          $sql .= " and  exists ( select 1 from ". DB_PREFIX . "comment_key f where co.comments like concat('%',f.key_name,'%')) ";
        }
      }
    }

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalReviewsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review WHERE status = '0'");

		return $query->row['total'];
  }
  
  public function getCategories(){

		$sql = "SELECT c.category_id, cd.name FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$query = $this->db->query($sql);

		return $query->rows;
  }

  public function enableReview($review_id) {
		$this->event->trigger('pre.supplier.review.enableReview', $review_id);

		$this->db->query("UPDATE " . DB_PREFIX . "customer_ophistory SET status = 0 WHERE coh_id = '" . (int)$review_id . "'");

		$this->cache->delete('product');

		$this->event->trigger('post.supplier.review.enableReview', $review_id);

	}


  public function disableReview($review_id) {
		$this->event->trigger('pre.supplier.review.disableReview', $review_id);

		$this->db->query("UPDATE " . DB_PREFIX . "customer_ophistory SET status = 1 WHERE coh_id = '" . (int)$review_id . "'");

		$this->cache->delete('product');

		$this->event->trigger('post.supplier.review.disableReview', $review_id);

		return $review_id;
	}


  public function abortReview($data) {
		$this->event->trigger('pre.admin.review.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['author']) . "', product_id = '" . (int)$data['product_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");

		$review_id = $this->db->getLastId();

		$this->cache->delete('product');

		$this->event->trigger('post.admin.review.add', $review_id);

		return $review_id;
	}

  public function getProductTypes(){

		$sql = "SELECT pt.product_type_id, pt.type_name FROM " . DB_PREFIX . "product_type pt";

		$query = $this->db->query($sql);

		return $query->rows;
  }
 















}
