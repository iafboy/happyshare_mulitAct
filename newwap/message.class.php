<?php

/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2015/10/28
 * Time: 0:38
 */
namespace leshare\json;
    class message
    {
        public $data = array();

        public $resultCode='0';
        public $resultMsg;
        /**
         * message constructor.
         * @param array $data
         */
        public function __construct(array $data,$_resultCode,$_resultMsg)
        {
            $this->data = $data;
            $this->resultCode=$_resultCode;
            $this->resultMsg=$_resultMsg;
        }
        public function writeJson(){

            print_r(json_encode($this));
        }

    }
?>
