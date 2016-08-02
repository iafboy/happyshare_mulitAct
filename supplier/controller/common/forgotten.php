<?php
class ControllerCommonForgotten extends Controller {
	private $error = array();

	public function index() {
		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$this->response->redirect($this->url->link('common/dashboard', '', 'SSL'));
		}

		if (!$this->config->get('config_password')) {
			//$this->response->redirect($this->url->link('common/login', '', 'SSL'));
		}

		$this->load->language('common/forgotten');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('user/user');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->language('mail/forgotten');

      $code = sha1(uniqid(mt_rand(), true));

      $sms_pin = rand(1001,9999);// used as pin code in SMS

			$this->model_user_user->editCode($this->request->post['mobile'], $code, $sms_pin);
      
      // call API of SMS server and send captcha to the end user
      $sms_req_url = "http://120.26.69.248/msg/HttpSendSM?";
      $sms_req_url .= "account=002002&pswd=Sy123002";
      $sms_req_url .= "&mobile=".$this->request->post['mobile'];
      //$sms_req_url .= "&mobile=15101116047";
      $sms_req_url .= "&msg=".urlencode("[来自好就分享]您好，您的验证码：").$sms_pin;
      $sms_req_url .= "&needstatus=true&product=";
      
      $html = file_get_contents($sms_req_url);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('common/reset', 'code=' . $code . '&text=' . $html, 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_your_mobile'] = $this->language->get('text_your_mobile');
		$data['text_mobile'] = $this->language->get('text_mobile');

		$data['entry_mobile'] = $this->language->get('entry_mobile');

		$data['button_reset'] = $this->language->get('button_reset');
		$data['button_cancel'] = $this->language->get('button_cancel');

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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/forgotten', 'token=' . '', 'SSL')
		);
		
		$data['action'] = $this->url->link('common/forgotten', '', 'SSL');

		$data['cancel'] = $this->url->link('common/login', '', 'SSL');

    if (isset($this->request->post['mobile'])) {
			$data['mobile'] = $this->request->post['mobile'];
		} else {
			$data['mobile'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/forgotten.tpl', $data));
	}

	protected function validate() {
/*
    if (!isset($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		} elseif (!$this->model_user_user->getTotalUsersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		}
*/
    
    if (!isset($this->request->post['mobile'])) {
			$this->error['warning'] = $this->language->get('error_mobile');
		} elseif (!$this->model_user_user->getTotalUsersByMobile($this->request->post['mobile'])) {
			$this->error['warning'] = $this->language->get('error_mobile');
		}

		return !$this->error;
  }


}
