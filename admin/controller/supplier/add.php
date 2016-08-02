<?php
class ControllerSupplierAdd extends MyController {

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

	private $module_name = 'supplier/add' ;

	public function index() {
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->show($lans);
	}

	public function add(){
		$this->load->model($this->module_name);
		$array= [
			'supplier_name'=>['field'=>'供货商名称'],
			'username'=>['field'=>'供货商姓名'],
			'bankid'=>['field'=>'银行'],
			'bankcard'=>['field'=>'银行卡'],
			'service_phone'=>['field'=>'售后服务电话'],
			'images'=>['field'=>'供货商介绍图片']
		];
		$valid = $this->validFields($array);
		if($valid['success'] !== true){
			writeJson($valid);
			return;
		}
		$data = $this->request->post;
		$result = $this->model_supplier_add->addSupplier($data);
        if($result===true){
            $json['success'] = true;
            $json['location'] = html_entity_decode($this->url->link('supplier/list', 'token=' . $this->session->data['token']));
        }else{
            $json['success'] = false;
            $json['errMsg'] = is_valid($result['errMsg'])?$result['errMsg']:'添加失败！';
		}
		writeJson($json);

	}

	protected function show($lans) {

		$data = array();
		$data = array_merge($data,$lans);
		$array=[];
		$data['breadcrumbs'] = $this->parseBreadCrumbs($array);
		$data['heading_title'] = $this->language->get('heading_title');
		$data['token'] = $this->session->data['token'];
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$banks = $this->model_supplier_add->getBanks();

		$data['banks'] = $banks;
		$data['supplier_no'] = $this->model_supplier_add->getSupplierNo();
		$data['supplier_reg_id'] = $this->request->get['supplier_reg_id'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('supplier/add.tpl', $data));
	}

}