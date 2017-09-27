<?php
if(!defined('TOKEN')){
	 exit('illegal access : app');
}

class Admin extends App{

	private $table;

	public function __construct(){

		// $this->table = $this->conf['prefix'].'admin';
		parent::__construct();
	}

	public function Adminadd(){

		$data = $this->AcceptPostData();
		$data['ctime'] = time();
		$res = DB::DBInsert($this->conf['prefix'].'menu',$data);
		if($res) $this->Jump('index.php',1,'添加成功');
	}

	public function GetUid(){
		$userinfo = $this->session('_user');
		if(!$userinfo) return 0;
		return $userinfo['uid'];
	}

	public function AdminIndex(){
		$this->AdmincheckPermission();
		$groups = DB::DBGetALL("SELECT * FROM {$this->conf['prefix']}admin_group");
		$listinfo = $this->ObtainAdminInfo();

		$this->assign('adminmenu',$this->GetMenu());
		$this->assign('groups',$groups);
		$this->assign('listinfo',$listinfo);
		$this->display('admin_list.html');
	}

	public function AdmincheckPermission(){

		$uid = $this->GetUid();

		$appEncode = $this->TopMcDecode('admin','encode',$this->conf['mkey']);
		$AdminMenuCode = DB::DBGetValue("SELECT menu_permissions FROM {$this->conf['prefix']}admin WHERE uid=$uid ");
		$MenuCodeArray = explode(',', $AdminMenuCode);

		if(!in_array($appEncode, $MenuCodeArray)){
			$this->ShowErrorMsg('无权访问admin模块');
		}
	}


	public function GetMenu(){
		$sql = "SELECT name,nw_field from {$this->conf['prefix']}menu WHERE giveadmin=1 ";
		$data = DB::DBGetALL($sql);
		return $data;
	}

	//Ajax添加管理员
	public function _admin_add(){

		$data = $_POST;
		if(!$data['uname'] || !$data['uname']){
			$this->JsonReturnMsg(1,'username or password con\'t be empty');
		}else{
			$data['passwd'] = md5($data['passwd'].$this->conf['salt']);
			$data['menu_permissions'] = '';
			$data['other_permissions'] = '';
			$data['lastip'] = $_SERVER["REMOTE_ADDR"];;
			$data['lasttime'] = $data['ctime'] =  time();
			$data['status'] = 1;
			$data['menu_permissions'] = $this->AcceptPstr();
			$res = DB::DBInsert($this->conf['prefix'].'admin',$data);
			if($res) $this->JsonReturnMsg(0);
			else $this->JsonReturnMsg(3306,'Insert error');
		}
	}


	/**
	 * 接收菜单权限并返回加密后的权限代码
	 */
	private function AcceptPstr(){

		$ps_array = $this->TopResolve($this->AcceptPostData('psstr',''),',');
		$menu_permissions = '';
		//分解后的菜单数组
		foreach($ps_array as $p){
			//加密前app全部转为小写
			$menu_permissions .= $this->TopMcDecode(strtolower($p),'encode',$this->conf['mkey']).',';
			// echo strtolower($p).'=>'.$menu_permissions;
		}
		// exit('');
		return rtrim($menu_permissions,',');
					
	}

	/**
	 * 赋予菜单权限
	 * @param [type] $uid [description]
	 */
	private function AssignMenuPermission($uid){

		$mPermission = DB::DBGetValue("SELECT menu_permissions FROM {$this->conf['prefix']}admin WHERE uid=$uid");
		if(empty($mPermission)) return false;

		$mPermissionArray = explode(',', $mPermission);
		$mDecodeArray = array();
		foreach ($mPermissionArray as $v) {
			if(empty($v)) continue;
			$mDecodeArray[] = $this->TopMcDecode($v,'decode',$this->conf['mkey']);
		}
		// $this->dump($mDecodeArray,true);
		$this->assign('mPermissionList',$mDecodeArray);
	}



