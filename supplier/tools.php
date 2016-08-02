<?php
/**
 * get formatted sql tablename from a table short code
 * @param $table  short code name
 * @param $prefix customized prefix
 * @return string  formatted name
 */
function getTable($table,$prefix){
    if(!$prefix || isset($prefix)){
        $prefix = DB_PREFIX;
    }
    return "`" . $prefix . $table."`";
}
class MyLanguage extends Language{

}
class MyController extends Controller{

    /**
     * @param array $array
     * [
     * 'name' => ['field'=>'用户名','regex' = > '\w{1,10}' ,'msg'=>'用户名不为空,长度为1-10的字符']，
     * 'password' => ['field'=>'密码','regex' = > '\w{1,10}' ,'msg'=>'密码不为空,长度为1-10的字符']，
     * ]
     *
     * @param string $method
     * @return array
     *
     */
    public function validFields($array=array(),$method='post'){
        if($method=='get'){
            $data = $this->request->get;
        }else{
            $data = $this->request->post;
        }
        if(sizeof($array) ==0 ){
            return ['success'=>true];
        }
        foreach($array as $key => $value){
            if(!is_valid($data[$key])){
                if(is_valid($value['msg'])){
                    $msg = $value['msg'];
                }else{
                    $msg = $value['field'].'不为空';
                }
                return ['success'=>false,'errMsg'=>$msg];
            }
        }
        return ['success'=>true];
    }


    /**
     * parse a field from request
     * @param $entry
     * @return null
     */
    public function parseEntry($entry){
        if (isset($this->request->get[$entry])) {
            return $this->request->get[$entry];
        } else {
            return null;
        }
    }

    public function parsePostEntry($entry){
        if (isset($this->request->post[$entry])) {
            return $this->request->post[$entry];
        } else {
            return null;
        }
    }

    /**
     * parse fields from request with given name array
     *
     * @param $array
     * @param $sort  whether parse sort
     * @param $order whether parse order
     * @param $page  whether parse page
     * @return array
     */
    public function parseEntries($array,$sort,$order,$page){
        $results = array();
        foreach($array as $key=>$value){
            if(is_array($value)){
                foreach($value as $v){
                    $results['filter_'.$v] = $this->parseEntry('filter_'.$v);
                }
            }else{
                $results['filter_'.$value] = $this->parseEntry('filter_'.$value);
            }
        }
        if ($sort===true && isset($this->request->get['sort'])) {
            $results['sort'] =  $this->request->get['sort'];
        }
        if ($order===true && isset($this->request->get['order'])) {
            $results['order'] =  $this->request->get['order'];
        }
        if($page===false){

        }else{
            if (isset($this->request->get['page'])) {
                $results['page'] = $this->request->get['page'];
            } else {
                $results['page'] = 1;
            }
            if($results['page'] < 1){
                $results['page'] = 1;
            }
            $results['start'] = ($results['page'] - 1) * $this->config->get('config_limit_admin');
            $results['limit'] = $this->config->get('config_limit_admin');
        }
        return $results;
    }
    public function parsePostEntries($array,$sort,$order,$page){
        $results = array();
        foreach($array as $key=>$value){
            if(is_array($value)){
                foreach($value as $v){
                    $results['filter_'.$v] = $this->parsePostEntry('filter_'.$v);
                }
            }else{
                $results['filter_'.$value] = $this->parsePostEntry('filter_'.$value);
            }
        }
        if ($sort===true && isset($this->request->post['sort'])) {
            $results['sort'] =  $this->request->post['sort'];
        }
        if ($order===true && isset($this->request->post['order'])) {
            $results['order'] =  $this->request->post['order'];
        }
        if($page===false){

        }else{
            if (isset($this->request->post['page'])) {
                $results['page'] = $this->request->post['page'];
            } else {
                $results['page'] = 1;
            }
            if($results['page'] < 1){
                $results['page'] = 1;
            }
            $results['start'] = ($results['page'] - 1) * $this->config->get('config_limit_admin');
            $results['limit'] = $this->config->get('config_limit_admin');
        }
        return $results;
    }
    /**
     *  parse current url from request
     * @param $array
     * @param $sort
     * @param $order
     * @param $page
     * @return string
     */
    //['field_1','field_2']
    public function parseUrl($array,$sort,$order,$page){
        $url = '';
        foreach($array as $key=>$value){
            if(is_array($value)){
                foreach($value as $v){
                    $url = $url. '&filter_'.$v.'='. $this->parseEntry('filter_'.$v);
                }
            }else{
                $url = $url. '&filter_'.$value.'='. $this->parseEntry('filter_'.$value);
            }
        }
        if ($sort===true && isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        if ($order===true && isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        if($page===false){

        }else{
            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }else{
                $url .= '&page=1';
            }
        }
        return $url;
    }
    public function parsePostUrl($array,$sort,$order,$page){
        $url = '';
        foreach($array as $key=>$value){
            $url = $url. '&'.$value.'='. $this->parsePostEntry($value);
        }
        if ($sort===true && isset($this->request->post['sort'])) {
            $url .= '&sort=' . $this->request->post['sort'];
        }
        if ($order===true && isset($this->request->post['order'])) {
            $url .= '&order=' . $this->request->post['order'];
        }
        if($page===false){

        }else{
            if(isset($this->request->post['page'])){
                $url .= '&page=' . $this->request->post['page'];
            }else{
                $url .= '&page=1';
            }
        }
        return $url;
    }

    /**
     * parse breadcrumb html
     * @param $array
     * @return array
     */
    public function parseBreadCrumbs(){
        $breadcrumbs = array();
        $breadcrumbs[] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
            'type' => 'link'
        );
//        $url = $this->parseUrl($array,false,false,false);
        $breadcrumbs[] = array(
            'text' => $this->language->get('heading_title'),
            'type' => 'text'
        );
        return $breadcrumbs;
    }

