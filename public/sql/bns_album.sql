CREATE TABLE IF NOT EXISTS bns_album(
	id int primary key auto_increment,
	name varchar(50) default 'NoName' ,
	remark  varchar(500) default '' COMMENT '介绍' ,
	number int default 0    COMMENT '照片数量',
	ctime int default 0
);