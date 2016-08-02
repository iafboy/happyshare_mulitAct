<?php
class ControllerReportsPay extends MyController {

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
	 *
	 */

	private $module_name = 'reports/pay';

	private $base_url= '';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->pay($lans);
	}
	protected function pay($lans) {
		$data = array();
		$this->load->model('common/model');
		$this->load->model('reports/add');
		$data = array_merge($data,$lans);
		$supplier_id = $this->request->get['supplier_id'];
		$order_ids = $this->request->get['order_ids'];
		$data['order_ids'] = $order_ids;
		$order_ids = explode(",",$order_ids);
		$data['supplier_id'] = $supplier_id;
		$data['supplier'] = $this->model_reports_pay->queryPaySupplier($supplier_id);
		$data['transfer_amount'] = $this->model_reports_add->querySupplierPriceSum($order_ids,$supplier_id);
		$data['banks'] = $this->model_common_model->getBanks();
		$data['breadcrumbs'] = $this->parseBreadCrumbs(array());
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
	}

	public function dopay(){
		$this->load->model('reports/pay');
		$post = $this->request->post;
		$result = $this->model_reports_pay->dopay($post);
		writeJson($result);
	}
}