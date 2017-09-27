<?php

/*  关于使用此功能的必要条件:
 * 	1.配置Mysql日志:
 * 		a.在mysql配置文件my.ini中的[mysqld]下添加:
 * 		log-raw=true
 *		general_log=ON
 *		general_log_file=D:/Web/MySQL/SkrillexLog/mysql_bz.log  #这个文件自己添加,按照实际情况配置(如果是linux则是配置/etc/my.cnf)
 *		
 *	2.在每次重启之后,在mysql管理器如Navcat或者直接在mysql命令行中执行:
 *		SHOW VARIABLES LIKE '%general_log%';
 *		SET GLOBAL general_log = 1;
 *		SET GLOBAL general_log_file = 'D:/Web/Mysql/SkrillexLog/mysql_bz.log';
 *
 *  3.代码中mysql日志文件的配置在根目录下的config.php文件中
 * 
 */

if(!defined('TOKEN')){
	 exit('illegal access : app');
}

class SqlTrace extends Top{


	public $filename;
	public $start_time;
	public $log_str;
	public $url_path;
	public $content = '';
	public $echotime;
	public $notes = '如果发现Mysql的log文件没内容,那是因为没有开启日志记录(每次启动Mysql都需要)';

	public function __construct(){
		parent::__construct();
	}

	public function SqlTraceIndex(){
		$this->init();
		$this->echotime = $this->echotime();	//输出时间
		$this->assign('content',$this->content);
		$this->assign('mysql_log',$this->conf['mysql_log']);
		$this->assign('echotime',$this->echotime);
		$this->assign('sql_tip',$this->ShowTips());
		$this->display('sql_list2.html');
	}

	public function init(){
		// $this->Setlog();
		$this->log_str = "";	//清空log时,覆盖log的内容
		$this->start_time = microtime(true);
		$this->filename = $this->conf['mysql_log'];
		$this->url_path = 'index.php?app=sqltrace';
		$this->run();
	}

	public function ShowTips(){
		if($this->session('sql_first')){
			$this->session('sql_first',$this->session('sql_first')+1);
		}else{
			$this->session('sql_first',1);
		}
		return $this->session('sql_first');
	}

	public function Setlog(){
		$isset = $this->session('sqllog');
		var_dump($isset);
		if(!$isset){
			DB::DBExec("SHOW VARIABLES LIKE '%general_log%'");
			DB::DBExec("SET GLOBAL general_log = 1");
			DB::DBExec("SET GLOBAL general_log_file = '{$this->filename}'");
			$this->session('sqllog',true);
		}
	}

	public function del(){

		$this->filename = $this->conf['mysql_log'];
		$fp = @fopen($this->filename, "w+"); //打开文件指针，创建文件
		
		if ( !is_writable($this->filename) ){
			  die("log文件:" .$this->filename. "不可写,请检查!或尝试删除log文件,重启mysql");
		}else{
		   fwrite($fp, $this->log_str."\r\n");
		   @fclose($fp);  //关闭指针
		   $this->Refresh();	//重新载入
		}	

	}


