<?php
	if(!defined('TOKEN')) $this->ShowErrorMsg('illegal access');

//编译模板类
class Trantpl{

	private $left = '{';
	private $right = '}';
	private $tplfile;		//编译前的文件
	public  $temfile;		//编译后的文件
	private $PREG = array();	//正则规则
	private $PCODE = array();  //php code
	private $value ;

	public function TranslateTPL($path,$cache=true,$flag=false,$save=true){
		$this->tplfile = file_get_contents($path);	//未编译模板
		$last_modify_time_tpl = filemtime($path);

		if($flag === 10000){
			$this->PREG[] = "/<\?(=|php|)(.+?)\?>/is";
			$this->PCODE[] = "&lt;? \\1\\2? &gt";
		}
		//正则的回溯,以及固化分组是优化正则执行效率的重点
		//1.{$var}
		$this->PREG[] = "/\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/";
		$this->PCODE[] = "<?php echo \$this->value['\\1']; ?>";

		//2.<each $var as $v>
		$this->PREG[] = '/<each\s+\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff].*?)\s+as\s+\$([a-zA-Z0-9]+)\s{0,10}>/';
		$this->PCODE[] = "
		<?php if(count(\$this->value['\\1'])>0){ ?>
		<?php foreach(\$this->value['\\1'] as \$this->value['\\2']){ ?>
			";

		//3.</each>
		$this->PREG[] = "/<\/each>/";
		$this->PCODE[] = "<?php } ?><?php } ?>";
		
		//4.{$data.id}
		$this->PREG[] = '/\{\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\.([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/';
		$this->PCODE[] = "<?php echo \$this->value['\\1']['\\2']; ?>";

		//5.<each $data as $k,$v>
		$this->PREG[] = '/<each\s+\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff].*?)\s+as\s+\$([a-zA-Z0-9].*?),.*?\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*?)\s*>/';
		$this->PCODE[] = "
		<?php if(count(\$this->value['\\1'])>0){ ?>
		<?php foreach(\$this->value['\\1'] as \$this->value['\\2'] => \$this->value['\\3']){ ?>
		";

 		//6.{if(condition)}  >>>>>>>>  condition里面的变量需要用$this->value['']替换
 		//==1.eq相等 2.neq不相等，3.gt大于，4.lt小于 5.gte、ge大于等于  6.lte、le 小于等于
 		//
 		//或者{if($this->session('sql_tip') <4)}
 		//
 		// $this->PREG[] = '/\{if\s{0,3}\((.*?)\)\}/';
 		$this->PREG[] = '/\{if\((.*?)\)\}/';
 		$this->PCODE[] = "<?php if(\\1){ ?>";

 		//7.{elseif(condition)} 或者 {else if(condition)} 
 		// $this->PREG[] = '/\{else\s{0,3}if\((.*?)\)\}/';
 		$this->PREG[] = '/\{elseif\((.*?)\)\}/';
 		$this->PCODE[] = "<?php }elseif(\\1){ ?>";

 		//8.{else}
 		$this->PREG[] = '/\{else\}/';
 		$this->PCODE[] = "<?php }else{ ?>";

 		//8.{endif}
 		$this->PREG[] = '/\{endif\}/';
 		$this->PCODE[] = "<?php } ?>";

 		//9.__XXX__
 		$this->PREG[] = '/__(JS|CSS|IMG)__/';
 		$this->PCODE[] = "<?php echo __\\1__; ?>";

 		//10.<include src="__PUBTPL__/abc.html">
 		$this->PREG[]  = '/<include\s+src=[\'"](([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\.html)[\'"]>/';
 		$this->PCODE[] = "<?php include __PUBTPL__.'\\1'; ?>";

 		//11.<incache src="__PUBTPL__/abc.html">
 		$this->PREG[]  = '/<incache\s+src=[\'"](([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\.html)[\'"]>/';
 		$this->PCODE[] = "<?php include TCACHE.'\\1'; ?>";

		//数据渲染
		$this->temfile = preg_replace($this->PREG, $this->PCODE, $this->tplfile);	//编译后的文件内容
		$filename = CACHE.basename($path).'.php';	//编译模板存放位置
		
		//不保存则返回编译模板(问题 这里也有)
		if(!$save)	return $this->temfile;
		
		//是否启动缓存
		if($cache){

			if(is_file($filename)){

				$last_modify_time_tmp = filemtime($filename);
				if($last_modify_time_tpl > $last_modify_time_tmp){	//1.1.1最近修改过源文件,重新生成编译文件
					file_put_contents($filename, $this->temfile);
					include $filename;
				}else{
					include $filename;	//1.1.2 未修改过源文件,直接加载编译文件
				}

			}else{	//1.2不存在编译文件,生成并加载
				file_put_contents($filename, $this->temfile);
				include $filename;
			}
		}else{	//2.1不启用缓存.直接覆盖或生成编译文件
			file_put_contents($filename, $this->temfile);
			include $filename;
		}
	}


	//二次replace ,但是include 是在编译文件里,等include才显示出来,现在替换无意义
	private function Preg_twice($temfile){
		return $temfile;
		
		$preg = $repalce = array();

 		$temfile = preg_replace($preg, $repalce, $temfile);
 		return $temfile;
	}

	//为模板变量赋值
	public function Set($name,$data){
		$this->value["$name"] = $data;
	}

	private function GetProTime(){
		$stime = $GLOBALS['sys_start_time'];
		$ttime = explode(" ", microtime());
		$etime = $ttime[1]+$ttime[0];
		$runtime = $etime - $stime;
		return $runtime;
	}

	//二次替换
	private function repalce_twice($preg,$replace,$str){
		//$name == '')  
		return preg_replace($preg, $replace, $str);
	}

	//额外的内容
	public function session($first,$second=''){
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
}