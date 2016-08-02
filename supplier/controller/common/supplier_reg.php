<?php
class ControllerCommonSupplierReg extends Controller {
	private $error = array();
  private $user_info = array();
  private $succ=false;
 
  public function index() {
		
		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$this->response->redirect($this->url->link('common/dashboard', '', 'SSL'));
		}

		if (!$this->config->get('config_password')) {
			//$this->response->redirect($this->url->link('common/login', '', 'SSL'));
		}

		$this->load->language('common/supplier_reg');

		$this->document->setTitle($this->language->get('heading_title'));

    
    /* liuhang : load address info for province-city-county menu */
    if (isset($this->session->data['company_address']['province_id'])) {
			$data['province_id'] = $this->session->data['company_address']['province_id'];
		} else {
			$data['province_id'] = 0; // 0 is a default value, not a valid value! just a prompt
		}

    if (isset($this->session->data['company_address']['city_id'])) {
			$data['city_id'] = $this->session->data['company_address']['city_id'];
		} else {
			$data['city_id'] = '';
		}
    $this->load->model('localisation/cn_addressbook');
		$data['provinces'] = $this->model_localisation_cn_addressbook->getProvinces();

    /*
    if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['check_code'])) {
 
      $ret = array();
       
      if (strcasecmp($this->session->data['rand'],$this->request->post['check_code']) == 0){
          $ret['success'] = true;
      }else{
          $ret['success'] = false;
      }
      echo json_encode($ret);

    }
     */
    $data['company_name'] = "";
    $data['company_addr_details'] = "";
    $data['company_contacts'] = "";
    $data['contacts_phone'] = "";
      $data['contacts_email'] = "";
    $data['province_id'] = "";
    $data['city_id'] = "";
    $data['city_name'] = "";
    $data['district_id'] = "";
    $data['district_name'] = "";

    if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

      $data['company_name'] = $this->request->post['company_name'];
      $data['company_addr_details'] = $this->request->post['company_addr_details'];
      $data['company_contacts'] = $this->request->post['company_contacts'];
      $data['contacts_phone'] = $this->request->post['contacts_phone'];
        $data['contacts_email'] = $this->request->post['contacts_email'];
      if (isset($this->request->post['company_addr_province'])){ 
        $data['province_id'] = $this->request->post['company_addr_province'];
      }

      if ((isset($this->request->post['company_addr_city'])) && ($this->request->post['company_addr_city'] != 0)){
        $data['city_id'] = $this->request->post['company_addr_city'];
        $data['city_name'] = $this->model_localisation_cn_addressbook->getCityById($this->request->post['company_addr_city']);
      }
      if ((isset($this->request->post['company_addr_district'])) && ($this->request->post['company_addr_district'] != 0)){
        $data['district_id'] = $this->request->post['company_addr_district'];
        $data['district_name'] = $this->model_localisation_cn_addressbook->getDistrictById($this->request->post['company_addr_district']);
      }


