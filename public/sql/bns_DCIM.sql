CREATE TABLE IF NOT EXISTS bns_DCIM(
	id INT primary key auto_increment,
	path char(50),
	ctime int,
	author int default 0
);