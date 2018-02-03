<?php
class UserControl{

		function login(){
			//会员登录模块
			//var_dump($_SESSION);	
			// var_dump($_SERVER);
			//把跳过来的页面地址保存在cookie中
			setcookie('path',$_SERVER['HTTP_REFERER'],time()+600,'/'); 
			// $str = 'http://'.$_SERVER['SERVER_NAME'].'/baoan/project2/projectbao/home/index.php?m=user&a=login';
			// 				echo $str;	
			include('./view/login.html');
			
		}

		function check(){
			var_dump($_POST);
			if(isset($_POST['username']) && isset($_POST['pwd'])){
				echo 111;
				//这里要跟后台的对接
				try {

					$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
					$pdo = new PDO($dsn,DB_USER,DB_PWD);
					$pdo->exec("set names ".DB_CHARSET);
					//准备与处理对象
					//进行数据比对
					$pwd = md5($_POST['pwd']);
					$sql = "select * from user where username='{$_POST['username']}' and pwd='{$pwd}'";
					//var_dump($_SERVER);
					//echo $sql; exit;
					$stmt = $pdo->query($sql);
				
					if($stmt->rowCount()>0){
						//登陆成功
						//获取到登陆会员的信息

						$info = $stmt->fetch(PDO::FETCH_ASSOC);
						//如果用户被禁用或者删除(拉黑)remove=0 那么不可以登录 
						if($info['level'] != 2 && $info['remove']!=0){
							//制作session
							$_SESSION['god']['islogin'] = true;
							$_SESSION['god']['id'] = $info['id'];
							
							$arr = array('普通会员','VIP会员','禁用','超级管理员');
							$_SESSION['god']['level'] = $arr[$info['level']];
							//获取头像地址
							$sql2 = "select * from user_info where uid={$info['id']}";
							$stmt2 = $pdo->query($sql2);
							$user = $stmt2->fetch(PDO::FETCH_ASSOC);
							$_SESSION['god']['pic'] = $user['pic'];
							$_SESSION['god']['name'] = $user['name'];
							$str = 'http://'.$_SERVER['SERVER_NAME'].'/baoan/project2/projectbao/home/index.php?m=user&a=login';
							//echo $str;				
							$str2 = 'http://'.$_SERVER['SERVER_NAME'].'/baoan/project2/projectbao/home/index.php?m=user&a=sign';
							//echo $_COOKIE['path'];exit;
							//echo $_SERVER['HTTP_REFERER']; exit;
							//isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=$str && $_SERVER['HTTP_REFERER']!=$str2	

							if(isset($_COOKIE['path']) && $_COOKIE['path']!=$str && $_COOKIE['path']!=$str2	){
								echo '<script>alert("登录成功");location="'.$_COOKIE['path'].'"</script>';
							}else{
								echo '<script>alert("登录成功");location="index.php"</script>';
							}

							
						}else{
							echo '<script>alert("你已被封号.");location="index.php?m=user&a=login";</script>';
						}
						
					}else{
						echo '<script>alert("密码或用户名有误");location="index.php?m=user&a=login";</script>';
					}


					
				} catch (PDOException $e) {
					
				}

			}
				
		}

		function sign(){

			//
			//var_dump($_SERVER);

			include('./view/sign.html');
		}

		function do_sign(){
			session_start();
			//验证码
			if(strtolower($_POST['code']) == strtolower($_SESSION['code'])){
				
			}else{
				echo '<script>alert("验证码错误");location="index.php?m=user&a=sign";</script>';
				die();
			}
			try {
				$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
				$pdo = new PDO($dsn,DB_USER,DB_PWD);
				$pdo->exec("set names ".DB_CHARSET);
				//判断用户名是否存在

				$sqlname = "select * from user where username='{$_POST['username']}'";
				echo $sqlname;
				$re = $pdo->query($sqlname);
				$a = $re->fetch(PDO::FETCH_ASSOC);
				if(!empty($a)){
					echo '<script>alert("用户名已存在");location="./index.php?m=user&a=sign"</script>';
					die();
				}
			//添加会员函数
			//需要表单传过来的post值
			//先判断密码
			if(!empty($_POST['pwd']) && !empty($_POST['repwd'])){
				if($_POST['pwd'] == $_POST['repwd']){
					//加密
					$pwd = md5($_POST['pwd']);
				}else{
					echo '<script>alert("两次密码不一致");location="./index.php?m=user&a=sign"</script>';
					die();
				}
			}

		

				$time = time();//注册时间
				$sql = "insert into user(username,pwd,addtime) value('{$_POST['username']}','{$pwd}',{$time})";
				//添加时间
				
				$row = $pdo->exec($sql);
				$newid = $pdo->lastinsertid();
				if($row){
					//制作session
						$_SESSION['god']['islogin'] = true;
						$_SESSION['god']['id'] = $newid;
						
						$arr = array('普通会员','VIP会员','禁用','超级管理员');
						$_SESSION['god']['level'] = $arr[0];
						//获取头像地址
												
						$_SESSION['god']['name'] = $_POST['username'];


					echo '<script>alert("注册成功");location="index.php"</script>';

				}else{
					echo '<script>alert("注册失败");location="index.php?m=user&a=sign"</script>';
				}

				



			} catch (PDOException $e) {

				echo $e->getMessage();

			}	
			

		}

		function info(){
			header("location:index.php?m=info&a=show");
		}
		//退出登录操作
		function out(){
			$_SESSION['god'] = array();
			echo '<script>alert("退出成功");location="index.php";</script>';
		}



}
	



?>