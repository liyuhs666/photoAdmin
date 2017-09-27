<?php
if(!defined('TOKEN')){
	 exit('illegal access : app');
}

class News extends App{

	private $_conf;
	public $logfile ;

	public function __construct(){
		parent::__construct();
		$this->logfile = LOGS.'newslog.txt';
	}

	public function NewsIndex(){
		if(isset($_SERVER['HTTP_USER_AGENT'])){
			$this->show();
		}
	}

	public function run(){

		$configs = $this->LoadConf('preg_config.php');
		$url = $configs['url']['newsurl'];
		$HtmlContent = $this->getHTML($url);
		$this->getLink($HtmlContent,$configs);
	}

	private function getLink($HtmlContent,$configs=''){

		$preg = $configs['preg']['all'];
		$preg_img = $configs['preg']['newsimg'];
		preg_match_all($preg, $HtmlContent, $match_array);	//[0]=tag [1]=imgurl [2]=newsurl [3]=title
		$count = count($match_array[0]);

		DB::Limit100('bns_news');	//超过100条删除

		$s = $f = 0;
		for($i=0;$i<$count;$i++){
			$data = array();
			$data['title'] = $this->ChangeCharset($match_array[3][$i],'gbk','utf-8');
			$data['imgurl'] = $match_array[1][$i];
			$data['newsurl'] = $match_array[2][$i];
			$data['newsday'] = date("Ymd",time());
			$data['ctime'] = time();
			$res = DB::DBInsert('bns_news',$data);
			$res ? $s++ : $f++;
		}
		$this->Setlogs($s,$f);
		$this->show();
		exit;
	}

	public function Setlogs($s,$f){

		$logs = date('Y-m-d H:i:s',time());
		$logs .= ' '.'Success:'.$s.' Failure:'.$f."\r\n";

		if(!isset($_SERVER['HTTP_USER_AGENT'])){
		    echo $logs."\n";
		}

		$f = fopen($this->logfile, 'a');
		fwrite($f, $logs);
		fclose($f);
	}

	public function Show(){
		$count = DB::DBGetValue("SELECT count(*) FROM {$this->conf['prefix']}news");
		$start = rand(0, $count-10);
		$list = DB::DBGetall("SELECT * FROM {$this->conf['prefix']}news LIMIT $start,10 ");
		$this->assign('list',$list);
		$this->assign('title','list of nw');
		$this->display('newlist.html');
	}

	public function getHTML($url){
		$ch = curl_init();	
		curl_setopt($ch,CURLOPT_URL, $url);	
		curl_setopt($ch,CURLOPT_HEADER,FALSE);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$html = curl_exec($ch);	
		curl_close($ch);		
		return $html;
	}



}

