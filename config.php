<?php
	
if(defined('TOKEN')){

	$conf = array();
	$ap = range("u", 'z');
	$ad = range(TOKEN, (TOKEN+3)*3);

	if(DIRECTORY_SEPARATOR == '\\'){
		$conf['top_htdocs'] = '';
		$conf['skrillex'] = 'mysqlPassword'; //windows
		$conf['dburl'] = "localhost";
		$conf['dbusername'] = 'root';
		$conf['database'] = 'test';
	}else{
		$conf['top_htdocs'] = '';	//linux
		$conf['dburl'] = "localhost";
		$conf['dbusername'] = 'root';
		$conf['database'] = 'bns';
		$conf['skrillex'] = 'mysql';
	}

	//其他
	$conf['error_file'] = "";
	$conf['ROOT_TYPE'] = 'domain';	// file | domain
	$conf['cache'] = false;
	$conf['prefix'] = 'bns_';
 	$conf['mysql_log'] ='D:/Web/MySQL/SkrillexLog/mysql_bz.log' ;
 	$conf['salt'] ='liyuhs' ;
 	$conf['mkey'] ='menu' ;

	//站点根目录(域名虚拟路径)  总结 include需要使用物理地址,scr需要域名地址
	$PHP_SELF=$_SERVER['PHP_SELF'];
	$conf['TOP']='http://'.$_SERVER['HTTP_HOST'].substr($PHP_SELF,0,strrpos($PHP_SELF,'/')+1).$conf['top_htdocs'];
	
	//站点根目录(文件物理路径),config.php的目录,也就是本文件的目录,就是项目的ROOT目录
    $conf['ROOT'] = realpath(dirname(__FILE__) . '/') . '/';
    $conf['CONF'] = $conf['ROOT'].'conf/';
	$conf['CLASS'] = $conf['ROOT'].'class/';
	//当前链接地址
	$conf['nowurl'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

    //相关路径
    if (!defined('ROOT')) define('ROOT',$conf['ROOT']);
    if (!defined('TOP'))  define('TOP',$conf['TOP']);
	if (!defined('SERVER_ROOT_PATH')) define('SERVER_ROOT_PATH',$_SERVER['DOCUMENT_ROOT']);
	if (!defined('NEWS')) define('NEWS', ROOT.'news/');
	if (!defined('TPL')) define('TPL', ROOT.'tpl/');
	if (!defined('CLS')) define('CLS', ROOT.'class/');
	if (!defined('CACHE')) define('CACHE', ROOT.'cache/');
	if (!defined('TCACHE')) define('TCACHE', ROOT.'cache/tplcache/');
	if (!defined('APP')) define('APP', ROOT.'app/');
	if (!defined('CONF')) define('CONF', $conf['CONF']);
	if (!defined('CLASS')) define('CLASS', $conf['CLASS']);
	if (!defined('LOGS')) define('LOGS', ROOT.'logs/');

	if (!defined('__EXT__')) define('__EXT__', TOP.'extend');
	if (!defined('__PUB__')) define('__PUB__', TOP.'public');
	if (!defined('__JS__')) define('__JS__', __PUB__.'/js');
	if (!defined('__CSS__')) define('__CSS__',__PUB__.'/css');
	if (!defined('__IMG__')) define('__IMG__',__PUB__.'/img');
	if (!defined('__FONT__')) define('__FONT__',__PUB__.'/fonts');
	if (!defined('__PUBTPL__')) define('__PUBTPL__',ROOT.'public/pubtpl/');
	if (!defined('__UPLOAD__')) define('__UPLOAD__','public/upload/');

	return $conf;
}

