
<?php

class LoginControl{


	function show(){


		
		include './view/index.html';
	}	
	function login(){
		//这里接受登陆的值 --判断 只有超级管理员可以登录 去user表里面查
		//var_dump($_POST);
		if(isset($_POST['name']) && isset($_POST['pwd'])){
			
			//main表 
			$admin = new MysqlModel('user');
			
			
			$pwd = md5($_POST['pwd']);
			
			$res = $admin->where("username='{$_POST['name']}' and pwd='{$pwd}'")->select();
			//var_dump($res);
			//echo $admin->sql;
			//查到会员登录的信息


			//得到结果
			if(!empty($res[0])){
				//判断权限是不是超级管理员
				if($res[0][level] == 3){
						//制作session
					$_SESSION['user']['islogin'] = true;
					$_SESSION['user']['name'] = $res[0]['username'];
					$_SESSION['user']['id'] = $res[0]['id'];
					//登陆成功后保存本次登录的时间
					$timedir = './cache/timedir/'.$_SESSION['user']['name'].$_SESSION['user']['id'].'.txt';
					if(file_exists($timedir)){
						$pretime = file_get_contents($timedir);
					}else{
						$pretime = time();
					} 
					//$pretime = file_get_contents($timedir);
					$_SESSION['user']['pretime'] = $pretime;
					file_put_contents($timedir,time());

					//登陆成功后保存登陆次数加一
					if(!file_exists('./cache/logintimes')){
						file_put_contents('./cache/logintimes',1);
					}
					$logintimes = file_get_contents('./cache/logintimes');
					$logintimes ++;
					file_put_contents('./cache/logintimes',$logintimes);
						echo '<script>alert("亲爱的'.$res[0]['username'].'欢迎回来");location="./index.php";</script>';
				}else{
					echo '<script>alert("权限不足,不能登录");location="./index.php";</script>';
				}	
				
			}else{
					echo '<script>alert("密码或用户名不正确");location="./index.php";</script>';
			}
		}

	}

	//退出操作
	function out(){

		//1。设置cookie 让session过期
		//setcookie(session_name(),'',-1,'/');
		//2.清空session值
		$_SESSION['user'] = array();
		//3.销毁服务器的session文件
		//session_destroy();

		echo '<script>alert("退出成功");location="./index.php";</script>';

	}

	

}









?>