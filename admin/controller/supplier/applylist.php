<?php
class ControllerSupplierApplyList extends MyController {

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

	private $module_name = 'supplier/applylist';

	private $base_url= '';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language('supplier/applylist');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('supplier/applylist');
		$this->getList($lans);
	}
	public function view(){

	}
	public function changestatus(){
		$this->load->language('supplier/applylist');
		$this->load->model('supplier/applylist');
		$supplier_approve_status = $this->request->post['supplier_approve_status'];
		$supplier_id = $this->request->post['supplier_reg_id'];
		$success = $this->model_supplier_applylist->changeStatus($supplier_approve_status,$supplier_id);
		$msg = new ReturnMsg();
		$status = $supplier_approve_status;
		if($status=='0' || $status == '1' || $status =='2' || $status=='3'){
			if($status=='3'){
				$status='已上架';
			}else {
				$status = $this->language->get('status_supplier_approve_status')[$status];
			}
		}
		$msg->success = $success;
		$msg->data = ['status'=>$status];
		if($success===false){
			$msg->err_msg='操作失败！';
		}
		$msg->writeJson();
	}

	protected function getList($lans) {

		$array = [
			'supplier_company_name',
			'supplier_create_date_start',
			'supplier_create_date_end',
			'supplier_approve_status',
		];
		$filter_data = $this->parseEntries($array,false,false,true);

		$data['breadcrumbs'] = $this->parseBreadCrumbs();

		$data['base_url'] = $this->base_url;
		$data['changestatus_url'] = $this->url->link($this->module_name.'/changestatus', 'token=' . $this->session->data['token']);


		$data['heading_title'] = $this->language->get('heading_title');
		$data = array_merge($data,$lans);
		$data['token'] = $this->session->data['token'];
		$data['route'] = $this->module_name;
		$data['suppliers'] = array();
		// query results
		$results = $this->model_supplier_applylist->getApplySuppliers($filter_data);
		// query total count
		$product_total = $this->model_supplier_applylist->getApplySupplierTotalCount($filter_data);
		foreach ($results as $result) {
			$status = $result['approve_status'];
			if($status=='0' || $status == '1' || $status =='2' || $status=='3'){
				$status = $this->language->get('status_supplier_approve_status')[$status];
			}
			$data['suppliers'][] = array(
				'supplier_reg_id' => $result['supplier_reg_id'],
				'supplier_company_name' => $result['supplier_company'],
				'supplier_company_address' => $result['prov'].' '.$result['city'].' '.$result['distic'].(is_valid($result['street'])?'('.$result['street'].')':''),
				'supplier_company_contactor' => $result['company_contacter'],
				'supplier_company_contactor_phone' => $result['company_contacter_phone'],
				'supplier_company_contactor_email' => $result['email'],
				'supplier_approve_status' => $status,
				'supplier_approve_status_oper'    => $this->language->get('text_supplier_approve_status_oper'),
				'supplier_is_registered'    => $result['is_registered']
			);
		}
		$page = $filter_data['page'];
		$url = $this->parseUrl($array,false,false,false);
		$pagination = $this->buildPagination($page,$product_total,$url,$this->module_name);
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));
		$data = array_merge($data,$this->parseEntries($array,false,false,false));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('supplier/applylist.tpl', $data));
	}

}