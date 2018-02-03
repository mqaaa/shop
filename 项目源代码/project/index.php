<?php

	//如果用户是第一次访问，就提示安装
	if(file_exists("./install/flag.lock")){

		header("location:./install/index.php");

	}else{

		header('location:./home/index.php');
		//不存在，说明不是首次登录
		

	}
	
	



?>