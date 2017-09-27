CREATE TABLE IF NOT EXISTS bns_admin(
	uid int primary key auto_increment,
	uname varchar(32)  unique,
	passwd varchar(32),
	groups int,
	menu_permissions varchar(1000),
	other_permissions varchar(1000),
	lastip char(15),
	lasttime int,
	status tinyint,
	ctime int
);