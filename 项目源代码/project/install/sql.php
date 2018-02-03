<?php


	$sqlarr = array(
		
		"DROP TABLE IF EXISTS `goods`;",
		"CREATE TABLE `goods` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `typeid` int(11) NOT NULL,
		  `name` varchar(60) NOT NULL,
		  `price` decimal(8,2) NOT NULL,
		  `pic` char(37) NOT NULL,
		  `company` varchar(255) NOT NULL,
		  `descr` text NOT NULL,
		  `state` tinyint(3) unsigned NOT NULL DEFAULT '0',
		  `store` smallint(5) unsigned NOT NULL DEFAULT '0',
		  `num` smallint(5) unsigned NOT NULL DEFAULT '0',
		  `clicknum` int(10) unsigned NOT NULL DEFAULT '0',
		  `addtime` int(10) unsigned NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;",

		"DROP TABLE IF EXISTS `link`;",
		"CREATE TABLE `link` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(32) NOT NULL,
		  `url` varchar(255) NOT NULL,
		  `type` tinyint(4) NOT NULL,
		  `state` tinyint(4) NOT NULL,
		  `addtime` int(10) unsigned NOT NULL,
		  `img` varchar(255) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;",

		"DROP TABLE IF EXISTS `main`;",
		"CREATE TABLE `main` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `admin` varchar(18) NOT NULL,
		  `pwd` char(32) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;",

		"DROP TABLE IF EXISTS `order_info`;",
		"CREATE TABLE `order_info` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `orderid` int(11) NOT NULL,
		  `goodsid` int(11) NOT NULL,
		  `name` varchar(60) NOT NULL,
		  `price` decimal(8,2) NOT NULL,
		  `gnum` smallint(6) NOT NULL,
		  `uid` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;",

		"DROP TABLE IF EXISTS `orders`;",
		"CREATE TABLE `orders` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `uid` int(11) NOT NULL,
		  `linkman` varchar(30) NOT NULL,
		  `address` varchar(255) NOT NULL,
		  `code` char(6) NOT NULL,
		  `phone` char(11) NOT NULL,
		  `addtime` int(10) unsigned NOT NULL,
		  `total` decimal(8,2) NOT NULL,
		  `status` tinyint(4) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;",

		"DROP TABLE IF EXISTS `type`;",
		"CREATE TABLE `type` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(30) NOT NULL,
		  `pid` int(11) NOT NULL DEFAULT '0',
		  `path` varchar(255) NOT NULL DEFAULT '0,',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;",

		"DROP TABLE IF EXISTS `user`;",
		"CREATE TABLE `user` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `username` varchar(13) NOT NULL,
		  `pwd` char(32) NOT NULL,
		  `level` tinyint(4) NOT NULL DEFAULT '0',
		  `addtime` int(10) unsigned NOT NULL,
		  `remove` tinyint(4) NOT NULL DEFAULT '1',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;",

		"DROP TABLE IF EXISTS `user_info`;",
		"CREATE TABLE `user_info` (
		  `uid` int(11) NOT NULL,
		  `name` varchar(30) NOT NULL,
		  `sex` tinyint(4) NOT NULL DEFAULT '0',
		  `age` tinyint(3) unsigned NOT NULL,
		  `hobby` varchar(255) NOT NULL,
		  `phone` char(11) NOT NULL,
		  `address` varchar(255) NOT NULL,
		  `hunfou` tinyint(3) unsigned NOT NULL DEFAULT '1',
		  `birthday` int(10) unsigned NOT NULL,
		  `pic` char(37) NOT NULL,
		  `code` char(6) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`uid`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;"


		);












?>