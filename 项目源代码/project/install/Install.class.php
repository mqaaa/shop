<?php

class Install
{


	function show(){
		include('./index.html');
	}

	function yuedu(){
		//这里判断是否阅读
		if(isset($_POST['read']) && $_POST['read']==1){
			//进行下一步
			include('./myserver.html');
		}else{
			echo '<script>alert("请先阅读条款");location="index.php";</script>';
		}

	} 

	function config(){
		include('./config.html');
	}
	function do_config(){
		//var_dump();exit;
		//这里接收到配置页面传过来的数据
		//判断是否安装测试数据
		//
		$link = mysqli_connect("{$_POST['db_host']}","{$_POST['db_user']}","{$_POST['db_pwd']}");
		mysqli_query($link,"DROP DATABASE IF EXISTS {$_POST['db_name']}");
		mysqli_query($link,"CREATE DATABASE IF NOT EXISTS {$_POST['db_name']}");
		mysqli_select_db($link,"{$_POST['db_name']}");
		mysqli_set_charset($link,'utf8');
		include('./sql.php');
		foreach($sqlarr as $k=>$v){
			mysqli_query($link,$v);
			if($k%2==1){
				//说明是建表语句
				//匹配正则 方便查看
				$pattern = "/CREATE\s+TABLE\s+\`(.*?)\`/Ss";
				preg_match($pattern,$v,$match);
				echo '创建'.$match[1].'表成功<br>';	
			}

		}
		$pwd = md5($_POST['pswd']);
		$time = time();
		//建表成功后开始插入管理员账户
		$sql = "INSERT INTO {$_POST['db_prefix']}user(username,pwd,level,addtime) VALUES('{$_POST['admin']}','{$pwd}',3,{$time})";

		$res = mysqli_query($link,$sql);
		var_dump($res);
		if($res && mysqli_affected_rows($link)>0){
			echo "安装成功";
			echo '<a href="../index.php">去前台</a>';
			echo '<a href="../admin">去后台</a>';
			//删除而文件锁
			unlink('./flag.lock');
		}else{
			echo '安装失败';
		}

		if(isset($_POST) && $_POST['ceshi']==1){
			//安装测试数据
			include('./data.php');
			foreach($data as $v){
				mysqli_query($link,$v);
			}
		}

		//更改项目中数据库配置文件
		$str = <<<EOF
<?php
		define('DB_HOST','{$_POST['db_host']}');
		define('DB_USER','{$_POST['db_user']}');
		define('DB_PWD','{$_POST['db_pwd']}');
		define('DB_CHARSET','utf8');
		define('DB_NAME','{$_POST['db_name']}');
		define('DB_PREFIX','{$_POST['db_prefix']}');
		//设置时区
		date_default_timezone_set('PRC');
EOF;
	//替换配置文件里面的内容
	file_put_contents('../public/dbconfig.php',$str);



	}


}




?>