    public function buildPagination($page,$total,$url,$module_name){
        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link($module_name, 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
        return $pagination;
    }
    public function buildPaginator($page,$total,$url,$module_name,$template){
        $paginator = new Paginator();
        $paginator->setPage($page);
        $paginator->setTotal($total);
        $paginator->setPageSize($this->config->get('config_limit_admin'));
        $paginator->setUrl($this->url->link($module_name, 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL'));
        $paginator->calculate();
        $paginator->setInfo(sprintf($template,$paginator->getStart(),$paginator->getEnd(),$paginator->getTotal(),$paginator->getTotalPages()));
        return $paginator->renderArray();
    }
}


class MyDB {

    private $db;

    public function __construct($driver, $hostname, $username, $password, $database) {
        $class = 'DB\\' . $driver;

        if (class_exists($class)) {
            $this->db = new $class($hostname, $username, $password, $database);
        } else {
            exit('Error: Could not load database driver ' . $driver . '!');
        }
    }

    public function startTransaction(){
        $this->db->getLink()->autocommit(FALSE);
    }
    public function commitTransaction(){
        $this->db->getLink()->commit();
    }
    public function rollbackTransaction(){
        $this->db->getLink()->rollback();
    }

    public function query($sql) {
        return $this->db->query($sql);
    }

    public function escape($value) {
        return $this->db->escape($value);
    }

    public function countAffected() {
        return $this->db->countAffected();
    }

    public function getLastId() {
        return $this->db->getLastId();
    }
}

class MyModel extends Model{

    protected $batch_sql = [];

    protected function executeSql($sql){
        return $this->db->query($sql);
    }

    protected function startTransaction(){
        $this->db->startTransaction();
    }
    protected function rollbackTransaction(){
        $this->db->rollbackTransaction();
    }
    protected function commitTransaction(){
        $this->db->commitTransaction();
    }

    protected function addTransactionBatch($sql){
        if(is_string($sql)){
            $this->batch_sql[] = $sql;
        }
        if(is_array($sql)){
            foreach($sql as $q){
                $this->batch_sql[] = $q;
            }
        }
    }
    protected function executeTransactionBatch(){
        $this->db->startTransaction();
        try {
            foreach($this->batch_sql as $sql){
                $this->db->query($sql);
            }
        } catch (Exception $e) {
            $this->db->rollbackTransaction();
            return false;
        }
        $this->db->commitTransaction();
        return true;
    }

    protected function getBanks(){
        $sql = "SELECT * FROM " . getTable('bank')." v where bank_status != '0' ";
        $query = $this->db->query($sql);
        return $query->rows;
    }
    protected function query($sql){
        $query = $this->db->query($sql);
        return $query;
    }

    protected function queryRows($sql){
        $query = $this->db->query($sql);
        return $query->rows;
    }

    protected function queryCount($sql){
        $query = $this->db->query($sql);
        return $query->row['count'];
    }

    protected function querySingleRow($sql){
        $query = $this->db->query($sql);
        return $query->row;
    }

    protected function getCurrentTime(){
        $sql = 'select current_timestamp as cur_time from dual';
        return $this->querySingleRow($sql)['cur_time'];
    }

    protected function wrapTransaction($sql){
        $this->db->startTransaction();
        try {
            $this->db->query($sql);
        } catch (Exception $e) {
            $this->db->rollbackTransaction();
            return false;
        }
        $this->db->commitTransaction();
        return true;
    }

    protected function queryNextId($table,$id){
        if(!is_valid($id)){
            $id = $table.'_id';
        }
        $sql = "select max(".$id.") 'maxValue' from ". getTable($table)." ";
        $row = $this->querySingleRow($sql);
        return $row['maxValue']+1;
    }

    protected function queryNextNo($table){
        $sql = "select max(".$table."_no) 'maxValue' from ". getTable($table)." ";
        $row = $this->querySingleRow($sql);
        return $row['maxValue']+1;
    }

    protected function queryProductTypes(){
        $sql = "select * from ". getTable('product_type')." where parent_type_id is null or parent_type_id = '' ";
        $query = $this->db->query($sql);
        return $query->rows;
    }
    protected function queryCategories(){
        $sql = "select a.*,b.name as category_name from ". getTable('category')." a , "
            .getTable('category_description')."  b where a.category_id = b.category_id and b.language_id = 1 ";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    protected function queryDict($dict_group,$dict_key){
        $sql = "select * from ". getTable('dict')." where group_name = ".to_db_str($dict_group)." and dkey = ".to_db_str($dict_key);
        return $this->querySingleRow($sql);
    }
    protected function updateDict($dict_group,$dict_key,$dvalue){
        $sql = "update ". getTable('dict')." set dvalue = ".to_db_str($dvalue)." where group_name = ".to_db_str($dict_group)." and dkey = ".to_db_str($dict_key);
        return $this->wrapTransaction($sql);
    }

    protected function queryProductTypeList($is_all){
        $sql = 'select * from '.getTable('product_type');
        if(isset($is_all) && $is_all===true){
        }else{
            $sql .= ' where status = '.to_db_str('1');
        }
        $sql.=' order by product_type_no asc';
        $query = $this->db->query($sql);
        return $query->rows;
    }

    protected function getLastId(){
        return $this->db->getLastId();
    }

}


/**
 * Paginator for ajax
 * Class Paginator
 */
class Paginator{

    private $page;

    private $totalPages;

    private $pageSize = 20;

    private $total;

    private $url;

    private $start;

    private $end;

    private $has_cal = false;

    private $info;

    public function calculate(){
        if($this->has_cal===true){
            return;
        }
        if($this->page <= 1){
            $this->page = 1;
        }
        if($this->total <= 0){
            $this->total = 0;
            $this->page = 1;
            $this->totalPages = 1;
        }else{
            $this->totalPages = ceil( $this->total / $this->pageSize);
            $this->start = 1 +  ($this->page -1) * $this->pageSize;
            $this->end = $this->page*$this->pageSize;
            if($this->end > $this->total){
                $this->end = $this->total;
            }
        }

    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param mixed $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    public function renderArray(){
        if($this->has_cal === false){
            $this->calculate();
        }
        return [
            'page' => $this->page,
            'totalPages' => $this->totalPages,
            'pageSize'=>$this->pageSize,
            'total' => $this->total,
            'url' =>$this->url,
            'start'=>$this->start,
            'end'=>$this->end,
            'info'=>$this->info
        ];
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Paginator constructor.
     */
    public function __construct($pagination)
    {

    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return mixed
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * @param mixed $totalPages
     */
    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param int $pageSize
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

}

class ReturnMsg{

    public $success = false;

    public $data = array();

    public $err_msg = '';

    /**
     * ReturnMsg constructor.
     */
    public function __construct()
    {
    }
    public function writeJson(){
        print_r(json_encode($this));
    }

}

function is_valid($var){
    return isset($var) && !is_null($var) && !empty($var) && strlen(trim((''.$var))) > 0;
}

function to_db_str($var){
    $var = str_replace('"','\\"',$var);
    $var = str_replace("'","\\'",$var);
    return " '".$var."' ";
}
function getTableColumn($name){
    return "`".$name."`";
}
function to_db_int($var){
    return " ".$var." ";
}
function writeJson($array){
    print_r(json_encode($array));
}
function get_img_url($path){
    if(strpos($path,'/')===false){
        return DIR_IMAGE_URL.'default/default-img.png';
    }
    return DIR_IMAGE_URL.$path;
}
function dealImageWithRows($list,$field){
    $new_list = array();
    foreach($list as $row){
        $row[$field] = get_img_url($row[$field]);
        $new_list[] = $row;
    }
    return $new_list;
}
// parse file extension
function parseExtension($filename){
    list($first,$last) = explode('.',$filename);
    return $last;
}

/**
 * @param $file
 * @param $fields
 * [
 * [name:'field1',col:0],
 * [name:'field2',col:1],
 * [name:'field3',col:2],
 * [name:'field4',col:3]
 * ]
 * @param $sheet
 * xls sheet number
 * @return array
 * [
 *  [field1:'value1',field2:'value2',field3:value3,field4:value4],
 *  [field1:'value1',field2:'value2',field3:value3,field4:value4],
 *  [field1:'value1',field2:'value2',field3:value3,field4:value4],
 *  [field1:'value1',field2:'value2',field3:value3,field4:value4]
 * ]
 *
 */
function readArrayFromXls($file,$fields,$sheet=0,$start_row = 2){
    if(!is_file($file)){
        return [];
    }
	$xls = new Spreadsheet_Excel_Reader(); 
    $xls->setOutputEncoding('utf-8');  //设置编码 
    $xls->read($file);  //解析文件
    $results = [];
    for ($i=$start_row; $i<=$xls->sheets[$sheet]['numRows']; $i++) {
        $row = [];
        foreach($fields as $field){
            $row[$field['name']] =
                 $xls->sheets[$sheet]['cells'][$i][$field['col']];
        }
        $results[] = $row;
    }
    return $results;
}



/**
 *
 * @param numStr
 * @param decimal opcity to keep
 * @param tf whether remove '0' after '.'
 * @returns {*}
 */
function parseFormatNum($numStr,$decimal,$tf){
    if(is_valid($numStr)){
        $numStr = $numStr . '';
        $dealStr =  getNumberWithDecimal($numStr,$decimal);
    }else {
        $dealStr = getNumberWithDecimal("0", $decimal);
    }
    if($tf !== false){
        if(strpos($dealStr,'.') != false){
            $pos = strpos($dealStr,'.');
            $bool= false;
            for($i = $pos+1;$i<strlen($dealStr);$i++){
                if($dealStr[$i] != '0'){
                    $bool = true;
                }
            }
            if(!$bool){
                $dealStr = substr($dealStr,0,$pos);
            }
        }
    }
    return $dealStr;
}
function getNumberWithDecimal($numStr,$decimal){
    if(!$decimal){
        $decimal = 0;
    }
    //8.34
    //6
    $index = strpos($numStr,'.'); // 1
    $length = strlen($numStr);  // 4
    $decimalLen = ($length-1) - $index;
    if($index== false){

        if($decimal >0){
            $numStr .= '.';
            for($i = 0;$i < $decimal;$i++){
                $numStr .= '0';
            }
        }

        return $numStr;
    }else{
        //99.0
        //888888888.301
        //9999999.80
        if($decimalLen > $decimal){
            $numStr = substr($numStr,0,$length-($decimalLen-$decimal)-1);
        }else if($decimalLen < $decimal){
            for($i = 0;$i < $decimal-$decimalLen;$i++){
                $numStr .= '0';
            }
        }else{
            return $numStr;
        }
    }
    return $numStr;
}

function getNumberOpacity($str){
    if(!is_valid($str)){
        return 0;
    }
    $str = trim($str . '');
    if(strpos($str,'.') == false){
        return 0;
    }
    $len = strlen($str);
    $pos = strpos($str,'.');
    return $len- 1 -$pos;
}


function DoPost($url, $post = null) {
    if (is_array($post)) {
        ksort($post);
        $content = http_build_query($post);
        $content_length = strlen($content);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' =>
                    "Content-type: application/x-www-form-urlencoded\r\n" .
                    "Content-length: $content_length\r\n",
                'content' => $content
            )
        );
        return file_get_contents($url, false, stream_context_create($options));
    }
}

function stringfyArray($arr,$sep){
    if(!is_valid($sep)){
        $sep = ',';
    }
    if($arr && is_array($arr) && sizeof($arr) > 0){
        $arrStr = '';
        for($i = 0; $i < sizeof($arr); $i ++){
            $arrStr = $arrStr . $arr[$i] . $sep;
        }
        if(is_valid($arrStr)){
            $arrStr = substr($arrStr,0,strlen($arrStr)-strlen($sep)) ;
        }
        return $arrStr;
    }else{
        if($arr){
            return $arr.'';
        }
        return '';
    }
}


include_once 'entries.php';
include_once 'excel.php';
include_once 'FileUtil.php';
include_once 'library/Excel/reader.php';
