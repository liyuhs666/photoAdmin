<?php


class Top{

	public $MySQL;
	public $conf;
	public $app_conf;

	public function __construct(){
		$this->Topinit();	//初始化成员变量
	}

	private function Topinit(){
		$res = require_once 'mysql.class.php';
		#$this->MySQL = new DB();
		// new DB();
		$conf = require 'config.php';
		$this->conf = $conf;

		if($res) DB::DBConnect($this->conf['dburl'],$this->conf['dbusername'],$this->conf['skrillex'],$this->conf['database'],'utf8'); 
		
	}

	/**
	 * 模板输出
	 * @param  [type] $tplname [description]
	 * @return [type]          [description]
	 */
	public function display($tplname){

		if(isset($_SERVER['HTTP_USER_AGENT'])){	//非浏览器访问不显示

			if(!strripos($tplname,'.php')){
				$position = strripos($tplname,'.html');
				if(!$position) $tplname.= '.html';
			}

			$_REQUEST['app'] ? $app = $_REQUEST['app'] :$app = 'app';
			$path  = strtolower(TPL.$app.DIRECTORY_SEPARATOR.$tplname);

			if(!is_file($path)) $this->ShowErrorMsg('File is not exists');

			echo $this->AssignDataForTPL($path,true,false,true); 
			exit('');
		}
	}

	/**
	 * 将某个模板文件进行编译,然后返回编译文件的路径,需要在模板文件中引用使用<incache>标签
	 * @param [type] $tplpath [description]
	 */
	public function TplCache($tplpath){
		$html = $this->AssignDataForTPL($tplpath,true,false,false);
		
		$filename = basename($tplpath);
		$tplcache_name = TCACHE.$filename;
		$res = file_put_contents($tplcache_name, $html);

		if($res){
			return $tplcache_name;
		}else{
			$this->ShowErrorMsg('权限不足,无法写入模板');
		}
	}

	/**
	 * 生成编译文件或返回编译内容
	 * @param [type]  $path  [description]
	 * @param boolean $cache [description]
	 * @param boolean $flag  [description]
	 * @param boolean $save  [description]
	 */
	public function AssignDataForTPL($path,$cache=true,$flag=false,$save=true){
		//启用skri模板引擎
		require_once CLS.'trantpl.class.php';
		$tranObject = new Trantpl();

		foreach($_SESSION as $k=>$v){
			$tranObject->Set($k,$v);
		}

		return $tranObject->TranslateTPL($path,$this->conf['cache'],$flag,$save);	//厉害了这里
	}

	public function assign($name,$data){
		unset($_SESSION[$name]);
		$this->session($name,$data);
	}

	/**
	 * set or get session
	 * @param  string $first  session name
	 * @param  Mix $second Null or data
	 * @return 1.if is set paramTwo return nothing 2.if not set param2,return session data
	 */
	public function session($first,$second =''){
		//get session 
		if(empty($second)){
			$key = $first;
			if(isset( $_SESSION[$key])){
				return  $_SESSION[$key];
			}else{
				return false;
			}

		}else{	//set session
			$key  = $first;$data = $second;
			$_SESSION[$key] = $data;
		}
	}

	/**
	 * 注销session
	 * @param [type] $name [description]
	 */
	public function UnsetSession($name){
		if($name=='clear_all') unset($_SESSION);
		unset($_SESSION[$name]);
	}


	/**
	 * 显示错误信息
	 * @param [type] $msg [description]
	 */
	public function ShowErrorMsg($msg,$getMsg=false){

		$show = "<table style=\"margin: 50px auto;border: 1px #ccc solid;border-collapse: collapse;width: 500px;\"><tr>
			<th style=\"background: #c00;text-align: center;border: 1px #ccc solid;font-size: 16px;color: #fff;line-height: 25px\">提示</th></tr><tr><td style=\"text-align: center;border: 1px #ccc solid;font-size: 14px;color: #369;line-height: 55px\">$msg</td></tr></table>";
		if($getMsg == true){
			return $show;
		}
		echo $show;
		$url =$_SERVER['HTTP_REFERER'];	//返回来源页面
		header("refresh:2;url=$url");
		exit;
	}

	
    
    /**
     * load AppConfig file and Return array
     * @param string $conf_name AppConfigName
     */
	public function LoadConf($conf_name){

		$file = $this->conf['CONF'].$conf_name;
		if(is_file($file)){

			$res =  require_once $file;
			if(is_array($res))	return $res;
		}else{

			$this->ShowErrorMsg('the requested config file was not found on this server');
		}
	}

	/**
	 * Load class and return ClassObject
	 * @param string $classname objectname or object file name
	 */
	public function LoadClass($classname){

		if(strrpos($classname, '.class.php') === false){
			$path = $this->conf['CLASS'].$classname.'.class.php';
			$class = ucfirst($classname);
		} else{
			$path = $this->conf['CLASS'].$classname;
			$class = ucfirst(str_replace('.class.php', '', $classname));
		}
		echo '$classname:'.$classname;echo '<br/>';
		echo '$path:'.$path;echo '<hr>';
		echo '$class:'.$class;

		if(is_file($path)){
			include_once $path;
			$object = new $class();
			return $object;
		}else{
			$this->ShowErrorMsg("The require class was not found");
		}
	}

	/**
	 * 加载类
	 * @param [type] $name [description]
	 */
	public function LoadDic($name){
		$data = $this->DBGetall("SELECT * FROM dic WHERE name='$name'");
		return $data;
	}

	
		
	


}