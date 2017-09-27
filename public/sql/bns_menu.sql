-- --------------------------------------------------------
-- 主机:                           localhost
-- 服务器版本:                        5.5.49-MariaDB - mariadb.org binary distribution
-- 服务器操作系统:                      Win64
-- HeidiSQL 版本:                  9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 导出  表 test.bns_menu 结构
CREATE TABLE IF NOT EXISTS `bns_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `nw_field` varchar(100) DEFAULT NULL,
  `url` varchar(50) DEFAULT NULL,
  `ctime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- 正在导出表  test.bns_menu 的数据：~2 rows (大约)
/*!40000 ALTER TABLE `bns_menu` DISABLE KEYS */;
INSERT INTO `bns_menu` (`id`, `name`, `nw_field`, `url`, `ctime`) VALUES
	(2, '新的', 'news', 'index.php?app=news', 12312312),
	(3, '窗口', 'iframe', 'index.php?app=iframe', 1503993014),
	(4, '翻译', 'translate', 'index.php?app=translate', 1503993154),
	(5, 'Mysql日志', 'SqlTrace', 'index.php?app=SqlTrace', 1503993287);
/*!40000 ALTER TABLE `bns_menu` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