	public function run(){

		if(!file_exists($this->filename))	{
			exit("log文件不存在, 请到config.php 文件中配置");
		}else{

			if(abs(filesize($this->filename) > (1024*1014*1)))  file_put_contents($this->filename, $this->log_str);

			$fp = @fopen($this->filename, "r") or exit("log文件打不开!");; //打开文件指针，创建文件
			$upstr = ''; //上一次匹配的值
			
			while(!feof($fp)){	
				$str = fgets($fp);	//一次获取一行,所以第一行是空行
				
				if(preg_match("/Connect/", $str)){
					$str = '<div style="background:#ebf8a4;border:1px solid;border-color:#a2d246;padding:10px 10px 10px 10px;">' . 
						$this->htmltag('p','font-size:14px',$str).
					'</div>';

				}else{
				
					/* MYSQL关键字类 */
					//防止关键字被短字符串先替换,按字符串长度排列
					$str = str_replace('SELECT',$this->htmltag('span','color:#708','SELECT'), $str);//SELECT比较特别
					$preplace = array(
							'SQL_CALC_FOUND_ROWS',
							'MINUTE_MICROSECOND','NO_WRITE_TO_BINLOG','SECOND_MICROSECOND',
							'CURRENT_TIMESTAMP',
							'HOUR_MICROSECOND','SQL_SMALL_RESULT',
							'DAY_MICROSECOND',
							'LOCALTIMESTAMP','SQL_BIG_RESULT',
							'DETERMINISTIC','HIGH_PRIORITY','MINUTE_SECOND','STRAIGHT_JOIN','UTC_TIMESTAMP',
							'CURRENT_DATE','CURRENT_TIME','CURRENT_USER','LOW_PRIORITY','SQLEXCEPTION','VARCHARACTER',
							'DISTINCTROW','HOUR_MINUTE','HOUR_SECOND','INSENSITIVE',
							'ASENSITIVE','CONNECTION','CONSTRAINT','DAY_MINUTE','DAY_SECOND','MEDIUMBLOB','MEDIUMTEXT','REFERENCES','SQLWARNING','TERMINATED','YEAR_MONTH',
							'CHARACTER','CONDITION','DATABASES','LOCALTIME','MEDIUMINT','MIDDLEINT','PRECISION','PROCEDURE','SENSITIVE','SEPARATOR','VARBINARY',
							'CONTINUE','DATABASE','DAY_HOUR','DESCRIBE','DISTINCT','ENCLOSED','FULLTEXT','INTERVAL','LONGBLOB','LONGTEXT','MODIFIES','OPTIMIZE','RESTRICT','SMALLINT','SPECIFIC','SQLSTATE','STARTING','TINYBLOB','TINYTEXT','TRAILING','UNSIGNED','UTC_DATE','UTC_TIME','ZEROFILL',
							'ANALYZE','BETWEEN','CASCADE','COLLATE','CONVERT','DECIMAL','DECLARE','DEFAULT','DELAYED','ESCAPED','EXPLAIN','FOREIGN','INTEGER','ITERATE','LEADING','NATURAL','NUMERIC','OUTFILE','PRIMARY','RELEASE','REPLACE','REQUIRE','SCHEMAS','SPATIAL','TINYINT','TRIGGER','VARCHAR','VARYING',
							'BEFORE','BIGINT','BINARY','CHANGE','COLUMN','CREATE','CURSOR','DELETE','DOUBLE','ELSEIF','EXISTS','FLOAT4','FLOAT8','HAVING','IGNORE','INFILE','INSERT','LINEAR','OPTION','REGEXP','RENAME','REPEAT','RETURN','REVOKE','SCHEMA','SELECT','UNIQUE','UNLOCK','UPDATE','VALUES',
							'ALTER','CHECK','CROSS','FALSE','FETCH','FLOAT','FORCE','GRANT','GROUP','INDEX','INNER','INOUT','LABEL','LEAVE','LIMIT','LINES','MATCH','ORDER','OUTER','PURGE','RAID0','RANGE','READS','RIGHT','RLIKE','UNION','USAGE','USING','WHERE','WHILE','WRITE',
							'BLOB','BOTH','CALL','CASE','CHAR','DESC','DROP','DUAL','EACH','ELSE','EXIT','FROM','GOTO','INT1','INT2','INT3','INT4','INT8','INTO','JOIN','KEYS','KILL','LEFT','LIKE','LOAD','LOCK','LONG','LOOP','READ','REAL','SHOW','THEN','TRUE','UNDO','WHEN','WITH','X509',
							'ADD','ALL','AND','ASC','DEC','DIV','FOR','INT','KEY','MOD','NOT','OUT','SET','SQL','SSL','USE','XOR',
							'AS','BY','IF','IN','IS','ON','OR','TO'
					);
					
					foreach($preplace as $keyword){
						$keyword = $keyword . ' ';
						$str = str_replace($keyword,$this->htmltag('span','color:#708',$keyword), $str);
					}
					/* MYSQL函数类 */
					// 防止函数被短字符串先替换,按字符串长度排列
					$preplace2 = array(
							'UNCOMPRESSED_LENGTH',
							'CURRENT_TIMESTAMP',
							'CHARACTER_LENGTH',
							'SUBSTRING_INDEX','MASTER_POS_WAIT',
							'UNIX_TIMESTAMP','LAST_INSERT_ID',
							'FROM_UNIXTIME','TIMESTAMPDIFF','UTC_TIMESTAMP','CONNECTION_ID',
							'GROUP_CONCAT','CURRENT_DATE','CURRENT_TIME','OCTET_LENGTH','TIMESTAMPADD','OLD_PASSWORD','COERCIBILITY','CURRENT_USER','SESSION_USER','IS_FREE_LOCK','IS_USED_LOCK','RELEASE_LOCK',
							'FIND_IN_SET','DATE_FORMAT','AES_ENCRYPT','AES_DECRYPT','TIME_FORMAT','CHAR_LENGTH','PERIOD_DIFF','SEC_TO_TIME','TIME_TO_SEC','STR_TO_DATE','DES_DECRYPT','DES_ENCRYPT','SYSTEM_USER','STDDEV_SAMP',
							'BIT_LENGTH','DAYOFMONTH','EXPORT_SET','PERIOD_ADD','UNCOMPRESS','CONVERT_TZ','GET_FORMAT','WEEKOFYEAR','FOUND_ROWS','NAME_CONST','STDDEV_POP',
							'CONCAT_WS','DAYOFWEEK','DAYOFYEAR','MONTHNAME','INET_ATON','INET_NTOA','SUBSTRING','LOAD_FILE','FROM_DAYS','LOCALTIME','CROSECOND','BENCHMARK','COLLATION','ROW_COUNT',
							'GREATEST','TRUNCATE','POSITION','PASSWORD','DATABASE','DISTINCT','DATE_ADD','DATE_SUB','MAKE_SET','COMPRESS','DATEDIFF','LAST_DAY','MAKEDATE','MAKETIME','UTC_TIME','GET_LOCK','VAR_SAMP','VARIANCE',
							'CEILING','REVERSE','CURDATE','CURTIME','DAYNAME','QUARTER','EXTRACT','ENCRYPT','VERSION','SOUNDEX','REPLACE','WEEKDAY','ADDDATE','ADDTIME','SUBDATE','TO_DAYS','SYSDATE','DEGREES','RADIANS','AGAINST','CHARSET','DEFAULT','BIT_AND','BIT_XOR','VAR_POP',
							'CONCAT','INSERT','LENGTH','REPEAT','STRCMP','MINUTE','DECODE','ENCODE','IFNULL','NULLIF','FORMAT','SECOND','LOCATE','SCHEMA','VALUES','BIT_OR','STDDEV',
							'FLOOR','ROUND','COUNT','ASCII','LCASE','LOWER','LTRIM','QUOTE','RIGHT','RTRIM','UCASE','UPPER','MONTH','INSTR','SPACE','FIELD','UNHEX','ATAN2','CRC32','LOG10','POWER','MATCH','SLEEP',
							'RAND','SIGN','SQRT','LEFT','TRIM','HOUR','WEEK','YEAR','CAST','USER','CONV','CHAR','LPAD','RPAD','ACOS','ASIN','ATAN','CEIL','LOG2','DATE','UUID',
							'ABS','BIN','EXP','LOG','MOD','AVG','MIN','MAX','SUM','NOW','MD5','SHA','ORD','OCT','HEX','MID','ELT','COS','COT','POW','SIN','TAN','DAY','STD',
							'LN','IF','IN','PI'
					);
					
					foreach($preplace2 as $function){
						$function1 = $function . '(';
						$function2 = $function . ' (';
						$str = str_ireplace($function1,$this->htmltag('span','color:#FF3E96',$function).'(', $str);
						$str = str_ireplace($function2,$this->htmltag('span','color:#FF3E96',$function).'(', $str);
					}
				}
				
				$str = preg_replace('/([0-9]{1,} Query)|([0-9]{1,} Quit)/', "", $str);
				$str = preg_replace('/([0-9]{1,})\sInit/', "Init", $str);

				//$str 就是显示的sql语句
				$this->content .= $this->htmltag('p','padding:2px;color:#05a;font-size:14px',$str) ."\r\n";
				
			} 	
			@fclose($fp);  //关闭指针
		       
		}
	}

	public function echotime(){
		$end_time = microtime(true);
		$interval = $end_time - $this->start_time;
		return $str = $this->htmltag('p','padding:2px;color:#8B0000;font-size:14px','获取花费:'.$interval.'秒');
	}
 
	/* 封装标签 */
	function htmltag($tag,$style,$str){
		return '<'.$tag.' style="'.$style.'">'.$str.'</'.$tag.'>';
	}

	function Refresh(){
		$this->SqlTraceIndex();
	}

}