<?php
    class MyProperties {
        private $data = array();

        public function get($key) {
            return (isset($this->data[$key]) ? $this->data[$key] : null);
        }

        public function set($key, $value) {
            $this->data[$key] = $value;
        }

        public function has($key) {
            return isset($this->data[$key]);
        }

        public function load($filename) {
            $file = DIR_CONFIG . $filename . '.php';

            if (file_exists($file)) {
                $_ = array();
                require($file);
                $this->data = array_merge($this->data, $_);
            } else {
                trigger_error('Error: Could not load config ' . $filename . '!');
                exit();
            }
        }
    }
    $pros = new MyProperties();
    // load configuration from db
    $query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
    foreach ($query->rows as $setting) {
        if (!$setting['serialized']) {
            $pros->set($setting['key'], $setting['value']);
        } else {
            $pros->set($setting['key'], unserialize($setting['value']));
        }
    }
?>