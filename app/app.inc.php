<?php
if(!defined('TOKEN')){
	 exit('illegal access : app');
}

class App extends Top{

	public function __construct(){
		parent::__construct();
	}

	public function appinit(){
		//浏览器访问
		if(isset($_SERVER['HTTP_USER_AGENT'])){
			$menu = DB::DBGetall(" SELECT * FROM bns_menu WHERE state=1 ");
			$this->assign('menu',$menu);
			$this->assign('url',$this->conf['nowurl']);
			$this->TplCache(__PUBTPL__.'nav.html');	//加载缓存,如果要在模板中使用<incache>标签则需要先缓存
			$this->AppIndex();
		}
	}

	public function AppIndex(){
		$REQUEST_URI = $_SERVER['REQUEST_URI']; //路径URL
		if(!empty($_GET)){
			$keys = array_keys($_GET);
			if(in_array('app', $keys)) $this->AppRoute();
		}
		$this->ShowMenuHome();	//默认首页
	}

	public function ShowMenuHome(){
		$this->display('index3.html');
	}

	//路由
	public function AppRoute(){
		
		$app = $_GET['app'] ? $_GET['app'] :'index';	

		if($this->checkPermission($app) !== true && $app !== 'index'){
			$this->ShowErrorMsg('无权访问');
		}

		$appfile = strtolower(APP.$_GET['app'].'.inc.php');
		$App     = ucfirst($app);
		$Action  = $_GET['action'] ? ucfirst($_GET['action']) :'';

		if(is_file($appfile)) $res = require_once $appfile;
		else $this->ShowErrorMsg("the requested URL was not found on this server:linux区分大小写所致");

		$obj = new $App();
		$defaut = $App.'Index';
		$_GET['action'] ? $obj->$Action() : $obj->$defaut(); //默认跳转
		exit;
	} 


	public function checkPermission($app){

		if($app == 'admin') return true;
		$obj = $this->NewObj('admin');
		$uid = $obj->GetUid();

		$appEncode = $this->TopMcDecode(strtolower($app),'encode',$this->conf['mkey']);

		$AdminMenuCode = DB::DBGetValue("SELECT menu_permissions FROM {$this->conf['prefix']}admin WHERE uid=$uid ");
		$MenuCodeArray = explode(',', $AdminMenuCode);

		// echo '<pre>';
		// var_dump($appEncode);
		// var_dump($AdminMenuCode);exit('');

		return in_array($appEncode, $MenuCodeArray);
	}
	
