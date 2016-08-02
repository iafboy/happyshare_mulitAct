<?php
class ControllerExtensionShipping extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping');

		$this->document->setTitle($this->language->get('heading_title'));

		//$this->load->model('extension/extension');

		//$this->getList();
		$this->load->model('extension/shipping_leshare');
		$this->getList_leshare();
	}
/*
	public function install() {
		$this->load->language('extension/shipping');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/extension');

		if ($this->validate()) {
			$this->model_extension_extension->install('shipping', $this->request->get['extension']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'shipping/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'shipping/' . $this->request->get['extension']);

			// Call install method if it exsits
			$this->load->controller('shipping/' . $this->request->get['extension'] . '/install');

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	public function uninstall() {
		$this->load->language('extension/shipping');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/extension');

		if ($this->validate()) {
			$this->model_extension_extension->uninstall('shipping', $this->request->get['extension']);

			$this->load->model('setting/setting');

			$this->model_setting_setting->deleteSetting($this->request->get['extension']);

			// Call uninstall method if it exsits
			$this->load->controller('shipping/' . $this->request->get['extension'] . '/uninstall');

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	public function getList() {
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
		);
				
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
    $data['text_confirm'] = $this->language->get('text_confirm');

    $data['text_free_shipping'] = $this->language->get('text_free_shipping');
    $data['text_free_tax'] = $this->language->get('text_free_tax');
    $data['text_free_prepay'] = $this->language->get('text_free_prepay');
    $data['text_shipping_reminder'] = $this->language->get('text_shipping_reminder');
    $data['text_shipping_unit'] = $this->language->get('text_shipping_unit');
    $data['text_express_select'] = $this->language->get('text_express_select');
    $data['text_express_namelist'] = $this->language->get('text_express_namelist');
    $data['text_shipping_price'] = $this->language->get('text_shipping_price');
    $data['text_shipping_price_reminder'] = $this->language->get('text_shipping_price_reminder');
    $data['text_shipping_price_unit'] = $this->language->get('text_shipping_price_unit');
    $data['text_express_dest'] = (array)$this->language->get('text_express_dest');
    $data['text_sales_return'] = $this->language->get('text_sales_return');
    $data['text_sales_return_name'] = $this->language->get('text_sales_return_name');
    $data['text_sales_return_phone'] = $this->language->get('text_sales_return_phone');
    $data['text_sales_return_addr'] = $this->language->get('text_sales_return_addr');


		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$this->load->model('extension/extension');

		$extensions = $this->model_extension_extension->getInstalled('shipping');

		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/shipping/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('shipping', $value);

				unset($extensions[$key]);
			}
		}

		$data['extensions'] = array();

		$files = glob(DIR_APPLICATION . 'controller/shipping/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('shipping/' . $extension);

				$data['extensions'][] = array(
					'name'       => $this->language->get('heading_title'),
					'status'     => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order' => $this->config->get($extension . '_sort_order'),
					'install'    => $this->url->link('extension/shipping/install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL'),
					'uninstall'  => $this->url->link('extension/shipping/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL'),
					'installed'  => in_array($extension, $extensions),
					'edit'       => $this->url->link('shipping/' . $extension . '', 'token=' . $this->session->data['token'], 'SSL')
				);
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

*/



	public function getList_leshare() {

		$this->load->language('extension/shipping');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/shipping_leshare');

    $data['filter_free_shipping']            = ''; 
    $data['filter_free_tax']                 = '';
    $data['filter_free_prepay']              = '';
    $data['inlineRadioOptions']              = '';
    $data['filter_sales_return_name']        = '';
    $data['filter_sales_return_phone']       = '';
    $data['province_id']                     = '';
    $data['city_id']                         = '';
    $data['district_id']                     = '';
    $data['filter_sales_return_addr_street'] = '';

    $supplierID = $this->session->data['supplier_id'];
    $query = $this->model_extension_shipping_leshare->getExpressGeneralInfo($supplierID);
    $data['filter_free_shipping'] = $query['free_shipping'];
    $data['filter_free_tax'] = $query['free_tax_min'];
    $data['filter_free_prepay'] = $query['free_tax_max'];
    $data['inlineRadioOptions'] = $query['order_charge'];

    $query = $this->model_extension_shipping_leshare->getExpressReturnInfo($supplierID);
    $data['filter_sales_return_name'] = $query['name'];
    $data['filter_sales_return_phone'] = $query['telephone'];
    $data['province_id'] = $query['addr_prov'];
    $data['city_id'] = $query['addr_city'];
    $data['district_id'] = $query['addr_dist'];
    $data['filter_sales_return_addr_street'] = $query['addr_info'];


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->isInnerForm()) {
    
      if ($this->validateInnerform()){

      $expco = explode(',',$this->request->post['input_expco_text']);
      array_pop($expco);

      $filter_weight = 0;
      $filter_weight_new = 0;
      $filter_price = 0;
      $filter_price_new = 0;
      $filter_price1 = 0;
      $filter_price2 = 0;
      $filter_price3 = 0;

      if ( isset($this->request->post['filter_weight']) ){
        $filter_weight =$this->request->post['filter_weight'];
      }

      if ( isset($this->request->post['filter_price']) ){
        $filter_price =$this->request->post['filter_price'];
      }

      if ( isset($this->request->post['filter_weight_new']) ){
        $filter_weight_new =$this->request->post['filter_weight_new'];
      }

      if ( isset($this->request->post['filter_price_new']) ){
        $filter_price_new =$this->request->post['filter_price_new'];
      }

      if ( isset($this->request->post['filter_price1']) ){
        $filter_price1 =$this->request->post['filter_price1'];
      }

      if ( isset($this->request->post['filter_price2']) ){
        $filter_price2 =$this->request->post['filter_price2'];
      }

      if ( isset($this->request->post['filter_price3']) ){
        $filter_price3 =$this->request->post['filter_price3'];
      }

      $input_data = array(
        'supplier_id' =>  $this->session->data['supplier_id'], 
        'expco' => $expco,
        'charge_type'         => $this->request->post['radio_price'],
        'place_origin'        => $this->request->post['input_place_origin'],
        'place_dest_prov'     => $this->request->post['input_place_dest_prov'],
        'place_dest_city'     => $this->request->post['input_place_dest_city'],
        'weight_start_weight' => $filter_weight,
        'weight_start_price'  => $filter_price,
        'weight_add_weight'   => $filter_weight_new,
        'weight_add_price'    => $filter_price_new,
        'piece_start_price'   => $filter_price1,
        'piece_add_price'     => $filter_price2,
        'volume_start_price'  => $filter_price3,
      );
 
			//$this->error['warning'] = "测试： ".json_encode($input_data);
      $query = $this->model_extension_shipping_leshare->addExpressPrice($input_data);
      $this->session->data['success'] = "快递价格信息保存成功!";
      
      }
    }

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && (!$this->isInnerForm())) {
  
      if (isset($this->request->post['filter_free_shipping'])){
        $data['filter_free_shipping'] = $this->request->post['filter_free_shipping']; 
      }
      if (isset($this->request->post['filter_free_tax'])){
        $data['filter_free_tax'] = $this->request->post['filter_free_tax'];
      }
      if (isset($this->request->post['filter_free_prepay'])){
        $data['filter_free_prepay'] = $this->request->post['filter_free_prepay'];
      }
      if (isset($this->request->post['inlineRadioOptions'])){
        $data['inlineRadioOptions'] = $this->request->post['inlineRadioOptions'];
        //$this->session->data['success'] = "liuhang inlineRadioOptions is ".$this->request->post['inlineRadioOptions'];
      }
      if (isset($this->request->post['filter_sales_return_name'])){
        $data['filter_sales_return_name'] = $this->request->post['filter_sales_return_name'];
      }
      if (isset($this->request->post['filter_sales_return_phone'])){
       $data['filter_sales_return_phone'] = $this->request->post['filter_sales_return_phone'];
      }
      if (isset($this->request->post['company_addr_province'])){
        $data['province_id'] = $this->request->post['company_addr_province'];
      }
      
      $this->load->model('localisation/cn_addressbook');
      if (isset($this->request->post['company_addr_city']) ){
        $data['city_id'] = $this->request->post['company_addr_city'];
        $data['city_name'] = $this->model_localisation_cn_addressbook->getCityById($this->request->post['company_addr_city']);
      }
      if (isset($this->request->post['company_addr_district']) ){
        $data['district_id'] = $this->request->post['company_addr_district'];
        $data['district_name'] = $this->model_localisation_cn_addressbook->getDistrictById($this->request->post['company_addr_district']);
      }
      if (isset($this->request->post['filter_sales_return_addr_street'])){
        $data['filter_sales_return_addr_street'] = $this->request->post['filter_sales_return_addr_street'];
      }
  
      if ($this->validateOuterForm()){

      $filter_sales_return_name = '';
      $filter_sales_return_phone = '';
      $company_addr_province = 0;
      $company_addr_city = 0;
      $company_addr_district = 0;
      $filter_sales_return_addr_street = '';
      $filter_free_shipping = 0;
      $filter_free_tax = 0;
      $filter_free_prepay = 0;
      $inlineRadioOptions = 0;

      if ( isset($this->request->post['filter_sales_return_name']) ){
        $filter_sales_return_name =$this->request->post['filter_sales_return_name'];
      }

      if ( isset($this->request->post['filter_sales_return_phone']) ){
        $filter_sales_return_phone =$this->request->post['filter_sales_return_phone'];
      }

      if ( isset($this->request->post['company_addr_province']) ){
        $company_addr_province =$this->request->post['company_addr_province'];
      }

      if ( isset($this->request->post['company_addr_city']) ){
        $company_addr_city =$this->request->post['company_addr_city'];
      }

      if ( isset($this->request->post['company_addr_district']) ){
        $company_addr_district =$this->request->post['company_addr_district'];
      }

      if ( isset($this->request->post['filter_sales_return_addr_street']) ){
        $filter_sales_return_addr_street =$this->request->post['filter_sales_return_addr_street'];
      }

      if ( isset($this->request->post['filter_free_shipping']) ){
        $filter_free_shipping =$this->request->post['filter_free_shipping'];
      }

      if ( isset($this->request->post['filter_free_tax']) ){
        $filter_free_tax =$this->request->post['filter_free_tax'];
      }

      if ( isset($this->request->post['filter_free_prepay']) ){
        $filter_free_prepay =$this->request->post['filter_free_prepay'];
      }

      if ( isset($this->request->post['inlineRadioOptions']) ){
        $inlineRadioOptions =$this->request->post['inlineRadioOptions'];
      }

	    $input_data = array(
        'supplier_id' =>  $this->session->data['supplier_id'],
        /*sales return info*/ 
        'name'      =>  $filter_sales_return_name,
        'telephone' =>  $filter_sales_return_phone,
        'addr_prov' =>  $company_addr_province,
        'addr_city' =>  $company_addr_city,
        'addr_dist' =>  $company_addr_district,
        'addr_info' =>  $filter_sales_return_addr_street,
        /*express general info*/ 
        'free_shipping' =>  $filter_free_shipping,
        'free_tax_min'  =>  $filter_free_tax,
        'free_tax_max'  =>  $filter_free_prepay,
        'order_charge'  =>  $inlineRadioOptions
      );
 
 
 
 
 
 
			//$this->error['warning'] = "测试： ".json_encode($input_data);
      $query = $this->model_extension_shipping_leshare->addExpressGeneralInfo($input_data);
      $this->session->data['success'] = "物流信息保存成功!";
      }

      //$url = '';
			//$this->response->redirect($this->url->link('extension/shipping/getList_leshare', 'token=' . $this->session->data['token'], 'SSL'));
    }


    $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
		);

    $data['token'] = $this->session->data['token']; 
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
    $data['text_confirm'] = $this->language->get('text_confirm');

    /***liuhang add***/
    $data['text_free_shipping'] = $this->language->get('text_free_shipping');
    $data['text_free_tax'] = $this->language->get('text_free_tax');
    $data['text_free_prepay'] = $this->language->get('text_free_prepay');
    $data['text_shipping_reminder'] = $this->language->get('text_shipping_reminder');
    $data['text_shipping_unit'] = $this->language->get('text_shipping_unit');
    $data['text_express_select'] = $this->language->get('text_express_select');
    //$data['text_express_namelist'] = $this->language->get('text_express_namelist');
    $data['text_shipping_price'] = $this->language->get('text_shipping_price');
    $data['text_shipping_price_reminder'] = $this->language->get('text_shipping_price_reminder');
    $data['text_shipping_price_unit'] = $this->language->get('text_shipping_price_unit');
    $data['text_express_dest'] = (array)$this->language->get('text_express_dest');
    $data['text_sales_return'] = $this->language->get('text_sales_return');
    $data['text_sales_return_name'] = $this->language->get('text_sales_return_name');
    $data['text_sales_return_phone'] = $this->language->get('text_sales_return_phone');
    $data['text_sales_return_addr'] = $this->language->get('text_sales_return_addr');
    /********/

		$data['button_edit'] = $this->language->get('button_edit');
  
    //$this->load->model('extension/shipping_leshare');
    $query = $this->model_extension_shipping_leshare->getExpressCompany();
    $data['text_express_namelist'] = $query;

    $query = $this->model_extension_shipping_leshare->getPlaceFromwhere();
    $data['place_origin'] = $query;

	  //$query = $this->model_extension_shipping_leshare->getPlaceDest();
	  $query = $this->model_extension_shipping_leshare->getProvinces();
    $data['place_dest_prov'] = $query;
			
	  $query = $this->model_extension_shipping_leshare->getProvinces();
    $data['provinces'] = $query;


    $supplierID = $this->session->data['supplier_id'];
    $query = $this->model_extension_shipping_leshare->getExpressGeneralInfo($supplierID);
    $data['filter_free_shipping'] = $query['free_shipping'];
    $data['filter_free_tax'] = $query['free_tax_min'];
    $data['filter_free_prepay'] = $query['free_tax_max'];
    $data['inlineRadioOptions'] = $query['order_charge'];



    $query = $this->model_extension_shipping_leshare->getExpressReturnInfo($supplierID);
    $data['filter_sales_return_name'] = $query['name'];
    $data['filter_sales_return_phone'] = $query['telephone'];
    $data['province_id'] = $query['addr_prov'];
    $data['city_id'] = $query['addr_city'];
    $data['district_id'] = $query['addr_dist'];
    $data['filter_sales_return_addr_street'] = $query['addr_info'];


    $url = '';
		$data['action'] = $this->url->link('extension/shipping/getList_leshare', 'token=' . $this->session->data['token'] . $url, 'SSL');


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
    }

		$this->load->model('extension/shipping_leshare');

		//$extensions = $this->model_extension_extension->getInstalled('shipping');


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping.tpl', $data));
	}

	protected function validateInnerform() {
		if (!$this->user->hasPermission('modify', 'extension/shipping')) {
			$this->error['warning'] = $this->language->get('error_permission');
		  return !$this->error;
		}

    if(!isset($this->request->post['input_expco_text']) || ($this->request->post['input_expco_text'] == '')){
			$this->error['warning'] = "错误！请选择快递商！";
		  return !$this->error;
    }

    if(!isset($this->request->post['radio_price']) || ($this->request->post['radio_price'] == '')){
			$this->error['warning'] = "错误！请选择计费方式！";
		  return !$this->error;
    }

    if(!isset($this->request->post['input_place_origin']) || ($this->request->post['input_place_origin'] == -1)){
			$this->error['warning'] = "错误！请选择发货地！";
		  return !$this->error;
    }

    if(!isset($this->request->post['input_place_dest_prov']) || ($this->request->post['input_place_dest_prov'] == '')){
			$this->error['warning'] = "错误！请选择到达地市！";
		  return !$this->error;
    }

    if(!isset($this->request->post['input_place_dest_city']) || ($this->request->post['input_place_dest_city'] == '')){
			$this->error['warning'] = "错误！请选择到达地市！";
		  return !$this->error;
    }

		return !$this->error;
	}

	protected function validateOuterForm() {
		if (!$this->user->hasPermission('modify', 'extension/shipping')) {
			$this->error['warning'] = $this->language->get('error_permission');
    }
/*
    if (isset($this->request->post['filter_free_shipping'])){
      $data['filter_free_shipping'] = $this->request->post['filter_free_shipping']; 
    }

    if (isset($this->request->post['filter_free_tax'])){
      $data['filter_free_tax']                 = $this->request->post['filter_free_tax'];
    }

    if (isset($this->request->post['filter_free_prepay'])){
      $data['filter_free_prepay']              = $this->request->post['filter_free_prepay'];
    }

    if (isset($this->request->post['inlineRadioOptions'])){
      $data['inlineRadioOptions']              = $this->request->post['inlineRadioOptions'];
    }

    if (isset($this->request->post['filter_sales_return_name'])){
      $data['filter_sales_return_name']        = $this->request->post['filter_sales_return_name'];
    }

    if (isset($this->request->post['filter_sales_return_phone'])){
     $data['filter_sales_return_phone']       = $this->request->post['filter_sales_return_phone'];
    }

    if (isset($this->request->post['province_id'])){
      $data['province_id']                     = $this->request->post['province_id'];
    }

    if (isset($this->request->post['filter_sales_return_addr_street'])){
      $data['filter_sales_return_addr_street'] = $this->request->post['filter_sales_return_addr_street'];
    }
 */

    if(!isset($this->request->post['filter_sales_return_name']) || ($this->request->post['filter_sales_return_name'] == '')){
			$this->error['warning'] = "错误！退货人姓名未填！";
		  return !$this->error;
    }

    if(!isset($this->request->post['filter_sales_return_phone']) || ($this->request->post['filter_sales_return_phone'] == '')){
			$this->error['warning'] = "错误！退货人联系方式未填！";
		  return !$this->error;
    }

    $phone_num = $this->request->post['filter_sales_return_phone'];
    if(!preg_match("/1[3458]{1}\d{9}$/",$phone_num)){  
      $this->error['warning'] = "请输入正确的手机号码！";
		  return !$this->error;
    }

    if(!isset($this->request->post['company_addr_province']) || ($this->request->post['company_addr_province'] == '')){
			$this->error['warning'] = "错误！退货地址省份信息错误！";
		  return !$this->error;
    }

    if(!isset($this->request->post['company_addr_city']) || ($this->request->post['company_addr_city'] == '')){
			$this->error['warning'] = "错误！退货地址地市信息错误！";
		  return !$this->error;
    }

    if(!isset($this->request->post['company_addr_district']) || ($this->request->post['company_addr_district'] == '')){
			$this->error['warning'] = "错误！退货地址区县信息错误！";
		  return !$this->error;
    }

    if(!isset($this->request->post['filter_sales_return_addr_street']) || ($this->request->post['filter_sales_return_addr_street'] == '')){
			$this->error['warning'] = "错误！退货详细地址信息未填！";
		  return !$this->error;
    }

    if(!isset($this->request->post['filter_free_shipping']) || ($this->request->post['filter_free_shipping'] == '')){
			$this->error['warning'] = "错误！包邮标准未设置！";
		  return !$this->error;
    }

    if(!isset($this->request->post['filter_free_tax']) || ($this->request->post['filter_free_tax'] == '')){
			$this->error['warning'] = "错误！免税标准未设置！";
		  return !$this->error;
    }

    if(!isset($this->request->post['filter_free_prepay']) || ($this->request->post['filter_free_prepay'] == '')){
			$this->error['warning'] = "错误！包税标准未设置！";
		  return !$this->error;
    }

    if(!isset($this->request->post['inlineRadioOptions']) || ($this->request->post['inlineRadioOptions'] == '')){
			$this->error['warning'] = "错误！同一订单多种计费方式条件下，邮费标准未设置！";
		  return !$this->error;
    }

		return !$this->error;
	}

	protected function isInnerForm() {

    if ( (isset($this->request->post['input_inner_form'])) && ($this->request->post['input_inner_form'] == 1 ) ) {
      return true;
    } else {
      return false;
    }
	}


	public function getExpressPriceList(){
		$this->load->model('extension/shipping_leshare');
    $fromwhere_id = $this->request->post['fromwhere_id'];
    $query_info = array(
      'supplier_id' => $this->session->data['supplier_id'],
      'place_origin' => ''
    );
    
    $result = $this->model_extension_shipping_leshare->getExpressPrice_new($query_info);
    $exp_price_list = array(
      'fromwhere_name' => '',
      'result' => $result
    );
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($exp_price_list));
   
	}


	public function getExpcoByAddress(){
		$this->load->model('extension/shipping_leshare');
    $fromwhere_id = $this->request->get['fromwhere_id'];
    $province_id = $this->request->get['province_id'];
    $city_id = $this->request->get['city_id'];
    $query_info = array(
      'supplier_id' => $this->session->data['supplier_id'],
      'place_origin' => $fromwhere_id,
      'province_id'  => $province_id,
      'city_id'     => $city_id
    );
    
    $result = $this->model_extension_shipping_leshare->getExpcoByAddress($query_info);
    if (!empty($result['expco'])){
    $expco = array(
      'expco' => $result['expco']
    );
    } else{
     $expco = array(
      'expco' => "" 
    );
    }
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($expco));
   
	}





























}
