<?php
class ControllerCommentKey extends MyController {

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

	private $module_name = 'comment/key';

	private $base_url= '';

	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->getKeyList($lans);
	}
	public function addKey(){
		$this->load->model($this->module_name);
		$key_name = $this->request->post['key_name'];
		$success = $this->model_comment_key->addKey($key_name);
		writeJson(['success'=>$success]);
	}
	public function deleteKey(){
		$this->load->model($this->module_name);
		$key_id = $this->request->post['key_id'];
		$success = $this->model_comment_key->delKey($key_id);
		writeJson(['success'=>$success]);
	}
	public function setStatus(){
		$this->load->model($this->module_name);
		$key_id = $this->request->post['key_id'];
		$status = $this->request->post['status'];
		$success = $this->model_comment_key->setKeyStatus($key_id,$status);
		writeJson(['success'=>$success]);
	}

	public function pager(){
		$this->load->model($this->module_name);
		$filter_data = $this->parsePostEntries(array(),false,false,true);
		$list = $this->model_comment_key->queryKeys($filter_data);
		$count_total = $this->model_comment_key->queryKeysCount($filter_data);
		$url = $this->parseUrl(array(),false,false,false);
		$page = $filter_data['page'];
		$paginator = $this->buildPaginator($page,$count_total,$url,$this->module_name.'/pager',$this->language->get('text_pagination'));
		writeJson(['list'=>$list,'paginator'=>$paginator]);
	}

	protected function getKeyList($lans) {
		$data = array();
		$data['set_key_status_url'] = $this->url->link($this->module_name.'/setStatus', 'token=' . $this->session->data['token']);
		$data['add_key_url'] = $this->url->link($this->module_name.'/addKey', 'token=' . $this->session->data['token']);
		$data['delete_key_url'] = $this->url->link($this->module_name.'/deleteKey', 'token=' . $this->session->data['token']);
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$filter_data = $this->parseEntries(array(),false,false,true);
		$data = array_merge($data,$lans);
		$data['keys'] = $this->model_comment_key->queryKeys($filter_data);
		$count_total = $this->model_comment_key->queryKeysCount($filter_data);
		$url = $this->parseUrl(array(),false,false,false);
		$page = $filter_data['page'];
		$paginator = $this->buildPaginator($page,$count_total,$url,$this->module_name.'/pager',$this->language->get('text_pagination'));
		$data['paginator'] = $paginator;
		$data['breadcrumbs'] = $this->parseBreadCrumbs(array());
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view($this->module_name.'.tpl', $data));
	}
}