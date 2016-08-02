<?php
class ControllerProductAdd extends MyController {

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

	private $module_name = 'product/add';

	private $base_url= '';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->load->model('tool/image');
		$this->load->model('common/product');
		$this->getProductDetail($lans);
	}
	public function getProductNo(){
		$date = new DateTime();
		$tp=$date->getTimestamp();
		$length=4;
		$pattern = '1234567890';
		$key='';
		for($i=0;$i<$length;$i++)
		{
			$key .= $pattern{mt_rand(0,9)};    //生成php随机数
		}
		return 'PD'.$tp.$key;
	}
	protected function getProductDetail($lans) {

		$data = array();
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		$productNo = $this->getProductNo();
		$data['product'] = ['product_no'=>$productNo];
		$data['product_no'] = $productNo;
		$data = array_merge($data,$lans);
		$data['breadcrumbs'] = $this->parseBreadCrumbs(array());
		$data['prducttypes'] = $this->model_common_product->queryProducttypes();
		$data['originPlaces'] = $this->model_common_product->queryOriginPlaces();
		$data['expressPlaces'] = $this->model_common_product->queryExpressPlaces();
		$user_id = $this->session->data['supplier_id'];
		$data['self_set']=$this->model_common_product->checkCreditSettingPower($user_id);
		$data['expressTemplates'] = $this->model_common_product->queryExpressTemplates($user_id);

		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
	}

	public function deleteSharecaseImage(){
		$image_path = $this->request->post['image_path'];
		if(!is_valid($image_path)){
			writeJson(['success'=>false,'errMsg'=>'图片异常']);
			return;
		}
		if(is_file(DIR_IMAGE.$image_path)){
			FileUtil::unlinkFile(DIR_IMAGE.$image_path);
		}
		writeJson(['success'=>true]);
	}
	public function deleteSubImage(){
		$image_path = $this->request->post['image_path'];
		if(!is_valid($image_path)){
			writeJson(['success'=>false,'errMsg'=>'图片异常']);
			return;
		}
		if(is_file(DIR_IMAGE.$image_path)){
			FileUtil::unlinkFile(DIR_IMAGE.$image_path);
		}
		writeJson(['success'=>true]);
	}
	public function deleteShareCase(){
		$this->load->model($this->module_name);
		$image_path = $this->request->post['image_path'];
		if(!is_null($image_path)&&!is_valid($image_path)&&!ctype_space($image_path)) {
			if (is_file(DIR_IMAGE . $image_path)) {
				FileUtil::unlinkFile(DIR_IMAGE . $image_path);
			}

		}
		writeJson(['success' => true]);
	}

	public function addProduct(){
		$this->load->model($this->module_name);
		$data = $this->request->post;
		$success = $this->model_product_add->addProduct($data);
		if($success===true){
			writeJson(['success'=>true,'location'=>
				html_entity_decode($this->url->link('catalog/product', 'token=' . $this->session->data['token']))]);
		}else{
			writeJson(['success'=>false,'errMsg'=>'操作错误']);
		}
	}

	public function uploadImage(){
		$this->load->model('tool/image');
		$this->load->model($this->module_name);
		$type = $this->request->post['type'];
		$product_id = $this->request->post['product_no'];
		if($type=='1'){
			$directory = 'temp/products/main';
			$file_name = 'products_'.$product_id.'_main';
			if(is_file(DIR_IMAGE.$directory.'/'.$file_name)){
				FileUtil::unlinkFile(DIR_IMAGE.$directory.'/'.$file_name);
			}
			$msg = $this->model_tool_image->doUploadedImage($directory,$file_name);
			if($msg['success']===true){
				$file_path = $msg['file_path'];
				$success = true;
				$result = ['success'=>$success];
				$result['file_path'] = DIR_IMAGE_URL.$file_path;
				$result['image'] = $file_path;
				if($success===true){
				}else{
					$result['error'] = '操作失败！';
				}
			}else{
				$result = $msg;
			}
		}else if($type == '2'){
			$directory = 'temp/products/sub';
			$sub_index = $this->request->post['sub_index'];
			$file_name = 'products_'.$product_id.'_sub_'.$sub_index;
			if(is_file(DIR_IMAGE.$directory.'/'.$file_name)){
				FileUtil::unlinkFile(DIR_IMAGE.$directory.'/'.$file_name);
			}
			$msg = $this->model_tool_image->doUploadedImage($directory,$file_name);
			if($msg['success']===true){
				$file_path = $msg['file_path'];
				$success = true;
				$result = ['success'=>$success];
				$result['file_path'] = DIR_IMAGE_URL.$file_path;
				$result['image'] = $file_path;
				if($success===true){
				}else{
					$result['error'] = '操作失败！';
				}
			}else{
				$result = $msg;
			}
		}else if($type == '3'){
			$directory = 'temp/products/sharecases';
			$case_index = $this->request->post['case_index'];
			$image_index = $this->request->post['image_index'];
			$file_name = 'products_'.$product_id.'_case_'.$case_index.'_img_'.$image_index;
			if(is_file(DIR_IMAGE.$directory.'/'.$file_name)){
				FileUtil::unlinkFile(DIR_IMAGE.$directory.'/'.$file_name);
			}
			$msg = $this->model_tool_image->doUploadedImage($directory,$file_name);
			if($msg['success']===true){
				$file_path = $msg['file_path'];
				$success = true;
				$result = ['success'=>$success];
				$result['file_path'] = DIR_IMAGE_URL.$file_path;
				$result['image'] = $file_path;
				if($success===true){
				}else{
					$result['error'] = '操作失败！';
				}
			}else{
				$result = $msg;
			}
		}else if($type=='4') {
			$directory = 'temp/products/main';
			$file_name = 'products_'.$product_id.'_maintitle1';
			if(is_file(DIR_IMAGE.$directory.'/'.$file_name)){
				FileUtil::unlinkFile(DIR_IMAGE.$directory.'/'.$file_name);
			}
			$msg = $this->model_tool_image->doUploadedImage($directory,$file_name);
			if($msg['success']===true){
				$file_path = $msg['file_path'];
				$success = true;
				$result = ['success'=>$success];
				$result['file_path'] = DIR_IMAGE_URL.$file_path;
				$result['image'] = $file_path;
				if($success===true){
				}else{
					$result['error'] = '操作失败！';
				}
			}else{
				$result = $msg;
			}
		}else if($type=='5') {
			$directory = 'temp/products/main';
			$file_name = 'products_'.$product_id.'_maintitle2';
			if(is_file(DIR_IMAGE.$directory.'/'.$file_name)){
				FileUtil::unlinkFile(DIR_IMAGE.$directory.'/'.$file_name);
			}
			$msg = $this->model_tool_image->doUploadedImage($directory,$file_name);
			if($msg['success']===true){
				$file_path = $msg['file_path'];
				$success = true;
				$result = ['success'=>$success];
				$result['file_path'] = DIR_IMAGE_URL.$file_path;
				$result['image'] = $file_path;
				if($success===true){
				}else{
					$result['error'] = '操作失败！';
				}
			}else{
				$result = $msg;
			}
		}else if($type=='6') {
			$directory = 'temp/products/main';
			$file_name = 'products_'.$product_id.'_maintitle3';
			if(is_file(DIR_IMAGE.$directory.'/'.$file_name)){
				FileUtil::unlinkFile(DIR_IMAGE.$directory.'/'.$file_name);
			}
			$msg = $this->model_tool_image->doUploadedImage($directory,$file_name);
			if($msg['success']===true){
				$file_path = $msg['file_path'];
				$success = true;
				$result = ['success'=>$success];
				$result['file_path'] = DIR_IMAGE_URL.$file_path;
				$result['image'] = $file_path;
				if($success===true){
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