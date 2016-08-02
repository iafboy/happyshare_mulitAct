<?php
class ControllerExpressRule extends MyController {
	private $error = array();

    private $base_url= '';

    private $module_name = 'express/rule';

	public function index() {
		$this->load->language($this->module_name);

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model($this->module_name);
		$this->renderConfig();
	}

    public function renderConfig() {
        $this->load->model($this->module_name);
        $this->load->model('common/apis');
        $data = [];
        $data['template'] = null;
        $data['token'] = $this->session->data['token'];
        $data['privs'] = $this->model_common_apis->getAllPrivs();
        $data['fromwheres'] = $this->model_common_apis->getFromWheres();
        $data['breadcrumbs'] = $this->parseBreadCrumbs(array());
        $data['token'] = $this->session->data['token'];
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('express/express-price-row.tpl', $data));
    }
    public function renderDistrictConfig() {
        $this->load->model($this->module_name);
        $this->load->model('common/apis');
        $privs = $this->request->post['privs'];
        $areas = $this->request->post['areas'];
        $cities = $this->request->post['cities'];
        $data = [];
        $data['tmp_privs'] = $privs;
        $data['tmp_areas'] = $areas;
        $data['tmp_cities'] = $cities;
        $data['token'] = $this->session->data['token'];
        $areas = $this->model_common_apis->getAreas();
        foreach($areas as &$area){
            $privs = $this->model_common_apis->getAllPrivs($area['region_code']);
            foreach($privs as &$priv){
                $cities = $this->model_common_apis->getCities($priv['region_code']);
                $priv['cities'] = $cities;
            }
            $area['privs'] = $privs;
        }
        $data['areas'] = $areas;
        $this->response->setOutput($this->load->view('express/edit-express-district.tpl', $data));
    }

    public function renderModifyTemplate(){
        $this->load->language($this->module_name);
        $this->load->model($this->module_name);
        $this->load->model('common/apis');
        $data = [];
        $templateId = $this->request->get['template_id'];
        $data['template_id'] = $templateId;
        $data['template'] = $this->model_express_rule->queryTemplateById($templateId);
        $data['token'] = $this->session->data['token'];
        $data['privs'] = $this->model_common_apis->getAllPrivs();
        $data['fromwheres'] = $this->model_common_apis->getFromWheres();
        $data['breadcrumbs'] = $this->parseBreadCrumbs(array());
        $data['token'] = $this->session->data['token'];
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('express/express-price-row.tpl', $data));
    }

    public function addTemplate(){
        $post = $this->request->post;
        $this->load->model($this->module_name);
        $this->load->model('common/apis');
        $msg = $this->model_express_rule->addTemplate($post);
        writeJson($msg);
    }
    public function modTemplate(){
        $post = $this->request->post;
        $this->load->model($this->module_name);
        $this->load->model('common/apis');
        $msg = $this->model_express_rule->modTemplate($post);
        writeJson($msg);
    }
}
