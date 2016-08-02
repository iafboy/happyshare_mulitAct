<?php
class ControllerSupplierView extends MyController {

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

	private $module_name = 'supplier/view' ;

	public function index() {
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->show($lans);
	}
	public function view(){
	}

	public function uploadImage(){
		$this->load->model('tool/image');
		$this->load->model($this->module_name);
		$type = $this->request->post['type'];
		$supplier_id = $this->request->post['supplier_id'];
		$directory = 'supplier/desc';
		//replace
		if($type=='1') {
			$vis_id = $this->request->post['vis_id'];
			$file_name = 'supplier_' . $supplier_id . '_' . time();
			$old_img = $this->model_supplier_view->querySupplierImage($vis_id)['imgurl'];
			$msg = $this->model_tool_image->doUploadedImage($directory, $file_name);
			if ($msg['success'] === true) {
				$file_path = $msg['file_path'];
				$success = $this->model_supplier_view->setSupplierImage($vis_id, $file_path);
				$result = ['success' => $success];
				$result['file_path'] = DIR_IMAGE_URL . $file_path;
				if ($success === true) {
					if (is_file(DIR_IMAGE . $old_img)) {
						FileUtil::unlinkFile(DIR_IMAGE . $old_img);
					}
				} else {
					$result['error'] = '操作失败！';
				}
			} else {
				$result = $msg;
			}
		// add image
		}else if($type=='2'){

			$seq = $this->request->post['seq'];
			$file_name = 'supplier_' . $supplier_id . '_' . time();
			$msg = $this->model_tool_image->doUploadedImage($directory, $file_name);
			if ($msg['success'] === true) {
				$file_path = $msg['file_path'];
				$last_id = $this->model_supplier_view->addSupplierImage($supplier_id, $file_path,$seq);
				if($last_id != '0'){
					$success = true;
				}else{
					$success = false;
				}
				$result = ['success' => $success];
				$result['file_path'] = DIR_IMAGE_URL . $file_path;
				$result['vis_id'] = $last_id;
				if ($success !== true) {
					$result['error'] = '操作失败！';
				}
			} else {
				$result = $msg;
			}
		}
		writeJson($result);
	}



	public function mod(){
		$this->load->model($this->module_name);
		$array= [
			'supplier_name'=>['field'=>'供货商名称'],
			'username'=>['field'=>'供货商姓名'],
			'bankid'=>['field'=>'银行'],
			'bankcard'=>['field'=>'银行卡'],
			'service_phone'=>['field'=>'售后服务电话']
		];
		$valid = $this->validFields($array);
		if($valid['success'] !== true){
			writeJson($valid);
			return;
		}
		$data = $this->request->post;
		$result = $this->model_supplier_view->modSupplier($data);
		$json['success'] = $result;
		if($result===true){
			$json['location'] = html_entity_decode($this->url->link('supplier/list', 'token=' . $this->session->data['token']));
		}else{
			$json['errMsg'] = '添加失败！';
		}
		writeJson($json);

	}

	public function deleteSubImage(){
		$this->load->model($this->module_name);
		$supplierId = $this->request->post['supplier_id'];
		$seq = $this->request->post['seq'];
		if(!is_valid($supplierId)){
			writeJson(['success'=>false,'errMsg'=>'供货商信息错误']);
			return;
		}
		if(!is_valid($seq)){
			writeJson(['success'=>false,'errMsg'=>'图片信息错误']);
			return;
		}
		$result = $this->model_supplier_view->del_image($supplierId,$seq);
		writeJson($result);

	}

	protected function show($lans) {
		$data = array();
		$supplier_id = $this->request->get['supplier_id'];
		$supplier = $this->model_supplier_view->querySupplier($supplier_id);
		$data['supplier'] = $supplier;
		$data['supplier_id'] = $supplier_id;
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
		$banks = $this->model_supplier_view->getBanks();

		$data['banks'] = $banks;
		$data['supplier_no'] = $supplier['supplier_no'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('supplier/view.tpl', $data));
	}

}