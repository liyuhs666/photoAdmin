<?php
if(!defined('TOKEN')){
	 exit('illegal access : app');
}

class Sii extends App{


	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->SiiIndex();
	}

	public function SiiIndex(){
		$this->display('siihome');
	}

	//非添加字段请勿使用下划线
	public function AdminAdd(){
		$appname = $_POST['appname'];
		$appcomment = $_POST['appcomment'];
		$create = isset($_POST['create']) ? 1:0;
		$create_tpl = isset($_POST['tpl']) ? 1:0;
		$fields = array();

		if(!$appname) $this->ShowErrorMsg('appname con\'t be empty');

		//All fied like  "field_id type_id length_id"
		foreach ($_POST as $key => $v) {
			if(strpos($key, '_')){
				$arr = explode('_', $key);
				$field = $arr[1];
				$other = $arr[0];
				// echo '内容:'.$arr[0];echo ' 字段:'.$arr[1];echo '<hr>';
				$fields["$field"]["$other"] = $v;
			}
		}
		// echo '<pre>';
		// var_dump($fields);exit('');IF NOT EXISTS
		$sql = " CREATE TABLE  {$this->conf['prefix']}$appname( ";
		foreach ($fields as $key => $v) {

			if($key == 'id'){
				$v['length'] ? $l =  '('.$v['length'].')' : '';
				$sql .= '`'.$v['field'].'`'.' '.$v['type'].$l.'PRIMARY KEY AUTO_INCREMENT '.',';
			}

			if($key != 'id'){
				$v['length'] ? $l =  '('.$v['length'].')' : '';
				$sql .= '`'.$v['field'].'`'.' '.$v['type'].$l.',';
			}
		}
		$sql = rtrim($sql,',');
		$sql .=')charset=utf8';

		$create ?  $res = DB::DBExec($sql) : $res = true;
		$this->InsertApp($appname,$appcomment,$fields);  //添加记录 toosimple
		if(!$res) exit("创建表失败<br>$sql");

		$this->CreateAppInc($appname);	//创建文件
		if($create_tpl) $this->CreateTpl($appname);	//创建模板文件 
		$this->ShowErrorMsg('完毕');
		
	}

	public function Siilist(){
		$data = DB::DBGetAll("SELECT * FROM {$this->conf['prefix']}app");
		// $this->UnsetSession('list');
		$this->assign('list',$data);
		$this->display('sii_list');	
	}

	public function InsertApp($appname,$appcomment,$fields){
		$app_fields = array_keys($fields);
		foreach($app_fields as $f) $str.= $f.',';
		$data['app_field'] = rtrim($str,',');
		$data['appname'] = $appname;
		$data['app_comment'] = $appcomment;
		$data['function'] ='';
		$data['ctime'] = date("Y-m-d");

		echo '<pre/>';print_r($data);
		DB::DBInsert("{$this->conf['prefix']}app",$data);
 	}

	//创建app文件
	public function CreateAppInc($appname){
		$appfile = APP.$appname.'.inc.php';
		if(!is_file($appfile)){
			$handle = fopen($appfile, 'w');
			// fwrite($handle, '<?php');
			$this->WritePHP($handle,$appname);
			fclose($handle);
		}else{
			$this->ShowErrorMsg('Inc已存在');
		}

	}

	public function Siidelete(){
		$id = $_GET['id'];
		$rows = DB::DBGetOne("SELECT * FROM {$this->conf['prefix']}app WHERE id=$id ");	
		$appname = $rows['appname'];	
		if($rows){
			$res1 = DB::DBDelete($this->conf['prefix']."app"," id = $id ");	//1.app表中移除记录
			$res2 = DB::DBExec("DROP TABLE {$this->conf['prefix']}{$appname}"); //2.移除此表
 			if(file_exists(APP.$appname.'.inc.php')) $res3 = @unlink(APP.$appname.'.inc.php');	//3.删除文件
 			if(file_exists(TPL.$appname.'_index.html')) $res4 = @unlink(TPL.$appname.'_index.html');	//4.删除文件
 			if($res1) $str .= "移除app表记录成功<hr>";
 			if($res2) $str .= "移除数据表成功<hr>";
 			if($res3) $str .= "删除inc文件成功<hr>";
 			if($res4) $str .= "删除tpl文件成功<hr>";

 			$this->UnsetSession('list');
 			$this->ShowErrorMsg($str);
		}
		$this->UnsetSession('list');
	}

	public function WritePHP($handle,$appname){
		$classname = ucfirst($appname);
		$content = "<?php\n";
		$content .= "if(!defined('TOKEN')){ \n	 exit('illegal access : app'); \n}";
		$content .= "\n";
		$content .= "class $classname extends App{";
		$content .= "\n\n";
		$content .= "	public function __construct(){\n";
		$content .= "		parent::__construct();\n";
		$content .= "	}\n\n";
		$content .= "	public function AppIndex(){";
		$content .= "\n\n";
		$content .= "	}";
		$content .= "\n\n";
		$content .= "}";
		fwrite($handle, $content);
	}

	public function CreateTpl($appname){
		$html = "<!DOCTYPE html>\n<html lang='en'>\n<head>\n<meta charset='UTF-8'>\n<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n<title>$appname</title>\n<include src='head.html'>\n<style type='text/css'>\n</style>\n</head>\n<body>\n\n<incache src='nav.html'>\n\n</body>\n</html>";
		$file = TPL.$appname.'_index.html';
		$f = fopen($file, 'w');
		fwrite($f, $html);
		fclose($f);
	}


	public function SiiCmd(){
		$cmd[] = 'crontab -l';
		$cmd[] = '/data/sftp/mysftp/upload/htdocs/script/dm01.sh';
		$cmd[] = '/root/sh/runlog.sh';
	}
}