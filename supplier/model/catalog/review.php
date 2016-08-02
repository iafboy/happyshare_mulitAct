<?php

class ModelCatalogReview extends Model
{
    public function addReview($data)
    {
        $this->event->trigger('pre.supplier.review.add', $data);

        $this->db->query("INSERT INTO " . DB_PREFIX . "review SET product_id = '" . (int)$data['product_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', status = 0, date_added = NOW(), author = '" . "供应商：" . $this->session->data['supplier_id'] . "'");

        $review_id = $this->db->getLastId();

        $this->cache->delete('product');

        $this->event->trigger('post.supplier.review.add', $review_id);

        return $review_id;
    }

    public function replyReview($review_id, $data, $supplier_id)
    {
        $this->event->trigger('pre.supplier.review.reply', $data);

        $reply_info = array(
            'user_id' => $supplier_id,
            'text' => urlencode($data['text']),
            'date_added' => time(),
            'type' => 2,
        );
        $json_data = json_encode($reply_info);

        $this->db->query("UPDATE " . DB_PREFIX . "review SET reply = '" . $json_data . "', status = 1 WHERE review_id = '" . (int)$review_id . "'");

        //$review_id = $this->db->getLastId();

        $this->cache->delete('product');

        //$this->event->trigger('post.supplier.review.add', $review_id);
        $this->event->trigger('post.supplier.review.reply');

        //return $review_id;
    }

    public function editReview($review_id, $data)
    {
        $this->event->trigger('pre.admin.review.edit', $data);

        $this->db->query("UPDATE " . DB_PREFIX . "review SET author = '" . $this->db->escape($data['author']) . "', product_id = '" . (int)$data['product_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE review_id = '" . (int)$review_id . "'");

        $this->cache->delete('product');

        $this->event->trigger('post.admin.review.edit', $review_id);
    }

    public function deleteReview($review_id)
    {
        $this->event->trigger('pre.admin.review.delete', $review_id);

        $this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE review_id = '" . (int)$review_id . "'");

        $this->cache->delete('product');

        $this->event->trigger('post.admin.review.delete', $review_id);
    }

    public function getReview($review_id)
    {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT pd.name FROM " . DB_PREFIX . "product_description pd WHERE pd.product_id = r.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS product FROM " . DB_PREFIX . "review r WHERE r.review_id = '" . (int)$review_id . "'");

        return $query->row;
    }

    public function getReviews($data = array())
    {
        //$sql = "SELECT r.review_id, r.product_id, pd.name, p.product_type_id, r.customer_id, c.fullname, r.text, r.date_added FROM " . DB_PREFIX . "review r";
        $sql = "SELECT r.review_id, p.product_no product_id,p.product_id pro_id, pd.name, p.product_type_id, r.customer_id, c.fullname, r.text,r.reply, r.date_added FROM " . DB_PREFIX . "review r";

        $sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) ";
        $sql .= " LEFT JOIN " . DB_PREFIX . "customer c ON (r.customer_id = c.customer_id) ";


        $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' and r.admin_review=0";

        $sql .= "  and p.supplier_id = '" . $this->session->data['supplier_id'] . "'";
        if (!empty($data['filter_product_code'])) {
            $sql .= " AND p.product_no = '" . $this->db->escape($data['filter_product_code']) . "'";
        }

        if (!empty($data['filter_user_account'])) {
            $sql .= " AND c.fullname LIKE '%" . $this->db->escape($data['filter_user_account']) . "%'";
        }

        if (!empty($data['filter_product_name'])) {
            $sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_product_name']) . "%'";
        }

        if (!empty($data['filter_product_type'])) {
            $sql .= " AND p.product_type_id = '" . $this->db->escape($data['filter_product_type']) . "'";
        }


        if (!empty($data['filter_start_time'])) {
            $sql .= " AND DATE(r.date_added) >= DATE('" . $this->db->escape($data['filter_start_time']) . "')";
        }

        if (!empty($data['filter_end_time'])) {
            $sql .= " AND DATE(r.date_added) <= DATE('" . $this->db->escape($data['filter_end_time']) . "')";
        }

        if (!empty($data['filter_key_word'])) {
            $sql .= " AND r.text LIKE '%" . $this->db->escape($data['filter_key_word']) . "%'";
            if ($data['filter_key_word_checkbox'] == 1) {
                $tquery = $this->db->query('select count(1) as count from ' . DB_PREFIX . 'comment_key');
                $count = $tquery->row['count'];
                if ($count > 0) {
                    $sql .= " and  exists ( select 1 from " . DB_PREFIX . "comment_key f where r.text like concat('%',f.key_name,'%')) ";
                }
            }
        }


        $sort_data = array(
            'r.product_id',
            'pd.name',
            'r.customer_id',
            'r.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY r.date_added";
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

    public function getTotalReviews($data = array())
    {
        //$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) inner JOIN " . DB_PREFIX . "product_to_category ptc ON (r.product_id = ptc.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r ";

        //if (!empty($data['filter_product_category'])) {
        //$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category ptc ON (r.product_id = ptc.product_id) ";
        //}

        $sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) ";
        $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' and r.admin_review=0";

        $sql .= "  and p.supplier_id = '" . $this->session->data['supplier_id'] . "'";
        /*
                if (!empty($data['filter_product'])) {
                    $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product']) . "%'";
                }

                if (!empty($data['filter_author'])) {
                    $sql .= " AND r.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
                }

                if (!empty($data['filter_status'])) {
                    $sql .= " AND r.status = '" . (int)$data['filter_status'] . "'";
                }

                if (!empty($data['filter_date_added'])) {
                    $sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
                }
         */
        if (!empty($data['filter_product_code'])) {
            $sql .= " AND r.product_id = '" . $this->db->escape($data['filter_product_code']) . "'";
        }

        if (!empty($data['filter_user_account'])) {
            $sql .= " AND r.customer_id = '" . $this->db->escape($data['filter_user_account']) . "'";
        }

        if (!empty($data['filter_product_name'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product_name']) . "%'";
        }

        //if (!empty($data['filter_product_category'])) {
        //$sql .= " AND ptc.category_id = '" . $this->db->escape($data['filter_product_category']) . "'";
        //}

        if (!empty($data['filter_product_type'])) {
            $sql .= " AND p.product_type_id = '" . $this->db->escape($data['filter_product_type']) . "'";
        }

        if (!empty($data['filter_start_time'])) {
            $sql .= " AND DATE(r.date_added) >= DATE('" . $this->db->escape($data['filter_start_time']) . "')";
        }

        if (!empty($data['filter_end_time'])) {
            $sql .= " AND DATE(r.date_added) <= DATE('" . $this->db->escape($data['filter_end_time']) . "')";
        }

        if (!empty($data['filter_key_word'])) {
            $sql .= " AND r.text LIKE '%" . $this->db->escape($data['filter_key_word']) . "%'";
            if ($data['filter_key_word_checkbox'] == 1) {
                $tquery = $this->db->query('select count(1) as count from ' . DB_PREFIX . 'comment_key');
                $count = $tquery->row['count'];
                if ($count > 0) {
                    $sql .= " and  exists ( select 1 from " . DB_PREFIX . "comment_key f where r.text like concat('%',f.key_name,'%')) ";
                }
            }
        }


        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalReviewsAwaitingApproval()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review WHERE status = '0'");

        return $query->row['total'];
    }

    public function getCategories()
    {

        $sql = "SELECT c.category_id, cd.name FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getProductTypes()
    {

        $sql = "SELECT pt.product_type_id, pt.type_name FROM " . DB_PREFIX . "product_type pt";

        $query = $this->db->query($sql);

        return $query->rows;
    }


}
