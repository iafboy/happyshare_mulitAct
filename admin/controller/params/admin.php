<?php
class ControllerParamsAdmin extends MyController {

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

	private $module_name = 'params/admin';

	private $base_url= '';


	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->getOrderList($lans);
	}

	public function setUnpaidCloseHour(){
		$this->load->model($this->module_name);
		$hour = $this->request->post['unpaid_close_hour'];
		$result = $this->model_params_admin->updateUnpaidCloseHour($hour);
		writeJson(['success'=>$result]);
	}
	public function changePlaceStatus(){
		$this->load->model($this->module_name);
		$place_id = $this->request->post['place_id'];
		$status = $this->request->post['status'];
		$result = $this->model_params_admin->changePlaceStatus($place_id,$status);
		$place = $this->model_params_admin->queryPlaceById($place_id);
		$result['place'] = $place;
		writeJson($result);
	}
	public function changeExpressPlaceStatus(){
		$this->load->model($this->module_name);
		$place_id = $this->request->post['place_id'];
		$status = $this->request->post['status'];
		$result = $this->model_params_admin->changeExpressPlaceStatus($place_id,$status);
		$place = $this->model_params_admin->queryExpressPlaceById($place_id);
		$result['place'] = $place;
		writeJson($result);
	}
	public function changeProducttypeStatus(){
		$this->load->model($this->module_name);
		$producttype_id = $this->request->post['producttype_id'];
		$status = $this->request->post['status'];
		$result = $this->model_params_admin->changeProducttypeStatus($producttype_id,$status);
		$producttype = $this->model_params_admin->queryProducttypeById($producttype_id);
		$result['producttype'] = $producttype;
		writeJson($result);
	}



	public function addPlace(){
		$this->load->model($this->module_name);
		$place_code = $this->request->post['place_code'];
		$place_name = $this->request->post['place_name'];
		$is_code_exist = $this->model_params_admin->checkPlaceCode($place_code);
		if($is_code_exist == true){
			writeJson(['success'=>false,'errMsg'=>'编码重复']);
			return;
		}
		$result = $this->model_params_admin->addPlace($place_code,$place_name);

        $list = $this->model_params_admin->queryOriginPlaces();
		writeJson(['success'=>$result]);
	}
	public function addExpressPlace(){
		$this->load->model($this->module_name);
		$place_code = $this->request->post['place_code'];
		$place_name = $this->request->post['place_name'];
		$is_code_exist = $this->model_params_admin->checkExpressPlaceCode($place_code);
		if($is_code_exist == true){
			writeJson(['success'=>false,'errMsg'=>'编码重复']);
			return;
		}
		$result = $this->model_params_admin->addExpressPlace($place_code,$place_name);
		$list = $this->model_params_admin->queryExpressPlaces();
		writeJson(['success'=>$result]);
	}
	public function addProducttype(){
		$this->load->model($this->module_name);
		$producttype_code = $this->request->post['producttype_code'];
		$producttype_name = $this->request->post['producttype_name'];

        $is_code_exist = $this->model_params_admin->checkProducttypeCode($producttype_code);
        if($is_code_exist == true){
            writeJson(['success'=>false,'errMsg'=>'编码重复']);
            return;
        }


		$result = $this->model_params_admin->addProducttype($producttype_code,$producttype_name);
		$list = $this->model_params_admin->queryProductTypes();
		writeJson(['success'=>$result]);
	}

	protected function getOrderList($lans) {
		$data = array();

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['origin_places'] = $this->model_params_admin->queryOriginPlaces();
		$data['product_types'] = $this->model_params_admin->queryProductTypes();
		$data['base_url'] = $this->base_url;
		$data['unpaid_close_hour'] = $this->model_params_admin->queryUnpaidCloseHour();
		$data['update_unpaid_hour_url'] = $this->url->link($this->module_name.'/setUnpaidCloseHour', 'token=' . $this->session->data['token']);
		$data['change_place_status_url'] = $this->url->link($this->module_name.'/changePlaceStatus', 'token=' . $this->session->data['token']);
		$data['change_express_place_status_url'] = $this->url->link($this->module_name.'/changeExpressPlaceStatus', 'token=' . $this->session->data['token']);
		$data['change_producttype_status_url'] = $this->url->link($this->module_name.'/changeProducttypeStatus', 'token=' . $this->session->data['token']);
		$data['add_place_url'] = $this->url->link($this->module_name.'/addPlace', 'token=' . $this->session->data['token']);
		$data['add_express_place_url'] = $this->url->link($this->module_name.'/addExpressPlace', 'token=' . $this->session->data['token']);
		$data['add_producttype_url'] = $this->url->link($this->module_name.'/addProducttype', 'token=' . $this->session->data['token']);
		$data = array_merge($data,$lans);
		$data['breadcrumbs'] = $this->parseBreadCrumbs(array());
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
	}

	public function originPlacePager(){
		$this->load->model($this->module_name);
		$filter_data = $this->parsePostEntries(array(),false,false,true);
		if(!is_valid($filter_data['page'])){
			$filter_data['page'] = 1;
		}
		$list = $this->model_params_admin->queryOriginPlaces($filter_data);

		$count_total = $this->model_params_admin->queryOriginPlacesCount($filter_data);
		$url = $this->parseUrl(array(),false,false,false);
		$page = $filter_data['page'];
		$paginator = $this->buildPaginator($page,$count_total,$url,$this->module_name.'/originPlacePager',$this->language->get('text_pagination'));
		writeJson(['list'=>$list,'paginator'=>$paginator]);
	}
	public function expressPlacePager(){
		$this->load->model($this->module_name);
		$filter_data = $this->parsePostEntries(array(),false,false,true);
		if(!is_valid($filter_data['page'])){
			$filter_data['page'] = 1;
		}
		$list = $this->model_params_admin->queryExpressPlaces($filter_data);

		$count_total = $this->model_params_admin->queryExpressPlacesCount($filter_data);
		$url = $this->parseUrl(array(),false,false,false);
		$page = $filter_data['page'];
		$paginator = $this->buildPaginator($page,$count_total,$url,$this->module_name.'/expressPlacePager',$this->language->get('text_pagination'));
		writeJson(['list'=>$list,'paginator'=>$paginator]);
	}
	public function productTypePager(){
		$this->load->model($this->module_name);
		$filter_data = $this->parsePostEntries(array(),false,false,true);
		if(!is_valid($filter_data['page'])){
			$filter_data['page'] = 1;
		}
		$list = $this->model_params_admin->queryProductTypes($filter_data);

		$count_total = $this->model_params_admin->queryProductTypesCount($filter_data);
		$url = $this->parseUrl(array(),false,false,false);
		$page = $filter_data['page'];
		$paginator = $this->buildPaginator($page,$count_total,$url,$this->module_name.'/originPlacePager',$this->language->get('text_pagination'));
		writeJson(['list'=>$list,'paginator'=>$paginator]);
	}
}