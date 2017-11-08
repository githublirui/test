<?php

$link = new mysqli();
$link->real_connect('127.0.0.1', 'root', 'root', 'lrtest', null, null, MYSQLI_CLIENT_COMPRESS);
$sql = "CREATE TABLE IF NOT EXISTS `wxz_live_setting` (
	`id` INT (11) NOT NULL AUTO_INCREMENT,
	`type` VARCHAR (50) NOT NULL DEFAULT '',
	`title` VARCHAR (250) NOT NULL DEFAULT '',
	`desc` text NOT NULL DEFAULT '',
	`img` VARCHAR (255) NOT NULL DEFAULT '',
	`link` VARCHAR (255) NOT NULL DEFAULT '',
	`create_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`update_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE = INNODB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `testaa`(
	`id` INT (11) NOT NULL AUTO_INCREMENT,
	`img` VARCHAR (255) NOT NULL DEFAULT '',
	`link` VARCHAR (255) NOT NULL DEFAULT '',
	`is_show` TINYINT (1) NOT NULL DEFAULT '1',
	`sort_order` INT (11) NOT NULL DEFAULT '0',
	`create_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`update_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE = INNODB DEFAULT CHARSET = utf8";
$ret = $link->query($sql);

var_dump($ret);
var_dump($link->errno);
var_dump($link->error);
die;
?>
