<?php
class ModelCommonProduct extends MyModel {
    public function queryExpressTemplates($userid){
        $sql='select express_template_id as id, express_template_name name from '.getTable('express_template').' where status=1 and supplier_id='.$userid.'';
        return parent::queryRows($sql);

    }
    public function queryProductByProducttypes($categories = array()){
        $sql = 'select distinct a.*,c.name as product_name from '
            .getTable('product').' a , '
            .getTable('product_type').' b , '
			.getTable('product_description').' c '
            .' where a.product_type_id = b.product_type_id and a.product_id = c.product_id and a.status = 3 '
            .' and a.product_type_id in (';
        foreach($categories as $category){
            $sql .= to_db_int($category).' ,';
        }
        $sql = substr($sql,0,strlen($sql)-1);
        $sql .= ')';
        return parent::queryRows($sql);
    }
    public function queryProducttypes(){
        $sql = 'select * from '.getTable('product_type');
        $sql .= 'where status = 1 order by product_type_no asc ';
        return parent::queryRows($sql);
    }
    public function queryOriginPlaces(){

        $sql = 'select * from '.getTable('origin_place');
        $sql .= ' where status = 1 order by place_code asc';
        return parent::queryRows($sql);
    }
    public function queryExpressPlaces(){

        $sql = 'select * from '.getTable('fromwhere');
        $sql .= ' where status = 1 order by place_code asc';
        return parent::queryRows($sql);
    }
    public function queryProductByCategories($categories = array()){
        $sql = 'select distinct a.*,b.name as product_name from '
            .getTable('product').' a , '
            .getTable('product_description').' b , '
            .getTable('category').' c , '
            .getTable('product_to_category').' d , '
            .getTable('category_description').' e '
            .' where a.product_id = b.product_id and a.status = 3 '
            .' and ( ( c.category_id = d.category_id '
            .' and d.product_id = a.product_id) or c.category_id = 0 )'
            .' and e.category_id = c.category_id '
            .' and c.category_id in (';
        foreach($categories as $category){
            $sql .= to_db_int($category).' ,';
        }
        $sql = substr($sql,0,strlen($sql)-1);
        $sql .= ')';
        return parent::queryRows($sql);
    }
    public function queryProductById($product_id){
        $sql = 'select distinct a.*,c.name as product_name from '
            .getTable('product').' a , '
            .getTable('product_type').' b , '
			.getTable('product_description').' c '
            .' where a.product_type_id = b.product_type_id and a.product_id = c.product_id '
            .' and a.product_id = '.to_db_int($product_id)
        ;
        return parent::querySingleRow($sql);
    }
    public function queryProductByNo($product_no){
        $sql = 'select distinct a.*,c.name as product_name from '
            .getTable('product').' a , '
            .getTable('product_type').' b , '
			.getTable('product_description').' c '
            .' where a.product_type_id = b.product_type_id and a.product_id = c.product_id '
            .' and a.product_no = '.to_db_str($product_no).'';
        return parent::querySingleRow($sql);
    }

    public function queryNosByIds($productIds = array()){
        if(count($productIds) == 0){
            return [];
        }
        $str = '';
        foreach($productIds as $productId){
            $str .= $productId .' ,';
        }
        if(is_valid($str)){
            $str = substr($str,0,strlen($str)-1);
        }
        $sql = ' select product_id,product_no from '.getTable('product').' where product_id in ('.$str.')';
        return parent::queryRows($sql);
    }
}