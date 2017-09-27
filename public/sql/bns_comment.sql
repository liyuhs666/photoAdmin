CREATE TABLE IF NOT EXISTS bns_comment(
	id int primary key auto_increment,
	pid int comment '父级id',
	logid int,
	content varchar(250) default '',
	ip varchar(15),
	randname varchar(10)  default '游客' comment '随机产生的名字',
	ctime int default 0,
	agree_num INT default 0
);