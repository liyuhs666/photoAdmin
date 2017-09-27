<?php

/*

如果是 mysql5.4以下版本
在my.ini 文件中 设置 log='你的log 日志路径'  这个配置下面数组配置需要用得上
在[mysqld]后面增加一行  然后重启mysql 生效
log=D:/wamp/www/mysql_bz.log


* 如果是 mysql5.5以上版本  一次性修改方法 重启后无效
SHOW VARIABLES LIKE '%general_log%'
SET GLOBAL general_log = 1
SET GLOBAL general_log_file = '你的log 日志路径' 这个配置下面数组配置需要用得上


长期有效修改方法在 my.ini 里面 [mysqld] 后面加上如下代码 没有 [mysqld] 自己加上
[mysqld]
general_log=ON
general_log_file=D:/wamp/www/mysql_bz.log // 这里设置你 log日志路径  这个配置下面数组配置需要用得上
# log-raw=true  如果错误日志没记录 则开启这行, 参考地址 http://dev.mysql.com/doc/refman/5.7/en/query-log.html
# http://dev.mysql.com/doc/refman/5.7/en/password-logging.html
然后重启mysql 生效
错误的sql不会被成功解析，所以不会记录到general log中
如果需要记录所有的语句，包括那些错误的，请加 log-raw选项  log-raw=true
*/
return array(
	'web_url'  =>'localhost/phpgjx/index.php', // php工具箱访问 url 根路径  能访问到你工具箱的 地址
	'mysql_log'=>'D:/Servers/MySQL/SkrillexLog/mysql_bz.log', // mysql 标准日志文件路径			
    'mysql_host' =>'localhost', //mysql连接地址
	'mysql_user' =>'root', //mysql账号
	'mysql_password' =>'6614584', //mysql密码
	'mysql_port' =>'3306', //mysql端口	
	'menu'=>array( // 菜单配置项
	     
	),
);

