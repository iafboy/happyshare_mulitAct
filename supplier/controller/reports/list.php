<?php
class ControllerReportsList extends Controller {
	private $error = array();
  public $module_name = 'reports/list';
  
  public function index() {
		$this->load->language('reports/list');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('reports/list');

		$this->getList();
	}

	protected function getList() {

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

    $filter_order_id = null;
    $filter_order_type = null;
    $filter_repay_status = null;
    $filter_buyer_name = null;
    $filter_order_status = null;
    $filter_repay_no = null;
    $filter_receiver_name = null;
    $filter_date_start = null;
    $filter_receiver_phone = null;
    $filter_date_end = null;

    if ( (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) ) {

   		if (isset($this->request->post['filter_order_id'])) {
        $filter_order_id = $this->request->post['filter_order_id'];
        $this->session->data['filter_order_id'] = $filter_order_id;
      }

   		if (isset($this->request->post['filter_order_type'])) {
        $filter_order_type = $this->request->post['filter_order_type'];
        $this->session->data['filter_order_type'] = $filter_order_type;
      }

   		if (isset($this->request->post['filter_repay_status'])) {
        $filter_repay_status = $this->request->post['filter_repay_status'];
        $this->session->data['filter_repay_status'] = $filter_repay_status;
      }

   		if (isset($this->request->post['filter_buyer_name'])) {
        $filter_buyer_name = $this->request->post['filter_buyer_name'];
        $this->session->data['filter_buyer_name'] = $filter_buyer_name;
      }

   		if (isset($this->request->post['filter_order_status'])) {
        $filter_order_status = $this->request->post['filter_order_status'];
        $this->session->data['filter_order_status'] = $filter_order_status;
      }

   		if (isset($this->request->post['filter_repay_no'])) {
        $filter_repay_no = $this->request->post['filter_repay_no'];
        $this->session->data['filter_repay_no'] = $filter_repay_no;
      }

   		if (isset($this->request->post['filter_receiver_name'])) {
        $filter_receiver_name = $this->request->post['filter_receiver_name'];
        $this->session->data['filter_receiver_name'] = $filter_receiver_name;
      }

   		if (isset($this->request->post['filter_date_start'])) {
        $filter_date_start = $this->request->post['filter_date_start'];
        $this->session->data['filter_date_start'] = $filter_date_start;
      }

   		if (isset($this->request->post['filter_receiver_phone'])) {
        $filter_receiver_phone = $this->request->post['filter_receiver_phone'];
        $this->session->data['filter_receiver_phone'] = $filter_receiver_phone;
      }

   		if (isset($this->request->post['filter_date_end'])) {
        $filter_date_end = $this->request->post['filter_date_end'];
        $this->session->data['filter_date_end'] = $filter_date_end;
      }

    } else if (isset($this->request->get['page'])) {
 
   		if (isset($this->session->data['filter_order_id'])) {
        $filter_order_id = $this->session->data['filter_order_id'];
      }

   		if (isset($this->session->data['filter_order_type'])) {
        $filter_order_type = $this->session->data['filter_order_type'];
      }

   		if (isset($this->session->data['filter_repay_status'])) {
        $filter_repay_status = $this->session->data['filter_repay_status'];
      }

   		if (isset($this->session->data['filter_buyer_name'])) {
        $filter_buyer_name = $this->session->data['filter_buyer_name'];
      }

   		if (isset($this->session->data['filter_order_status'])) {
        $filter_order_status = $this->session->data['filter_order_status'];
      }

   		if (isset($this->session->data['filter_repay_no'])) {
        $filter_repay_no = $this->session->data['filter_repay_no'];
      }

   		if (isset($this->session->data['filter_receiver_name'])) {
        $filter_receiver_name = $this->session->data['filter_receiver_name'];
      }

   		if (isset($this->session->data['filter_date_start'])) {
        $filter_date_start = $this->session->data['filter_date_start'];
      }

   		if (isset($this->session->data['filter_receiver_phone'])) {
        $filter_receiver_phone = $this->session->data['filter_receiver_phone'];
      }

   		if (isset($this->session->data['filter_date_end'])) {
        $filter_date_end = $this->session->data['filter_date_end'];
      }
   
    
    }

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('reports/list', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		//$data['add'] = $this->url->link('reports/list/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		//$data['delete'] = $this->url->link('reports/list/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
    $data['action'] = $this->url->link('reports/list', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['reports'] = array();

		$filter_data = array(
      'filter_order_id'         => $filter_order_id,
      'filter_order_type'       => $filter_order_type,
      'filter_repay_status'     => $filter_repay_status,
      'filter_buyer_name'       => $filter_buyer_name,
      'filter_order_status'     => $filter_order_status,
      'filter_repay_no'         => $filter_repay_no,
      'filter_receiver_name'    => $filter_receiver_name,
			'filter_date_start'       => $filter_date_start,
			'filter_receiver_phone'   => $filter_receiver_phone,
      'filter_date_end'         => $filter_date_end,
		  'sort'                    => $sort,
			'order'                   => $order,
			'start'                   => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                   => $this->config->get('config_limit_admin')
		);

		//$this->error['warning'] = "测试： ".json_encode($filter_data);

		$order_total = $this->model_reports_list->getTotalOrders($filter_data);
		$results = $this->model_reports_list->getOrders($filter_data);
    $order_types = $this->model_reports_list->getOrderTypes(); 
    $order_statuses = $this->model_reports_list->getOrderStatus(); 
    
    $repay_status_map = array(
      '0' => '未支付/结算',
      '1' => '已支付/结算',
		'2'=>'部分支付/结算'
    ); 
    $data['repay_statuses'][] = array (
      'repay_status_id' => '0', 
      'name' => $repay_status_map[0]
    );
    $data['repay_statuses'][] = array (
      'repay_status_id' => '1', 
      'name' => $repay_status_map[1]
    );
		$data['repay_statuses'][] = array (
			'repay_status_id' => '2',
			'name' => $repay_status_map[2]
		);

    foreach ($order_statuses as $order_status ){
      $order_status_map[$order_status['order_status_id']] = $order_status['name']; 
    }
    //workaround for leshare project, for order status, 0 value has no meaning
    $order_status_map['0'] = "错误类型！"; 

    $data['order_types'] = $order_types;
    $data['order_statuses'] = $order_statuses;

    foreach ($results as $result) {
			$data['reports'][] = array(
				'order_no'          => $result['order_no'],
				'order_info'        => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_no'], 'SSL'),
				'order_status'      => $order_status_map[$result['order_status']],
				'supplier_price'    => $result['supplier_price'],
				'repay_status'      => $repay_status_map[$result['repay_status']],
				'repay_time'        => $result['repay_time'],
				'finish_time'       => $result['finish_time'],
				'transfer_no'       => $result['transfer_no'],
			);
		}


		$data['heading_title'] = $this->language->get('heading_title');

    $data['entry_order_no']           = $this->language->get('entry_order_no');
    $data['entry_buyer_name']         = $this->language->get('entry_buyer_name');
    $data['entry_receiver_name']      = $this->language->get('entry_receiver_name');
    $data['entry_receiver_phone']     = $this->language->get('entry_receiver_phone');
    $data['entry_order_type']         = $this->language->get('entry_order_type');
    $data['entry_order_status']       = $this->language->get('entry_order_status');
    $data['entry_supplier_id']        = $this->language->get('entry_supplier_id');
    $data['entry_repay_status']       = $this->language->get('entry_repay_status');
    $data['entry_repay_no']           = $this->language->get('entry_repay_no');
    $data['entry_order_finishtime_start'] = $this->language->get('entry_order_finishtime_start');
    $data['entry_order_finishtime_end']   = $this->language->get('entry_order_finishtime_end');
    $data['entry_date']                   = $this->language->get('entry_date');
    
    $data['column_order_no']              = $this->language->get('column_order_no');
    $data['column_order_status']          = $this->language->get('column_order_status');
    $data['column_order_repay_amount']    = $this->language->get('column_order_repay_amount');
    $data['column_order_repay_status']    = $this->language->get('column_order_repay_status');
    $data['column_order_repay_date']      = $this->language->get('column_order_repay_date');
    $data['column_order_repay_trade_no']  = $this->language->get('column_order_repay_trade_no');
    
    $data['btn_reports_query']            = $this->language->get('btn_reports_query');
    $data['btn_reports_export']           = $this->language->get('btn_reports_export');
    
    $data['text_paid']      = $this->language->get('text_paid');
    $data['text_not_paid']  = $this->language->get('text_not_paid');
    $data['text_missing']   = $this->language->get('text_missing');
    $data['text_list']     = $this->language->get('text_list');
    $data['text_no_results']     = $this->language->get('text_no_results');




		$data['token'] = $this->session->data['token'];

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
    /* liuhang add for code debug*/
    //$data['success'] = json_encode($filter_data);

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order_no'] = $this->url->link('reports/list', 'token=' . $this->session->data['token'] . '&sort=o.order_no' . $url, 'SSL');
		$data['sort_order_status'] = $this->url->link('reports/list', 'token=' . $this->session->data['token'] . '&sort=o.order_status' . $url, 'SSL');
		$data['sort_supplier_price'] = $this->url->link('reports/list', 'token=' . $this->session->data['token'] . '&sort=o.supplier_price' . $url, 'SSL');
		$data['sort_repay_status'] = $this->url->link('reports/list', 'token=' . $this->session->data['token'] . '&sort=o.repay_status' . $url, 'SSL');
		$data['sort_finish_time'] = $this->url->link('reports/list', 'token=' . $this->session->data['token'] . '&sort=r.repay_time' . $url, 'SSL');
		$data['sort_transfer_no'] = $this->url->link('reports/list', 'token=' . $this->session->data['token'] . '&sort=r.transfer_no' . $url, 'SSL');

		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reports/list', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		//$data['results'] = $order_total; //liuhang -> for debug

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_order_type'] = $filter_order_type;
		$data['filter_repay_status'] = $filter_repay_status;
		$data['filter_buyer_name'] = $filter_buyer_name;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_repay_no'] = $filter_repay_no;
		$data['filter_receiver_name'] = $filter_receiver_name;
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_receiver_phone'] = $filter_receiver_phone;
		$data['filter_date_end'] = $filter_date_end;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('reports/list.tpl', $data));

  }

	public function export(){
    
    $this->load->language($this->module_name);
		$this->load->model($this->module_name);

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

   		if (isset($this->request->post['filter_order_id'])) {
        $filter_order_id = $this->request->post['filter_order_id'];
      } else {
        $filter_order_id = '';
      }

   		if (isset($this->request->post['filter_order_type'])) {
        $filter_order_type = $this->request->post['filter_order_type'];
      } else {
        $filter_order_type = '';
      }

   		if (isset($this->request->post['filter_repay_status'])) {
        $filter_repay_status = $this->request->post['filter_repay_status'];
      } else {
        $filter_repay_status = '';
      }

   		if (isset($this->request->post['filter_buyer_name'])) {
        $filter_buyer_name = $this->request->post['filter_buyer_name'];
      } else {
        $filter_buyer_name = '';
      }

   		if (isset($this->request->post['filter_order_status'])) {
        $filter_order_status = $this->request->post['filter_order_status'];
      } else {
        $filter_order_status = '';
      }

   		if (isset($this->request->post['filter_order_status'])) {
        $filter_order_status = $this->request->post['filter_order_status'];
      } else {
        $filter_order_status = '';
      }

   		if (isset($this->request->post['filter_repay_no'])) {
        $filter_repay_no = $this->request->post['filter_repay_no'];
      } else {
        $filter_repay_no = '';
      }

   		if (isset($this->request->post['filter_receiver_name'])) {
        $filter_receiver_name = $this->request->post['filter_receiver_name'];
      } else {
        $filter_receiver_name = '';
      }

   		if (isset($this->request->post['filter_date_start'])) {
        $filter_date_start = $this->request->post['filter_date_start'];
      } else {
        $filter_date_start = '';
      }

   		if (isset($this->request->post['filter_receiver_phone'])) {
        $filter_receiver_phone = $this->request->post['filter_receiver_phone'];
      } else {
        $filter_receiver_phone = '';
      }

   		if (isset($this->request->post['filter_date_end'])) {
        $filter_date_end = $this->request->post['filter_date_end'];
      } else {
        $filter_date_end = '';
      }
    
    } else {
   
      $filter_order_id = null;
      $filter_order_type = null;
      $filter_repay_status = null;
      $filter_buyer_name = null;
      $filter_order_status = null;
      $filter_repay_no = null;
      $filter_receiver_name = null;
      $filter_date_start = null;
      $filter_receiver_phone = null;
      $filter_date_end = null;
   
    }

    $filter_data = array(
      'filter_order_id'         => $filter_order_id,
      'filter_order_type'       => $filter_order_type,
      'filter_repay_status'     => $filter_repay_status,
      'filter_buyer_name'       => $filter_buyer_name,
      'filter_order_status'     => $filter_order_status,
      'filter_repay_no'         => $filter_repay_no,
      'filter_receiver_name'    => $filter_receiver_name,
			'filter_date_start'       => $filter_date_start,
			'filter_receiver_phone'   => $filter_receiver_phone,
      'filter_date_end'         => $filter_date_end,
		  //'sort'                    => $sort,
			//'order'                   => $order,
			//'start'                   => ($page - 1) * $this->config->get('config_limit_admin'),
			//'limit'                   => $this->config->get('config_limit_admin')
		);

		//$this->error['warning'] = "测试： ".json_encode($filter_data);
    
    //$data['action'] = $this->url->link('reports/list', 'token=' . $this->session->data['token'] , 'SSL');
    //$data['action'] = '#';

		$results = $this->model_reports_list->getOrders($filter_data);

    $order_types = $this->model_reports_list->getOrderTypes(); 
    $order_statuses = $this->model_reports_list->getOrderStatus(); 
    
    $repay_status_map = array(
      '0' => '未支付/结算',
      '1' => '已支付/结算',
    );
    $data['repay_statuses'] = $repay_status_map;

    foreach ($order_statuses as $order_status ){
      $order_status_map[$order_status['order_status_id']] = $order_status['name']; 
    }

		$reports[] = array();
    foreach ($results as $result) {
			$reports[] = array(
				'order_no'        => $result['order_no'],
				'order_status'    => $order_status_map[$result['order_status']],
				'supplier_price'  => $result['supplier_price'],
				'repay_status'    => $repay_status_map[$result['repay_status']],
				'repay_time'      => $result['repay_time'],
				'transfer_no'     => $result['transfer_no'],
			);
		}

		$excel = new Excel();
		$columns =
			['column_order_no'            =>$this->language->get('column_order_no'),
 			 'column_order_status'        =>$this->language->get('column_order_status'),
 			 'column_order_repay_amount'  =>$this->language->get('column_order_repay_amount'),
 			 'column_order_repay_status'  =>$this->language->get('column_order_repay_status'),
 			 'column_order_repay_date'    =>$this->language->get('column_order_repay_date'),
 			 'column_order_repay_trade_no'=>$this->language->get('column_order_repay_trade_no')
			];
		$excel->addHeader($columns);
		$excel->addBody($reports);
		$excel->downLoad("export_" . date('Y_m_d_H_i_s') . ".xls");

    //$url = '';
    //$this->response->redirect($this->url->link('reports/list', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'reports/list')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
 

    return !$this->error;

  }







}
