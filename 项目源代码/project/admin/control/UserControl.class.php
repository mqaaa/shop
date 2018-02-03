<?php

class UserControl
{
	//搜索类的成员属性
	//public $search;
	//这个类实现所有的会员操作

	/********会员列表信息模块*******/
	function show(){

	/********搜索*******/
		
		$s = new Search();
		$like = $s->sou();


    /********搜索*******/

    	//var_dump($_POST);
		//var_dump($_GET);
		$m = new MysqlModel('user');
		//需要查询表中的所有内容
		
		$m->where($like);

		$row1 = $m->select();

		echo $m->sql;
		
		//分页类调用
		$page= new Page(count($row1),5);
		//var_dump($page->limit());
		$limit = $page->limit();
		//搜索条件决定显示的内容(正反排序)
		if(isset($_GET['order'])){
			$m->order($_GET['order']);
		}

		$m->where($like);
		//执行查询，决定会员列表页显示的条数
		$row = $m->limit($limit)->select();
		//var_dump($row);
		//制作权限转换数组
		$level = array('普通','<font color="red">VIP</font>','<font color="#ccc">禁用</font>','<font color="green">超级管理员</font>');
		date_default_timezone_set('PRC');
		include('./view/user/right.html');


	}

	function add(){

		//判断传过来的是修改还是添加
		//传过来ID就是修改 否则就是添加
		if(!empty($_GET['id'])){
			$id = $_GET['id'];
			//说明是修改
			$title = "修改会员信息";
			$method = 'update'; //改变$method 可以让index页面直接调用update方法
			//把ID传过去
			$hidden = '<input type="hidden" name="id" value="'.$_GET['id'].'">';
			$m = new MysqlModel('user');
			$res = $m->where("id={$id}")->select(); 
			//var_dump($res);
		}else{
			//说明是添加
			$title = "添加会员";
			$method = "go_add";//改变$method 可以让index页面直接调用go_add方法
			$hidden = '';
			$res = '';
		}

		include('./view/user/form.html');
	}

	function go_add(){
		//添加会员函数
		//需要表单传过来的post值
		//先判断密码
		//判断用户名是否存在
		$m = new MysqlModel('user');
		$re = $m->where("username='{$_POST['username']}'")->select();
		echo $m->sql;
		//var_dump($re);exit;
		if(!empty($re)){
			echo '<script>alert("用户名已存在");location="./index.php?m=user&a=add"</script>';
			die();
		}
		if(!empty($_POST['pwd']) && !empty($_POST['repwd'])){
			if($_POST['pwd'] == $_POST['repwd']){
				//加密
				$_POST['pwd'] = md5($_POST['pwd']);
			}else{
				echo '<script>alert("两次密码不一致");location="./index.php?m=user&a=add"</script>';

			}
		}else{
			unset($_POST['pwd']); //由于model类的需要 所以要删除
		}
		
		if($_POST['level'] == 'xz'){
			echo '<script>alert("请输入权限");location="./index.php?m=user&a=add"</script>';
		}
		//添加时间
		$_POST['addtime'] = time();
		//执行插入操作
		var_dump($_POST);
		

		$res = $m->insert($_POST);
		echo $m->sql;
		if($res){ //返回一个影响行
			
			echo '<script>alert("插入成功");location="./index.php?m=user&a=show"</script>';

		}else{
			echo '<script>alert("插入失败");location="./index.php?m=user&a=show"</script>';
		}
	}

	function update(){
		//修改会员信息函数
		//需要表单传过来的post值
		//先判断密码,如果为空 就是不需要修改密码
		if(!empty($_POST['pwd']) && !empty($_POST['repwd'])){
			if($_POST['pwd'] == $_POST['repwd']){
				//加密
				$_POST['pwd'] = md5($_POST['pwd']);
			}else{
				echo '<script>alert("两次密码不一致");location="./index.php?m=user&a=add&id='.$_POST['id'].'"</script>';
			}
		}else{
			unset($_POST['pwd']); //由于model类的需要 所以要删除
		}
		
		if($_POST['level'] == 'xz'){
			echo '<script>alert("请输入权限");location="./index.php?m=user&a=add&id='.$_POST['id'].'"</script>';
		}

		//执行插入操作
		$m = new MysqlModel('user');
		//var_dump($_POST);
		
		if($m->update($_POST)){ //返回一个影响行
			
			echo '<script>alert("修改成功");location="./index.php?m=user&a=show"</script>';

		}else{
			
			echo '<script>alert("修改失败");location="./index.php?m=user&a=show"</script>';
		}
	}

