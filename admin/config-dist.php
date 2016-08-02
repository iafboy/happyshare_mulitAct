<?php

define('LOCAL_IP','localhost');
define('ROOT_PATH','C:/developer/Wnmp/html/leshare/');

// HTTP
define('HTTP_SERVER', 'http://'.LOCAL_IP.'/leshare/admin/');
define('HTTP_CATALOG', 'http://'.LOCAL_IP.'/leshare/');

// HTTPS
define('HTTPS_SERVER', 'http://'.LOCAL_IP.'/leshare/admin/');
define('HTTPS_CATALOG', 'http://'.LOCAL_IP.'/leshare/');

// DIR
define('DIR_APPLICATION',     ROOT_PATH.'admin/');
define('DIR_SYSTEM',          ROOT_PATH.'system/');
define('DIR_WAP',             ROOT_PATH.'wap/');
define('DIR_LANGUAGE',        ROOT_PATH.'admin/language/');
define('DIR_TEMPLATE',        ROOT_PATH.'admin/view/template/');
define('DIR_CONFIG',          ROOT_PATH.'system/config/');
define('DIR_IMAGE',           HTTP_CATALOG.'image/');
define('DIR_CACHE',           ROOT_PATH.'system/cache/');
define('DIR_DOWNLOAD',        ROOT_PATH.'system/download/');
define('DIR_UPLOAD',          ROOT_PATH.'system/upload/');
define('DIR_LOGS',            ROOT_PATH.'system/logs/');
define('DIR_MODIFICATION',    ROOT_PATH.'system/modification/');
define('DIR_CATALOG',         ROOT_PATH.'catalog/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'zuuka');
define('DB_PASSWORD', 'zuuka');
define('DB_DATABASE', 'mycncart_db');
define('DB_PREFIX', 'mcc_');



