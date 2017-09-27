<?php
if(!defined('TOKEN')){
	 exit('illegal access : app');
}

class Image extends App{

	private $apptable;

	public function __construct(){
		parent::__construct();
		$this->apptable = $this->conf['prefix'].'dcim';
	}

	public function ImageIndex(){
		$imgs = $this->getImgsList();
		$albumlist = $this->getAlbum();

		$this->assign('albumlist',$albumlist);
		$this->display('image_index');
	}

	public function getImgsList(){

		//是否选择了相册
		$albumid = $this->AcceptPostData('albumid',1);
		$albumid ? $where .= " WHERE album=$albumid ":$were ='';

		//sql以及获取总照片数
		$sql = "SELECT * FROM {$this->conf['prefix']}dcim ".$where;
		$count    = DB::DBGetValue(" SELECT count(*) FROM {$this->conf['prefix']}dcim ".$where );
		$jumpURL  = "index.php?app=image";
		if($albumid) $jumpURL =$jumpURL."&albumid=$albumid";

		//获取页数,生成分页代码
		isset($_GET['page']) ? $page = $_GET['page'] :$page = 1;
		$return = $this->CreatePageDetails($sql,$count,$jumpURL,$size=12,$page,'size_type');

		$sql = $return['sql'];

		$imgs = DB::DBGetALL($sql);
		$albumName = DB::DBGetValue(" SELECT name FROM {$this->conf['prefix']}album WHERE id= $albumid ");
		foreach($imgs as &$i) $i['time'] = date('Y-m-d',$i['ctime']);
		if(empty($imgs) && $albumid !=1) $this->ShowErrorMsg('相册为空');

		$this->assign('albumName',$albumName);
		$this->assign('albumid',$albumid);
		$this->assign('imgs',$imgs);
		$this->assign('sql',$sql); //输出调试echo
		$this->assign('pagecode',$return['page_string']);
		$this->assign('pagecount',$return['pagecount']);
		$this->assign('page',$page);
	}

	public function getAlbum(){
		$sql = " SELECT * FROM {$this->conf['prefix']}album ";
		$data = DB::DBGetALL($sql);
		return $data;
	}

	public function upLoadImg(){
		$imgInfo = getimagesize($_FILES["file_data"]["tmp_name"]);
		$json['width'] = $imgInfo[0];
		$json['height'] = $imgInfo[1];

		$json['size_type'] = $json['width']/$json['height'];

		if($_FILES['file_data]']['error']>0){
			$json['state'] = 0;
		}else{
			$json['path'] = $this->CreatePath();
			$res = move_uploaded_file($_FILES["file_data"]["tmp_name"],$json['path']);
			$res ? $json['state'] = 1:$json['state'] = 0;
		}
		$json['album'] =$this->AcceptPostData('ChinaNo1',1);	;
		$this->AddImg($json);

		echo json_encode($json);
	}

	//只是上传封面,不添加图册
	public function upLoadAlbumCover(){

		if($_FILES['file_data]']['error']>0){
			$json['state'] = 0;
		}else{
			$suffix = substr($_FILES["file_data"]['name'], strpos($_FILES["file_data"]['name'], '.'));
			$json['path'] = __UPLOAD__.'album/'.rand(1,999).$suffix ;
			$res = move_uploaded_file($_FILES["file_data"]["tmp_name"],$json['path']);
			$res ? $json['state'] = 1:$json['state'] = 0;
		}
		echo json_encode($json);
	}

	public function imgdelete(){

		$id = $this->AcceptPostData('id');
		if(!$id) $this->ShowErrorMsg('图片不存在');

		$page = $this->AcceptPostData('page',1);
		$path = DB::DBGetValue("SELECT path FROM {$this->conf['prefix']}dcim WHERE id=$id ");
		if(is_file($path)) unlink($path);
		
		$res = DB::DBDelete($this->conf['prefix'].'dcim'," id=$id ");
		if($res) $this->Jump("index.php?app=image&page=$page",1,'删除成功');

	}

	public function AddImg($data){
		$data['ctime'] = time();
		$adminObj = $this->NewObj('admin');
		$data['auth'] = $adminObj->GetUid();
		DB::DBInsert($this->conf['prefix'].'dcim',$data);
	}

	public function albumdelete(){

		$id = $this->AcceptPostData('id');
		if(!$id) $this->ShowErrorMsg('图册不存在');
		$res = DB::DBDelete($this->conf['prefix'].'album'," id=$id ");
		if($res) $this->Jump("index.php?app=image",1,'删除成功');
	}

	

	public function CreatePath(){

		$file   = $_FILES["file_data"]['name'];
		$dir    = __UPLOAD__.'dcim/'.date("Ym",time()).'/';	//相片位置
		if(!is_dir($dir)) mkdir($dir,777);

		$suffix = substr($file, strpos($file, '.'));
		$name   = rand(1,99999).$suffix;
		$path   = $dir.$name;
		return  $path;
	}


	public function ChangeTitle(){
		$data['title'] = $this->AcceptPostData('value','');
		$id = $this->AcceptPostData('id',0);
		$res = DB::DBUpdate($this->conf['prefix'].'dcim',$data," id=$id ");
		if($res) $this->JsonReturnMsg(0);
		else $this->JsonReturnMsg(1,'failure');
	}

	public function CreateAlbum(){
		$data = $this->AcceptPostData();
		$data['ctime'] = time();
		$res = DB::DBInsert($this->conf['prefix'].'album',$data);
		if($res) $this->JsonReturnMsg(0);
		else $this->JsonReturnMsg(1);
	}

}

