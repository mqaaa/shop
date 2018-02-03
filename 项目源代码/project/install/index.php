<?php

	//包含类
	include('./Install.class.php');

	// 调用方法
	$m = new Install();
	$method = isset($_GET['a'])?$_GET['a']:'show';
	$m->$method();	











?>