	/**
	 * URL重定向
	 * @param string $url 重定向的URL地址
	 * @param integer $time 重定向的等待时间（秒）
	 * @param string $msg 重定向前的提示信息
	 * @return void
	 */
	function Jump($url, $time=0, $msg='') {
	    //多行URL地址支持
	    $url = TOP.str_replace(array("\n", "\r"), '', $url);
	    $msg = $this->ShowErrorMsg($msg,true);
	    
	    if (empty($msg))
	        $msg    = "系统将在{$time}秒之后自动跳转到{$url}！";
	    if (!headers_sent()) {
	        // redirect
	        if (0 === $time) {
	            header('Location: ' . $url);
	        } else {
	            header("refresh:{$time};url={$url}");
	            echo($msg);
	        }
	        exit();
	    } else {
	        $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
	        if ($time != 0)
	            $str .= $msg;
	        exit($str);
	    }
	}

	
	/**
	 * 加密函数
	 * @param string $str   需要处理的内容
	 * @param string $model 加密[encode]或者解密[decode]
	 * @param string $key   密匙
	 */
	public function TopMcDecode($str,$model='encode',$key=''){
		if(strlen($key) ==4 ) $key = str_repeat($key, 4);
		$iv = $key;
		if($model == 'encode'){
			return str_replace('=', '', base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $str ,MCRYPT_MODE_CBC, $iv)));
		}elseif($model == 'decode'){
			$str = $str.str_repeat('=', (4-strlen($str)%4));
			return @mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($str) , MCRYPT_MODE_CBC,$iv);
		}
	}


	/**
	 * 分解字符串或者组织数组为字符串
	 * @param [type] $income    [description]
	 * @param string $delimiter [description]
	 */
	public function TopResolve($income,$delimiter = ','){
		if(is_array($income)){
			return implode($delimiter, $income);
		}else{
			return explode($delimiter, rtrim($income,$delimiter));
		}
	}


	/**
	 * 返回对象	
	 * @param [type] $app [description]
	 */
	public function NewObj($app){

		$appfile = strtolower(APP.$app.'.inc.php');
		$App     = ucfirst($app);
		if(is_file($appfile))  require_once $appfile;
		else $this->ShowErrorMsg("failure to get object");
		return new $App();
	}


	/**
	 * 不是很有用的一个方法
	 * @param [type] $postdata [description]
	 * @param array  $fields   [description]
	 */
	public function AcceptPostData($field='',$defaultValue =0){
		//无参数则返回POS数据
		if(!$field){
			$data = $_POST;
			// foreach($data as &$v) $v = htmlspecialchars($v); 		
			return $data;
		}else{
			$value = isset($_REQUEST[$field]) ? $_REQUEST[$field] : $defaultValue;
			return $value;
		}
	}


	/**
	 * json返回信息
	 * @param int $state 0:正常返回,100:普通错误,3306:Mysql执行失败
	 * @param Mix $msg   返回的数据
	 */
	public function JsonReturnMsg($state=0,$msg='Successful!'){
		$data['state'] = $state;
		$data['msg'] = $msg;
		echo json_encode($data);
		exit;
	}


	/**
	 *  
	 * @param mix $need    需要转码的字符串或二维数组或多维数组
	 * @param string $before_charset 转码前的字符编码
	 * @param string $after_charset  转码后的字符编码
	 */
	public function ChangeCharset($need,$before_charset,$after_charset){

		if(is_array($need)){

			foreach ($need as $key => &$v) {

				if(is_array($v)){
					 $v = $this->ChangeCharset($v,$before_charset,$after_charset);
				}else{
					 $v = iconv($before_charset, $after_charset, $v);
				}
			}

		}else{
			$need = iconv($before_charset, $after_charset, $need);
		}

		return $need;
	}
	

	/**
	 * 生成分页代码
	 * @param string $sql     获取所有数据的sql语句
	 * @param string $count   总记录数
	 * @param string $jumpURL 跳转链接
	 * @param int    $size    每页显示的记录数
	 * @param int    $page    当前页数
	 */
	public function  CreatePage($sql,$count,$jumpURL,$size=5,$page=1,$desc='id'){

		isset($_GET['page']) ? $page = $_GET['page'] :$page = 1;
		$pagecount = ceil($count/$size);	//总页数
		$start = ($page-1)*$size;
		$sql .= " ORDER BY $desc DESC ";
		$sql .= " LIMIT $start,$size ";

		$page_string = '<ul class="pagination">';

		if($pagecount == 1){
			 // $page_string .= '第一页|下一页|';
			 $page_string .= ' <li><a href="#">&laquo;</a></li>';
			 $page_string .= ' <li><a href="#">1</a></li>';
			 $page_string .= ' <li><a href="#">&raquo;</a></li>';

		}elseif($page == 1){

			$page_string .= '<li><a href="#">首页</a></li>';
			$page_string .= "<li><a href='{$jumpURL}&page=".($page+1)."'>下一页</a></li>";
			$page_string .= "<li><a href='{$jumpURL}&page=$pagecount'>尾页</a></li>";

		}elseif($page == $pagecount || $pagecount == 0){
			
			$page_string .= "<li><a href='{$jumpURL}&page=1'>首页</a></li>";
			$page_string .= "<li><a href='{$jumpURL}&page=".($page-1)."'>上一页</a></li>";
			$page_string .= '<li><a href="#">尾页</a></li>';

		}else{
			$page_string .= "<li><a href='{$jumpURL}&page=1'>首页</a></li>";
			$page_string .= "<li><a href='{$jumpURL}&page=".($page-1)."'>上一页</a></li>";
			$page_string .= "<li><a href='{$jumpURL}&page=".($page+1)."'>下一页</a></li>";
			$page_string .= "<li><a href='{$jumpURL}&page=$pagecount'>尾页</a></li>";
		}

		$page_string .= '</ul>';
		$return['sql'] = $sql;
		$return['page_string'] = $page_string ;
		$return['pagecount'] = $pagecount;

		return $return;
	}


	/**
	 * 生成详细的分页代码
	 * @param string $sql     获取所有数据的sql语句
	 * @param string $count   总记录数
	 * @param string $jumpURL 跳转链接
	 * @param int    $size    每页显示的记录数
	 * @param int    $page    当前页数
	 */
	public function CreatePageDetails($sql,$count,$jumpURL,$size=5,$page=1,$desc='id'){

		isset($_GET['page']) ? $page = $_GET['page'] :$page = 1;
		$pagecount = ceil($count/$size);	//总页数
		$start = ($page-1)*$size;
		$sql .= " ORDER BY $desc DESC ";
		$sql .= " LIMIT $start,$size ";

		$page_string = '<ul class="pagination">';

		//1.总页数大于10    首页..6|7|8|9|十|11|12|13|14..尾页
		//2.总页数小雨10    1|2|3|4|5|6|7|8|9|10
		if( $pagecount <10 ){
			for ($i=1; $i <=$pagecount ; $i++) { 

				if($i != $page){
					$page_string .= "<li><a href='{$jumpURL}&page=$i'>$i</a></li>";
				}else{
					$page_string .= "<li><a href='#' style='color:red'>$i</a></li>";
				}
			}
		}elseif($pagecount>=10){

			if($page<=5){

				for ($i=1; $i <=10 ; $i++) { 
					if($i == $page){
						$page_string .= "<li><a href='#' style='color:red'>$i</a></li>";
					}else{
						$page_string .= "<li><a href='{$jumpURL}&page=$i'>$i</a></li>";
					}
				}

			}elseif($pagecount-$page <=5){

				$last = $pagecount-$page;
				$page_string .= "<li><a href='{$jumpURL}&page=1'>首页</a></li>";
				$page_string .= "<li><a href='#'>...</a></li>";
				$page_string .="<li><a href='{$jumpURL}&page=".($page-4)."'>".($page-4)."</a></li>";
				$page_string .="<li><a href='{$jumpURL}&page=".($page-3)."'>".($page-3)."</a></li>";
				$page_string .="<li><a href='{$jumpURL}&page=".($page-2)."'>".($page-2)."</a></li>";
				$page_string .="<li><a href='{$jumpURL}&page=".($page-1)."'>".($page-1)."</a></li>";
				$page_string .="<li><a href='#' style='color:red'>$page</a></li>";
				for ($i= $page; $i <=$pagecount ; $i++) { 
					if($i != $page)	$page_string .= "<li><a href='{$jumpURL}&page=$i'>$i</a></li>";
				}
			}else{
				$page_string .= "<li><a href='{$jumpURL}&page=1'>首页</a></li>";
				$page_string .= "<li><a href='{$jumpURL}&page=".($page-1)."'>&lsaquo;&lsaquo;&lsaquo;</a></li>";
				$page_string .="<li><a href='{$jumpURL}&page=".($page-4)."'>".($page-4)."</a></li>";
				$page_string .="<li><a href='{$jumpURL}&page=".($page-3)."'>".($page-3)."</a></li>";
				$page_string .="<li><a href='{$jumpURL}&page=".($page-2)."'>".($page-2)."</a></li>";
				$page_string .="<li><a href='{$jumpURL}&page=".($page-1)."'>".($page-1)."</a></li>";
				$page_string .="<li><a href='#' style='color:red'>$page</a></li>";
				$page_string .="<li><a href='{$jumpURL}&page=".($page+1)."'>".($page+1)."</a></li>";
				$page_string .="<li><a href='{$jumpURL}&page=".($page+2)."'>".($page+2)."</a></li>";
				$page_string .="<li><a href='{$jumpURL}&page=".($page+3)."'>".($page+3)."</a></li>";
				$page_string .="<li><a href='{$jumpURL}&page=".($page+4)."'>".($page+4)."</a></li>";
				$page_string .= "<li><a href='{$jumpURL}&page=".($page+1)."'>&rsaquo;&rsaquo;&rsaquo;</a></li>";
				$page_string .= "<li><a href='{$jumpURL}&page=$pagecount'>尾页</a></li>";
			}
		}

		$page_string .= '</ul>';
		$return['sql'] = $sql;
		$return['page_string'] = $page_string ;
		$return['pagecount'] = $pagecount;

		return $return;
	}


	public function dump($data,$var=false){
		if(empty($data)){
			echo '数据为空';
			exit;
		}
		if(is_array($data)){
			echo '<pre>';
			echo '共有'.count($data).'条记录';
			echo '<hr>';
			if($var) var_dump($data);
			else print_r($data);
		}else{
			echo $data;
		}
		exit;
	}


}
