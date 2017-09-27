<?php

class DB{

	public static $connect;
	public static $link;

	function __construct(){}

	/**
	 * 连接数据库
	 * @param [type] $dbhost  [description]
	 * @param [type] $dbuser  [description]
	 * @param [type] $dbpw    [description]
	 * @param string $dbname  [description]
	 * @param string $charset [description]
	 */
	static function DBConnect($dbhost, $dbuser, $dbpw, $dbname = '', $charset = 'utf8'){

		self::$link = mysqli_connect($dbhost,$dbuser,$dbpw);
		if(!self::$link) exit( "can't connect to DBserver");
		if($dbname) mysqli_select_db(self::$link,$dbname);
		mysqli_query(self::$link,"SET NAMES $charset");
	}


	/**
	 * 自动插入数据库
	 * @param [type] $table [description]
	 * @param [type] $data  [description]
	 */
	static function DBInsert($table,$data){
		if(empty($data)) return;
		$fields = self::DBGetTableFields($table);

		$sql = "INSERT INTO $table";
		foreach ($data as $key => $v) {
			if(!in_array($key, $fields)) continue;
			$keys .= '`'.$key.'`'.',';
			$values .= '"'.$v.'"'.',';
		}
		$keys = rtrim($keys,',');$values = rtrim($values,',');
		$sql .= '('.$keys.')'.'VALUES'.'('.$values.')';
		return mysqli_query(self::$link,$sql);
	}


	/**
	 * 自动更新数据库
	 * @param [type] $table [description]
	 * @param [type] $data  [description]
	 */
	static function DBUpdate($table,$data,$where,$test=false){
		if(empty($data)) return;
		$fields = self::DBGetTableFields($table);

		$sql  = "UPDATE `$table` SET ";

		foreach($data as $key=>$v){
			if(!in_array($key, $fields)) continue;
			$sql .= " `$key`=" .'"'. $v .'"'. ',';
		}
		$sql = rtrim($sql,',');
		$where = str_replace('WHERE', '', $where);
		$sql .= " WHERE " .$where;

		if($test){
			echo $sql;exit();
		}
		return mysqli_query(self::$link,$sql);
	}


	/**
	 * 执行sql语句
	 * @param [type] $sql [description]
	 */
	static function DBExec($sql){
		return mysqli_query(self::$link,$sql);
	}


	/**
	 * 获取数据表字段
	 * @param [type] $table [description]
	 */
	static function DBGetTableFields($table){
		$array = array();
		$data = self::DBGetAll("SHOW COLUMNS FROM $table");
		foreach ($data as $k => $v) {
			$array[] = $v['Field'];
		}
		return $array;
	}


	/**
	 * 获取所有记录
	 * @param [type] $sql [description]
	 */
	static function DBGetAll($sql){
		$res = mysqli_query(self::$link,$sql);
		$data = array();
		if($res) while ($row = mysqli_fetch_assoc($res)) $data[] = $row;
		return $data;
	}


	/**
	 * 获取一条记录
	 * @param [type] $sql [description]
	 */
	static function DBGetOne($sql){
		if(!stripos($sql, 'limit')) $sql .= ' LIMIT 1 ';
		$res = mysqli_query(self::$link,$sql);
		$row = array();
		if($res) $row = mysqli_fetch_assoc($res);
		return $row;
	}

	static function DBGetTableData($table){
		$sql = "SELECT * FROM $table ";
		$data = self::DBGetAll($sql);
		return $data;
	}

	/**
	 * 限定数据库记录数为100条
	 * @param [type] $table [description]
	 */
	static function Limit100($table){
		$sql = "SELECT count(*) FROM $table ";
		$count = self::DBGetValue($sql);
		$WHERE = " WHERE 1 ";
		if($count >100)	self::DBDelete($table,$WHERE);
	}


	/**
	 * 删除数据
	 * @param [type] $table [description]
	 * @param [type] $WHERE [description]
	 */
	static function DBDelete($table,$WHERE){
		if(stripos($WHERE, 'WHERE') === false ) $WHERE = ' WHERE '.$WHERE;
		$sql = "DELETE FROM $table $WHERE ";
		$res = mysqli_query(self::$link,$sql);
		return $res;
	}


	/**
	 * 获取某个字段的值
	 * @param [type] $sql [description]
	 */
	static function DBGetValue($sql){
		if(stripos($sql, "LIMIT") === false) $sql.= " LIMIT 1 ";
		$res = mysqli_query(self::$link,$sql);
		$row = mysqli_fetch_assoc($res);
		if(count($row) ==1)	foreach($row as $v) return $v;
		return $row;
	}


}
