<?php
class ControllerOrderPhone extends MyController {

	/**
	 * @var array
	 *
	 *	Model View Controller
	 *
	 * 	Model :  if load model by $this->load->model('supplier/supplier');
	 *
	 * 		a attribute model_supplier_supplier is gonna be injected into this Class;
	 *
	 * 		As you can see, the rule is modelName.replace('/','_');
	 *
	 *  View :
	 *
	 */

	private $error = array();

	private $module_name = 'order/phone';

	private $base_url= '';


	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->getOrderList($lans);
	}

	public function setUnpaidCloseHour(){
		$this->load->model($this->module_name);
		$hour = $this->request->post['unpaid_close_hour'];
		$result = $this->model_order_phone->updateUnpaidCloseHour($hour);
		writeJson(['success'=>$result]);
	}
	public function changePlaceStatus(){
		$this->load->model($this->module_name);
		$place_id = $this->request->post['place_id'];
		$status = $this->request->post['status'];
		$result = $this->model_order_phone->changePlaceStatus($place_id,$status);
		$place = $this->model_order_phone->queryPlaceById($place_id);
		writeJson(['success'=>$result,'place'=>$place]);
	}
	public function changeProducttypeStatus(){
		$this->load->model($this->module_name);
		$producttype_id = $this->request->post['producttype_id'];
		$status = $this->request->post['status'];
		$result = $this->model_order_phone->changeProducttypeStatus($producttype_id,$status);
		$producttype = $this->model_order_phone->queryProducttypeById($producttype_id);
		writeJson(['success'=>$result,'producttype'=>$producttype]);
	}
	public function addPlace(){
		$this->load->model($this->module_name);
		$place_code = $this->request->post['place_code'];
		$place_name = $this->request->post['place_name'];
		$result = $this->model_order_phone->addPlace($place_code,$place_name);
		$list = $this->model_order_phone->queryOriginPlaces();
		writeJson(['success'=>$result,'list'=>$list]);
	}
	public function addProducttype(){
		$this->load->model($this->module_name);
		$producttype_code = $this->request->post['producttype_code'];
		$producttype_name = $this->request->post['producttype_name'];
		$result = $this->model_order_phone->addProducttype($producttype_code,$producttype_name);
		$list = $this->model_order_phone->queryProductTypes();
		writeJson(['success'=>$result,'list'=>$list]);
	}

	protected function getOrderList($lans) {
		$data = array();

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['origin_places'] = $this->model_order_phone->queryOriginPlaces();
		$data['product_types'] = $this->model_order_phone->queryProductTypes();
		$data['base_url'] = $this->base_url;
		$data['unpaid_close_hour'] = $this->model_order_phone->queryUnpaidCloseHour();
		$data['update_unpaid_hour_url'] = $this->url->link($this->module_name.'/setUnpaidCloseHour', 'token=' . $this->session->data['token']);
		$data['change_place_status_url'] = $this->url->link($this->module_name.'/changePlaceStatus', 'token=' . $this->session->data['token']);
		$data['change_producttype_status_url'] = $this->url->link($this->module_name.'/changeProducttypeStatus', 'token=' . $this->session->data['token']);
		$data['add_place_url'] = $this->url->link($this->module_name.'/addPlace', 'token=' . $this->session->data['token']);
		$data['add_producttype_url'] = $this->url->link($this->module_name.'/addProducttype', 'token=' . $this->session->data['token']);
		$data = array_merge($data,$lans);
		$data['breadcrumbs'] = $this->parseBreadCrumbs(array());
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
	}
}
