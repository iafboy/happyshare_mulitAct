<?php
class ControllerBrandAdd extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('brand/add');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('brand/add');

		$this->getList();
	}

	public function add() {
		$this->load->language('brand/add');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('brand/add');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

      $info = array(
        'img_src' => $this->request->post['input_upload_img'],
        'input_url' => $this->request->post['input_url'],
        'input_seq' => $this->request->post['input_seq'],
        'supplier_id' => $this->session->data['supplier_id']
      );

      $this->model_brand_add->addBanner($info);
			$this->session->data['success'] = $this->language->get('text_success_add');
    }

		$this->getList();
	}

	public function delete() {
		$this->load->language('brand/add');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('brand/add');

		if (isset($this->request->get['banner_id'])) {

      $results = $this->model_brand_add->delBanner($this->request->get['banner_id']);
			$this->session->data['success'] = $this->language->get('text_success_delete');
		}

		$this->getList();
	}

	public function enable() {

		$this->load->language('brand/add');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('brand/add');

		if (isset($this->request->get['banner_id'])) {

      $results = $this->model_brand_add->enableBanner($this->request->get['banner_id']);
			$this->session->data['success'] = $this->language->get('text_success_enable');
		}

		$this->getList();
	}

	public function disable() {
		$this->load->language('brand/add');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('brand/add');

		if (isset($this->request->get['banner_id'])) {

      $results = $this->model_brand_add->disableBanner($this->request->get['banner_id']);
			$this->session->data['success'] = $this->language->get('text_success_disable');
		}

		$this->getList();
	}

	protected function getList() {

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pwb.sort_order';
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
			'href' => $this->url->link('brand/add', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('brand/add/add', 'token=' . $this->session->data['token'] . $url , 'SSL');
		//$data['enable'] = $this->url->link('brand/add/enable', 'token=' . $this->session->data['token'] . $url . "&banner_id=", 'SSL');
		//$data['disable'] = $this->url->link('brand/add/disable', 'token=' . $this->session->data['token'] . $url . "&banner_id=", 'SSL');

		$data['banners'] = array();

		$filter_data = array(
			'supplier_id'     => $this->session->data['supplier_id'],
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
    );

		$this->load->model('tool/image');
    $this->load->model('brand/add');    
    
    $total = $this->model_brand_add->getTotalBanners($filter_data); 
    $results = $this->model_brand_add->getBanners($filter_data);

    $data['total'] = $total;
		foreach ($results as $result) {

      $data['banners'][] = array(
				'id'         => $result['brandbanner_id'],
				'img_src'    => HTTP_CATALOG."image/".$result['image'],
				'url'        => $result['link'],
				'seq'        => $result['sort_order'],
			  'status'     => $result['enable_status'],
				'enable'       => $this->url->link('brand/add/enable', 'token=' . $this->session->data['token'] . $url . "&banner_id=" . $result['brandbanner_id'], 'SSL'),
				'disable'      => $this->url->link('brand/add/disable', 'token=' . $this->session->data['token'] . $url . "&banner_id=" . $result['brandbanner_id'], 'SSL'),
				'delete'       => $this->url->link('brand/add/delete', 'token=' . $this->session->data['token'] . $url . "&banner_id=" . $result['brandbanner_id'], 'SSL')
			);
	    
    }

		$data['heading_title'] = $this->language->get('heading_title');
    
    $data['text_list'] = $this->language->get('text_list');
    $data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['column_lvl1'] = $this->language->get('column_lvl1');
		$data['column_image'] = $this->language->get('column_image');
		$data['column_link'] = $this->language->get('column_link');
		$data['column_seq'] = $this->language->get('column_seq');
		$data['column_ops'] = $this->language->get('column_ops');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_enable'] = $this->language->get('button_enable');
		$data['button_disable'] = $this->language->get('button_disable');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

    if (isset($this->error['text'])) {
			$data['error_warning'] = $this->error['text'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		//$data['sort_name'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
    
    $url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		//$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

    $data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('brand/add.tpl', $data));
	}
  
  protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'brand/add')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

    //if (!$this->request->post['img_src']) {
    if (!isset($this->request->post['input_upload_img'])) {
			//$this->error['text'] = $this->language->get('error_text_img');
    }

		//if (!is_numeric($this->request->post['input_seq'])) {
		if (!is_numeric($this->request->post['input_seq'])) {
			$this->error['text'] = $this->language->get('error_text_seq');
		}

    /*the correct url should be like this   "http://"  */
		//if (utf8_strlen($this->request->post['input_url']) < 7) {
		//if (utf8_strlen($this->request->post['input_url']) < 7) {
		//	$this->error['text'] = $this->language->get('error_text_url');
		//}

		return !$this->error;
	}


}