	//删除操作函数
	function truedel(){
		//需要穿过来的ID

		$m = new MysqlModel('user');
		$res = $m->del();
		

		if($res){
			$um = new MysqlModel('user_info');
			$pic = $um->field('pic')->where("uid={$_GET['id']}")->select();
			$res2 = $um->where("uid={$_GET['id']}")->del(); 
			
			//删除头像
			if($res2){
				unlink("./uploadimg/user/{$pic[0]['pic']}");
			}
			
				echo '<script>alert("删除成功");location="./index.php?m=user&a=rshow"</script>';

		}else{
			echo '<script>alert("删除失败");location="./index.php?m=user&a=rshow"</script>';
		}
	}
	////这是真的删除
	function truedels(){
		if(isset($_POST['dels'])){
			$m = new MysqlModel('user');
			$str = implode(',',$_POST['dels']);
			$where = 'id in('.$str.')';
			$infowhere = 'uid in('.$str.')';

			$res = $m->where($where)->del();
			if($res){
				$um = new MysqlModel('user_info');
				$pics = $um->field('pic')->where($infowhere)->select();
				$res2 = $um->where($infowhere)->del();
				//同时删除对应的头像
				if($res2){
					foreach($pics as $k=>$v){

						unlink("./uploadimg/user/{$v['pic']}");
					} 
				}
				
					echo '<script>alert("删除成功");location="./index.php?m=user&a=rshow"</script>';

			}else{
				echo '<script>alert("删除失败");location="./index.php?m=user&a=rshow"</script>';
			}
			
		}else{
			echo '<script>alert("请选择要删除的会员");location="./index.php?m=user&a=rshow"</script>';
		}
		
	}
	/********会员详细信息模块*******/
	function info(){
		
		//判断是修改还是添加页面//--》需要去数据库中查询才能判断；有信息为修改，没有信息为添加
		//数据库连接步骤
		$m = new MysqlModel('user_info');
		// $link = mysqli_connect('localhost','root','','demo') or die('数据库连接失败或者选择失败');
		// //设置字符集
		// mysqli_set_charset($link,'utf8');
		//查询语句,在会员信息表中执行
		$username = $_GET['u'];
		$id = $_GET['id'];

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
			$hidden = '<input type="hidden" name="uid" value="'.$_GET['id'].'">';
			$picrequired = '';
		}else{
			//表示为添加
			$title = '添加会员信息';
			$method = 'addinfo';
			$submit = '添加';
			$hidden = '<input type="hidden" name="uid" value="'.$_GET['id'].'">';
			$picrequired = 'required';
			//var_dump($hidden);
		}
		//var_dump(123);
		include('./view/user/userinfo.html');	
	}
	function updateinfo(){
			//此if成立说明是执行修改信息。
		//var_dump($_POST);
		//var_dump($_FILES);exit;
		//此模块需要传入的uID
		
		if(!empty($_FILES['pic']['name'])){
			$oldimg = './uploadimg/'.$_POST['img'].'';	
			file_exists($oldimg)?unlink($oldimg):'';
			$tu = new Upload('pic','./uploadimg');
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
			location="index.php?m=user&a=info&id='.$_POST['uid'].'&u='.$_POST['username'].'&ji='.$_POST['ji'].'";</script>';
		}else{
			echo '<script>alert("修改失败");
			location="index.php?m=user&a=info&id='.$_POST['uid'].'&u='.$_POST['username'].'&ji='.$_POST['ji'].'";</script>';
		}
		


	}
	function addinfo(){
	//此模块需要传入的ID以及用户名
		// var_dump($_POST);
		// var_dump($_FILES);
		// exit;
		$m = new MysqlModel('user_info');
		//要判断传过来的图片
		$tu = new Upload('pic','./uploadimg/user');

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
			location="index.php?m=user&a=info&id='.$_POST['uid'].'&u='.$_POST['username'].'&ji='.$_POST['ji'].'";</script>';
		}else{
			echo '<script>alert("添加失败,信息不全");
			location="index.php?m=user&a=info&id='.$_POST['uid'].'&u='.$_POST['username'].'&ji='.$_POST['ji'].'";</script>';
		}

		
	}

	////////////回收站模块///////////////////////////
	//主要是操作查询类中的 ’R‘=‘remove=1’条件;

	function rshow(){
		//回收站显示页面

		/********搜索*******/
		
		$s = new Search();
		$s->wherelist['R'] = 'remove=0';
		$like = $s->sou();

    /********搜索*******/

    	//var_dump($_POST);
		//var_dump($_GET);
		$m = new MysqlModel('user');
		//需要查询表中的所有内容
		
		$m->where($like);

		$row1 = $m->select();

		echo $m->sql;
		
		//分页类调用
		$page= new Page(count($row1),5);
		//var_dump($page->limit());
		$limit = $page->limit();
		//搜索条件决定显示的内容(正反排序)
		if(isset($_GET['order'])){
			$m->order($_GET['order']);
		}

		$m->where($like);
		//执行查询，决定会员列表页显示的条数
		$row = $m->limit($limit)->select();
		//var_dump($row);
		//制作权限转换数组
		$level = array('普通','<font color="red">VIP</font>','<font color="#ccc">禁用</font>','<font color="green">超级管理员</font>');
		date_default_timezone_set('PRC');


		include('./view/user/remove.html');
	}
	//放入回收站
	function do_remove(){
		 //这里接受传过来的ID值，将其设置为0即可
		//$_GET['id']
		//update user set remove=0 where id in (可多选);
		$remove = array('remove'=>0);
		if(isset($_GET['id']) || isset($_POST['dels'])){
			//说明修改单条ID
			$dis = '';
			if(isset($_GET['id'])){
				$ids = $_GET['id']; 
			}elseif(isset($_POST['dels'])){
				$ids = implode(',',$_POST['dels']);
			}


			$m = new MysqlModel('user');
			$res =$m->where("id in({$ids})")->update($remove);
			//var_dump($remove);
			echo $m->sql;
			if($res){
				//说明修改成功	
				echo '<script>alert("放入回收站成功");location="./index.php?m=user&a=show"</script>';
				die();
			}else{
				echo '<script>alert("放入回收站失败");location="./index.php?m=user&a=show"</script>';
				die();
			}

		}else{
				echo '<script>alert("请选择要删除的ID");location="./index.php?m=user&a=show"</script>';
				die();
		}

	}
	//回收站还原
	function replace(){
		 //这里接受传过来的ID值，将其设置为0即可
		//$_GET['id']
		//update user set remove=0 where id in (可多选);
		$remove = array('remove'=>1);
		if(isset($_GET['id']) || isset($_POST['dels'])){
			//说明修改单条ID
			$dis = '';
			if(isset($_GET['id'])){
				$ids = $_GET['id']; 
			}elseif(isset($_POST['dels'])){
				$ids = implode(',',$_POST['dels']);
			}


			$m = new MysqlModel('user');
			$res =$m->where("id in({$ids})")->update($remove);
			//var_dump($remove);
			echo $m->sql;
			if($res){
				//说明修改成功	
				echo '<script>alert("还原成功");location="./index.php?m=user&a=rshow"</script>';
				die();
			}else{
				echo '<script>alert("还原失败");location="./index.php?m=user&a=rshow"</script>';
				die();
			}

		}else{
				echo '<script>alert("请选择要还原的ID");location="./index.php?m=user&a=rshow"</script>';
				die();
		}

	}
}




?>