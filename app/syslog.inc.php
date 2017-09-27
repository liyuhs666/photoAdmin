<?php

/* 含有自带key的加密log
 * 
 * 
 */

if(!defined('TOKEN')){
	 exit('illegal access : app');
}

class Syslog extends App{

	private $app_tpl_dir = 'syslog/';	//app模板目录

	public function __construct(){
		parent::__construct();
	}

	//系统日志首页
	public function SyslogIndex(){

		//获取页数,生成分页代码
		isset($_GET['page']) ?  $page = $_GET['page'] :$page = 1;
		isset($_GET['month']) ? $month = $_GET['month'] :$month = 0;

		if($month){
			 $where = " WHERE month=$month ";
			 $addUrl = "&month=$month";
		}else{
			 $where ='';
			 $addUrl = '';
		}  

		$sql = "SELECT * FROM {$this->conf['prefix']}syslog".$where;
		$count = DB::DBGetValue("SELECT count(*) FROM {$this->conf['prefix']}syslog".$where);
		$jumpURL = 'index.php?app=syslog'.$addUrl;

		$return = $this->CreatePageDetails($sql,$count,$jumpURL,$size=15,$page);

		//返回值
		$sql = $return['sql'];
		$data = DB::DBGetAll($sql);
		$data = $this->dealData($data);

		$this->AssignMonth();

		$this->assign('list',$data);
		$this->assign('page',$page);
		$this->assign('pagecode',$return['page_string']);
		$this->assign('pagecount',$return['pagecount']);
		$this->assign('count',$count);
		if($month) $this->assign('now_month',substr_replace($month,'年',4,0).'月');
		$this->display("syslog.html");
	}

	/**
	 * 处理数据
	 * @param  [type] $data [description] 
	 * @return [type]       [description]
	 */
	public function dealData($data){

		foreach ($data as $key => &$v) {
			$v['time'] = date('Y-m-d',$v['create_time']);
			$v['typename'] = DB::DBGetValue(" SELECT typename FROM {$this->conf['prefix']}logtype WHERE id={$v['logtype']} ");
			$v['tag'] = rtrim($v['tag'],',');
		}
		return $data;
	}

	public function detail(){

		$id = $_REQUEST['id'];
		$this->PageViewStatistics($id);	//统计页面UV,PV等

		$data = DB::DBGetOne("SELECT id,title,create_time,img,views,auth FROM {$this->conf['prefix']}syslog WHERE id=$id ");
		$data['time'] = date('Y-m-d',$data['create_time']);
		$data['author'] = DB::DBGetValue("SELECT uname FROM {$this->conf['prefix']}admin WHERE uid={$data['auth']}");

		$this->assign('info',$data);
		$this->display("syslog_detail.html");
	}


	private function PageViewStatistics($id){

		$ip = $_SERVER["REMOTE_ADDR"];
		$cookievalue = $id.'_'.time().'_'.$ip;
		$key = 'views'.$id;

		//cookie内容:id_time_ip  
		if(!$_COOKIE[$key]){

			SetCookie($key,$cookievalue ,time()+3600*24);	//有效期一天
			$sql = "UPDATE bns_syslog SET views=views+1 WHERE id=$id ";
			DB::DBExec($sql);
		}

	}

	//设置月份
	public function SetMonth(){
		$sql = "select * from bns_syslog";
		$data = DB::DBGetAll($sql);
		foreach ($data as $k => $v) {
			$id = $v['id'];
			$time = date('Ym',$v['create_time']);
			$up_sql = " UPDATE bns_syslog SET month=$time WHERE id=$id ";
			if($id && $time) DB::DBExec($up_sql);
		}
		$this->ShowErrorMsg('设置成功');
	}

	function AssignMonth(){
		$sql = "SELECT month,count(*) as count FROM bns_syslog GROUP BY month";
		$months = DB::DBGetAll($sql);
		 foreach($months as &$m) $m['time'] = substr_replace($m['month'],'年',4,0).'月';  
		$this->assign('months',$months);
	}

	public function showadd(){
		$this->assign('logtypes',DB::DBGetTableData($this->conf['prefix'].'logtype'));
		$this->display('syslog_add.html');
	}

	public function upLoadImg(){
		$json =array();
		if($_FILES['file_data]']['error']>0){
			$json['state'] = 0;

		}else{
			$json['path'] = $this->CreatePath();
			$res = move_uploaded_file($_FILES["file_data"]["tmp_name"],$json['path']);
			$res ? $json['state'] = 1:$json['state'] = 0;
		}
		echo json_encode($json);
	}

	public function CreatePath(){

		$file   = $_FILES["file_data"]['name'];
		$dir    = __UPLOAD__.date("Ym",time()).'/';
		if(!is_dir($dir)) mkdir($dir,777);

		$suffix = substr($file, strpos($file, '.'));
		$name   = rand(1000,9999).$suffix;
		$path   = $dir.$name;
		return  $path;
	}

	//Ajax添加系统日志
	public function _addlog(){

		$adminOjb = $this->NewObj('admin');
		$data['uid'] =  $adminOjb->GetUid();

		$data['title'] = $_POST['title'];
		$data['logtype'] = $_POST['logtype'];
		$data['month'] = date('Ym',$_POST['create_time']);
 		$data['content'] = $this->TopMcDecode($_POST['content'],'encode',$_POST['key']);
		$data['create_time'] = time();
		$data['tag'] = rtrim($_POST['tags']);
		$data['img'] = $_POST['filepath'];
 
		$res = DB::DBInsert($this->conf['prefix'].'syslog',$data);
		$res ? $state = 1:$state=0;

		echo json_encode(array('state'=>$state));
	}

	public function deletelog(){
		$id = $_REQUEST['id'];
		$sql = "DELETE FROM {$this->conf['prefix']}syslog WHERE id=$id ";
		$res = DB::DBExec($sql);
		if($res) $this->Jump('index.php?app=syslog',1,'删除成功');
		else $this->Jump('index.php?app=syslog',1,'删除失败');
	}


	//显示修改页面,只能修改内容,密钥,标题
	public function editlog(){

		$id = $_REQUEST['id'];
		$key = $_REQUEST['key'];

		$sql = "SELECT * FROM {$this->conf['prefix']}syslog WHERE id=$id ";
		$data = DB::DBGetOne($sql);
		$data['content'] = $this->TopMcDecode($data['content'],'decode',$key);

		$this->assign('editdata',$data);
		$this->assign('key',$key);
		$this->assign('id',$id);
		$this->display('syslog_edit.html');
	}

	public function _EditSure(){

		$id = $_REQUEST['id'];
		$data['title'] = $_POST['title'];
 		$data['content'] = $this->TopMcDecode($_POST['content'],'encode',$_POST['key']);
 		$res = DB::DBUpdate($this->conf['prefix'].'syslog',$data," id=$id ");
 		if($res ) $this->JsonReturnMsg(0,'修改成功');
	}

	/**
	 * 返回解密后的字符
	 * @return [type] [description]
	 */
	public function _DecodeContent(){

		$id = $_REQUEST['id'];
		$key = $_REQUEST['key'];
		$EncodeContent = DB::DBGetValue("SELECT content FROM {$this->conf['prefix']}syslog WHERE id=$id");

		$DecodeContent = $this->TopMcDecode($EncodeContent,'decode',$key);
		if($DecodeContent && json_encode($DecodeContent)){
			$json = array(
			'DecodeContent'=>$DecodeContent,
			'state'=>1
			);
		}else{
			$json = array('state'=>0);
		}
		echo json_encode($json);
	}

	
}