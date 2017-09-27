<?php
if(!defined('TOKEN')){
	 exit('illegal access : app');
}

class Index extends App{

	public function __construct(){
		parent::__construct();
	}

	public function IndexIndex(){
		$this->display('index.html');
	}

}