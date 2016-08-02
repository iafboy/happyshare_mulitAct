<?php





class ModelCommonUser extends MyModel{




    public function getUserGroupInfo($user_id){
        if(!is_valid($user_id)){
            return '';
        }
        $sql =
            'select a.name,a.user_group_id from '.getTable('user_group').' a , '
            .getTable('user').' b '
            .' where a.user_group_id = b.user_group_id and b.user_id = '.to_db_int($user_id);





    }




}