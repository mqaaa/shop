<?php

class InfoControl
{

	

	/********会员详细信息模块*******/
	function show(){
		if($_SESSION['god']['islogin']!=true){
			echo '<script>alert("请登录");location="index.php?m=user&a=login"</script>';
			die();
		}
		//判断是修改还是添加页面//--》需要去数据库中查询才能判断；有信息为修改，没有信息为添加
		//数据库连接步骤
		$m = new MysqlModel('user_info');
		// $link = mysqli_connect('localhost','root','','demo') or die('数据库连接失败或者选择失败');
		// //设置字符集
		// mysqli_set_charset($link,'utf8');
		//查询语句,在会员信息表中执行
		$uname = $_SESSION['god']['name'];
		$id = $_SESSION['god']['id'];

		$res = $m->where("uid={$id}")->select();
		//var_dump($res);
		//查到返回一个二维数组，否则false
		//$sql = "select  * from user_info where uid={$_GET['id']}";
		//执行语句，在会员信息表中执行
		//$res = mysqli_query($link,$sql);
		//判断按照ID搜到的信息
		$hidden = '';
		if($res==true){
			//表示为修改
			$title = '修改会员信息';
			$method = 'updateinfo';
			$submit = '修改';
			//将得到的结果
			//var_dump($row);
			$hidden = '<input type="hidden" name="uid" value="'.$id.'">';
			$picrequired = '';
		}else{
			//表示为添加
			$title = '添加会员信息';
			$method = 'addinfo';
			$submit = '添加';
			$hidden = '<input type="hidden" name="uid" value="'.$id.'">';
			$picrequired = 'required';
			//var_dump($hidden);
		}
		//var_dump(123);
		include('./view/userinfo.html');
		include('./view/footer.html');
	}
	function updateinfo(){
			//此if成立说明是执行修改信息。
		//var_dump($_POST);
		//var_dump($_FILES);exit;
		//此模块需要传入的uID
		
		if(!empty($_FILES['pic']['name'])){
			$oldimg = '../admin/uploadimg/user/'.$_POST['img'].'';	
			file_exists($oldimg)?unlink($oldimg):'';
			$tu = new Upload('pic','../admin/uploadimg/user');
			//这个函数返回存入的图片信息(路径，名字)
			$img = $tu->upload();
			//var_dump($img['name']);
			$_POST['pic'] = $img['name'];
			
		}else{
			unset($_POST['pic']);
		}

		$a = implode(',',$_POST['hobby']);
		$_POST['hobby'] = $a;
		//处理年龄 转换为时间戳
		$_POST['birthday'] = mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year']);
		//var_dump($_POST);
		$m = new MysqlModel('user_info');
		$res = $m->update($_POST);
		
		if($res){
			echo '<script>alert("修改成功");
			location="index.php?m=info&a=show&id='.$_POST['uid'].'&u='.$_POST['username'].'&ji='.$_POST['ji'].'";</script>';
		}else{
			echo '<script>alert("修改失败");
			location="index.php?m=info&a=show&id='.$_POST['uid'].'&u='.$_POST['username'].'&ji='.$_POST['ji'].'";</script>';
		}
		


	}
	function addinfo(){
		if($_SESSION['god']['islogin']!=true){
			echo '<script>alert("请登录");location="index.php?m=user&a=login"</script>';
			die();
		}
	//此模块需要传入的ID以及用户名
		// var_dump($_POST);
		// var_dump($_FILES);
		// exit;
		$m = new MysqlModel('user_info');
		//要判断传过来的图片
		$tu = new Upload('pic','../admin/uploadimg/user');

		//这个函数返回存入的图片信息(路径，名字)
		$img = $tu->upload();
		//var_dump($img['name']);
		$_POST['pic'] = $img['name'];
		//处理爱好数组
		
		$a = implode(',',$_POST['hobby']);
		$_POST['hobby'] = $a;
		//处理年龄 转换为时间戳
		$_POST['birthday'] = mktime(0,0,0,$_POST['month'],$_POST['day'],$_POST['year']);

		//var_dump($_POST);
		$res = $m->insert($_POST);
		//var_dump($res);
		//echo $m->sql;exit;
		//判断
		if($res){
			echo '<script>alert("添加成功");
			location="index.php?m=info&a=show&id='.$_POST['uid'].'&u='.$_POST['username'].'&ji='.$_POST['ji'].'";</script>';
		}else{
			echo '<script>alert("添加失败,信息不全");
			location="index.php?m=info&a=show&id='.$_POST['uid'].'&u='.$_POST['username'].'&ji='.$_POST['ji'].'";</script>';
		}

		
	}


}











?>