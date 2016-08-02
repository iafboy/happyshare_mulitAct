<?php
class ModelCommentKey extends MyModel {

    private function mainQuerySql($data,$columns_str){
        $sql = 'select '.$columns_str.' from '.getTable('comment_key');
        return $sql;
    }

    public function queryKeys($data=array()){
        $sql = $this->mainQuerySql($data,'*');
        $sql .= ' order by key_name asc';
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        return parent::queryRows($sql);
    }
    public function  queryKeysCount($data=array()){
        $sql = $this->mainQuerySql($data,'count(1) as count');
        return parent::queryCount($sql);
    }

    public function addKey($key_name){
        $sql = 'insert into '.getTable('comment_key') .' ('.getTableColumn('key_name').') values('.to_db_str($key_name).') ';
        return parent::wrapTransaction($sql);
    }
    public function delKey($key_id){
        $sql = 'delete from '.getTable('comment_key') .' where comment_key_id = '.$key_id;
        return parent::wrapTransaction($sql);
    }
    public function setKeyStatus($key_id,$key_status){
        $sql = 'update  '.getTable('comment_key') .' set status = '.to_db_int($key_status).' where comment_key_id = '.$key_id;
        return parent::wrapTransaction($sql);
    }

}