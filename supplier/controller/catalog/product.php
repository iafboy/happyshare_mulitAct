<?php


class ControllerCatalogProduct extends MyController
{

    private $error = array();

    private $module_name = 'catalog/product';

    private $base_url = '';

    public function index()
    {
        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');

        $this->getList();
    }

    public function getProductNo()
    {
        $date = new DateTime();
        $tp = $date->getTimestamp();
        $length = 4;
        $pattern = '1234567890';
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, 9)};    //生成php随机数
        }
        return 'PD' . $tp . $key;
    }

    public function modQuantity(){
        $this->load->model('catalog/product');
        $productId = $this->request->post['product_id'];
        $quantity = $this->request->post['quantity'];
        $this->model_catalog_product->modQuantity($productId,$quantity);
        writeJson(['success'=>true]);
    }
    public function putOffShell(){
        $this->load->model('catalog/product');
        $productId = $this->request->post['product_id'];
        $this->model_catalog_product->putOffShell($productId);
        writeJson(['success'=>true]);
    }

    public function add_new()
    {

        $this->load->language('catalog/product');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/product');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $product_info = $this->request->post;
            $current_supplier_id = $this->model_catalog_product->getParentSupplier($this->session->data['supplier_id']);
            $product_info[] = array(
                'supplier_id' => $current_supplier_id
            );
            $supplier_id = $current_supplier_id;

            $total = $this->model_catalog_product->getTotalProductsById($this->request->post['input_product_code']);
            if ($total == 1) {
                // $this->model_catalog_product->editProductNew($this->request->post['input_product_code'], $product_info);
                // $this->session->data['success'] = "成功！商品信息已更新。";
                //$this->session->data['success'] .= "product_id : ".$this->request->post['input_product_code']." total : ".$total ;
                $this->session->data['error'] = "失败！商品已存在！";
            } else {
                $this->model_catalog_product->addProductNew($supplier_id, $product_info);
                $this->session->data['success'] = "成功！已经添加新商品。";
                //$this->session->data['success'] .= "product_id : ".$this->request->post['input_product_code']." total : ".$total ;
            }

            //$this->session->data['success'] = $this->language->get('text_success');
            //$this->session->data['success'] = $this->request->post['input_upload_img_title'];

            $url = '';
            $this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }


        $data['heading_title'] = $this->language->get('heading_title');
        $data['heading_title_edit'] = $this->language->get('heading_title_edit');
        $data['text_product_edit_basicinfo'] = $this->language->get('text_product_edit_basicinfo');
        $data['text_product_edit_document'] = $this->language->get('text_product_edit_document');
        $data['text_product_edit_shareinfo'] = $this->language->get('text_product_edit_shareinfo');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_product_code'] = $this->language->get('entry_product_code');
        $data['entry_product_place'] = $this->language->get('entry_product_place');
        $data['entry_product_weight'] = $this->language->get('entry_product_weight');
        $data['entry_product_price_store'] = $this->language->get('entry_product_price_store');
        $data['entry_product_stock'] = $this->language->get('entry_product_stock');
        $data['entry_product_price_market'] = $this->language->get('entry_product_price_market');
        $data['entry_product_return'] = $this->language->get('entry_product_return');
        $data['entry_product_place_shipment'] = $this->language->get('entry_product_place_shipment');
        $data['entry_product_volume'] = $this->language->get('entry_product_volume');
        $data['entry_product_tax'] = $this->language->get('entry_product_tax');
        $data['entry_charge_mode'] = $this->language->get('entry_charge_mode');
        $data['entry_product_recommend_index'] = $this->language->get('entry_product_recommend_index');
        $data['entry_reward'] = $this->language->get('entry_reward');
        $data['text_product_return_no'] = $this->language->get('text_product_return_no');
        $data['text_product_return_yes1'] = $this->language->get('text_product_return_yes1');
        $data['text_product_return_yes2'] = $this->language->get('text_product_return_yes2');

        $data['help_tag'] = $this->language->get('help_tag');
        $data['token'] = $this->session->data['token'];


        if (isset($this->request->post['input_upload_img_title']) && ($this->request->post['input_upload_img_title'] != '')) {
            $data['product_title_img'] = HTTP_CATALOG . "image/" . $this->request->post['input_upload_img_title'];
        } else {
            $data['product_title_img'] = '';
        }
        $data['product_imgs'][0] = array(
            'pid' => 0,
            'img' => ''
        );

        $data['product_imgs'][1] = array(
            'pid' => 1,
            'img' => ''
        );

        $data['product_imgs'][2] = array(
            'pid' => 2,
            'img' => ''
        );

        $data['product_imgs'][3] = array(
            'pid' => 3,
            'img' => ''
        );
        $data['product_imgs'][4] = array(
            'pid' => 4,
            'img' => ''
        );

        $data['share_docs'] = null;

        $data['doc_imgs'][0] = array(
            'pid' => 0,
            'status' => 1,
            'img' => ''
        );

        $data['doc_imgs'][1] = array(
            'pid' => 0,
            'status' => 1,
            'img' => ''
        );

        $data['doc_imgs'][2] = array(
            'pid' => 0,
            'status' => 1,
            'img' => ''
        );

        $data['doc_imgs'][3] = array(
            'pid' => 0,
            'status' => 1,
            'img' => ''
        );

        $data['doc_imgs'][4] = array(
            'pid' => 0,
            'status' => 0,
            'img' => ''
        );

        $data['doc_imgs'][5] = array(
            'pid' => 0,
            'status' => 1,
            'img' => ''
        );

        $data['doc_imgs'][6] = array(
            'pid' => 0,
            'status' => 0,
            'img' => ''
        );

        $data['doc_imgs'][7] = array(
            'pid' => 0,
            'status' => 0,
            'img' => ''
        );

        $data['doc_imgs'][8] = array(
            'pid' => 0,
            'status' => 0,
            'img' => ''
        );


        if (isset($this->request->post['input_name']) && ($this->request->post['input_name'] != '')) {
            $data['name'] = $this->request->post['input_name'];
        } else {
            $data['name'] = '';
        }

        $models = $this->model_catalog_product->getProductTypes();
        $data['models'] = $models;
        //$data['category_id'] = '';
        if (isset($this->request->post['input_model']) && ($this->request->post['input_model'] != '')) {
            $data['category_id'] = $this->request->post['input_model'];
        } else {
            $data['category_id'] = '';
        }

        if (isset($this->request->post['input_product_stock']) && ($this->request->post['input_product_stock'] != '')) {
            $data['product_stock'] = $this->request->post['input_product_stock'];
        } else {
            $data['product_stock'] = '';
        }
        if (isset($this->request->post['input_product_price_market']) && ($this->request->post['input_product_price_market'] != '')) {
            $data['product_price_market'] = $this->request->post['input_product_price_market'];
        } else {
            $data['product_price_market'] = '';
        }

        //$data['product_code'] = $this->model_catalog_product->getLastProduct() + 1;
        $data['product_code'] = $this->getProductNo();

        //$places = $this->model_catalog_product->getCountries();
        $origin_places = $this->model_catalog_product->getOriginPlaces();
        $fromwhere_places = $this->model_catalog_product->getFromwherePlaces();
        //$data['places'] = $places;
        $data['origin_places'] = $origin_places;
        $data['fromwhere_places'] = $fromwhere_places;
        //$data['country_id'] = '';
        if (isset($this->request->post['input_product_place']) && ($this->request->post['input_product_place'] != '')) {
            $data['fromwhere'] = $this->request->post['input_product_place'];
        } else {
            $data['fromwhere'] = '';
        }
        if (isset($this->request->post['input_product_place_shipment']) && ($this->request->post['input_product_place_shipment'] != '')) {
            $data['origin_place_id'] = $this->request->post['input_product_place_shipment'];
        } else {
            $data['origin_place_id'] = '';
        }

        if (isset($this->request->post['input_weight']) && ($this->request->post['input_weight'] != '')) {
            $data['product_weight'] = $this->request->post['input_weight'];
        } else {
            $data['product_weight'] = '';
        }
        if (isset($this->request->post['input_product_price_store']) && ($this->request->post['input_product_price_store'] != '')) {
            $data['product_price_store'] = $this->request->post['input_product_price_store'];
        } else {
            $data['product_price_store'] = '';
        }
        if (isset($this->request->post['input_volume']) && ($this->request->post['input_volume'] != '')) {
            $data['product_product_volume'] = $this->request->post['input_volume'];
        } else {
            $data['product_product_volume'] = '';
        }
        if (isset($this->request->post['input_product_tax']) && ($this->request->post['input_product_tax'] != '')) {
            $data['product_product_tax'] = $this->request->post['input_product_tax'];
        } else {
            $data['product_product_tax'] = '';
        }
        if (isset($this->request->post['input_product_return_deadline']) && ($this->request->post['input_product_return_deadline'] != '')) {
            $data['product_return_deadline'] = $this->request->post['input_product_return_deadline'];
        } else {
            $data['product_return_deadline'] = '';
        }
        if (isset($this->request->post['input_product_reward']) && ($this->request->post['input_product_reward'] != '')) {
            $data['product_reward'] = $this->request->post['input_product_reward'];
        } else {
            $data['product_reward'] = '';
        }

        $chargetypes[0] = array(
            'id' => '1',
            'name' => '重量计费'
        );

        $chargetypes[1] = array(
            'id' => '2',
            'name' => '体积计费'
        );

        $chargetypes[2] = array(
            'id' => '3',
            'name' => '件数计费'
        );
        $data['chargetypes'] = $chargetypes;
        if (isset($this->request->post['input_charge_mode']) && ($this->request->post['input_charge_mode'] != '')) {
            $data['chargetype_id'] = $this->request->post['input_charge_mode'];
        } else {
            $data['chargetype_id'] = '';
        }

        if (isset($this->request->post['input_product_recommand_index']) && ($this->request->post['input_product_recommand_index'] != '')) {
            $data['product_recommand_index'] = $this->request->post['input_product_recommand_index'];
        } else {
            $data['product_recommand_index'] = '';
        }


        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }


        $url = '';
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title_add'),
            'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['action'] = $this->url->link('catalog/product/add_new', 'token=' . $this->session->data['token'] . $url, 'SSL');


        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/product_edit.tpl', $data));
    }

    public function edit_new()
    {

        $this->base_url = $this->url->link($this->module_name, 'token=' . $this->session->data['token']);
        $lans = $this->load->language('product/edit');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('product/edit');
        $this->load->model('tool/image');
        $this->load->model('common/product');
        $this->getProductDetail($lans);
    }

    protected function  getProductDetail($lans)
    {

        $data = array();
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        $product_id = $this->request->get['product_id'];
        $data['product'] = $this->model_product_edit->queryProduct($product_id);
        $data = array_merge($data, $lans);
        $data['breadcrumbs'] = $this->parseBreadCrumbs(array());
        $data['prducttypes'] = $this->model_common_product->queryProducttypes();
        $data['originPlaces'] = $this->model_common_product->queryOriginPlaces();
        $data['expressPlaces'] = $this->model_common_product->queryExpressPlaces();
        $chargetypes = [];
        $chargetypes[0] = array(
            'id' => '1',
            'name' => '重量计费'
        );

        $chargetypes[1] = array(
            'id' => '2',
            'name' => '体积计费'
        );

        $chargetypes[2] = array(
            'id' => '3',
            'name' => '件数计费'
        );
        $data['chargetypes'] = $chargetypes;
        $data['token'] = $this->session->data['token'];
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('product/edit' . '.tpl', $data));
    }


    public function delete_new()
    {
        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');

        if (isset($this->request->get['product_id'])) {
            $product_id = $this->request->get['product_id'];
            $this->model_catalog_product->deleteProductNew($product_id);
            $this->session->data['success'] = $this->language->get('text_success');
        }

        $url = '';

        $this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));

    }

    protected function getList()
    {
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['filter_id'])) {
            $filter_id = $this->request->get['filter_id'];
        } else {
            $filter_id = null;
        }
        if (isset($this->request->get['filter_product_no'])) {
            $filter_product_no = $this->request->get['filter_product_no'];
        } else {
            $filter_product_no = null;
        }

        if (isset($this->request->get['filter_product_category'])) {
            $filter_product_category = $this->request->get['filter_product_category'];
        } else {
            $filter_product_category = null;
        }

        if (isset($this->request->get['filter_quantity_min'])) {
            $filter_quantity_min = $this->request->get['filter_quantity_min'];
        } else {
            $filter_quantity_min = null;
        }

        if (isset($this->request->get['filter_quantity_max'])) {
            $filter_quantity_max = $this->request->get['filter_quantity_max'];
        } else {
            $filter_quantity_max = null;
        }

        if (isset($this->request->get['filter_product_price_market_min'])) {
            $filter_product_price_market_min = $this->request->get['filter_product_price_market_min'];
        } else {
            $filter_product_price_market_min = null;
        }

        if (isset($this->request->get['filter_product_price_market_max'])) {
            $filter_product_price_market_max = $this->request->get['filter_product_price_market_max'];
        } else {
            $filter_product_price_market_max = null;
        }

        if (isset($this->request->get['filter_product_sales_min'])) {
            $filter_product_sales_min = $this->request->get['filter_product_sales_min'];
        } else {
            $filter_product_sales_min = null;
        }

        if (isset($this->request->get['filter_product_sales_max'])) {
            $filter_product_sales_max = $this->request->get['filter_product_sales_max'];
        } else {
            $filter_product_sales_max = null;
        }

        if (isset($this->request->get['filter_origin'])) {
            $filter_origin = $this->request->get['filter_origin'];
        } else {
            $filter_origin = null;
        }

        if (isset($this->request->get['filter_product_price_supplier_min'])) {
            $filter_product_price_supplier_min = $this->request->get['filter_product_price_supplier_min'];
        } else {
            $filter_product_price_supplier_min = null;
        }

        if (isset($this->request->get['filter_product_price_supplier_max'])) {
            $filter_product_price_supplier_max = $this->request->get['filter_product_price_supplier_max'];
        } else {
            $filter_product_price_supplier_max = null;
        }

        if (isset($this->request->get['filter_product_comments_min'])) {
            $filter_product_comments_min = $this->request->get['filter_product_comments_min'];
        } else {
            $filter_product_comments_min = null;
        }

        if (isset($this->request->get['filter_product_comments_max'])) {
            $filter_product_comments_max = $this->request->get['filter_product_comments_max'];
        } else {
            $filter_product_comments_max = null;
        }

        if (isset($this->request->get['filter_product_recommend_index_min'])) {
            $filter_product_recommend_index_min = $this->request->get['filter_product_recommend_index_min'];
        } else {
            $filter_product_recommend_index_min = null;
        }

        if (isset($this->request->get['filter_product_recommend_index_max'])) {
            $filter_product_recommend_index_max = $this->request->get['filter_product_recommend_index_max'];
        } else {
            $filter_product_recommend_index_max = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.product_id';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_product_no'])) {
            $url .= '&filter_product_no=' . urlencode(html_entity_decode($this->request->get['filter_product_no'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . $this->request->get['filter_model'];
        }
        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }


        if (isset($this->request->get['filter_id'])) {
            $url .= '&filter_id=' . $this->request->get['filter_id'];
        }

        if (isset($this->request->get['filter_product_category'])) {
            $url .= '&filter_product_category=' . $this->request->get['filter_product_category'];
        }

        if (isset($this->request->get['filter_quantity_min'])) {
            $url .= '&filter_quantity_min=' . $this->request->get['filter_quantity_min'];
        }

        if (isset($this->request->get['filter_quantity_max'])) {
            $url .= '&filter_quantity_max=' . $this->request->get['filter_quantity_max'];
        }

        if (isset($this->request->get['filter_product_price_market_min'])) {
            $url .= '&filter_product_price_market_min=' . $this->request->get['filter_product_price_market_min'];
        }

        if (isset($this->request->get['filter_product_price_market_max'])) {
            $url .= '&filter_product_price_market_max=' . $this->request->get['filter_product_price_market_max'];
        }

        if (isset($this->request->get['filter_product_sales_min'])) {
            $url .= '&filter_product_sales_min=' . $this->request->get['filter_product_sales_min'];
        }

        if (isset($this->request->get['filter_product_sales_max'])) {
            $url .= '&filter_product_sales_max=' . $this->request->get['filter_product_sales_max'];
        }

        if (isset($this->request->get['filter_origin'])) {
            $url .= '&filter_origin=' . $this->request->get['filter_origin'];
        }

        if (isset($this->request->get['filter_product_price_supplier_min'])) {
            $url .= '&filter_product_price_supplier_min=' . $this->request->get['filter_product_price_supplier_min'];
        }

        if (isset($this->request->get['filter_product_price_supplier_max'])) {
            $url .= '&filter_product_price_supplier_max=' . $this->request->get['filter_product_price_supplier_max'];
        }

        if (isset($this->request->get['filter_product_comments_min'])) {
            $url .= '&filter_product_comments_min=' . $this->request->get['filter_product_comments_min'];
        }

        if (isset($this->request->get['filter_product_comments_max'])) {
            $url .= '&filter_product_comments_max=' . $this->request->get['filter_product_comments_max'];
        }

        if (isset($this->request->get['filter_product_recommend_index_min'])) {
            $url .= '&filter_product_recommend_index_min=' . $this->request->get['filter_product_recommend_index_min'];
        }

        if (isset($this->request->get['filter_product_recommend_index_max'])) {
            $url .= '&filter_product_recommend_index_max=' . $this->request->get['filter_product_recommend_index_max'];
        }

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
            'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );


        $data['products'] = array();

        $current_supplier_id = $this->model_catalog_product->getParentSupplier($this->session->data['supplier_id']);

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_status' => $filter_status,
            'filter_id' => $filter_id,
            'filter_product_no' => $filter_product_no,
            'filter_product_category' => $filter_product_category,
            'filter_quantity_min' => $filter_quantity_min,
            'filter_quantity_max' => $filter_quantity_max,
            'filter_product_price_market_min' => $filter_product_price_market_min,
            'filter_product_price_market_max' => $filter_product_price_market_max,
            'filter_product_sales_min' => $filter_product_sales_min,
            'filter_product_sales_max' => $filter_product_sales_max,
            'filter_origin' => $filter_origin,
            'filter_product_price_supplier_min' => $filter_product_price_supplier_min,
            'filter_product_price_supplier_max' => $filter_product_price_supplier_max,
            'filter_product_comments_min' => $filter_product_comments_min,
            'filter_product_comments_max' => $filter_product_comments_max,
            'filter_product_recommend_index_min' => $filter_product_recommend_index_min,
            'filter_product_recommend_index_max' => $filter_product_recommend_index_max,
            'supplier_id' => $current_supplier_id,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $this->load->model('tool/image');

        $categories = $this->model_catalog_product->getProductTypes();

        foreach ($categories as $category) {
            $data['categories'][] = array(
                'cid' => $category['product_type_id'],
                'cname' => $category['type_name'],
            );
        }

        $product_total = $this->model_catalog_product->getTotalProducts($filter_data);

        $results = $this->model_catalog_product->getProducts($filter_data);

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 40, 40);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 40, 40);
            }

            $special = false;

            $product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

            foreach ($product_specials as $product_special) {
                if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
                    $special = $product_special['price'];

                    break;
                }
            }

            $tname = $this->model_catalog_product->getProductTypeByTId($result['product_type_id']);
            if (!empty($result['origin_place_id'])) {
                $origin_place = $this->model_catalog_product->getOriginalplaceById($result['origin_place_id']);
            } else {
                $origin_place = "无效值！";
            }
            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'product_link'=> $this->url->link('product/edit', 'token=' . $this->session->data['token'].'&product_id=' . $result['product_id'].'&page=null', 'SSL'),
                'product_no' => $result['product_no'],
                'image' => $image,
                'name' => $result['name'],
                'model' => $tname, //product_type : type_name
                'price' => $result['market_price'],
                'special' => $special,
                'quantity' => $result['quantity'],
                'origin' => $origin_place,
                'supplyprice' => $result['price'],
                'sales' => $result['sales'],
                'document' => $result['document'],
                'comments' => $result['comments'],
                'recommend' => $result['shareLevel'],
                'status' => $this->statusMapping($result['status']),
                'status_code' => $result['status'],
                'edit' => $this->url->link('catalog/product/edit_new', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL'),
            );
        }

        $pstatus = $this->model_catalog_product->getProductStatus();
        $data['pstatus'] = $pstatus;

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['text_status_0'] = $this->language->get('text_status_0');
        $data['text_status_1'] = $this->language->get('text_status_1');
        $data['text_status_2'] = $this->language->get('text_status_2');
        $data['text_status_3'] = $this->language->get('text_status_3');
        $data['text_status_4'] = $this->language->get('text_status_4');
        $data['text_status_5'] = $this->language->get('text_status_5');

        $data['column_image'] = $this->language->get('column_image');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_id'] = $this->language->get('column_id');
        $data['column_origin'] = $this->language->get('column_origin');
        $data['column_sales'] = $this->language->get('column_sales');
        $data['column_supply_price'] = $this->language->get('column_supply_price');
        $data['column_document'] = $this->language->get('column_document');
        $data['column_comments'] = $this->language->get('column_comments');
        $data['column_product_recommend_index'] = $this->language->get('column_product_recommend_index');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_price'] = $this->language->get('entry_price');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['entry_product_code'] = $this->language->get('entry_product_code');
        $data['entry_product_name'] = $this->language->get('entry_product_name');
        $data['entry_product_place'] = $this->language->get('entry_product_place');;
        $data['entry_product_catagory'] = $this->language->get('entry_product_catagory');
        $data['entry_product_stock'] = $this->language->get('entry_product_stock');
        $data['entry_product_price_market'] = $this->language->get('entry_product_price_market');
        $data['entry_product_price_supplier'] = $this->language->get('entry_product_price_supplier');
        $data['entry_product_status'] = $this->language->get('entry_product_status');
        $data['entry_product_sales'] = $this->language->get('entry_product_sales');
        $data['entry_product_comments'] = $this->language->get('entry_product_comments');
        $data['entry_product_recommend_index'] = $this->language->get('entry_product_recommend_index');

        $countries = $this->model_catalog_product->getOriginPlaces();
        $data['countries'] = $countries;


        $data['button_copy'] = $this->language->get('button_copy');
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');
        $data['button_filter'] = $this->language->get('button_filter');
        $data['button_query'] = $this->language->get('button_query');

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

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_product_no'])) {
            $url .= '&filter_product_no=' . urlencode(html_entity_decode($this->request->get['filter_product_no'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_product_category'])) {
            $url .= '&filter_product_category=' . $this->request->get['filter_product_category'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_id'])) {
            $url .= '&filter_id=' . $this->request->get['filter_id'];
        }

        if (isset($this->request->get['filter_quantity_min'])) {
            $url .= '&filter_quantity_min=' . $this->request->get['filter_quantity_min'];
        }

        if (isset($this->request->get['filter_quantity_max'])) {
            $url .= '&filter_quantity_max=' . $this->request->get['filter_quantity_max'];
        }

        if (isset($this->request->get['filter_product_price_market_min'])) {
            $url .= '&filter_product_price_market_min=' . $this->request->get['filter_product_price_market_min'];
        }

        if (isset($this->request->get['filter_product_price_market_max'])) {
            $url .= '&filter_product_price_market_max=' . $this->request->get['filter_product_price_market_max'];
        }

        if (isset($this->request->get['filter_product_sales_min'])) {
            $url .= '&filter_product_sales_min=' . $this->request->get['filter_product_sales_min'];
        }

        if (isset($this->request->get['filter_product_sales_max'])) {
            $url .= '&filter_product_sales_max=' . $this->request->get['filter_product_sales_max'];
        }

        if (isset($this->request->get['filter_origin'])) {
            $url .= '&filter_origin=' . $this->request->get['filter_origin'];
        }

        if (isset($this->request->get['filter_product_price_supplier_min'])) {
            $url .= '&filter_product_price_supplier_min=' . $this->request->get['filter_product_price_supplier_min'];
        }

        if (isset($this->request->get['filter_product_price_supplier_max'])) {
            $url .= '&filter_product_price_supplier_max=' . $this->request->get['filter_product_price_supplier_max'];
        }

        if (isset($this->request->get['filter_product_comments_min'])) {
            $url .= '&filter_product_comments_min=' . $this->request->get['filter_product_comments_min'];
        }

        if (isset($this->request->get['filter_product_comments_max'])) {
            $url .= '&filter_product_comments_max=' . $this->request->get['filter_product_comments_max'];
        }

        if (isset($this->request->get['filter_product_recommend_index_min'])) {
            $url .= '&filter_product_recommend_index_min=' . $this->request->get['filter_product_recommend_index_min'];
        }

        if (isset($this->request->get['filter_product_recommend_index_max'])) {
            $url .= '&filter_product_recommend_index_max=' . $this->request->get['filter_product_recommend_index_max'];
        }


        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_product_no'])) {
            $url .= '&filter_product_no=' . urlencode(html_entity_decode($this->request->get['filter_product_no'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['filter_id'])) {
            $url .= '&filter_id=' . $this->request->get['filter_id'];
        }

        if (isset($this->request->get['filter_product_category'])) {
            $url .= '&filter_product_category=' . $this->request->get['filter_product_category'];
        }

        if (isset($this->request->get['filter_quantity_min'])) {
            $url .= '&filter_quantity_min=' . $this->request->get['filter_quantity_min'];
        }

        if (isset($this->request->get['filter_quantity_max'])) {
            $url .= '&filter_quantity_max=' . $this->request->get['filter_quantity_max'];
        }

        if (isset($this->request->get['filter_product_price_market_min'])) {
            $url .= '&filter_product_price_market_min=' . $this->request->get['filter_product_price_market_min'];
        }

        if (isset($this->request->get['filter_product_price_market_max'])) {
            $url .= '&filter_product_price_market_max=' . $this->request->get['filter_product_price_market_max'];
        }

        if (isset($this->request->get['filter_product_sales_min'])) {
            $url .= '&filter_product_sales_min=' . $this->request->get['filter_product_sales_min'];
        }

        if (isset($this->request->get['filter_product_sales_max'])) {
            $url .= '&filter_product_sales_max=' . $this->request->get['filter_product_sales_max'];
        }

        if (isset($this->request->get['filter_origin'])) {
            $url .= '&filter_origin=' . $this->request->get['filter_origin'];
        }

        if (isset($this->request->get['filter_product_price_supplier_min'])) {
            $url .= '&filter_product_price_supplier_min=' . $this->request->get['filter_product_price_supplier_min'];
        }

        if (isset($this->request->get['filter_product_price_supplier_max'])) {
            $url .= '&filter_product_price_supplier_max=' . $this->request->get['filter_product_price_supplier_max'];
        }

        if (isset($this->request->get['filter_product_comments_min'])) {
            $url .= '&filter_product_comments_min=' . $this->request->get['filter_product_comments_min'];
        }

        if (isset($this->request->get['filter_product_comments_max'])) {
            $url .= '&filter_product_comments_max=' . $this->request->get['filter_product_comments_max'];
        }

        if (isset($this->request->get['filter_product_recommend_index_min'])) {
            $url .= '&filter_product_recommend_index_min=' . $this->request->get['filter_product_recommend_index_min'];
        }

        if (isset($this->request->get['filter_product_recommend_index_max'])) {
            $url .= '&filter_product_recommend_index_max=' . $this->request->get['filter_product_recommend_index_max'];
        }


        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_status'] = $filter_status;

        $data['filter_id'] = $filter_id;
        $data['filter_product_no'] = $filter_product_no;
        $data['filter_product_category'] = $filter_product_category;
        $data['filter_quantity_min'] = $filter_quantity_min;
        $data['filter_quantity_max'] = $filter_quantity_max;
        $data['filter_product_price_market_min'] = $filter_product_price_market_min;
        $data['filter_product_price_market_max'] = $filter_product_price_market_max;
        $data['filter_product_sales_min'] = $filter_product_sales_min;
        $data['filter_product_sales_max'] = $filter_product_sales_max;
        $data['filter_origin'] = $filter_origin;
        $data['filter_product_price_supplier_min'] = $filter_product_price_supplier_min;
        $data['filter_product_price_supplier_max'] = $filter_product_price_supplier_max;
        $data['filter_product_comments_min'] = $filter_product_comments_min;
        $data['filter_product_comments_max'] = $filter_product_comments_max;
        $data['filter_product_recommend_index_min'] = $filter_product_recommend_index_min;
        $data['filter_product_recommend_index_max'] = $filter_product_recommend_index_max;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/product_list.tpl', $data));
    }

    private function statusMapping($status)
    {

        $text = $this->language->get('text_status_0');

        switch ($status) {
            case 0:
                $text = $this->language->get('text_status_0');
                break;
            case 1:
                $text = $this->language->get('text_status_1');
                break;
            case 2:
                $text = $this->language->get('text_status_2');
                break;
            case 3:
                $text = $this->language->get('text_status_3');
                break;
            case 4:
                $text = $this->language->get('text_status_4');
                break;
            case 5:
                $text = $this->language->get('text_status_5');
                break;
            default:
                $text = $this->language->get('text_status_wrong');
                break;
        }
        return $text;
    }

    protected function getForm()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_plus'] = $this->language->get('text_plus');
        $data['text_minus'] = $this->language->get('text_minus');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_option'] = $this->language->get('text_option');
        $data['text_option_value'] = $this->language->get('text_option_value');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_percent'] = $this->language->get('text_percent');
        $data['text_amount'] = $this->language->get('text_amount');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_keyword'] = $this->language->get('entry_keyword');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_sku'] = $this->language->get('entry_sku');
        $data['entry_upc'] = $this->language->get('entry_upc');
        $data['entry_ean'] = $this->language->get('entry_ean');
        $data['entry_jan'] = $this->language->get('entry_jan');
        $data['entry_isbn'] = $this->language->get('entry_isbn');
        $data['entry_mpn'] = $this->language->get('entry_mpn');
        $data['entry_location'] = $this->language->get('entry_location');
        $data['entry_minimum'] = $this->language->get('entry_minimum');
        $data['entry_shipping'] = $this->language->get('entry_shipping');
        $data['entry_date_available'] = $this->language->get('entry_date_available');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_stock_status'] = $this->language->get('entry_stock_status');
        $data['entry_price'] = $this->language->get('entry_price');
        $data['entry_tax_class'] = $this->language->get('entry_tax_class');
        $data['entry_points'] = $this->language->get('entry_points');
        $data['entry_option_points'] = $this->language->get('entry_option_points');
        $data['entry_subtract'] = $this->language->get('entry_subtract');
        $data['entry_weight_class'] = $this->language->get('entry_weight_class');
        $data['entry_weight'] = $this->language->get('entry_weight');
        $data['entry_dimension'] = $this->language->get('entry_dimension');
        $data['entry_length_class'] = $this->language->get('entry_length_class');
        $data['entry_length'] = $this->language->get('entry_length');
        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_height'] = $this->language->get('entry_height');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
        $data['entry_download'] = $this->language->get('entry_download');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_filter'] = $this->language->get('entry_filter');
        $data['entry_related'] = $this->language->get('entry_related');
        $data['entry_attribute'] = $this->language->get('entry_attribute');
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_option'] = $this->language->get('entry_option');
        $data['entry_option_value'] = $this->language->get('entry_option_value');
        $data['entry_required'] = $this->language->get('entry_required');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_priority'] = $this->language->get('entry_priority');
        $data['entry_tag'] = $this->language->get('entry_tag');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_reward'] = $this->language->get('entry_reward');
        $data['entry_layout'] = $this->language->get('entry_layout');
        $data['entry_recurring'] = $this->language->get('entry_recurring');

        $data['help_keyword'] = $this->language->get('help_keyword');
        $data['help_sku'] = $this->language->get('help_sku');
        $data['help_upc'] = $this->language->get('help_upc');
        $data['help_ean'] = $this->language->get('help_ean');
        $data['help_jan'] = $this->language->get('help_jan');
        $data['help_isbn'] = $this->language->get('help_isbn');
        $data['help_mpn'] = $this->language->get('help_mpn');
        $data['help_minimum'] = $this->language->get('help_minimum');
        $data['help_manufacturer'] = $this->language->get('help_manufacturer');
        $data['help_stock_status'] = $this->language->get('help_stock_status');
        $data['help_points'] = $this->language->get('help_points');
        $data['help_category'] = $this->language->get('help_category');
        $data['help_filter'] = $this->language->get('help_filter');
        $data['help_download'] = $this->language->get('help_download');
        $data['help_related'] = $this->language->get('help_related');
        $data['help_tag'] = $this->language->get('help_tag');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_attribute_add'] = $this->language->get('button_attribute_add');
        $data['button_option_add'] = $this->language->get('button_option_add');
        $data['button_option_value_add'] = $this->language->get('button_option_value_add');
        $data['button_discount_add'] = $this->language->get('button_discount_add');
        $data['button_special_add'] = $this->language->get('button_special_add');
        $data['button_image_add'] = $this->language->get('button_image_add');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_recurring_add'] = $this->language->get('button_recurring_add');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_data'] = $this->language->get('tab_data');
        $data['tab_attribute'] = $this->language->get('tab_attribute');
        $data['tab_option'] = $this->language->get('tab_option');
        $data['tab_recurring'] = $this->language->get('tab_recurring');
        $data['tab_discount'] = $this->language->get('tab_discount');
        $data['tab_special'] = $this->language->get('tab_special');
        $data['tab_image'] = $this->language->get('tab_image');
        $data['tab_links'] = $this->language->get('tab_links');
        $data['tab_reward'] = $this->language->get('tab_reward');
        $data['tab_design'] = $this->language->get('tab_design');
        $data['tab_openbay'] = $this->language->get('tab_openbay');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = array();
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
        }

        if (isset($this->error['model'])) {
            $data['error_model'] = $this->error['model'];
        } else {
            $data['error_model'] = '';
        }

        if (isset($this->error['date_available'])) {
            $data['error_date_available'] = $this->error['date_available'];
        } else {
            $data['error_date_available'] = '';
        }

        if (isset($this->error['keyword'])) {
            $data['error_keyword'] = $this->error['keyword'];
        } else {
            $data['error_keyword'] = '';
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_product_no'])) {
            $url .= '&filter_product_no=' . urlencode(html_entity_decode($this->request->get['filter_product_no'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }
        /* liuhang : add new filter options for query
         *
         * initial filter option vars
         */
        if (isset($this->request->get['filter_id'])) {
            $filter_id = $this->request->get['filter_id'];
        } else {
            $filter_id = null;
        }

        if (isset($this->request->get['filter_quantity_min'])) {
            $filter_quantity_min = $this->request->get['filter_quantity_min'];
        } else {
            $filter_quantity_min = null;
        }

        if (isset($this->request->get['filter_quantity_max'])) {
            $filter_quantity_max = $this->request->get['filter_quantity_max'];
        } else {
            $filter_quantity_max = null;
        }

        if (isset($this->request->get['filter_product_price_market_min'])) {
            $filter_product_price_market_min = $this->request->get['filter_product_price_market_min'];
        } else {
            $filter_product_price_market_min = null;
        }

        if (isset($this->request->get['filter_product_price_market_max'])) {
            $filter_product_price_market_max = $this->request->get['filter_product_price_market_max'];
        } else {
            $filter_product_price_market_max = null;
        }

        if (isset($this->request->get['filter_product_sales_min'])) {
            $filter_product_sales_min = $this->request->get['filter_product_sales_min'];
        } else {
            $filter_product_sales_min = null;
        }

        if (isset($this->request->get['filter_product_sales_max'])) {
            $filter_product_sales_max = $this->request->get['filter_product_sales_max'];
        } else {
            $filter_product_sales_max = null;
        }

        if (isset($this->request->get['filter_origin'])) {
            $filter_origin = $this->request->get['filter_origin'];
        } else {
            $filter_origin = null;
        }

        if (isset($this->request->get['filter_product_price_supplier_min'])) {
            $filter_product_price_supplier_min = $this->request->get['filter_product_price_supplier_min'];
        } else {
            $filter_product_price_supplier_min = null;
        }

        if (isset($this->request->get['filter_product_price_supplier_max'])) {
            $filter_product_price_supplier_max = $this->request->get['filter_product_price_supplier_max'];
        } else {
            $filter_product_price_supplier_max = null;
        }

        if (isset($this->request->get['filter_product_comments_min'])) {
            $filter_product_comments_min = $this->request->get['filter_product_comments_min'];
        } else {
            $filter_product_comments_min = null;
        }

        if (isset($this->request->get['filter_product_comments_max'])) {
            $filter_product_comments_max = $this->request->get['filter_product_comments_max'];
        } else {
            $filter_product_comments_max = null;
        }

        if (isset($this->request->get['filter_product_recommend_index_min'])) {
            $filter_product_recommend_index_min = $this->request->get['filter_product_recommend_index_min'];
        } else {
            $filter_product_recommend_index_min = null;
        }

        if (isset($this->request->get['filter_product_recommend_index_max'])) {
            $filter_product_recommend_index_max = $this->request->get['filter_product_recommend_index_max'];
        } else {
            $filter_product_recommend_index_max = null;
        }

        /* end of < add new filter options for query > */

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
            'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        if (!isset($this->request->get['product_id'])) {
            $data['action'] = $this->url->link('catalog/product/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
        }

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['product_description'])) {
            $data['product_description'] = $this->request->post['product_description'];
        } elseif (isset($this->request->get['product_id'])) {
            $data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->request->get['product_id']);
        } else {
            $data['product_description'] = array();
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($product_info)) {
            $data['image'] = $product_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['model'])) {
            $data['model'] = $this->request->post['model'];
        } elseif (!empty($product_info)) {
            $data['model'] = $product_info['model'];
        } else {
            $data['model'] = '';
        }

        if (isset($this->request->post['sku'])) {
            $data['sku'] = $this->request->post['sku'];
        } elseif (!empty($product_info)) {
            $data['sku'] = $product_info['sku'];
        } else {
            $data['sku'] = '';
        }

        if (isset($this->request->post['upc'])) {
            $data['upc'] = $this->request->post['upc'];
        } elseif (!empty($product_info)) {
            $data['upc'] = $product_info['upc'];
        } else {
            $data['upc'] = '';
        }

        if (isset($this->request->post['ean'])) {
            $data['ean'] = $this->request->post['ean'];
        } elseif (!empty($product_info)) {
            $data['ean'] = $product_info['ean'];
        } else {
            $data['ean'] = '';
        }

        if (isset($this->request->post['jan'])) {
            $data['jan'] = $this->request->post['jan'];
        } elseif (!empty($product_info)) {
            $data['jan'] = $product_info['jan'];
        } else {
            $data['jan'] = '';
        }

        if (isset($this->request->post['isbn'])) {
            $data['isbn'] = $this->request->post['isbn'];
        } elseif (!empty($product_info)) {
            $data['isbn'] = $product_info['isbn'];
        } else {
            $data['isbn'] = '';
        }

        if (isset($this->request->post['mpn'])) {
            $data['mpn'] = $this->request->post['mpn'];
        } elseif (!empty($product_info)) {
            $data['mpn'] = $product_info['mpn'];
        } else {
            $data['mpn'] = '';
        }

        if (isset($this->request->post['location'])) {
            $data['location'] = $this->request->post['location'];
        } elseif (!empty($product_info)) {
            $data['location'] = $product_info['location'];
        } else {
            $data['location'] = '';
        }

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['product_store'])) {
            $data['product_store'] = $this->request->post['product_store'];
        } elseif (isset($this->request->get['product_id'])) {
            $data['product_store'] = $this->model_catalog_product->getProductStores($this->request->get['product_id']);
        } else {
            $data['product_store'] = array(0);
        }

        if (isset($this->request->post['keyword'])) {
            $data['keyword'] = $this->request->post['keyword'];
        } elseif (!empty($product_info)) {
            $data['keyword'] = $product_info['keyword'];
        } else {
            $data['keyword'] = '';
        }

        if (isset($this->request->post['shipping'])) {
            $data['shipping'] = $this->request->post['shipping'];
        } elseif (!empty($product_info)) {
            $data['shipping'] = $product_info['shipping'];
        } else {
            $data['shipping'] = 1;
        }

        if (isset($this->request->post['price'])) {
            $data['price'] = $this->request->post['price'];
        } elseif (!empty($product_info)) {
            $data['price'] = $product_info['price'];
        } else {
            $data['price'] = '';
        }

        $this->load->model('catalog/recurring');

        $data['recurrings'] = $this->model_catalog_recurring->getRecurrings();

        if (isset($this->request->post['product_recurrings'])) {
            $data['product_recurrings'] = $this->request->post['product_recurrings'];
        } elseif (!empty($product_info)) {
            $data['product_recurrings'] = $this->model_catalog_product->getRecurrings($product_info['product_id']);
        } else {
            $data['product_recurrings'] = array();
        }

        $this->load->model('localisation/tax_class');

        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        if (isset($this->request->post['tax_class_id'])) {
            $data['tax_class_id'] = $this->request->post['tax_class_id'];
        } elseif (!empty($product_info)) {
            $data['tax_class_id'] = $product_info['tax_class_id'];
        } else {
            $data['tax_class_id'] = 0;
        }

        if (isset($this->request->post['date_available'])) {
            $data['date_available'] = $this->request->post['date_available'];
        } elseif (!empty($product_info)) {
            $data['date_available'] = ($product_info['date_available'] != '0000-00-00') ? $product_info['date_available'] : '';
        } else {
            $data['date_available'] = date('Y-m-d');
        }

        if (isset($this->request->post['quantity'])) {
            $data['quantity'] = $this->request->post['quantity'];
        } elseif (!empty($product_info)) {
            $data['quantity'] = $product_info['quantity'];
        } else {
            $data['quantity'] = 1;
        }

        if (isset($this->request->post['minimum'])) {
            $data['minimum'] = $this->request->post['minimum'];
        } elseif (!empty($product_info)) {
            $data['minimum'] = $product_info['minimum'];
        } else {
            $data['minimum'] = 1;
        }

        if (isset($this->request->post['subtract'])) {
            $data['subtract'] = $this->request->post['subtract'];
        } elseif (!empty($product_info)) {
            $data['subtract'] = $product_info['subtract'];
        } else {
            $data['subtract'] = 1;
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($product_info)) {
            $data['sort_order'] = $product_info['sort_order'];
        } else {
            $data['sort_order'] = 1;
        }

        $this->load->model('localisation/stock_status');

        $data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

        if (isset($this->request->post['stock_status_id'])) {
            $data['stock_status_id'] = $this->request->post['stock_status_id'];
        } elseif (!empty($product_info)) {
            $data['stock_status_id'] = $product_info['stock_status_id'];
        } else {
            $data['stock_status_id'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($product_info)) {
            $data['status'] = $product_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['weight'])) {
            $data['weight'] = $this->request->post['weight'];
        } elseif (!empty($product_info)) {
            $data['weight'] = $product_info['weight'];
        } else {
            $data['weight'] = '';
        }

        $this->load->model('localisation/weight_class');

        $data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

        if (isset($this->request->post['weight_class_id'])) {
            $data['weight_class_id'] = $this->request->post['weight_class_id'];
        } elseif (!empty($product_info)) {
            $data['weight_class_id'] = $product_info['weight_class_id'];
        } else {
            $data['weight_class_id'] = $this->config->get('config_weight_class_id');
        }

        if (isset($this->request->post['length'])) {
            $data['length'] = $this->request->post['length'];
        } elseif (!empty($product_info)) {
            $data['length'] = $product_info['length'];
        } else {
            $data['length'] = '';
        }

        if (isset($this->request->post['width'])) {
            $data['width'] = $this->request->post['width'];
        } elseif (!empty($product_info)) {
            $data['width'] = $product_info['width'];
        } else {
            $data['width'] = '';
        }

        if (isset($this->request->post['height'])) {
            $data['height'] = $this->request->post['height'];
        } elseif (!empty($product_info)) {
            $data['height'] = $product_info['height'];
        } else {
            $data['height'] = '';
        }

        $this->load->model('localisation/length_class');

        $data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

        if (isset($this->request->post['length_class_id'])) {
            $data['length_class_id'] = $this->request->post['length_class_id'];
        } elseif (!empty($product_info)) {
            $data['length_class_id'] = $product_info['length_class_id'];
        } else {
            $data['length_class_id'] = $this->config->get('config_length_class_id');
        }

        $this->load->model('catalog/manufacturer');

        if (isset($this->request->post['manufacturer_id'])) {
            $data['manufacturer_id'] = $this->request->post['manufacturer_id'];
        } elseif (!empty($product_info)) {
            $data['manufacturer_id'] = $product_info['manufacturer_id'];
        } else {
            $data['manufacturer_id'] = 0;
        }

        if (isset($this->request->post['manufacturer'])) {
            $data['manufacturer'] = $this->request->post['manufacturer'];
        } elseif (!empty($product_info)) {
            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

            if ($manufacturer_info) {
                $data['manufacturer'] = $manufacturer_info['name'];
            } else {
                $data['manufacturer'] = '';
            }
        } else {
            $data['manufacturer'] = '';
        }

        // Categories
        $this->load->model('catalog/category');

        if (isset($this->request->post['product_category'])) {
            $categories = $this->request->post['product_category'];
        } elseif (isset($this->request->get['product_id'])) {
            $categories = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
        } else {
            $categories = array();
        }

        $data['product_categories'] = array();

        foreach ($categories as $category_id) {
            $category_info = $this->model_catalog_category->getCategory($category_id);

            if ($category_info) {
                $data['product_categories'][] = array(
                    'category_id' => $category_info['category_id'],
                    'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
                );
            }
        }

        // Filters
        $this->load->model('catalog/filter');

        if (isset($this->request->post['product_filter'])) {
            $filters = $this->request->post['product_filter'];
        } elseif (isset($this->request->get['product_id'])) {
            $filters = $this->model_catalog_product->getProductFilters($this->request->get['product_id']);
        } else {
            $filters = array();
        }

        $data['product_filters'] = array();

        foreach ($filters as $filter_id) {
            $filter_info = $this->model_catalog_filter->getFilter($filter_id);

            if ($filter_info) {
                $data['product_filters'][] = array(
                    'filter_id' => $filter_info['filter_id'],
                    'name' => $filter_info['group'] . ' &gt; ' . $filter_info['name']
                );
            }
        }

        // Attributes
        $this->load->model('catalog/attribute');

        if (isset($this->request->post['product_attribute'])) {
            $product_attributes = $this->request->post['product_attribute'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_attributes = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
        } else {
            $product_attributes = array();
        }

        $data['product_attributes'] = array();

        foreach ($product_attributes as $product_attribute) {
            $attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);

            if ($attribute_info) {
                $data['product_attributes'][] = array(
                    'attribute_id' => $product_attribute['attribute_id'],
                    'name' => $attribute_info['name'],
                    'product_attribute_description' => $product_attribute['product_attribute_description']
                );
            }
        }

        // Options
        $this->load->model('catalog/option');

        if (isset($this->request->post['product_option'])) {
            $product_options = $this->request->post['product_option'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
        } else {
            $product_options = array();
        }

        $data['product_options'] = array();

        foreach ($product_options as $product_option) {
            $product_option_value_data = array();

            if (isset($product_option['product_option_value'])) {
                foreach ($product_option['product_option_value'] as $product_option_value) {
                    $product_option_value_data[] = array(
                        'product_option_value_id' => $product_option_value['product_option_value_id'],
                        'option_value_id' => $product_option_value['option_value_id'],
                        'quantity' => $product_option_value['quantity'],
                        'subtract' => $product_option_value['subtract'],
                        'price' => $product_option_value['price'],
                        'price_prefix' => $product_option_value['price_prefix'],
                        'points' => $product_option_value['points'],
                        'points_prefix' => $product_option_value['points_prefix'],
                        'weight' => $product_option_value['weight'],
                        'weight_prefix' => $product_option_value['weight_prefix']
                    );
                }
            }

            $data['product_options'][] = array(
                'product_option_id' => $product_option['product_option_id'],
                'product_option_value' => $product_option_value_data,
                'option_id' => $product_option['option_id'],
                'name' => $product_option['name'],
                'type' => $product_option['type'],
                'value' => isset($product_option['value']) ? $product_option['value'] : '',
                'required' => $product_option['required']
            );
        }

        $data['option_values'] = array();

        foreach ($data['product_options'] as $product_option) {
            if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                if (!isset($data['option_values'][$product_option['option_id']])) {
                    $data['option_values'][$product_option['option_id']] = $this->model_catalog_option->getOptionValues($product_option['option_id']);
                }
            }
        }

        $this->load->model('sale/customer_group');

        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        if (isset($this->request->post['product_discount'])) {
            $product_discounts = $this->request->post['product_discount'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
        } else {
            $product_discounts = array();
        }

        $data['product_discounts'] = array();

        foreach ($product_discounts as $product_discount) {
            $data['product_discounts'][] = array(
                'customer_group_id' => $product_discount['customer_group_id'],
                'quantity' => $product_discount['quantity'],
                'priority' => $product_discount['priority'],
                'price' => $product_discount['price'],
                'date_start' => ($product_discount['date_start'] != '0000-00-00') ? $product_discount['date_start'] : '',
                'date_end' => ($product_discount['date_end'] != '0000-00-00') ? $product_discount['date_end'] : ''
            );
        }

        if (isset($this->request->post['product_special'])) {
            $product_specials = $this->request->post['product_special'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_specials = $this->model_catalog_product->getProductSpecials($this->request->get['product_id']);
        } else {
            $product_specials = array();
        }

        $data['product_specials'] = array();

        foreach ($product_specials as $product_special) {
            $data['product_specials'][] = array(
                'customer_group_id' => $product_special['customer_group_id'],
                'priority' => $product_special['priority'],
                'price' => $product_special['price'],
                'date_start' => ($product_special['date_start'] != '0000-00-00') ? $product_special['date_start'] : '',
                'date_end' => ($product_special['date_end'] != '0000-00-00') ? $product_special['date_end'] : ''
            );
        }

        // Images
        if (isset($this->request->post['product_image'])) {
            $product_images = $this->request->post['product_image'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_images = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
        } else {
            $product_images = array();
        }

        $data['product_images'] = array();

        foreach ($product_images as $product_image) {
            if (is_file(DIR_IMAGE . $product_image['image'])) {
                $image = $product_image['image'];
                $thumb = $product_image['image'];
            } else {
                $image = '';
                $thumb = 'no_image.png';
            }

            $data['product_images'][] = array(
                'image' => $image,
                'thumb' => $this->model_tool_image->resize($thumb, 100, 100),
                'sort_order' => $product_image['sort_order']
            );
        }

        // Downloads
        $this->load->model('catalog/download');

        if (isset($this->request->post['product_download'])) {
            $product_downloads = $this->request->post['product_download'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_downloads = $this->model_catalog_product->getProductDownloads($this->request->get['product_id']);
        } else {
            $product_downloads = array();
        }

        $data['product_downloads'] = array();

        foreach ($product_downloads as $download_id) {
            $download_info = $this->model_catalog_download->getDownload($download_id);

            if ($download_info) {
                $data['product_downloads'][] = array(
                    'download_id' => $download_info['download_id'],
                    'name' => $download_info['name']
                );
            }
        }

        if (isset($this->request->post['product_related'])) {
            $products = $this->request->post['product_related'];
        } elseif (isset($this->request->get['product_id'])) {
            $products = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
        } else {
            $products = array();
        }

        $data['product_relateds'] = array();

        foreach ($products as $product_id) {
            $related_info = $this->model_catalog_product->getProduct($product_id);

            if ($related_info) {
                $data['product_relateds'][] = array(
                    'product_id' => $related_info['product_id'],
                    'name' => $related_info['name']
                );
            }
        }

        if (isset($this->request->post['points'])) {
            $data['points'] = $this->request->post['points'];
        } elseif (!empty($product_info)) {
            $data['points'] = $product_info['points'];
        } else {
            $data['points'] = '';
        }

        if (isset($this->request->post['product_reward'])) {
            $data['product_reward'] = $this->request->post['product_reward'];
        } elseif (isset($this->request->get['product_id'])) {
            $data['product_reward'] = $this->model_catalog_product->getProductRewards($this->request->get['product_id']);
        } else {
            $data['product_reward'] = array();
        }

        if (isset($this->request->post['product_layout'])) {
            $data['product_layout'] = $this->request->post['product_layout'];
        } elseif (isset($this->request->get['product_id'])) {
            $data['product_layout'] = $this->model_catalog_product->getProductLayouts($this->request->get['product_id']);
        } else {
            $data['product_layout'] = array();
        }

        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/product_form.tpl', $data));
    }

    protected function validateForm()
    {
        if (!$this->user->hasPermission('modify', 'catalog/product')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        /*
            foreach ($this->request->post['product_description'] as $language_id => $value) {
                if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
                    $this->error['name'][$language_id] = $this->language->get('error_name');
                }

                if ((utf8_strlen($value['meta_title']) < 1) || (utf8_strlen($value['meta_title']) > 255)) {
                    $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
                }
            }

            if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
                $this->error['model'] = $this->language->get('error_model');
            }

            if (utf8_strlen($this->request->post['keyword']) > 0) {
                $this->load->model('catalog/url_alias');

                $url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

                if ($url_alias_info && isset($this->request->get['product_id']) && $url_alias_info['query'] != 'product_id=' . $this->request->get['product_id']) {
                    $this->error['keyword'] = sprintf($this->language->get('error_keyword'));
                }

                if ($url_alias_info && !isset($this->request->get['product_id'])) {
                    $this->error['keyword'] = sprintf($this->language->get('error_keyword'));
                }
            }
         */

        /*TODO add more value check here*/
        if (!isset($this->request->post['input_name']) || ($this->request->post['input_name'] == '')) {
            $this->error['warning'] = sprintf($this->language->get('error_name'));
            return !$this->error;
        }

        if (!isset($this->request->post['input_model']) || ($this->request->post['input_model'] == '')) {
            $this->error['warning'] = "商品类型未选择！";
            return !$this->error;
        }

        if (!isset($this->request->post['input_product_place_shipment']) || ($this->request->post['input_product_place_shipment'] == '')) {
            $this->error['warning'] = "商品来源地未选择！";
            return !$this->error;
        }

        if (!isset($this->request->post['input_product_place']) || ($this->request->post['input_product_place'] == '')) {
            $this->error['warning'] = "商品发货地未选择！";
            return !$this->error;
        }

        if (!isset($this->request->post['input_product_stock']) || ($this->request->post['input_product_stock'] == '')) {
            $this->error['warning'] = "商品库存量未填！";
            return !$this->error;
        }

        if (!isset($this->request->post['input_weight']) || ($this->request->post['input_weight'] == '')) {
            $this->error['warning'] = "商品物流计算重量未填！";
            return !$this->error;
        }

        if (!isset($this->request->post['input_product_price_market']) || ($this->request->post['input_product_price_market'] == '')) {
            $this->error['warning'] = "市场价未填！";
            return !$this->error;
        }

        if (!isset($this->request->post['input_product_price_store']) || ($this->request->post['input_product_price_store'] == '')) {
            $this->error['warning'] = "供货价未填！";
            return !$this->error;
        }

        if (!isset($this->request->post['input_product_recommand_index']) || ($this->request->post['input_product_recommand_index'] == '')) {
            $this->error['warning'] = "推荐指数未填！";
            return !$this->error;
        }

        if (($this->request->post['input_product_recommand_index'] < 0) || ($this->request->post['input_product_recommand_index'] > 10)) {
            $this->error['warning'] = "推荐指数超出正常范围！";
            return !$this->error;
        }

        if (!isset($this->request->post['input_volume']) || ($this->request->post['input_volume'] == '')) {
            $this->error['warning'] = "商品物流计算体积未填！";
            return !$this->error;
        }

        if (!isset($this->request->post['input_product_tax']) || ($this->request->post['input_product_tax'] == '')) {
            $this->error['warning'] = "商品代收税金额未填！";
            return !$this->error;
        }

        if (!isset($this->request->post['input_charge_mode']) || ($this->request->post['input_charge_mode'] == '')) {
            $this->error['warning'] = "计费方式未选择！";
            return !$this->error;
        }

//    if(!isset($this->request->post['input_product_return_deadline']) || ($this->request->post['input_product_return_deadline'] == '')){
//		  $this->error['warning'] = "退货设置未填！";
//		  return !$this->error;
//    }

        if (!isset($this->request->post['input_product_reward']) || ($this->request->post['input_product_reward'] == '')) {
            $this->error['warning'] = "回馈积分未填!";
            return !$this->error;
        }

        if (!isset($this->request->post['input_upload_img_title']) || ($this->request->post['input_upload_img_title'] == '')) {
            $this->error['warning'] = "商品文案标题图片未上传！";
            return !$this->error;
        }
        /*
            if(!isset($this->request->post['']) || ($this->request->post[''] == '')){
                  $this->error['warning'] = "";
                  return !$this->error;
            }
         */

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }


        return !$this->error;
    }

    protected function validateDelete()
    {
        if (!$this->user->hasPermission('modify', 'catalog/product')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateCopy()
    {
        if (!$this->user->hasPermission('modify', 'catalog/product')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete()
    {
        $json = array();

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
            $this->load->model('catalog/product');
            $this->load->model('catalog/option');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_model'])) {
                $filter_model = $this->request->get['filter_model'];
            } else {
                $filter_model = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 5;
            }

            $filter_data = array(
                'filter_name' => $filter_name,
                'filter_model' => $filter_model,
                'start' => 0,
                'limit' => $limit
            );

            $results = $this->model_catalog_product->getProducts($filter_data);

            foreach ($results as $result) {
                $option_data = array();

                $product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

                foreach ($product_options as $product_option) {
                    $option_info = $this->model_catalog_option->getOption($product_option['option_id']);

                    if ($option_info) {
                        $product_option_value_data = array();

                        foreach ($product_option['product_option_value'] as $product_option_value) {
                            $option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

                            if ($option_value_info) {
                                $product_option_value_data[] = array(
                                    'product_option_value_id' => $product_option_value['product_option_value_id'],
                                    'option_value_id' => $product_option_value['option_value_id'],
                                    'name' => $option_value_info['name'],
                                    'price' => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
                                    'price_prefix' => $product_option_value['price_prefix']
                                );
                            }
                        }

                        $option_data[] = array(
                            'product_option_id' => $product_option['product_option_id'],
                            'product_option_value' => $product_option_value_data,
                            'option_id' => $product_option['option_id'],
                            'name' => $option_info['name'],
                            'type' => $option_info['type'],
                            'value' => $product_option['value'],
                            'required' => $product_option['required']
                        );
                    }
                }

                $json[] = array(
                    'product_id' => $result['product_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'model' => $result['model'],
                    'option' => $option_data,
                    'price' => $result['price']
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    public function getForm_new()
    {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_plus'] = $this->language->get('text_plus');
        $data['text_minus'] = $this->language->get('text_minus');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_option'] = $this->language->get('text_option');
        $data['text_option_value'] = $this->language->get('text_option_value');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_percent'] = $this->language->get('text_percent');
        $data['text_amount'] = $this->language->get('text_amount');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_keyword'] = $this->language->get('entry_keyword');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_sku'] = $this->language->get('entry_sku');
        $data['entry_upc'] = $this->language->get('entry_upc');
        $data['entry_ean'] = $this->language->get('entry_ean');
        $data['entry_jan'] = $this->language->get('entry_jan');
        $data['entry_isbn'] = $this->language->get('entry_isbn');
        $data['entry_mpn'] = $this->language->get('entry_mpn');
        $data['entry_location'] = $this->language->get('entry_location');
        $data['entry_minimum'] = $this->language->get('entry_minimum');
        $data['entry_shipping'] = $this->language->get('entry_shipping');
        $data['entry_date_available'] = $this->language->get('entry_date_available');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_stock_status'] = $this->language->get('entry_stock_status');
        $data['entry_price'] = $this->language->get('entry_price');
        $data['entry_tax_class'] = $this->language->get('entry_tax_class');
        $data['entry_points'] = $this->language->get('entry_points');
        $data['entry_option_points'] = $this->language->get('entry_option_points');
        $data['entry_subtract'] = $this->language->get('entry_subtract');
        $data['entry_weight_class'] = $this->language->get('entry_weight_class');
        $data['entry_weight'] = $this->language->get('entry_weight');
        $data['entry_dimension'] = $this->language->get('entry_dimension');
        $data['entry_length_class'] = $this->language->get('entry_length_class');
        $data['entry_length'] = $this->language->get('entry_length');
        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_height'] = $this->language->get('entry_height');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
        $data['entry_download'] = $this->language->get('entry_download');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_filter'] = $this->language->get('entry_filter');
        $data['entry_related'] = $this->language->get('entry_related');
        $data['entry_attribute'] = $this->language->get('entry_attribute');
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_option'] = $this->language->get('entry_option');
        $data['entry_option_value'] = $this->language->get('entry_option_value');
        $data['entry_required'] = $this->language->get('entry_required');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_priority'] = $this->language->get('entry_priority');
        $data['entry_tag'] = $this->language->get('entry_tag');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_reward'] = $this->language->get('entry_reward');
        $data['entry_layout'] = $this->language->get('entry_layout');
        $data['entry_recurring'] = $this->language->get('entry_recurring');

        $data['help_keyword'] = $this->language->get('help_keyword');
        $data['help_sku'] = $this->language->get('help_sku');
        $data['help_upc'] = $this->language->get('help_upc');
        $data['help_ean'] = $this->language->get('help_ean');
        $data['help_jan'] = $this->language->get('help_jan');
        $data['help_isbn'] = $this->language->get('help_isbn');
        $data['help_mpn'] = $this->language->get('help_mpn');
        $data['help_minimum'] = $this->language->get('help_minimum');
        $data['help_manufacturer'] = $this->language->get('help_manufacturer');
        $data['help_stock_status'] = $this->language->get('help_stock_status');
        $data['help_points'] = $this->language->get('help_points');
        $data['help_category'] = $this->language->get('help_category');
        $data['help_filter'] = $this->language->get('help_filter');
        $data['help_download'] = $this->language->get('help_download');
        $data['help_related'] = $this->language->get('help_related');
        $data['help_tag'] = $this->language->get('help_tag');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_attribute_add'] = $this->language->get('button_attribute_add');
        $data['button_option_add'] = $this->language->get('button_option_add');
        $data['button_option_value_add'] = $this->language->get('button_option_value_add');
        $data['button_discount_add'] = $this->language->get('button_discount_add');
        $data['button_special_add'] = $this->language->get('button_special_add');
        $data['button_image_add'] = $this->language->get('button_image_add');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_recurring_add'] = $this->language->get('button_recurring_add');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_data'] = $this->language->get('tab_data');
        $data['tab_attribute'] = $this->language->get('tab_attribute');
        $data['tab_option'] = $this->language->get('tab_option');
        $data['tab_recurring'] = $this->language->get('tab_recurring');
        $data['tab_discount'] = $this->language->get('tab_discount');
        $data['tab_special'] = $this->language->get('tab_special');
        $data['tab_image'] = $this->language->get('tab_image');
        $data['tab_links'] = $this->language->get('tab_links');
        $data['tab_reward'] = $this->language->get('tab_reward');
        $data['tab_design'] = $this->language->get('tab_design');
        $data['tab_openbay'] = $this->language->get('tab_openbay');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = array();
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
        }

        if (isset($this->error['model'])) {
            $data['error_model'] = $this->error['model'];
        } else {
            $data['error_model'] = '';
        }

        if (isset($this->error['date_available'])) {
            $data['error_date_available'] = $this->error['date_available'];
        } else {
            $data['error_date_available'] = '';
        }

        if (isset($this->error['keyword'])) {
            $data['error_keyword'] = $this->error['keyword'];
        } else {
            $data['error_keyword'] = '';
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_product_no'])) {
            $url .= '&filter_product_no=' . urlencode(html_entity_decode($this->request->get['filter_product_no'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }
        /* liuhang : add new filter options for query
         *
         * initial filter option vars
         */
        if (isset($this->request->get['filter_id'])) {
            $filter_id = $this->request->get['filter_id'];
        } else {
            $filter_id = null;
        }

        if (isset($this->request->get['filter_quantity_min'])) {
            $filter_quantity_min = $this->request->get['filter_quantity_min'];
        } else {
            $filter_quantity_min = null;
        }

        if (isset($this->request->get['filter_quantity_max'])) {
            $filter_quantity_max = $this->request->get['filter_quantity_max'];
        } else {
            $filter_quantity_max = null;
        }

        if (isset($this->request->get['filter_product_price_market_min'])) {
            $filter_product_price_market_min = $this->request->get['filter_product_price_market_min'];
        } else {
            $filter_product_price_market_min = null;
        }

        if (isset($this->request->get['filter_product_price_market_max'])) {
            $filter_product_price_market_max = $this->request->get['filter_product_price_market_max'];
        } else {
            $filter_product_price_market_max = null;
        }

        if (isset($this->request->get['filter_product_sales_min'])) {
            $filter_product_sales_min = $this->request->get['filter_product_sales_min'];
        } else {
            $filter_product_sales_min = null;
        }

        if (isset($this->request->get['filter_product_sales_max'])) {
            $filter_product_sales_max = $this->request->get['filter_product_sales_max'];
        } else {
            $filter_product_sales_max = null;
        }

        if (isset($this->request->get['filter_origin'])) {
            $filter_origin = $this->request->get['filter_origin'];
        } else {
            $filter_origin = null;
        }

        if (isset($this->request->get['filter_product_price_supplier_min'])) {
            $filter_product_price_supplier_min = $this->request->get['filter_product_price_supplier_min'];
        } else {
            $filter_product_price_supplier_min = null;
        }

        if (isset($this->request->get['filter_product_price_supplier_max'])) {
            $filter_product_price_supplier_max = $this->request->get['filter_product_price_supplier_max'];
        } else {
            $filter_product_price_supplier_max = null;
        }

        if (isset($this->request->get['filter_product_comments_min'])) {
            $filter_product_comments_min = $this->request->get['filter_product_comments_min'];
        } else {
            $filter_product_comments_min = null;
        }

        if (isset($this->request->get['filter_product_comments_max'])) {
            $filter_product_comments_max = $this->request->get['filter_product_comments_max'];
        } else {
            $filter_product_comments_max = null;
        }

        if (isset($this->request->get['filter_product_recommend_index_min'])) {
            $filter_product_recommend_index_min = $this->request->get['filter_product_recommend_index_min'];
        } else {
            $filter_product_recommend_index_min = null;
        }

        if (isset($this->request->get['filter_product_recommend_index_max'])) {
            $filter_product_recommend_index_max = $this->request->get['filter_product_recommend_index_max'];
        } else {
            $filter_product_recommend_index_max = null;
        }

        /* end of < add new filter options for query > */

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
            'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        if (!isset($this->request->get['product_id'])) {
            $data['action'] = $this->url->link('catalog/product/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $data['action'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
        }

        $data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
        }

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['product_description'])) {
            $data['product_description'] = $this->request->post['product_description'];
        } elseif (isset($this->request->get['product_id'])) {
            $data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->request->get['product_id']);
        } else {
            $data['product_description'] = array();
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($product_info)) {
            $data['image'] = $product_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['model'])) {
            $data['model'] = $this->request->post['model'];
        } elseif (!empty($product_info)) {
            $data['model'] = $product_info['model'];
        } else {
            $data['model'] = '';
        }

        if (isset($this->request->post['sku'])) {
            $data['sku'] = $this->request->post['sku'];
        } elseif (!empty($product_info)) {
            $data['sku'] = $product_info['sku'];
        } else {
            $data['sku'] = '';
        }

        if (isset($this->request->post['upc'])) {
            $data['upc'] = $this->request->post['upc'];
        } elseif (!empty($product_info)) {
            $data['upc'] = $product_info['upc'];
        } else {
            $data['upc'] = '';
        }

        if (isset($this->request->post['ean'])) {
            $data['ean'] = $this->request->post['ean'];
        } elseif (!empty($product_info)) {
            $data['ean'] = $product_info['ean'];
        } else {
            $data['ean'] = '';
        }

        if (isset($this->request->post['jan'])) {
            $data['jan'] = $this->request->post['jan'];
        } elseif (!empty($product_info)) {
            $data['jan'] = $product_info['jan'];
        } else {
            $data['jan'] = '';
        }

        if (isset($this->request->post['isbn'])) {
            $data['isbn'] = $this->request->post['isbn'];
        } elseif (!empty($product_info)) {
            $data['isbn'] = $product_info['isbn'];
        } else {
            $data['isbn'] = '';
        }

        if (isset($this->request->post['mpn'])) {
            $data['mpn'] = $this->request->post['mpn'];
        } elseif (!empty($product_info)) {
            $data['mpn'] = $product_info['mpn'];
        } else {
            $data['mpn'] = '';
        }

        if (isset($this->request->post['location'])) {
            $data['location'] = $this->request->post['location'];
        } elseif (!empty($product_info)) {
            $data['location'] = $product_info['location'];
        } else {
            $data['location'] = '';
        }

        $this->load->model('setting/store');

        $data['stores'] = $this->model_setting_store->getStores();

        if (isset($this->request->post['product_store'])) {
            $data['product_store'] = $this->request->post['product_store'];
        } elseif (isset($this->request->get['product_id'])) {
            $data['product_store'] = $this->model_catalog_product->getProductStores($this->request->get['product_id']);
        } else {
            $data['product_store'] = array(0);
        }

        if (isset($this->request->post['keyword'])) {
            $data['keyword'] = $this->request->post['keyword'];
        } elseif (!empty($product_info)) {
            $data['keyword'] = $product_info['keyword'];
        } else {
            $data['keyword'] = '';
        }

        if (isset($this->request->post['shipping'])) {
            $data['shipping'] = $this->request->post['shipping'];
        } elseif (!empty($product_info)) {
            $data['shipping'] = $product_info['shipping'];
        } else {
            $data['shipping'] = 1;
        }

        if (isset($this->request->post['price'])) {
            $data['price'] = $this->request->post['price'];
        } elseif (!empty($product_info)) {
            $data['price'] = $product_info['price'];
        } else {
            $data['price'] = '';
        }

        $this->load->model('catalog/recurring');

        $data['recurrings'] = $this->model_catalog_recurring->getRecurrings();

        if (isset($this->request->post['product_recurrings'])) {
            $data['product_recurrings'] = $this->request->post['product_recurrings'];
        } elseif (!empty($product_info)) {
            $data['product_recurrings'] = $this->model_catalog_product->getRecurrings($product_info['product_id']);
        } else {
            $data['product_recurrings'] = array();
        }

        $this->load->model('localisation/tax_class');

        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        if (isset($this->request->post['tax_class_id'])) {
            $data['tax_class_id'] = $this->request->post['tax_class_id'];
        } elseif (!empty($product_info)) {
            $data['tax_class_id'] = $product_info['tax_class_id'];
        } else {
            $data['tax_class_id'] = 0;
        }

        if (isset($this->request->post['date_available'])) {
            $data['date_available'] = $this->request->post['date_available'];
        } elseif (!empty($product_info)) {
            $data['date_available'] = ($product_info['date_available'] != '0000-00-00') ? $product_info['date_available'] : '';
        } else {
            $data['date_available'] = date('Y-m-d');
        }

        if (isset($this->request->post['quantity'])) {
            $data['quantity'] = $this->request->post['quantity'];
        } elseif (!empty($product_info)) {
            $data['quantity'] = $product_info['quantity'];
        } else {
            $data['quantity'] = 1;
        }

        if (isset($this->request->post['minimum'])) {
            $data['minimum'] = $this->request->post['minimum'];
        } elseif (!empty($product_info)) {
            $data['minimum'] = $product_info['minimum'];
        } else {
            $data['minimum'] = 1;
        }

        if (isset($this->request->post['subtract'])) {
            $data['subtract'] = $this->request->post['subtract'];
        } elseif (!empty($product_info)) {
            $data['subtract'] = $product_info['subtract'];
        } else {
            $data['subtract'] = 1;
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($product_info)) {
            $data['sort_order'] = $product_info['sort_order'];
        } else {
            $data['sort_order'] = 1;
        }

        $this->load->model('localisation/stock_status');

        $data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

        if (isset($this->request->post['stock_status_id'])) {
            $data['stock_status_id'] = $this->request->post['stock_status_id'];
        } elseif (!empty($product_info)) {
            $data['stock_status_id'] = $product_info['stock_status_id'];
        } else {
            $data['stock_status_id'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($product_info)) {
            $data['status'] = $product_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['weight'])) {
            $data['weight'] = $this->request->post['weight'];
        } elseif (!empty($product_info)) {
            $data['weight'] = $product_info['weight'];
        } else {
            $data['weight'] = '';
        }

        $this->load->model('localisation/weight_class');

        $data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

        if (isset($this->request->post['weight_class_id'])) {
            $data['weight_class_id'] = $this->request->post['weight_class_id'];
        } elseif (!empty($product_info)) {
            $data['weight_class_id'] = $product_info['weight_class_id'];
        } else {
            $data['weight_class_id'] = $this->config->get('config_weight_class_id');
        }

        if (isset($this->request->post['length'])) {
            $data['length'] = $this->request->post['length'];
        } elseif (!empty($product_info)) {
            $data['length'] = $product_info['length'];
        } else {
            $data['length'] = '';
        }

        if (isset($this->request->post['width'])) {
            $data['width'] = $this->request->post['width'];
        } elseif (!empty($product_info)) {
            $data['width'] = $product_info['width'];
        } else {
            $data['width'] = '';
        }

        if (isset($this->request->post['height'])) {
            $data['height'] = $this->request->post['height'];
        } elseif (!empty($product_info)) {
            $data['height'] = $product_info['height'];
        } else {
            $data['height'] = '';
        }

        $this->load->model('localisation/length_class');

        $data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

        if (isset($this->request->post['length_class_id'])) {
            $data['length_class_id'] = $this->request->post['length_class_id'];
        } elseif (!empty($product_info)) {
            $data['length_class_id'] = $product_info['length_class_id'];
        } else {
            $data['length_class_id'] = $this->config->get('config_length_class_id');
        }

        $this->load->model('catalog/manufacturer');

        if (isset($this->request->post['manufacturer_id'])) {
            $data['manufacturer_id'] = $this->request->post['manufacturer_id'];
        } elseif (!empty($product_info)) {
            $data['manufacturer_id'] = $product_info['manufacturer_id'];
        } else {
            $data['manufacturer_id'] = 0;
        }

        if (isset($this->request->post['manufacturer'])) {
            $data['manufacturer'] = $this->request->post['manufacturer'];
        } elseif (!empty($product_info)) {
            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

            if ($manufacturer_info) {
                $data['manufacturer'] = $manufacturer_info['name'];
            } else {
                $data['manufacturer'] = '';
            }
        } else {
            $data['manufacturer'] = '';
        }

        // Categories
        $this->load->model('catalog/category');

        if (isset($this->request->post['product_category'])) {
            $categories = $this->request->post['product_category'];
        } elseif (isset($this->request->get['product_id'])) {
            $categories = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
        } else {
            $categories = array();
        }

        $data['product_categories'] = array();

        foreach ($categories as $category_id) {
            $category_info = $this->model_catalog_category->getCategory($category_id);

            if ($category_info) {
                $data['product_categories'][] = array(
                    'category_id' => $category_info['category_id'],
                    'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
                );
            }
        }

        // Filters
        $this->load->model('catalog/filter');

        if (isset($this->request->post['product_filter'])) {
            $filters = $this->request->post['product_filter'];
        } elseif (isset($this->request->get['product_id'])) {
            $filters = $this->model_catalog_product->getProductFilters($this->request->get['product_id']);
        } else {
            $filters = array();
        }

        $data['product_filters'] = array();

        foreach ($filters as $filter_id) {
            $filter_info = $this->model_catalog_filter->getFilter($filter_id);

            if ($filter_info) {
                $data['product_filters'][] = array(
                    'filter_id' => $filter_info['filter_id'],
                    'name' => $filter_info['group'] . ' &gt; ' . $filter_info['name']
                );
            }
        }

        // Attributes
        $this->load->model('catalog/attribute');

        if (isset($this->request->post['product_attribute'])) {
            $product_attributes = $this->request->post['product_attribute'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_attributes = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
        } else {
            $product_attributes = array();
        }

        $data['product_attributes'] = array();

        foreach ($product_attributes as $product_attribute) {
            $attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);

            if ($attribute_info) {
                $data['product_attributes'][] = array(
                    'attribute_id' => $product_attribute['attribute_id'],
                    'name' => $attribute_info['name'],
                    'product_attribute_description' => $product_attribute['product_attribute_description']
                );
            }
        }

        // Options
        $this->load->model('catalog/option');

        if (isset($this->request->post['product_option'])) {
            $product_options = $this->request->post['product_option'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
        } else {
            $product_options = array();
        }

        $data['product_options'] = array();

        foreach ($product_options as $product_option) {
            $product_option_value_data = array();

            if (isset($product_option['product_option_value'])) {
                foreach ($product_option['product_option_value'] as $product_option_value) {
                    $product_option_value_data[] = array(
                        'product_option_value_id' => $product_option_value['product_option_value_id'],
                        'option_value_id' => $product_option_value['option_value_id'],
                        'quantity' => $product_option_value['quantity'],
                        'subtract' => $product_option_value['subtract'],
                        'price' => $product_option_value['price'],
                        'price_prefix' => $product_option_value['price_prefix'],
                        'points' => $product_option_value['points'],
                        'points_prefix' => $product_option_value['points_prefix'],
                        'weight' => $product_option_value['weight'],
                        'weight_prefix' => $product_option_value['weight_prefix']
                    );
                }
            }

            $data['product_options'][] = array(
                'product_option_id' => $product_option['product_option_id'],
                'product_option_value' => $product_option_value_data,
                'option_id' => $product_option['option_id'],
                'name' => $product_option['name'],
                'type' => $product_option['type'],
                'value' => isset($product_option['value']) ? $product_option['value'] : '',
                'required' => $product_option['required']
            );
        }

        $data['option_values'] = array();

        foreach ($data['product_options'] as $product_option) {
            if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                if (!isset($data['option_values'][$product_option['option_id']])) {
                    $data['option_values'][$product_option['option_id']] = $this->model_catalog_option->getOptionValues($product_option['option_id']);
                }
            }
        }

        $this->load->model('sale/customer_group');

        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        if (isset($this->request->post['product_discount'])) {
            $product_discounts = $this->request->post['product_discount'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
        } else {
            $product_discounts = array();
        }

        $data['product_discounts'] = array();

        foreach ($product_discounts as $product_discount) {
            $data['product_discounts'][] = array(
                'customer_group_id' => $product_discount['customer_group_id'],
                'quantity' => $product_discount['quantity'],
                'priority' => $product_discount['priority'],
                'price' => $product_discount['price'],
                'date_start' => ($product_discount['date_start'] != '0000-00-00') ? $product_discount['date_start'] : '',
                'date_end' => ($product_discount['date_end'] != '0000-00-00') ? $product_discount['date_end'] : ''
            );
        }

        if (isset($this->request->post['product_special'])) {
            $product_specials = $this->request->post['product_special'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_specials = $this->model_catalog_product->getProductSpecials($this->request->get['product_id']);
        } else {
            $product_specials = array();
        }

        $data['product_specials'] = array();

        foreach ($product_specials as $product_special) {
            $data['product_specials'][] = array(
                'customer_group_id' => $product_special['customer_group_id'],
                'priority' => $product_special['priority'],
                'price' => $product_special['price'],
                'date_start' => ($product_special['date_start'] != '0000-00-00') ? $product_special['date_start'] : '',
                'date_end' => ($product_special['date_end'] != '0000-00-00') ? $product_special['date_end'] : ''
            );
        }

        // Images
        if (isset($this->request->post['product_image'])) {
            $product_images = $this->request->post['product_image'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_images = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
        } else {
            $product_images = array();
        }

        $data['product_images'] = array();

        foreach ($product_images as $product_image) {
            if (is_file(DIR_IMAGE . $product_image['image'])) {
                $image = $product_image['image'];
                $thumb = $product_image['image'];
            } else {
                $image = '';
                $thumb = 'no_image.png';
            }

            $data['product_images'][] = array(
                'image' => $image,
                'thumb' => $this->model_tool_image->resize($thumb, 100, 100),
                'sort_order' => $product_image['sort_order']
            );
        }

        // Downloads
        $this->load->model('catalog/download');

        if (isset($this->request->post['product_download'])) {
            $product_downloads = $this->request->post['product_download'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_downloads = $this->model_catalog_product->getProductDownloads($this->request->get['product_id']);
        } else {
            $product_downloads = array();
        }

        $data['product_downloads'] = array();

        foreach ($product_downloads as $download_id) {
            $download_info = $this->model_catalog_download->getDownload($download_id);

            if ($download_info) {
                $data['product_downloads'][] = array(
                    'download_id' => $download_info['download_id'],
                    'name' => $download_info['name']
                );
            }
        }

        if (isset($this->request->post['product_related'])) {
            $products = $this->request->post['product_related'];
        } elseif (isset($this->request->get['product_id'])) {
            $products = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
        } else {
            $products = array();
        }

        $data['product_relateds'] = array();

        foreach ($products as $product_id) {
            $related_info = $this->model_catalog_product->getProduct($product_id);

            if ($related_info) {
                $data['product_relateds'][] = array(
                    'product_id' => $related_info['product_id'],
                    'name' => $related_info['name']
                );
            }
        }

        if (isset($this->request->post['points'])) {
            $data['points'] = $this->request->post['points'];
        } elseif (!empty($product_info)) {
            $data['points'] = $product_info['points'];
        } else {
            $data['points'] = '';
        }

        if (isset($this->request->post['product_reward'])) {
            $data['product_reward'] = $this->request->post['product_reward'];
        } elseif (isset($this->request->get['product_id'])) {
            $data['product_reward'] = $this->model_catalog_product->getProductRewards($this->request->get['product_id']);
        } else {
            $data['product_reward'] = array();
        }

        if (isset($this->request->post['product_layout'])) {
            $data['product_layout'] = $this->request->post['product_layout'];
        } elseif (isset($this->request->get['product_id'])) {
            $data['product_layout'] = $this->model_catalog_product->getProductLayouts($this->request->get['product_id']);
        } else {
            $data['product_layout'] = array();
        }

        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/product_edit.tpl', $data));
    }


    public function addsharedoc()
    {

        $this->load->language('catalog/product');
        $this->load->model('catalog/product');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            $sharedoc_info = $this->request->post;

            $current_supplier_id = $this->model_catalog_product->getParentSupplier($this->session->data['supplier_id']);

            $sharedoc_info[] = array(
                'supplier_id' => $current_supplier_id
            );

            $prdshare_id = $this->model_catalog_product->addShareDocs($sharedoc_info);
        }

        $this->response->setOutput($prdshare_id);
    }


}
