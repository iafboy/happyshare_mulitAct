<?php
class ControllerSupplierGallery extends MyController {

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

	private $module_name = 'supplier/gallery' ;

	public function index() {
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->show($lans);
	}

	protected function show($lans) {

		$data = array();
		$data = array_merge($data,$lans);
		$array=[];
		$supplier_id = $this->request->get['supplier_id'];
		$supplier = $this->model_supplier_gallery->getSupplier($supplier_id);
		$data['supplier'] = $supplier;
		$data['breadcrumbs'] = $this->parseBreadCrumbs($array);
		$data['heading_title'] = $this->language->get('heading_title');
		$data['token'] = $this->session->data['token'];
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
	}

	public function addOrUpdateBrand(){
		$this->load->model($this->module_name);
		$post = $this->request->post;
		if(!is_valid($post['name'])){
			writeJson(['success'=>false,errMsg=>'品牌名称不能为空！']);
			return;
		}
		$supplier_id = $post['supplier_id'];
		$img = $post['imgurl'];
		if(is_valid($img)){
			$imgurl = 'supplier/brand/supplier_'.$supplier_id.'.'.parseExtension($img);
			if(is_file(DIR_IMAGE.$imgurl)){
				FileUtil::unlinkFile(DIR_IMAGE.$imgurl);
			}
			$tf = FileUtil::moveFile(DIR_IMAGE.$img,DIR_IMAGE.$imgurl);
			if($tf !== true){
				writeJson(['success'=>false,errMsg=>'处理图片错误！']);
				return;
			}
			$post['imgurl']=$imgurl;
		}
		$result = $this->model_supplier_gallery->saveOrUpdateBrand($post);
		$msg = ['success'=>$result];
		if($result!==true){
			$msg['errMsg'] = '操作失败！';
		}else{
			$location = $this->url->link('supplier/list', 'token=' . $this->session->data['token']);
			$msg['location'] = html_entity_decode($location);
		}
		writeJson($msg);



	}

}