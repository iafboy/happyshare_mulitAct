<?php
class ControllerCommonTemp extends MyController {

//    private $temp_path = DIR_IMAGE_ALL.'temp';
//    public static $index = 1;

    public function index() {

    }

    public function uploadFile(){
        $array = array();
        $posts = $this->request->post;
        $file_name = $posts['file_name'];
        $directory = $posts['directory'];
        $directory = rtrim(str_replace(array('../', '..\\', '..'), '', $directory), '/');
        $file = $this->request->files['file'];
        $extension = parseExtension($file['name']);
        $store_file_name = $directory.'/'.$file_name.'_'.time().'.'.$extension;
        echo $store_file_name;
        if(copy($file['tmp_name'],DIR_IMAGE.$store_file_name)===true){
            $array['success'] = true;
            $array['file_path'] = $store_file_name;
        }else{
            $array['success'] = false;
            $array['error'] = '上传失败！';
        }
        writeJson ($array);
    }

    private function getTempImageUrl($filename){
        return HTTP_CATALOG.'image/'.'temp/'.$filename;
    }

    public function genericuploader(){
        $json = array();

        // Make sure we have the correct directory
        if (isset($this->request->get['directory'])) {
            $directory = rtrim(DIR_UPLOADS . str_replace(array('../', '..\\', '..'), '', $this->request->get['directory']), '/');
            $file_url = rtrim(DIR_UPLOADS_URL . str_replace(array('../', '..\\', '..'), '', $this->request->get['directory']), '/');
        } else {
            $directory = DIR_UPLOADS;
            $file_url = DIR_UPLOADS_URL;
        }
        // Check it's a directory
        if (!is_dir($directory)) {
            $json['error'] = $this->language->get('error_directory');
        }

        if (!$json) {
            if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
                // Sanitize the filename
                $filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

                // Validate the filename length
                if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
                    $json['error'] = $this->language->get('error_filename');
                }

                // Allowed file extension types
               /* $allowed = array(
                    'jpg',
                    'jpeg',
                    'gif',
                    'png'
                );
                if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }*/

                // Allowed file mime types
                /*$allowed = array(
                    'image/jpeg',
                    'image/pjpeg',
                    'image/png',
                    'image/x-png',
                    'image/gif'
                );
                if (!in_array($this->request->files['file']['type'], $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }*/

                // Check to see if any PHP files are trying to be uploaded
                $content = file_get_contents($this->request->files['file']['tmp_name']);

                if (preg_match('/\<\?php/i', $content)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Return any upload error
                if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
                }
            } else {
                $json['error'] = $this->language->get('error_upload');
            }
        }

        if (!$json) {
            $extension = parseExtension($filename);
            if(!isset($this->request->post['file_name']) || empty($this->request->post['file_name'])){
                $json['error'] = '上传文件名为空';
            }else{
                $file_name = $this->request->post['file_name'];
                $filename = $file_name.'.'.$extension;
                if(move_uploaded_file($this->request->files['file']['tmp_name'], $directory . '/' . $filename)===true){
                    $file_path = str_replace(DIR_UPLOADS_URL,'',$file_url);
                    $json['success'] = true;
                    $json['file_url'] = $file_url . '/' . $filename;
                    $json['file_path'] = $file_path . '/' . $filename;
                }else{
                    $json['error'] = '上传失败';
                }
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function fileuploader(){
        $this->load->language('common/filemanager');
        $json = array();
        // Check user has permission
        if (!$this->user->hasPermission('modify', 'common/filemanager')) {
            $json['error'] = $this->language->get('error_permission');
        }

        $file_type = 'IMAGE';
        if(is_valid($this->request->post['file_type'])){
            $file_type = $this->request->post['file_type'];
        }
        // Make sure we have the correct directory
        if (isset($this->request->get['directory'])) {
            $directory = rtrim(DIR_IMAGE . str_replace(array('../', '..\\', '..'), '', $this->request->get['directory']), '/');
            $file_url = rtrim(DIR_IMAGE_URL . str_replace(array('../', '..\\', '..'), '', $this->request->get['directory']), '/');
        } else {
            $directory = DIR_IMAGE;
            $file_url = DIR_IMAGE_URL;
        }
        // Check it's a directory
        if (!is_dir($directory)) {
            $json['error'] = $this->language->get('error_directory');
        }

        if (!$json) {
            if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
                // Sanitize the filename
                $filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

                // Validate the filename length
                if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
                    $json['error'] = $this->language->get('error_filename');
                }

                // Allowed file extension types
                $allowed = array(
                    'jpg',
                    'jpeg',
                    'gif',
                    'png'
                );
                if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Allowed file mime types
                $allowed = array(
                    'image/jpeg',
                    'image/pjpeg',
                    'image/png',
                    'image/x-png',
                    'image/gif'
                );
                if (!in_array($this->request->files['file']['type'], $allowed)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Check to see if any PHP files are trying to be uploaded
                $content = file_get_contents($this->request->files['file']['tmp_name']);

                if (preg_match('/\<\?php/i', $content)) {
                    $json['error'] = $this->language->get('error_filetype');
                }

                // Return any upload error
                if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                    $json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
                }
            } else {
                $json['error'] = $this->language->get('error_upload');
            }
        }

        if (!$json) {
            $extension = parseExtension($filename);
            if(!isset($this->request->post['file_name']) || empty($this->request->post['file_name'])){
                $json['error'] = '上传文件名为空';
            }else{
                $delete_path = $this->request->post['delete_path'];
                if(is_file(DIR_IMAGE.$delete_path)){
                    FileUtil::unlinkFile(DIR_IMAGE.$delete_path);
                }
                $file_name = $this->request->post['file_name'];
//                $filename = $file_name.time().'.'.$extension;
                $filename = $file_name.'.'.$extension;
                if(move_uploaded_file($this->request->files['file']['tmp_name'], $directory . '/' . $filename)===true){
                    $image_path = str_replace(DIR_IMAGE_URL,'',$file_url);
//                    if(is_valid($this->request->post['resize_width']) && is_valid($this->request->post['resize_height'])){
//                        $this->load->model('tool/image');
//                        $width = $this->request->post['resize_width'];
//                        $height = $this->request->post['resize_height'];
//                        $resize_image = $this->model_tool_image->resize($image_path. '/' . $filename,$width,$height,true);
//                    }
                    $json['success'] = true;
//                    if(is_valid($resize_image)){
//                        $json['file_path'] = $resize_image;
//                    }else{
                        $json['file_path'] = $file_url . '/' . $filename;
//                    }
                    $json['image_path'] = $image_path . '/' . $filename;
                }else{
                    $json['error'] = '上传失败';
                }
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }




}