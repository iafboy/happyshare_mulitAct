<?php
class ControllerCommonReset extends Controller {
	private $error = array();

	public function index() {
		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$this->response->redirect($this->url->link('common/dashboard', '', 'SSL'));
		}

		if (!$this->config->get('config_password')) {
			//$this->response->redirect($this->url->link('common/login', '', 'SSL'));
		}

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}
    $this->session->data['success'] = '';
		$this->load->language('common/reset');
		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUserByCode($code);

		if ($user_info) {

			$this->document->setTitle($this->language->get('heading_title'));

      if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

        //check the authcode here!!!
        if (($this->request->post['authcode']) == $user_info['sms_pin']){

          $this->model_user_user->editPassword($user_info['supplier_id'], $this->request->post['password']);
          $this->session->data['success'] = $this->language->get('text_success');
          $this->response->redirect($this->url->link('common/login', '', 'SSL'));
        } else { // sms pin code verify failed
          
          $this->session->data['success'] = "短信验证码错误，请重新获取！";
          $this->response->redirect($this->url->link('common/forgotten', '', 'SSL'));

        }
			}

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_password'] = $this->language->get('text_password');

			$data['entry_password'] = $this->language->get('entry_password');
			$data['entry_confirm'] = $this->language->get('entry_confirm');
			$data['entry_authcode'] = $this->language->get('entry_authcode');

			$data['button_save'] = $this->language->get('button_save');
			$data['button_cancel'] = $this->language->get('button_cancel');

      $data['getpin'] = $this->url->link('common/reset', 'code=' . $code, 'SSL');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('common/reset', '', 'SSL')
			);

			if (isset($this->error['password'])) {
				$data['error_password'] = $this->error['password'];
			} else {
				$data['error_password'] = '';
			}

			if (isset($this->error['confirm'])) {
				$data['error_confirm'] = $this->error['confirm'];
			} else {
				$data['error_confirm'] = '';
			}

      if (isset($this->error['authcode'])) {
				$data['error_authcode'] = $this->error['authcode'];
			} else {
				$data['error_authcode'] = '';
			}

			$data['action'] = $this->url->link('common/reset', 'code=' . $code, 'SSL');

			$data['cancel'] = $this->url->link('common/login', '', 'SSL');

			if (isset($this->request->post['password'])) {
				$data['password'] = $this->request->post['password'];
			} else {
				$data['password'] = '';
			}

			if (isset($this->request->post['confirm'])) {
				$data['confirm'] = $this->request->post['confirm'];
			} else {
				$data['confirm'] = '';
			}

      if (isset($this->request->post['authcode'])) {
				$data['authcode'] = $this->request->post['authcode'];
			} else {
				$data['authcode'] = '';
			}

			$data['header'] = $this->load->controller('common/header');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('common/reset.tpl', $data));
		} else {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSettingValue('config', 'config_password', '0');

	  	$this->session->data['success'] = '';
			return new Action('common/login');
		}
	}
	
	protected function validate() {
		if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$this->error['confirm'] = $this->language->get('error_confirm');
		}

    if (utf8_strlen($this->request->post['authcode']) != 4 ) {
			$this->error['authcode'] = $this->language->get('error_authcode');
    }

		return !$this->error;
	}
}
