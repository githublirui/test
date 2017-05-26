-- phpMyAdmin SQL Dump
-- version 3.4.8
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 08 月 26 日 23:37
-- 服务器版本: 5.1.61
-- PHP 版本: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `addata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ad_type` varchar(234) NOT NULL,
  `ad_content` mediumtext NOT NULL,
  `ad_list` varchar(25) NOT NULL,
  `pv` varchar(30) NOT NULL,
  `endtime` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pv` (`pv`),
  KEY `endtime` (`endtime`),
  KEY `ad_list` (`ad_list`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;


CREATE TABLE IF NOT EXISTS `admindata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `q` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `password` (`password`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `admindata`
--

INSERT INTO `admindata` (`id`, `username`, `password`, `q`) VALUES
(8, 'admin', 'asdasd', '最高权限'),
(10, 'admin123', 'admin123', '最高权限');



CREATE TABLE IF NOT EXISTS `article` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `top` varchar(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `pic` varchar(999) NOT NULL,
  `type` varchar(25) NOT NULL,
  `pv` varchar(20) NOT NULL,
  `pv_max` varchar(30) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `day` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `title` (`title`),
  KEY `pv` (`pv`),
  KEY `day` (`day`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=186 ;


CREATE TABLE IF NOT EXISTS `koudata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` varchar(30) NOT NULL,
  `aid` int(30) NOT NULL,
  `title` varchar(255) NOT NULL,
  `long` varchar(999) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `day` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;


CREATE TABLE IF NOT EXISTS `newsdata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;


CREATE TABLE IF NOT EXISTS `refererdata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `aid` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `long` mediumtext NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `day` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=232 ;



CREATE TABLE IF NOT EXISTS `txdata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` varchar(30) NOT NULL,
  `sx1` varchar(30) NOT NULL COMMENT '一级上线',
  `realname` varchar(55) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `alipay` varchar(255) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `state` varchar(1) NOT NULL COMMENT '0等待，1确认，2拒绝',
  `time` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;



CREATE TABLE IF NOT EXISTS `typedata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type_pp` decimal(10,2) NOT NULL,
  `type_author` varchar(999) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

--
-- 转存表中的数据 `typedata`
--


CREATE TABLE IF NOT EXISTS `userdata` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tj_id` int(10) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `pass` varchar(20) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `wx` varchar(255) NOT NULL,
  `realname` varchar(25) NOT NULL,
  `alipay` varchar(100) NOT NULL,
  `wgateid` varchar(255) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `kou` varchar(10) NOT NULL,
  `day` varchar(10) NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `phone` (`phone`),
  KEY `pass` (`pass`),
  KEY `tj_id` (`tj_id`),
  KEY `money` (`money`),
  KEY `realname` (`realname`),
  KEY `alipay` (`alipay`),
  KEY `day` (`day`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=156 ;

