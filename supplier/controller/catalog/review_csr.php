<?php
class ControllerCatalogReviewCsr extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/review_csr');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review_csr');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/review_csr');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review_csr');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_review->addReview($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_product'])) {
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function reply() {
		$this->load->language('catalog/review_csr');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review_csr');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_review->replyReview($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

/*      
			if (isset($this->request->get['filter_product'])) {
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
			}
 */

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

/*      
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}
 */

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}



	public function edit() {
		$this->load->language('catalog/review_csr');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review_csr');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_review->editReview($this->request->get['review_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_product'])) {
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/review_csr');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review_csr');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $review_id) {
				$this->model_catalog_review->deleteReview($review_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_product'])) {
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_author'])) {
				$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
    
    
    /*
    if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		} else {
			$filter_product = null;
		}

		if (isset($this->request->get['filter_author'])) {
			$filter_author = $this->request->get['filter_author'];
		} else {
			$filter_author = null;
		}
     */

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = "*";
		}

    /*
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}
     */

		if (isset($this->request->get['filter_product_code'])) {
			$filter_product_code = $this->request->get['filter_product_code'];
		} else {
			$filter_product_code = null;
		}

		if (isset($this->request->get['filter_user_account'])) {
			$filter_user_account = $this->request->get['filter_user_account'];
		} else {
			$filter_user_account = null;
		}

		if (isset($this->request->get['filter_product_name'])) {
			$filter_product_name = $this->request->get['filter_product_name'];
		} else {
			$filter_product_name = null;
		}

    if (isset($this->request->get['filter_key_word'])) {
			$filter_key_word = $this->request->get['filter_key_word'];
		} else {
			$filter_key_word = null;
		}

    if (isset($this->request->get['filter_key_word_checkbox'])) {
			$filter_key_word_checkbox = $this->request->get['filter_key_word_checkbox'];
		} else {
			$filter_key_word_checkbox = 1;
		}

    if (isset($this->request->get['filter_product_type'])) {
			$filter_product_type = $this->request->get['filter_product_type'];
		} else {
			$filter_product_type = null;
		}

    if (isset($this->request->get['filter_start_time'])) {
			$filter_start_time = $this->request->get['filter_start_time'];
		} else {
			$filter_start_time = null;
		}

    if (isset($this->request->get['filter_end_time'])) {
			$filter_end_time = $this->request->get['filter_end_time'];
		} else {
			$filter_end_time = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'r.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

    /*
		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}
    */

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

    /*
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
    */
    if (isset($this->request->get['filter_product_code'])) {
			$url .= '&filter_product_code=' . $this->request->get['filter_product_code'];
		}

    if (isset($this->request->get['filter_user_account'])) {
			$url .= '&filter_user_account=' . $this->request->get['filter_user_account'];
		}

    if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . $this->request->get['filter_product_name'];
		}

    if (isset($this->request->get['filter_key_word'])) {
			$url .= '&filter_key_word=' . $this->request->get['filter_key_word'];
		}

    if (isset($this->request->get['filter_key_word_checkbox'])) {
			$url .= '&filter_key_word_checkbox=' . $this->request->get['filter_key_word_checkbox'];
		}

    if (isset($this->request->get['filter_product_type'])) {
			$url .= '&filter_product_type=' . $this->request->get['filter_product_type'];
		}

    if (isset($this->request->get['filter_start_time'])) {
			$url .= '&filter_start_time=' . $this->request->get['filter_start_time'];
		}

    if (isset($this->request->get['filter_end_time'])) {
			$url .= '&filter_end_time=' . $this->request->get['filter_end_time'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['add'] = $this->url->link('catalog/review_csr/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/review_csr/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['reviews'] = array();

		$filter_data = array(
			//'filter_product'    => $filter_product,
			//'filter_author'     => $filter_author,
			'filter_status'     => $filter_status,
      //'filter_date_added' => $filter_date_added,
      'filter_product_code'         => $filter_product_code,
      'filter_user_account'         => $filter_user_account,
      'filter_product_name'         => $filter_product_name,
      'filter_key_word'             => $filter_key_word,
      'filter_key_word_checkbox'             => $filter_key_word_checkbox,
      'filter_product_type'     => $filter_product_type,
      'filter_start_time'           => $filter_start_time,
      'filter_end_time'             => $filter_end_time,
			'sort'                        => $sort,
			'order'                       => $order,
			'start'                       => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                       => $this->config->get('config_limit_admin')
		);

    //$categories = $this->model_catalog_review_csr->getCategories(); 
    $product_types = $this->model_catalog_review_csr->getProductTypes(); 

		$review_total = $this->model_catalog_review_csr->getTotalReviews($filter_data);
		$results = $this->model_catalog_review_csr->getReviews($filter_data);

    /*
		foreach ($categories as $category) {
			$data['categories'][] = array(
				'cid'        => $category['category_id'],
				'cname'      => $category['name'],
			);
    }
    */

		foreach ($product_types as $product_type) {
			$data['product_types'][] = array(
				'cid'        => $product_type['product_type_id'],
				'cname'      => $product_type['type_name'],
			);
		}

    //TODO
    foreach ($results as $result) {

      $action_link = "";
      if ($result['status'] == 1){ // 审核不通过！
        $action_title = "通过";
        $action_link = $this->url->link('catalog/review_csr/enable', 'token=' . $this->session->data['token'] . '&review_id=' . $result['coh_id'] . $url, 'SSL');
      } else if ($result['status'] == 0) { // 审核通过！
        $action_title = "下架";
        $action_link = $this->url->link('catalog/review_csr/disable', 'token=' . $this->session->data['token'] . '&review_id=' . $result['coh_id'] . $url, 'SSL');
      } else { //无效状态！
        $action_title = "";
        $action_link = "";
      }

			$data['reviews'][] = array(
				'review_id'  => $result['coh_id'],
				//'name'       => $result['name'],
				//'author'     => $result['author'],
				//'rating'     => $result['rating'],
				'pid'        => $result['product_id'],
				'name'       => $result['name'],
				'cid'        => $result['customer_id'],
				'cname'      => $result['fullname'], //customer fullname
				'content'    => $result['comments'],
				'status'     => ($result['status']) ? $this->language->get('text_disabled_csr') : $this->language->get('text_enabled_csr'),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['createTime'])),
				'action_title' => $action_title,
				'action'       => $action_link,
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_enabled_csr'] = $this->language->get('text_enabled_csr');
		$data['text_disabled_csr'] = $this->language->get('text_disabled_csr');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_author'] = $this->language->get('column_author');
		$data['column_rating'] = $this->language->get('column_rating');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_product_id'] = $this->language->get('column_product_id');
		$data['column_review_content'] = $this->language->get('column_review_content');

		//$data['entry_product'] = $this->language->get('entry_product');
		//$data['entry_author'] = $this->language->get('entry_author');
		//$data['entry_rating'] = $this->language->get('entry_rating');
		$data['entry_status'] = $this->language->get('entry_status');
		//$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_product_code'] = $this->language->get('entry_product_code');
		$data['entry_user_account'] = $this->language->get('entry_user_account');
		$data['entry_product_name'] = $this->language->get('entry_product_name');
		$data['entry_product_category'] = $this->language->get('entry_product_category');
		$data['entry_key_word'] = $this->language->get('entry_key_word');
		$data['text_keyword_cb'] = $this->language->get('text_keyword_cb');
		$data['entry_review_date'] = $this->language->get('entry_review_date');
    
    $data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_query'] = $this->language->get('button_query');
		$data['button_review_edit'] = $this->language->get('button_review_edit');
		$data['button_review_reply'] = $this->language->get('button_review_reply');
		$data['button_review_enable'] = $this->language->get('button_review_enable');
		$data['button_review_disable'] = $this->language->get('button_review_disable');
		$data['button_review_abort'] = $this->language->get('button_review_abort');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_pid'] = $this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . '&sort=p.product_id' . $url, 'SSL');
		$data['sort_pname'] = $this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$data['sort_sid'] = $this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . '&sort=p.supplier_id' . $url, 'SSL');
		//$data['sort_author'] = $this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . '&sort=r.author' . $url, 'SSL');
		//$data['sort_rating'] = $this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . '&sort=r.rating' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . '&sort=r.status' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . '&sort=r.date_added' . $url, 'SSL');

		$url = '';

    /*
		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}
     */
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
    /*
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
    */
		if (isset($this->request->get['filter_product_code'])) {
			$url .= '&filter_product_code=' . $this->request->get['filter_product_code'];
		}

		if (isset($this->request->get['filter_user_account'])) {
			$url .= '&filter_user_account=' . $this->request->get['filter_user_account'];
		}

		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . $this->request->get['filter_product_name'];
		}

		if (isset($this->request->get['filter_product_type'])) {
			$url .= '&filter_product_type=' . $this->request->get['filter_product_type'];
		}

		if (isset($this->request->get['filter_start_time'])) {
			$url .= '&filter_start_time=' . $this->request->get['filter_start_time'];
		}

		if (isset($this->request->get['filter_end_time'])) {
			$url .= '&filter_end_time=' . $this->request->get['filter_end_time'];
		}

		if (isset($this->request->get['filter_key_word'])) {
			$url .= '&filter_key_word=' . $this->request->get['filter_key_word'];
		}

		if (isset($this->request->get['filter_key_word_checkbox'])) {
			$url .= '&filter_key_word_checkbox=' . $this->request->get['filter_key_word_checkbox'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($review_total - $this->config->get('config_limit_admin'))) ? $review_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $review_total, ceil($review_total / $this->config->get('config_limit_admin')));
		//$data['results'] = $review_total; //liuhang -> for debug

		//$data['filter_product'] = $filter_product;
		//$data['filter_author'] = $filter_author;
		$data['filter_status'] = $filter_status;
		//$data['filter_date_added'] = $filter_date_added;
		$data['filter_product_code'] = $filter_product_code;
		$data['filter_user_account'] = $filter_user_account;
		$data['filter_product_name'] = $filter_product_name;
		$data['filter_key_word'] = $filter_key_word;
		$data['filter_key_word_checkbox'] = $filter_key_word_checkbox;
		$data['filter_product_type'] = $filter_product_type;
		$data['filter_start_time'] = $filter_start_time;
		$data['filter_end_time'] = $filter_end_time;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/review_list_csr.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['review_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_author'] = $this->language->get('entry_author');
		$data['entry_rating'] = $this->language->get('entry_rating');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_text'] = $this->language->get('entry_text');

		$data['help_product'] = $this->language->get('help_product');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['product'])) {
			$data['error_product'] = $this->error['product'];
		} else {
			$data['error_product'] = '';
		}

		if (isset($this->error['author'])) {
			$data['error_author'] = $this->error['author'];
		} else {
			$data['error_author'] = '';
		}

		if (isset($this->error['text'])) {
			$data['error_text'] = $this->error['text'];
		} else {
			$data['error_text'] = '';
		}

		if (isset($this->error['rating'])) {
			$data['error_rating'] = $this->error['rating'];
		} else {
			$data['error_rating'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		if (!isset($this->request->get['review_id'])) {
			$data['action'] = $this->url->link('catalog/review_csr/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/review_csr/edit', 'token=' . $this->session->data['token'] . '&review_id=' . $this->request->get['review_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['review_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$review_info = $this->model_catalog_review->getReview($this->request->get['review_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$data['product_id'] = $this->request->post['product_id'];
		} elseif (!empty($review_info)) {
			$data['product_id'] = $review_info['product_id'];
		} else {
			$data['product_id'] = '';
		}

		if (isset($this->request->post['product'])) {
			$data['product'] = $this->request->post['product'];
		} elseif (!empty($review_info)) {
			$data['product'] = $review_info['product'];
		} else {
			$data['product'] = '';
		}

		if (isset($this->request->post['author'])) {
			$data['author'] = $this->request->post['author'];
		} elseif (!empty($review_info)) {
			$data['author'] = $review_info['author'];
		} else {
			$data['author'] = '';
		}

		if (isset($this->request->post['text'])) {
			$data['text'] = $this->request->post['text'];
		} elseif (!empty($review_info)) {
			$data['text'] = $review_info['text'];
		} else {
			$data['text'] = '';
		}

		if (isset($this->request->post['rating'])) {
			$data['rating'] = $this->request->post['rating'];
		} elseif (!empty($review_info)) {
			$data['rating'] = $review_info['rating'];
		} else {
			$data['rating'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($review_info)) {
			$data['status'] = $review_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/review_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/review_csr')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['product_id']) {
			$this->error['product'] = $this->language->get('error_product');
		}

		if ((utf8_strlen($this->request->post['author']) < 1) || (utf8_strlen($this->request->post['author']) > 64)) {
			$this->error['author'] = $this->language->get('error_author');
		}

		if (utf8_strlen($this->request->post['text']) < 1) {
			$this->error['text'] = $this->language->get('error_text');
		}

		if (!isset($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
			$this->error['rating'] = $this->language->get('error_rating');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/review_csr')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function disable() {
		$this->load->language('catalog/review_csr');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review_csr');

		if (isset($this->request->get['review_id'])) {
			$this->model_catalog_review_csr->disableReview($this->request->get['review_id']);

			$this->session->data['success'] = "分享报告下架成功！";

			$url = '';

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}


	public function abort() {
		$this->load->language('catalog/review_csr');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review_csr');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_review_csr->abortReview($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}


	public function enable() {
		$this->load->language('catalog/review_csr');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review_csr');

		if (isset($this->request->get['review_id'])) {
			$this->model_catalog_review_csr->enableReview($this->request->get['review_id']);

			$this->session->data['success'] = "分享报告审核通过！";

			$url = '';

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/review_csr', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}







































}