	private function ObtainAdminInfo(){

		$sql      = "SELECT * FROM {$this->conf['prefix']}admin";
		$count    = DB::DBGetValue("SELECT count(*) FROM {$this->conf['prefix']}admin");
		$jumpURL  = "index.php?app=admin";
		isset($_GET['page']) ? $page = $_GET['page'] :$page = 1;

		$return = $this->CreatePage($sql,$count,$jumpURL,$size=5,$page,'uid');
		$sql = $return['sql'];
		$this->assign('pagecode',$return['page_string']);
		$this->assign('pagecount',$return['pagecount']);
		$this->assign('page',$page);	

		// echo '<pre/>';print_r($count);exit('');

		$listinfo = DB::DBGetALL($sql);
		foreach ($listinfo as &$v) {
			$v['time'] = date('Y-m-d',$v['ctime']);
		}
		return $listinfo;
	}

	public function Login(){
		$this->display('admin_login.html');
	}

	public function adminlogin(){

		$data = $this->AcceptPostData();
		$postUname = $data['uname'];
		$truePasswd = DB::DBGetValue("SELECT passwd FROM {$this->conf['prefix']}admin WHERE uname='$postUname' ");
		$postPasswd = md5($data['passwd'].$this->conf['salt']);


		if($truePasswd === $postPasswd){
			//登录成功:1.添加跳转页面,2.保存登录信息,3修改admin登录数据
			$User = DB::DBGetOne("SELECT * from {$this->conf['prefix']}admin WHERE uname='$postUname' ");
			$this->session('_user',$User);
			$this->Jump('index.php?app=index',1,'login Successful');
		}else{
			$this->Jump('index.php?app=index',3,'login failed');
		}
	}

	//个人中心
	public function AdminDetails(){

		$uid = $this->AcceptPostData('uid',0);
		$info = DB::DBGetOne(" SELECT * FROM {$this->conf['prefix']}admin WHERE uid= $uid ");
		$info['time'] = date('Y-m-d',$info['lasttime']);

		$this->AssignMenuPermission($uid);	//获取菜单权限
		$this->assign('adminmenu',$this->GetMenu());
		$this->assign('adminInfo',$info);
		$this->display('admin_index');
	}

	//退出登录
	public function logout(){
		$uid = $_REQUEST['id'];
		unset($_SESSION['_user']);
		$this->Jump('index.php?app=index',1,'退出登陆成功');
	}

	public function admindelete(){
		$id = $this->AcceptPostData('id',0);
		if(!$id) $this->ShowErrorMsg('用户不存在失败');

		$optuid = $this->GetUid();
		$res = DB::DBDelete($this->conf['prefix'].'admin'," WHERE uid = $id ");
		if($res) $this->Jump('index.php?app=admin',1,'删除成功');
		else $this->ShowErrorMsg('删除失败');
	}

	public function changepwd(){
		$uid    = $this->AcceptPostData('uid',0);
		$oldPwd = $this->AcceptPostData('oldpwd',0);

		$truePasswd = DB::DBGetValue("SELECT passwd FROM {$this->conf['prefix']}admin WHERE uid=$uid ");
		$postPasswd = md5($oldPwd.$this->conf['salt']);

		if($truePasswd === $postPasswd){
			$newpwd = $this->AcceptPostData('newpwd',0);
			$data['passwd'] = md5($newpwd.$this->conf['salt']);
			$res = DB::DBUpdate($this->conf['prefix'].'admin',$data," WHERE uid=$uid ");
			if($res) $this->JsonReturnMsg(0,'修改成功');
			else $this->JsonReturnMsg(1,'修改失败,未知原因');
		}else{
			$this->JsonReturnMsg(1,'原密码错误');
		}
	}

	//修改权限
	public function _ChangePermission(){
		$data['menu_permissions'] = $this->AcceptPstr();
		$uid = $this->AcceptPostData('uid',0);
		$res = DB::DBUpdate($this->conf['prefix'].'admin',$data," WHERE uid= $uid ");
		if($res) $this->JsonReturnMsg();
		else $this->JsonReturnMsg(1,'failed');
	}


}

