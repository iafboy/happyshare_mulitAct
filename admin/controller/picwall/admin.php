<?php
class ControllerPicWallAdmin extends MyController {

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

	private $base_url= '';

	private $module_name = 'picwall/admin';

	public function addPicwall(){
		$this->load->model($this->module_name);
		$image_path = $this->request->post['imagePath'];
		$sort_order = $this->request->post['sort_order'];
		$link = $this->request->post['link'];
		$picwall_id = $this->request->post['picwall_id'];
		$has_picwall_image = $this->model_picwall_admin->hasPicwallImage($picwall_id,$sort_order);
		if($has_picwall_image===true){
			writeJson(['success'=>false,'errMsg'=>'排序重复！']);
			return;
		}
		if(!file_exists(DIR_IMAGE.$image_path)){
			writeJson(['success'=>false,'errMsg'=>'图片不存在！']);
			return;
		}
		$extension = parseExtension($image_path);
		$new_image_path = 'picwall/picwall_'.time().'.'.$extension;
		if(FileUtil::moveFile(DIR_IMAGE.$image_path,DIR_IMAGE.$new_image_path)===false){
			writeJson(['success'=>false,'errMsg'=>'图片处理错误！']);
			return;
		}
		$result = $this->model_picwall_admin->addPicwallImage($picwall_id,$new_image_path,$sort_order,$link);
		writeJson(['success'=>$result]);
	}

	public function delPicwall(){
		$this->load->model($this->module_name);
		$picwall_image_id = $this->request->post['picwall_image_id'];
		$picwall_image = $this->model_picwall_admin->queryPicwallImage($picwall_image_id);
		if(isset($picwall_image)){
			$image_path = $picwall_image['image'];
			if(!unlink(DIR_IMAGE.$image_path)){
				writeJson(['success'=>false,'errMsg'=>'删除失败！']);
				return;
			}
			$result = $this->model_picwall_admin->delPicwallImage($picwall_image_id);
		}else{
			writeJson(['success'=>false,'errMsg'=>'picwall图片不存在！']);
			return;
		}
		writeJson(['success'=>$result]);
	}

	public function setPicwallStatus(){
		$this->load->model($this->module_name);
		$picwall_image_id = $this->request->post['picwall_image_id'];
		$status = $this->request->post['status'];
		$result = $this->model_picwall_admin->setPicwallImageStatus($picwall_image_id,$status);
		writeJson(['success'=>$result]);
	}






	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->getPicwallList($lans);
	}


	protected function getPicwallList($lans) {
		$data = array();
		$data['add_picwall_url'] = $this->url->link($this->module_name.'/addPicwall', 'token=' . $this->session->data['token']);
		$data['del_picwall_url'] = $this->url->link($this->module_name.'/delPicwall', 'token=' . $this->session->data['token']);
		$data['set_picwall_status_url'] = $this->url->link($this->module_name.'/setPicwallStatus', 'token=' . $this->session->data['token']);
		$array = [
				'cascade_1',
				'cascade_2',
				'cascade_3',
				'picwall_address_or_code'
		];
		$filter_data = $this->parseEntries($array,false,false,true);
		$entries_form = new EntriesForm(4,$array);
		$entries_form->setEntriesValue($filter_data);
		$entries_form->setFormId('picwall_fm');
		$entries_form->setBaseUrl($this->base_url);
		$entries_form->setRoute($this->module_name);
		$entries_form->setToken($this->session->data['token']);
		$list = [
				'cascade_1',
				'cascade_2',
				'cascade_3'];
		$list_array = [
				'cascade_1'=>['首页'=>'首页','分享热榜'=>'分享热榜','热门商品'=>'热门商品','精彩活动'=>'精彩活动'],
				'cascade_2'=>[''=>'--'],
				'cascade_3'=>[''=>'--']
		];
		$entries_form->setSelectTypeEntries($list,$list_array);
		$btns = array();
		$btns[] = new Button('main-search-btn',$lans['btn_picwall_query'],'lfx-btn');
		$entries_form->setButtons($btns);
		$entries_form_array = $entries_form->toArray($lans);
		$data['entries'] = $this->load->view('common/entries.tpl',$entries_form_array);



		$column_arr =[
				'column_cascade_1',
				'column_cascade_2',
				'column_cascade_3',
				'column_picwall_address_or_code',
				'column_picwall_pic',
				'column_picwall_url',
				'column_picwall_seq',
				'column_operation'
		];
		$columns = array();
		foreach($column_arr as $c){
			$columns[$c] = $lans[$c];
		}
		$data['theader'] = $this->load->view('common/theader.tpl',['columns'=>$columns]);
		// query results
		$results = $this->model_picwall_admin->queryPicwalls($filter_data);
		$data['picwalls']=$results;
		$data = array_merge($data,$this->parseEntries($array,false,false,false));
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data = array_merge($data,$lans);
		$data['breadcrumbs'] = $this->parseBreadCrumbs($array);
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
	}
}