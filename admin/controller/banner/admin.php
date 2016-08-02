<?php
class ControllerBannerAdmin extends MyController {

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

	private $module_name = 'banner/admin';

	private $base_url= '';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->getBannerList($lans);
	}


	public function addBanner(){
		$this->load->model($this->module_name);
		$image_path = $this->request->post['imagePath'];
		$sort_order = $this->request->post['sort_order'];
		$link = $this->request->post['link'];
		$banner_id = $this->request->post['banner_id'];
		$has_banner_image = $this->model_banner_admin->hasBannerImage($banner_id,$sort_order);
		if($has_banner_image===true){
			writeJson(['success'=>false,'errMsg'=>'排序重复！']);
			return;
		}
		if(!file_exists(DIR_IMAGE.$image_path)){
			writeJson(['success'=>false,'errMsg'=>'图片不存在！']);
			return;
		}
		$extension = parseExtension($image_path);
		$new_image_path = 'banner/banner_'.time().'.'.$extension;
		if(FileUtil::moveFile(DIR_IMAGE.$image_path,DIR_IMAGE.$new_image_path)===false){
			writeJson(['success'=>false,'errMsg'=>'图片处理错误！']);
			return;
		}
		$result = $this->model_banner_admin->addBannerImage($banner_id,$new_image_path,$sort_order,$link);
		writeJson(['success'=>$result]);
	}

	public function delBanner(){
		$this->load->model($this->module_name);
		$banner_image_id = $this->request->post['banner_image_id'];
		$banner_image = $this->model_banner_admin->queryBannerImage($banner_image_id);
		if(isset($banner_image)){
			$image_path = $banner_image['image'];
			if(!unlink(DIR_IMAGE.$image_path)){
				writeJson(['success'=>false,'errMsg'=>'删除失败！']);
				return;
			}
			$result = $this->model_banner_admin->delBannerImage($banner_image_id);
		}else{
			writeJson(['success'=>false,'errMsg'=>'banner图片不存在！']);
			return;
		}
		writeJson(['success'=>$result]);
	}

	public function setBannerStatus(){
		$this->load->model($this->module_name);
		$banner_image_id = $this->request->post['banner_image_id'];
		$status = $this->request->post['status'];
		$result = $this->model_banner_admin->setBannerImageStatus($banner_image_id,$status);
		writeJson(['success'=>$result]);
	}
	public function updateBannerImage(){
		$this->load->model($this->module_name);
		$banner_image_id = $this->request->post['banner_image_id'];
		$link = $this->request->post['link'];
		$seq = $this->request->post['seq'];
		$result = $this->model_banner_admin->updateBannerImageSetting($banner_image_id,$link,$seq);
		writeJson(['success'=>$result]);
	}

	protected function getBannerList($lans) {
		$data = array();
		$data['add_banner_url'] = $this->url->link($this->module_name.'/addBanner', 'token=' . $this->session->data['token']);
		$data['del_banner_url'] = $this->url->link($this->module_name.'/delBanner', 'token=' . $this->session->data['token']);
		$data['set_banner_status_url'] = $this->url->link($this->module_name.'/setBannerStatus', 'token=' . $this->session->data['token']);
//		$array = [
//			'cascade_1',
//			'cascade_2',
//			'cascade_3',
//			'banner_address_or_code'
//		];
		$array = [
			'cascade_1',
			'cascade_2',
			'banner_address_or_code'
		];
		$filter_data = $this->parseEntries($array,false,false,true);
		$entries_form = new EntriesForm(4,$array);
		$entries_form->setEntriesValue($filter_data);
		$entries_form->setFormId('banner_fm');
		$entries_form->setBaseUrl($this->base_url);
		$entries_form->setRoute($this->module_name);
		$entries_form->setToken($this->session->data['token']);
//		$list = [
//			'cascade_1',
//			'cascade_2',
//			'cascade_3'];
		$list = [
			'cascade_1',
			'cascade_2'];
        if($filter_data['filter_cascade_1']=='精彩活动'){

//            $list_array = [
//                'cascade_1'=>['首页'=>'首页','分享热榜'=>'分享热榜','热门商品'=>'热门商品','精彩活动'=>'精彩活动'],
//                'cascade_2'=>[''=>'--','特价活动'=>'特价活动','积分翻倍活动'=>'积分翻倍活动','免费体验活动'=>'免费体验活动'],
//                'cascade_3'=>[''=>'--']
//            ];
			$list_array = [
				'cascade_1'=>['首页'=>'首页','分享热榜'=>'分享热榜','热门商品'=>'热门商品','精彩活动'=>'精彩活动'],
				'cascade_2'=>[''=>'--','特价活动'=>'特价活动','积分翻倍活动'=>'积分翻倍活动','免费体验活动'=>'免费体验活动']
			];
        }else{
//            $list_array = [
//                'cascade_1'=>['首页'=>'首页','分享热榜'=>'分享热榜','热门商品'=>'热门商品','精彩活动'=>'精彩活动'],
//                'cascade_2'=>[''=>'--'],
//                'cascade_3'=>[''=>'--']
//            ];
			$list_array = [
				'cascade_1'=>['首页'=>'首页','分享热榜'=>'分享热榜','热门商品'=>'热门商品','精彩活动'=>'精彩活动'],
				'cascade_2'=>[''=>'--']
			];
        }
		$entries_form->setSelectTypeEntries($list,$list_array);
		$btns = array();
		$btns[] = new Button('btn_banner_query main-search-btn',$lans['btn_banner_query'],'lfx-btn');
		$entries_form->setButtons($btns);
		$entries_form_array = $entries_form->toArray($lans);
		$data['entries'] = $this->load->view('common/entries.tpl',$entries_form_array);



//		$column_arr =[
//		'column_cascade_1',
//		'column_cascade_2',
//		'column_cascade_3',
//		'column_banner_address_or_code',
//		'column_banner_pic',
//		'column_banner_url',
//		'column_banner_seq',
//		'column_operation'
//		];
		$column_arr =[
			'column_cascade_1',
			'column_cascade_2',
			'column_banner_address_or_code',
			'column_banner_pic',
			'column_banner_url',
			'column_banner_seq',
			'column_operation'
		];
		$columns = array();
		foreach($column_arr as $c){
			$columns[$c] = $lans[$c];
		}
		$data['theader'] = $this->load->view('common/theader.tpl',['columns'=>$columns]);
		// query results
		$results = $this->model_banner_admin->queryBanners($filter_data);
		$data['banners']=$results;
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


	public function changeBannerImage(){
		$this->load->model('tool/image');
		$this->load->model($this->module_name);
		$banner_id = $this->request->post['banner_id'];
		$directory = 'banner';
		$file_name = 'banner_'.time();
		$old_img = $this->model_banner_admin->queryBannerImage($banner_id)['image'];
		$msg = $this->model_tool_image->doUploadedImage($directory,$file_name);
		if($msg['success']===true){
			$file_path = $msg['file_path'];
			$success = $this->model_banner_admin->setBannerImage($banner_id,$file_path);
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
		writeJson($result);

	}
}