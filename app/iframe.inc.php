<?php
if(!defined('TOKEN')){ 
	 exit('illegal access : app'); 
}
class Iframe extends App{


	public function __construct(){
		parent::__construct();
	}

	public function IframeIndex(){
		$ilist = DB::DBGetAll("SELECT * FROM {$this->conf['prefix']}iframe ");
		// echo '<pre>';var_dump($list);
		$this->assign('ilist',$ilist);
		$this->display('iframe_index');
	}

	public function IframeAdd(){
		$data['iurl'] = $this->Excep('iurl');
		$data['iname'] = $this->Excep('iname');
		$data['ifield'] = $this->Excep('ifield');
		$data['isort'] = 1000;
		if(DB::DBInsert($this->conf['prefix']."iframe",$data)) $this->ShowErrorMsg('add success');
	}

	public function IDelete(){
		$id = $this->Excep('id');
		if(DB::DBDelete( $this->conf['prefix']."iframe"," id=$id ")) $this->ShowErrorMsg('Delete Success');
	}

}