      if ($this->validate()) { 
        if (strcasecmp($this->session->data['rand'],$this->request->post['check_code']) != 0){
           
         //echo "random is ".$this->session->data['rand'];
         //echo "check_code is ".$this->request->post['check_code'];
         $this->error['warning'] = $this->language->get('error_check_code');
        }else {

          unset($this->request->post['check_code']);
          
          $user_info['company_contacter'] = $this->request->post['company_contacts'];
          $user_info['supplier_group_id'] = 0; // 0 stand for admin of supplier
          $user_info['supplier_company'] = $this->request->post['company_name'];
          
          //transfer id in database to related string(province name, city name, district name)
          $user_info['prov'] = $this->model_localisation_cn_addressbook->getProvinceById($this->request->post['company_addr_province'])['name'];
          $user_info['city'] = $this->model_localisation_cn_addressbook->getCityById($this->request->post['company_addr_city'])['name'];  
          $user_info['distic'] = $this->model_localisation_cn_addressbook->getDistrictById($this->request->post['company_addr_district'])['name'];
          
          $user_info['street'] = $this->request->post['company_addr_details'];
          $user_info['company_contacter_phone'] = $this->request->post['contacts_phone'];
            $user_info['company_contacter_email'] = $this->request->post['contacts_email'];
          $user_info['status'] = 0; // 0 stands for "not contact yet"

          
          $this->load->model('user/user');
          $this->model_user_user->addUserReg($user_info);

          $this->error['warning'] = $this->language->get('error_finish');
		  $this->succ=true;
          }
        //$this->session->data['success'] = $this->language->get('text_success');
      }
    }

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_company_name'] = $this->language->get('text_company_name');
		$data['text_company_addr'] = $this->language->get('text_company_addr');
		$data['text_company_addr_province'] = $this->language->get('text_company_addr_province');
		$data['text_company_addr_city'] = $this->language->get('text_company_addr_city');
		$data['text_company_addr_district'] = $this->language->get('text_company_addr_district');
		$data['text_company_addr_details'] = $this->language->get('text_company_addr_details');
		$data['text_company_contacts'] = $this->language->get('text_company_contacts');
		$data['text_contacts_phone'] = $this->language->get('text_contacts_phone');
      $data['text_contacts_email'] = $this->language->get('text_contacts_email');
		$data['text_prompt_msg'] = $this->language->get('text_prompt_msg');
		$data['text_check_code'] = $this->language->get('text_check_code');
    
   
		$data['button_submit'] = $this->language->get('button_submit');
    $data['button_cancel'] = $this->language->get('button_cancel');

    $data['error_company_name'] = $this->language->get('error_company_name');
		$data['error_company_addr'] = $this->language->get('error_company_addr');
		$data['error_company_addr_province'] = $this->language->get('error_company_addr_province');
		$data['error_company_addr_city'] = $this->language->get('error_company_addr_city');
		$data['error_company_addr_district'] = $this->language->get('error_company_addr_district');
		$data['error_company_addr_details'] = $this->language->get('error_company_addr_details');
		$data['error_company_contacts'] = $this->language->get('error_company_contacts');
		$data['error_contacts_phone'] = $this->language->get('error_contacts_phone');
		$data['error_finish'] = $this->language->get('error_finish');


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/supplier_reg', 'token=' . '', 'SSL')
		);
		
		$data['action'] = $this->url->link('common/supplier_reg', '', 'SSL');

		$data['cancel'] = $this->url->link('common/login', '', 'SSL');

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		if(!$this->succ){
		$this->response->setOutput($this->load->view('common/supplier_reg.tpl', $data));
		}else {
		$this->response->setOutput($this->load->view('common/supplier_reg_finish.tpl', $data));
		}
	}

	protected function validate() {

    if (!isset($this->request->post['company_name'])){
      $this->error['warning'] = $this->language->get('error_company_name'); 
		  return !$this->error;
    }elseif (!isset($this->request->post['company_addr_province'])){
			$this->error['warning'] = $this->language->get('error_company_addr_province');
		  return !$this->error;
    }elseif (!isset($this->request->post['company_addr_city'])){
			$this->error['warning'] = $this->language->get('error_company_addr_city');
		  return !$this->error;
    }elseif (!isset($this->request->post['company_addr_district'])){
			$this->error['warning'] = $this->language->get('error_company_addr_district');
		  return !$this->error;
    }elseif (!isset($this->request->post['company_addr_details'])){
      $this->error['warning'] = $this->language->get('error_company_addr_details');
		  return !$this->error;
    }elseif (!isset($this->request->post['company_contacts'])){
      $this->error['warning'] = $this->language->get('error_company_contacts');
		  return !$this->error;
    }elseif (!isset($this->request->post['contacts_phone'])){
      $this->error['warning'] = $this->language->get('error_contacts_phone');
		  return !$this->error;
    }elseif (!isset($this->request->post['check_code'])){
      $this->error['warning'] = $this->language->get('error_check_code');
		  return !$this->error;
    }


    if ((empty($this->request->post['company_name'])) || (ctype_space($this->request->post['company_name']))){
      $this->error['warning'] = "公司名不能为空！"; 
		  return !$this->error;
    }elseif ((empty($this->request->post['company_addr_province'])) || (ctype_space($this->request->post['company_addr_province']))){
			$this->error['warning'] = "省份信息不能为空！";
		  return !$this->error;
    }elseif ((empty($this->request->post['company_addr_city'])) || (ctype_space($this->request->post['company_addr_city']))){
			$this->error['warning'] = "地市信息不能为空！";
		  return !$this->error;
    }elseif ((empty($this->request->post['company_addr_district'])) || (ctype_space($this->request->post['company_addr_district']))){
			$this->error['warning'] = "城区信息不能为空！";
		  return !$this->error;
    }elseif ((empty($this->request->post['company_addr_details'])) || (ctype_space($this->request->post['company_addr_details']))){
      $this->error['warning'] = "详细地址信息不能为空！";
		  return !$this->error;
    }elseif ((empty($this->request->post['company_contacts'])) || (ctype_space($this->request->post['company_contacts']))){
      $this->error['warning'] = "公司联系人信息不能为空！";
		  return !$this->error;
    }elseif ((empty($this->request->post['contacts_phone'])) || (ctype_space($this->request->post['contacts_phone']))) {
        $this->error['warning'] = "联系人电话不能为空！";
        return !$this->error;
    }elseif ((empty($this->request->post['contacts_email'])) || (ctype_space($this->request->post['contacts_email']))){
            $this->error['warning'] = "联系人邮箱不能为空！";
            return !$this->error;
    }elseif ((empty($this->request->post['check_code'])) || (ctype_space($this->request->post['check_code']))){
      $this->error['warning'] = "校验码不能为空！";
		  return !$this->error;
    }else{
      //$this->error['warning'] = $this->language->get('error_finish');
    } 

    /*
    $phone_num = $this->request->post['contacts_phone'];
    if (!preg_match("^((\(\d{2,3}\))|(\d{3}\-))?13\d{9}$",$phone_num)){
      $this->error['warning'] = "请输入正确的手机号码！";
    }
     */
    $phone_num = $this->request->post['contacts_phone'];
    if(!preg_match("/1[3458]{1}\d{9}$/",$phone_num)){  
      $this->error['warning'] = "请输入正确的手机号码！";
		  return !$this->error;
    }
		
		return !$this->error;
  }

	public function province_cn() {
		$json = array();

		$this->load->model('localisation/cn_addressbook');

    if(isset($this->request->get['province_id'])){

      $province_info = $this->model_localisation_cn_addressbook->getProvinceById((int)$this->request->get['province_id']);

      if ($province_info != null) {
        //$this->load->model('localisation/cn_addressbook');
        
        $json = array(
          'province_id'       => $province_info['id'],
          'region_code'       => $province_info['region_code'],
          'name'              => $province_info['name'],
          'city'              => $this->model_localisation_cn_addressbook->getCityByProviceId($province_info['region_code'])
        );
      }

      $this->response->addHeader('Content-Type: application/json');
      $this->response->setOutput(json_encode($json));
    }
	}

	public function city_cn() {
		$json = array();

		$this->load->model('localisation/cn_addressbook');

    if(isset($this->request->get['cityid'])){

      $city_info = $this->model_localisation_cn_addressbook->getCityById((int)$this->request->get['cityid']);

      if ($city_info != null) {
        
        $json = array(
          'city_id'           => $city_info['id'],
          'region_code'       => $city_info['region_code'],
          'name'              => $city_info['name'],
          'parent_code'       => $city_info['parent_code'],
          'district'          => $this->model_localisation_cn_addressbook->getDistrictByCityId($city_info['region_code'])
        );
      }

      $this->response->addHeader('Content-Type: application/json');
      $this->response->setOutput(json_encode($json));
    }
	}

  public function captcha() {

    /*
    $this->load->library('captcha');
    $captcha = new Captcha();
    $random = $captcha->getCode();
    $this->session->data['captcha'] = $random;
    $captcha->showImage();
     */
    $random = substr(sha1(mt_rand()), 17, 6);
    $this->session->data['rand'] = $random;
    $image = imagecreatetruecolor(150, 35);

		$width = imagesx($image);
		$height = imagesy($image);

		$black = imagecolorallocate($image, 0, 0, 0);
		$white = imagecolorallocate($image, 255, 255, 255);
		$red = imagecolorallocatealpha($image, 255, 0, 0, 75);
		$green = imagecolorallocatealpha($image, 0, 255, 0, 75);
		$blue = imagecolorallocatealpha($image, 0, 0, 255, 75);

		imagefilledrectangle($image, 0, 0, $width, $height, $white);

		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $red);
		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $green);
		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $blue);

		imagefilledrectangle($image, 0, 0, $width, 0, $black);
		imagefilledrectangle($image, $width - 1, 0, $width - 1, $height - 1, $black);
		imagefilledrectangle($image, 0, 0, 0, $height - 1, $black);
		imagefilledrectangle($image, 0, $height - 1, $width, $height - 1, $black);

		imagestring($image, 10, intval(($width - (strlen($random) * 9)) / 2), intval(($height - 15) / 2), $random, $black);

		header('Content-type: image/jpeg');

		imagejpeg($image);
		imagedestroy($image);
 
  }

  public function check_code_img() {
    
    $json = array(
          'correct'       => 0 
        );

    if(isset($this->request->get['check_code_img_value']) && (!empty($this->request->get['check_code_img_value']))){

      if (strcasecmp($this->session->data['rand'],$this->request->get['check_code_img_value']) == 0){
        $json = array(
          'correct'       => 1
        );
      } else {
        $json = array(
          'correct'       => 2 
        );
     
      }

    }
      $this->response->addHeader('Content-Type: application/json');
      $this->response->setOutput(json_encode($json));
 
  }













}
