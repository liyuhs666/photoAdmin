<?php
	header("Content-type:text/html;charset=utf-8");
	// $ttime = explode(" ", microtime());	$GLOBALS['sys_start_time'] = $ttime[1]+$ttime[0];
	
	require 'init.php';
	require_once 'app/app.inc.php';
	$obj = new App();
	$obj->appinit();