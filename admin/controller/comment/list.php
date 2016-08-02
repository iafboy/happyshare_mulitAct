<?php
class ControllerCommentList extends MyController {

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

	private $module_name = 'comment/list';

	public function delComment(){
		$this->load->model($this->module_name);
		$comment_id = $this->request->post['comment_id'];
		$success = $this->model_comment_list->delComment($comment_id);
		writeJson(['success'=>$success]);
	}
	public function reply(){
		$this->load->model($this->module_name);
		$product_id = $this->request->post['product_id'];
		$reply_text = $this->request->post['reply_text'];
		$user_id = $this->session->data['user_id'];
		//$success = $this->model_comment_list->addReply($comment_id,$user_id,$reply_text);
		$success = $this->model_comment_list->addComment($product_id,$user_id,$reply_text);
		writeJson(['success'=>$success]);
	}
	public function index() {
		$this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
		$lans = $this->load->language($this->module_name);
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model($this->module_name);
		$this->getCommentList($lans);
	}
	protected function getCommentList($lans) {
		$data = array();
		$data['del_comment_url'] = $this->url->link($this->module_name.'/delComment', 'token=' . $this->session->data['token']);
		$data['reply_url'] = $this->url->link($this->module_name.'/reply', 'token=' . $this->session->data['token']);
//		$array = [
//			'product_no',
//			'product_name',
//			'product_type',
//			'buyer_account',
//			'comment_key',
//			'contain_comment_key',
//			['comment_time_start','comment_time_end']
//		];
		$array = [
			'product_id',
			'product_no',
			'product_name',
			'product_type',
			'buyer_account',
			'comment_key',
			['comment_time_start','comment_time_end']
		];
    $filter_data = $this->parseEntries($array,false,false,true);
    //debug
    //$data['success'] = json_encode($filter_data);
		$entries_form = new EntriesForm(4,$array);
		$entries_form->setEntriesValue($filter_data);
		$entries_form->setFormId('comments_list_fm');
		$entries_form->setBaseUrl($this->base_url);
		$entries_form->setRoute($this->module_name);
		$entries_form->setToken($this->session->data['token']);
		$list = ['product_type'];
		$product_types = $this->model_comment_list->getProductTypes();
		$product_array = ['*'=>'全部'];
		foreach($product_types as $product_type){
			$product_array[$product_type['product_type_id']] =$product_type['type_name'];
		}
		$list_array = [
			'product_type'=> $product_array
		];
		$entries_form->setSelectTypeEntries($list,$list_array);
//		$entries_form->setEntriesInputType(
//			[
//				'contain_comment_key'
//			]
//			,'checkbox');
//    if ($filter_data['filter_contain_comment_key'] == null){
//    $entries_form->setEntriesValue(['contain_comment_key' => 1 ]);
//    }
    /*else if ($filter_data['filter_contain_comment_key'] == 1){
    $entries_form->setEntriesValue(['contain_comment_key' => 1 ]);
    } else {
    $entries_form->setEntriesValue(['contain_comment_key' => 0 ]);
    }*/

    $entries_form->setEntriesInputType(
			['comment_time_start','comment_time_end']
			,'date');

		$btns = array();
		$btns[] = new Button('btn_comments_query main-lfx-btn',$lans['btn_comments_query'],'lfx-btn');
		$entries_form->setButtons($btns);
		$entries_form_array = $entries_form->toArray($lans);
		$data['entries'] = $this->load->view('common/entries.tpl',$entries_form_array);

//		$column_arr =[
//			'column_product_no',
//		'column_product_name',
//		'column_buyer_account',
//		'column_comment_time',
//		'column_comment_content',
//		'column_operation'];
		$column_arr =[
			'column_product_no',
			'column_product_name',
			'column_buyer_account',
			'column_comment_time',
			'column_comment_content',
			'column_operation'];
		$columns = array();
		foreach($column_arr as $c){
			$columns[$c] = $lans[$c];
		}
		$data['theader'] = $this->load->view('common/theader.tpl',['columns'=>$columns]);

		$page = $filter_data['page'];
		// query results
		$results = $this->model_comment_list->queryComments($filter_data);
		// query total count
		$count_total = $this->model_comment_list->queryCommentsCount($filter_data);
		$data['comments'] = $results;
		$url = $this->parseUrl($array,false,false,false);
		$pagination = $this->buildPagination($page,$count_total,$url,$this->module_name);
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($count_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($count_total - $this->config->get('config_limit_admin'))) ? $count_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $count_total, ceil($count_total / $this->config->get('config_limit_admin')));
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
