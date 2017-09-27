<?php

	header("Content-type:text/html;charset=utf-8");
	require '../init.php';
	require_once '../app/app.inc.php';
	$obj = new App();
	$obj->appinit();

	require '../app/news.inc.php';
	$n = new News();
	$n->run();