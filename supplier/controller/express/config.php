<?php
class ControllerExpressConfig extends MyController {
	private $error = array();

    private $base_url= '';

    private $module_name = 'express/config';

	public function index() {
		$this->load->language($this->module_name);

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model($this->module_name);
		$this->getList();
	}

	public function getList() {
        $this->load->model('common/apis');
        $data = [];
        $data['breadcrumbs'] = $this->parseBreadCrumbs(array());
        $data['token'] = $this->session->data['token'];
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['type_modetype'] = ['1'=>'件数计费','2'=>'重量计费','3'=>'体积计费'];
        $data['type_baoyoutype'] = ['1'=>'自定义运费','2'=>'包邮'];
        $data['status_express'] = ['0'=>'禁用','1'=>'启用'];
        $data['privs'] = $this->model_common_apis->getAllPrivs();
        $data['setting'] = $this->model_express_config->getSetting();
        $data['return'] = $this->model_express_config->getReturn();
        $data['templates'] = $this->model_express_config->getTemplates();
        $this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
	}

    public function renderConfig() {
        $this->load->model($this->module_name);
        $this->load->model('common/apis');
        $data = [];
        $config_id = $this->request->get['config_id'];
        if(is_valid($config_id)){
            $data['config'] = $this->model_express_config->getConfigById($config_id);
        }
        $data['token'] = $this->session->data['token'];
        $data['privs'] = $this->model_common_apis->getAllPrivs();
        $data['fromwheres'] = $this->model_common_apis->getFromWheres();
        $data['companies'] = $this->model_common_apis->getAllExpressCompanies();
        $data['breadcrumbs'] = $this->parseBreadCrumbs(array());
        $data['token'] = $this->session->data['token'];
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('express/express-price-row.tpl', $data));
    }

    public function renderConfigArea() {
        $this->load->model($this->module_name);
        $this->load->model('common/apis');
        $data = [];
        $data['token'] = $this->session->data['token'];
        $data['privs'] = $this->model_common_apis->getAllPrivs();
        $data['areas'] = $this->model_common_apis->getAreas();
        $this->response->setOutput($this->load->view('express/config-area.tpl', $data));
    }

    public function configArea(){
        $this->load->model($this->module_name);
        $this->load->model('common/apis');
        $privCodes = $this->request->post['privCodes'];
        $areaId = $this->request->post['areaId'];
        $this->model_common_apis->setAreaPrivs($areaId,$privCodes);
    }

    public function getAreas(){
        $this->load->model('common/apis');
        $areas = $this->model_common_apis->getAreas();
        writeJson($areas);
    }

    public function getCities() {
        $this->load->model('common/apis');
        $priv_code = $this->request->post['priv_code'];
        $priv_id = $this->request->post['priv_id'];
        $cities = $this->model_common_apis->getCities($priv_code,$priv_id);
        writeJson($cities);
    }
    public function getDists() {
        $this->load->model('common/apis');
        $city_code = $this->request->post['city_code'];
        $city_id = $this->request->post['city_id'];
        $dists = $this->model_common_apis->getDists($city_code,$city_id);
        writeJson($dists);
    }
    public function addExpressConfig() {
        $this->load->model($this->module_name);
        $post = $this->request->post;
        $msg = $this->model_express_config->addConfig($post);
        writeJson($msg);
    }
    public function modExpressConfig() {
        $this->load->model($this->module_name);
        $post = $this->request->post;
        $msg = $this->model_express_config->modConfig($post);
        writeJson($msg);
    }
    public function delTemplate() {
        $this->load->model($this->module_name);
        $templateId = $this->request->post['template_id'];
        $msg = $this->model_express_config->delTemplate($templateId);
        writeJson($msg);
    }

    public function saveOrModReturn(){
        $this->load->model($this->module_name);
        $post = $this->request->post;
        $msg = $this->model_express_config->saveOrModReturn($post);
        writeJson($msg);
    }
    public function saveOrModSetting(){
        $this->load->model($this->module_name);
        $post = $this->request->post;
        $msg = $this->model_express_config->saveOrModSetting($post);
        writeJson($msg);
    }

    public function activeTemplate(){
        $this->load->model($this->module_name);
        $templateId = $this->request->post['template_id'];
        $msg = $this->model_express_config->setTemplateStatus($templateId,1);
        writeJson($msg);
    }
    public function inactiveTemplate(){
        $this->load->model($this->module_name);
        $templateId = $this->request->post['template_id'];
        $msg = $this->model_express_config->setTemplateStatus($templateId,0);
        writeJson($msg);
    }

}
