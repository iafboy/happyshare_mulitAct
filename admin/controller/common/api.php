<?php
class ControllerCommonApi extends MyController {


    public function index() {

    }

    public function queryProductByCategories(){
        $this->load->model('common/product');
        $category_ids = $this->request->post['category_ids'];
        if(is_array($category_ids) && sizeof($category_ids) > 0){
            $products = $this->model_common_product->queryProductByCategories($category_ids);
            writeJson($products);
        }else{
            writeJson([]);
        }

    }
    public function queryProductByProducttypes(){
        $this->load->model('common/product');
        $category_ids = $this->request->post['producttype_ids'];
        if(is_array($category_ids) && sizeof($category_ids) > 0){
            $products = $this->model_common_product->queryProductByProducttypes($category_ids);
            writeJson($products);
        }else{
            writeJson([]);
        }

    }
    public function queryProductById(){
        $this->load->model('common/product');
        $product_id = $this->request->post['product_id'];
        if(!is_valid($product_id)){
            writeJson(['success'=>false,'errMsg'=>'产品编码非法!']);
            return;
        }
        $product = $this->model_common_product->queryProductById($product_id);
        if(!isset($product) || is_null($product) || empty($product)){
            writeJson(['success'=>false,'errMsg'=>'产品不存在!']);
            return;
        }
        writeJson(['success'=>true,'product'=>$product]);
    }
    public function queryProductByNo(){
        $this->load->model('common/product');
        $product_no = $this->request->post['product_no'];
        if(!is_valid($product_no)){
            writeJson(['success'=>false,'errMsg'=>'产品编码非法!']);
            return;
        }
        $product = $this->model_common_product->queryProductByNo($product_no);
        if(!isset($product) || is_null($product) || empty($product)){
            writeJson(['success'=>false,'errMsg'=>'产品不存在!']);
            return;
        }
        writeJson(['success'=>true,'product'=>$product]);
    }
    public function uploadProductByFile()
    {
        $this->load->model('common/product');
        $file_path = $this->request->post['file_path'];
        $file_path = DIR_UPLOADS . substr($file_path, 1);
        if (!is_valid($file_path)) {
            writeJson(['success' => false, 'errMsg' => '文件上传地址非法!']);
            return;
        }

        $products = readArrayFromXls($file_path, [['name' => 'product_no', 'col' => 1]]);
        $productls = array();

        foreach ($products as $prd) {
            $product_no = $prd['product_no'];
            $product = $this->model_common_product->queryProductByNo($product_no);
            if (!is_null($product) && !empty($product) && isset($product)) {
                    array_push($productls, $product);
            }

        }
        writeJson(['success' => true, 'product' => $productls]);
    }

    public function parseCustomerList(){
        $file_path = $this->request->post['file_path'];
        $file_path = DIR_UPLOADS.$file_path;
        $results = readArrayFromXls($file_path,[['name'=>'id','col'=>1],['name'=>'name','col'=>2]]);
        writeJson($results);
    }
    public function parseProductList(){
        $this->load->model('common/product');
        $file_path = $this->request->post['file_path'];
        $file_path = DIR_UPLOADS.$file_path;
        $results = readArrayFromXls($file_path,[
            ['name'=>'product_id','col'=>1],
            ['name'=>'act_price','col'=>2],
            ['name'=>'credit_percent','col'=>3],
            ['name'=>'limitpeople','col'=>4],
            ['name'=>'freedays','col'=>5],
            ['name'=>'sharenumber','col'=>6],
            ['name'=>'wxshare','col'=>7]
        ],0,3);
        foreach($results as $key=>&$row){
            if(!is_valid($row['product_id'])){
                unset($results[$key]);
            }
            $product = $this->model_common_product->queryProductById($row['product_id']);
            if($product['status']!= 3){
                unset($results[$key]);
                continue;
            }
            $row['product_name']=$product['product_name'];
            $row['storeprice'] = $product['storeprice'];
            if(!is_valid($row['credit_percent'])){
                $row['credit_percent'] = $product['credit_percent'];
            }
        }
        writeJson($results);
    }


    public function getCustomerAddressList(){
        $customerId = $this->request->post['customerId'];
        $this->load->model('common/customer');
        if(!is_valid($customerId)){
            writeJson([]);
        }
        $result = $this->model_common_customer->queryCustomerAddressList($customerId);
        writeJson($result);
    }




}