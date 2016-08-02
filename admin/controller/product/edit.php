<?php
class ControllerProductEdit extends MyController {

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

	private $module_name = 'product/edit';

	private $base_url= '';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->load->model('tool/image');
		$this->getProductDetail($lans);
	}
	protected function getProductDetail($lans) {
		$data = array();
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		$product_id = $this->request->get['product_id'];
		$data['product'] =$this->model_product_edit->queryProduct($product_id);
		$data = array_merge($data,$lans);
		$data['breadcrumbs'] = $this->parseBreadCrumbs(array());
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
	}

	public function deleteSharecaseImage(){
		$this->load->model($this->module_name);
		$product_id = $this->request->post['product_id'];
		$case_index = $this->request->post['case_index'];
		$img_index = $this->request->post['image_index'];
		if(!is_valid($product_id)){
			writeJson(['success'=>false,errMsg=>'产品id异常']);
			return;
		}
		if(!is_valid($case_index) || !is_valid($img_index) ){
			writeJson(['success'=>false,errMsg=>'图片id异常']);
			return;
		}
		$result = $this->model_product_edit->delSharecaseImage($product_id,$case_index,$img_index);
		writeJson($result);
	}

	public function deleteSubImage(){
		$this->load->model($this->module_name);
		$product_id = $this->request->post['product_id'];
		$sort_order = $this->request->post['sort_order'];
		if(!is_valid($product_id)){
			writeJson(['success'=>false,errMsg=>'产品id异常']);
			return;
		}
		if(!is_valid($sort_order)){
			writeJson(['success'=>false,errMsg=>'图片id异常']);
			return;
		}
		$result = $this->model_product_edit->delSubImage($product_id,$sort_order);
		writeJson($result);
	}
	public function deleteShareCase(){
		$this->load->model($this->module_name);
		$product_id = $this->request->post['product_id'];
		$seq = $this->request->post['seq'];
		if(!is_valid($product_id)){
			writeJson(['success'=>false,errMsg=>'产品id异常']);
			return;
		}
		if(!is_valid($seq)){
			writeJson(['success'=>false,errMsg=>'文案id异常']);
			return;
		}
		$result = $this->model_product_edit->delShareCase($product_id,$seq);
		writeJson($result);
	}

	public function unpassProduct(){
		$this->load->model($this->module_name);
		$product_id = $this->request->post['product_id'];
		$success = $this->model_product_edit->unpassProduct($product_id);
		if($success===true){
			writeJson(['success'=>true,'location'=>
					html_entity_decode($this->url->link('product/list', 'token=' . $this->session->data['token']))]);
		}else{
			writeJson(['success'=>false,errMsg=>'操作错误']);
		}
	}
	public function passProduct(){
		$this->load->model($this->module_name);
		$data = $this->request->post;
		if(!is_valid($data['product_id'])){
			writeJson(['success'=>false,'errMsg'=>'参数错误']);
			return;
		}
		$success = $this->model_product_edit->saveProduct($data);
		if($success===true){
			writeJson(['success'=>true,'location'=>
					html_entity_decode($this->url->link('product/list', 'token=' . $this->session->data['token']))]);
		}else{
			writeJson(['success'=>false,errMsg=>'操作错误']);
		}
	}

	public function uploadImage(){
		$this->load->model('tool/image');
		$this->load->model($this->module_name);
		$type = $this->request->post['type'];
		$product_id = $this->request->post['product_id'];
		if($type=='1'){
			$directory = 'products/main';
			$file_name = 'products_'.$product_id.'_main_'.time();
			$old_img = $this->model_product_edit->queryMainImage($product_id);
			$msg = $this->model_tool_image->doUploadedImage($directory,$file_name);
			if($msg['success']===true){
				$file_path = $msg['file_path'];
				$success = $this->model_product_edit->setProductImage($product_id,$file_path);
				$result = ['success'=>$success];
				$result['file_path'] = DIR_IMAGE_URL.$file_path;
				if($success===true){
					if(is_valid($old_img)){
						FileUtil::unlinkFile(DIR_IMAGE.$old_img);
					}
				}else{
					$result['error'] = '操作失败！';
				}
			}else{
				$result = $msg;
			}
		}else if($type == '2'){
			$directory = 'products/sub';
			$sub_index = $this->request->post['sub_index'];
			$file_name = 'products_'.$product_id.'_sub_'.$sub_index.'_'.time();
			$old_img = $this->model_product_edit->querySubImage($product_id,$sub_index);
			$msg = $this->model_tool_image->doUploadedImage($directory,$file_name);
			if($msg['success']===true){
				$file_path = $msg['file_path'];
				$success = $this->model_product_edit->setProductSubImage($product_id,$sub_index,$file_path);
				$result = ['success'=>$success];
				$result['file_path'] = DIR_IMAGE_URL.$file_path;
				if($success===true){
					if(is_valid($old_img)){
						FileUtil::unlinkFile(DIR_IMAGE.$old_img);
					}
				}else{
					$result['error'] = '操作失败！';
				}
			}else{
				$result = $msg;
			}
		}else if($type == '3'){
			$directory = 'products/sharecases';
			$case_index = $this->request->post['case_index'];
			$image_index = $this->request->post['image_index'];
			$file_name = 'products_'.$product_id.'_case_'.$case_index.'_img_'.$image_index.'_'.time();
			$old_img = $this->model_product_edit->queryCaseImage($product_id,$case_index,$image_index);
			$msg = $this->model_tool_image->doUploadedImage($directory,$file_name);
			if($msg['success']===true){
				$file_path = $msg['file_path'];
				$success = $this->model_product_edit->setCaseImage($product_id,$case_index,$image_index,$file_path);
				$result = ['success'=>$success];
				$result['file_path'] = DIR_IMAGE_URL.$file_path;
				if($success===true){
					if(is_valid($old_img)){
						 FileUtil::unlinkFile(DIR_IMAGE.$old_img);
					}
				}else{
					$result['error'] = '操作失败！';
				}
			}else{
				$result = $msg;
			}
		}else if($type=='4'){
			$directory = 'products/main';
			$file_name = 'products_'.$product_id.'_maintitle1_'.time();
			$old_img = $this->model_product_edit->queryMainTitleImage($product_id,1);
			$msg = $this->model_tool_image->doUploadedImage($directory,$file_name);
			if($msg['success']===true){
				$file_path = $msg['file_path'];
				$success = $this->model_product_edit->setProductTitleImage($product_id,$file_path,1);
				$result = ['success'=>$success];
				$result['file_path'] = DIR_IMAGE_URL.$file_path;
				if($success===true){
					if(is_valid($old_img)){
						FileUtil::unlinkFile(DIR_IMAGE.$old_img);
					}
				}else{
					$result['error'] = '操作失败！';
				}
			}else{
				$result = $msg;
			}
		}else if($type=='5'){
			$directory = 'products/main';
			$file_name = 'products_'.$product_id.'_maintitle2_'.time();
			$old_img = $this->model_product_edit->queryMainTitleImage($product_id,2);
			$msg = $this->model_tool_image->doUploadedImage($directory,$file_name);
			if($msg['success']===true){
				$file_path = $msg['file_path'];
				$success = $this->model_product_edit->setProductTitleImage($product_id,$file_path,2);
				$result = ['success'=>$success];
				$result['file_path'] = DIR_IMAGE_URL.$file_path;
				if($success===true){
					if(is_valid($old_img)){
						FileUtil::unlinkFile(DIR_IMAGE.$old_img);
					}
				}else{
					$result['error'] = '操作失败！';
				}
			}else{
				$result = $msg;
			}
		}else if($type=='6'){
			$directory = 'products/main';
			$file_name = 'products_'.$product_id.'_maintitle3_'.time();
			$old_img = $this->model_product_edit->queryMainTitleImage($product_id,3);
			$msg = $this->model_tool_image->doUploadedImage($directory,$file_name);
			if($msg['success']===true){
				$file_path = $msg['file_path'];
				$success = $this->model_product_edit->setProductTitleImage($product_id,$file_path,3);
				$result = ['success'=>$success];
				$result['file_path'] = DIR_IMAGE_URL.$file_path;
				if($success===true){
					if(is_valid($old_img)){
						FileUtil::unlinkFile(DIR_IMAGE.$old_img);
					}
				}else{
					$result['error'] = '操作失败！';
				}
			}else{
				$result = $msg;
			}
		}else{
			writeJson(
				['success'=>false,'error'=>'参数缺失']
			);
			return;
		}
		writeJson($result);
	}
}