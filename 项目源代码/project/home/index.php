<?php

	//本页面是用来显示后台的 通过控制层
session_start();
	error_reporting(E_ALL & ~E_NOTICE);
	//引入配置文件
	include('../public/dbconfig.php');
	//判断传进来的参数 
	//m ：类名
	//a : 方法名
	
	// $class = !empty($_GET['m'])?ucfirst(strtolower($_GET['m'])):'Index';
	// if(isset($_SESSION['user']['id'])){
	// 	$method = !empty($_GET['a'])?strtolower($_GET['a']):'index';
	// }else{
	// 	$method = !empty($_GET['a'])?strtolower($_GET['a']):'showindex';
	// }
	$class = !empty($_GET['m'])?ucfirst(strtolower($_GET['m'])):'Index';
	$method = !empty($_GET['a'])?strtolower($_GET['a']):'index';
	//魔术方法，自动加载类
	function __autoload($className){
		//先判断是不是控制层的类
		if(ucfirst(strtolower(substr($className,-7))) == 'Control'){
			$cfile = './control/'.$className.'.class.php';
			
			if(file_exists($cfile)){
				//文件路径存在开始引入
				include($cfile);
			}else{ 
				echo '<script>alert("这个类不存在");location="./view/error.html";</script>';
				die($className.'这个类不存在');
			} 
			
		}else if(ucfirst(strtolower(substr($className,-5))) == 'Model'){
			$mfile = './model/'.$className.'.class.php';
			//echo $mfile;
			if(file_exists($mfile)){
				//模型层文件存在引入
				include($mfile);
			}else{
				echo '<script>alert("这个类不存在");location="./view/error.html";</script>';
				die($className.'这个类不存在');
			}

		}else{
			//都不是说明是其他类的文件
			$ofile = './org/'.$className.'.class.php';
			if(file_exists($ofile)){
				//echo $ofile;
				include($ofile);
			}else{
				echo '<script>alert("这个类不存在");location="./view/error.html";</script>';
				die($className.'这个类不存在');
			}
		}

	}






	//拼接类名
	$name = ucfirst($class).'Control';
	//实例化
	$p = new $name;

	//调用方法
	$p->$method();
	//var_dump($p);









?>

