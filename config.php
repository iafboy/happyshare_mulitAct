<?php

define('LOCAL_IP','www.51hjfx.com');
define('ROOT_PATH','/opt/lampp/htdocs/leshare/');

// HTTP
define('HTTP_SERVER', 'http://'.LOCAL_IP.'/leshare/admin/');
define('HTTP_CATALOG', 'http://'.LOCAL_IP.'/leshare/');

// HTTPS
define('HTTPS_SERVER', 'https://'.LOCAL_IP.'/leshare/admin/');
define('HTTPS_CATALOG', 'https://'.LOCAL_IP.'/leshare/');

// DIR
define('DIR_APPLICATION',     ROOT_PATH.'admin/');
define('DIR_SYSTEM',          ROOT_PATH.'system/');
define('DIR_WAP',             ROOT_PATH.'wap/');
define('DIR_LANGUAGE',        ROOT_PATH.'admin/language/');
define('DIR_TEMPLATE',        ROOT_PATH.'admin/view/template/');
define('DIR_CONFIG',          ROOT_PATH.'system/config/');
define('DIR_IMAGE',           ROOT_PATH.'image/');
define('DIR_CACHE',           ROOT_PATH.'system/cache/');
define('DIR_DOWNLOAD',        ROOT_PATH.'system/download/');
define('DIR_UPLOAD',          ROOT_PATH.'system/upload/');
define('DIR_LOGS',            ROOT_PATH.'system/logs/');
define('DIR_MODIFICATION',    ROOT_PATH.'system/modification/');
define('DIR_CATALOG',         ROOT_PATH.'catalog/');
define('DIR_UPLOADS',         ROOT_PATH.'uploads/');
define('DIR_IMAGE_URL',       HTTP_CATALOG.'image/');
define('DIR_UPLOADS_URL',     HTTP_CATALOG.'uploads/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'lfx');
define('DB_PASSWORD', 'lfx,123');
define('DB_DATABASE', 'lfx');
define('DB_PREFIX', 'mcc_');



