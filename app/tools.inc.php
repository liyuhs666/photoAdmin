<?php
if(!defined('TOKEN')){
	 exit('illegal access : app');
}

class Tools extends App{

	public $en_api;

	public function __construct(){
		parent::__construct();
		$this->en_api = 'http://fanyi.youdao.com/openapi.do?keyfrom=sjlssp&key=901390085&type=data&doctype=json&version=1.1&q=';
	}

	public function ToolsIndex(){
		$this->display('tools.html');
	}

	public function Tools(){

		$keyword = $_REQUEST['keyword'];
		$res = json_decode(file_get_contents($this->en_api.$keyword));
		$data = array();

		if(is_object($res) && is_array($res->translation)){
			if($res->translation) foreach($res->translation as $t)  $data['translation'] .= $t.';';
			if($res->basic){
				foreach($res->basic as $k=>$v){
					if($k == 'explains'){
						$data['basic'] = '<p>';
						foreach($v as $vv) $data['basic'] .= $vv.'<br>';
						$data['basic'] .= '</p>';
					}
					if($k == 'uk-phonetic'){
						$data['fayin'] = $v;
					}
				}
			}
		}else{
			echo 'None';
		}

		$echo = '<div>';
		foreach($data as $k=>$d){
			if($k=='translation') $echo .= '<b>translation</b>:  '.$d.'<br>';
			if($k=='basic') $echo .= '<b>main</b>:  '.$d.'<br>';
			if($k=='fayin') $echo .= '<b>voice</b>:  '.$d.'<br>';
		}
		$echo .= '</div>';
		echo $echo;
		exit('');
	}

}

