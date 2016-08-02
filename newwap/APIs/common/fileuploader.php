<?php

include_once('../../index.php');

// parse file extension
function parseExtension($filename){
    list($first,$last) = explode('.',$filename);
    return $last;
}

define('FILETYPE_IMAGE',1);
define('FILETYPE_XLS',2);

class FileUploader {


    /**
     *
     * get  :
     *  directory   :: 图片上传目录
     *  delete_path ::　本次要删除的文件， 默认不删除任何文件
     *  file_name   :: 图片存储名称，不带后缀
     * post :
     *
     *
     */
    public function fileuploader(){

        $post = $_POST;
        $files = $_FILES;
        $get = $_GET;
        $json = array();

        // check directory
        if (isset($get['directory'])) {
            $directory = rtrim(DIR_IMAGE . str_replace(array('../', '..\\', '..'), '', $get['directory']), '/');
            $file_url = rtrim(DIR_IMAGE_URL . str_replace(array('../', '..\\', '..'), '', $get['directory']), '/');
        } else {
            $directory = DIR_IMAGE;
            $file_url = DIR_IMAGE_URL;
        }
        // Check it's a directory
        if (!is_dir($directory)) {
            $json['error'] = '上传目录不存在';
        }
        if (!$json) {

            if (!empty($files['file']['name']) && is_file($files['file']['tmp_name'])) {
                // Sanitize the filename
                $filename = basename(html_entity_decode($files['file']['name'], ENT_QUOTES, 'UTF-8'));

                // Validate the filename length
                if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
                    $json['error'] = '文件名长度超出范围：255 异常！';
                }

                // Allowed file extension types
                $allowed = array(
                    'jpg',
                    'jpeg',
                    'gif',
                    'png'
                );
                if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
                    $json['error'] = '错误的图片类型';
                }

                // Allowed file mime types
                $allowed = array(
                    'image/jpeg',
                    'image/pjpeg',
                    'image/png',
                    'image/x-png',
                    'image/gif'
                );
                if (!in_array($files['file']['type'], $allowed)) {
                    $json['error'] = '错误的图片类型';
                }

                // Check to see if any PHP files are trying to be uploaded
                $content = file_get_contents($files['file']['tmp_name']);

                if (preg_match('/\<\?php/i', $content)) {
                    $json['error'] = '错误的图片类型';
                }

                // Return any upload error
                if ($files['file']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = '上传失败';
                }
            } else {
                $json['error'] = '上传失败';
            }
        }
        if (!$json) {
            $extension = parseExtension($filename);
            if(!isset($get['file_name']) || empty($get['file_name'])){
                $json['error'] = '上传文件名为空';
            }else{
                $delete_path = $get['delete_path'];
                if(is_file(DIR_IMAGE.$delete_path)){
                    FileUtil::unlinkFile(DIR_IMAGE.$delete_path);
                }
                $file_name = $get['file_name'];
                $filename = $file_name.'.'.$extension;
                if(move_uploaded_file($files['file']['tmp_name'], $directory . '/' . $filename)===true){
                    $image_path = str_replace(DIR_IMAGE_URL,'',$file_url);
                    $json['success'] = true;
                    $json['file_path'] = $file_url . '/' . $filename;
                    $json['image_path'] = $image_path . '/' . $filename;
                }else{
                    $json['error'] = '上传失败';
                }
            }
        }

        print_r(json_encode($json));
    }

}

$f = new FileUploader();
$f->fileuploader();