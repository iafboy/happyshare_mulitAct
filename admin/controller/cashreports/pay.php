<?php
class ControllerCashreportsPay extends MyController {

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

	private $error = array();

	private $module_name = 'cashreports/pay';

	private $base_url= '';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->reportPay($lans);
	}
	protected function reportPay($lans) {
		$data = array();
		$this->load->model('common/model');
		$data = array_merge($data,$lans);
		$cash_report_id = $this->request->get['cash_report_id'];
		$data['cashreport'] = $this->model_cashreports_pay->queryCashReport($cash_report_id);
		$data['cashreport_id'] = $cash_report_id;
		$data['banks'] = $this->model_common_model->getBanks();
		$data['breadcrumbs'] = $this->parseBreadCrumbs(array());
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['is_view'] = $this->request->get['view'];
		$this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
	}

	public function doPay(){
		$this->load->model($this->module_name);
		$post = $this->request->post;
		$success = $this->model_cashreports_pay->doPay($post);
		writeJson(['success'=>$success,'errMsg'=>'提现失败']);
	}
}