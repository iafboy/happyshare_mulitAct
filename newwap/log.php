<?php
/**
 * Created by PhpStorm.
 * User: kzu
 * Date: 2015/10/29
 * Time: 11:39
 * This File is to handle global logger
 */

    define('WAP_LOGS',DIR_SYSTEM.'logs/wap/');
    class MYLOG{

        private $log;

        private $debug;

        private $info;

        private $warn;

        private $error;

        public function __construct() {
            $this->log =    fopen(WAP_LOGS . 'log.log', 'a');
            $this->debug =  fopen(WAP_LOGS . 'debug.log', 'a');
            $this->info =   fopen(WAP_LOGS . 'info.log', 'a');
            $this->warn =   fopen(WAP_LOGS . 'warn.log', 'a');
            $this->error =  fopen(WAP_LOGS . 'error.log', 'a');
        }

        public function __destruct() {
            fclose($this->log);
            fclose($this->debug);
            fclose($this->info);
            fclose($this->warn);
            fclose($this->error);
        }

        public function write($file,$message) {
            fwrite($file, '['.date('Y-m-d G:i:s') . '] -- ' . print_r($message, true) . "\n");
        }

        public function log($msg){
            $this->write($this->log,$msg);
        }

        public function debug($msg){
            $this->write($this->debug,$msg);
        }

        public function info($msg){
            $this->write($this->info,$msg);
        }

        public function warn($msg){
            $this->write($this->warn,$msg);
        }

        public function error($msg){
            $this->write($this->error,$msg);
        }

    }
    $logger = new MYLOG();
?>