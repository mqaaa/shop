<?php


class LinkControl
{
	//商品类

	function show(){

		/********搜索*******/
		$search = $sousuo="";	
		$wherelist = array();
	    if(!empty($_GET['search'])){
	        $wherelist[] = 'name like "%'.$_GET['search'].'%"';
	        $search = '&search='.$_GET['search'];
	        $sousuo = $_GET['search']; //保持搜索框的值
	    }

	    /*搜索权限条件*/
	   //  where typeid in(
	   // 当选择权限时，把它下面的都显示出来 
	  // var_dump($_GET);exit;	
	    $searchlev= $lev="";
		if(isset($_GET['type']) && $_GET['type'] != ''){
			$wherelist[] = 'type ='.$_GET['type'];
			$searchlev = '&lev='.$_GET['type'];
			$lev = $_GET['type'];  //保持等级搜索框的值
		
		}
		
			

		$like = '';
	    if(count($wherelist)>0){    
	    //如果条件数组有东西 则让like 开始拼接
			$like = implode(' and ',$wherelist);
			//var_dump($like);
		}
   		 /********搜索*******/


		$ln = new MysqlModel('link');
		$ln->where($like);	
		$link = $ln->select();
		
		
		//分页类调用
		$page= new Page(count($link),2);
		//var_dump($page->limit());
		$limit = $page->limit();

		//搜索条件决定显示的内容(正反排序);
		if(isset($_GET['order'])){
			$ln->order($_GET['order']);
		}
		$ln->where($like);	
		
		
		//var_dump($link);
		$link = $ln->limit($limit)->select();
		echo $ln->sql;
		date_default_timezone_set('PRC');
		include('./view/link/link.html');


	}

	function add(){

		//传入一个ID，表明要修改,
		if(isset($_GET['id'])){
			$ln = new MysqlModel('link');
			$link = $ln->where("id={$_GET['id']}")->select();
			date_default_timezone_set('PRC');
			
			$title = '链接详情';
			$method = 'update';
			
		}else{
			
			
			$title = '添加链接';
			$method = 'do_add';
		}


		include('./view/link/add.html');


	}

	function do_add(){
		//这里接受商品详情页传过来的添加信息
		

		$ln = new MysqlModel('link');
		//要判断传过来的图片
		if(!empty($_FILES['pic']['name'])){
			$tu = new Upload('pic','./uploadimg/links');

			//这个函数返回存入的图片信息(路径，名字)
			$img = $tu->upload();
			$msg = '';
			if(is_array($img)){
				$_POST['img'] = $img['name'];
			}else{
				$msg = $img; 
			}
		
		}else{
			unset($_POST['img']);
			
		}
		
		
		//添加时间 转换为时间戳
		$_POST['addtime'] = time();
		
		//var_dump($_POST);
		$res = $ln->insert($_POST);
		echo $ln->sql;
		//var_dump($res);
		
		//判断
		if($res){
			echo '<script>alert("链接添加成功");
			location="index.php?m=link&a=show"</script>';
		}else{
			echo '<script>alert("链接添加失败");
			location="index.php?m=link&a=add";</script>';
		}
		// &id='.$_POST['uid'].'&u='.$_POST['username'].'

	}

	function update(){
		//这里用来修改链接

		$ln = new MysqlModel('link');
		//如果传过来的图片就表示要修改
		$flag = false;
		if($_FILES['pic']['name']){
			$tu = new Upload('pic','./uploadimg/links');

			//这个函数返回存入的图片信息(路径，名字)
			$img = $tu->upload();
			$flag = true;
			$msg = '';
			if(is_array($img)){
				unlink("./uploadimg/links/{$_POST['img']}");
				$_POST['pic'] = $img['name'];
			}else{
				$msg = $img; 
			}	
			
		}else{
			
			unset($_POST['img']);
		}
		//不修改时间
		unset($_POST['addtime']);
		//var_dump($_POST);exit;
		$res = $ln->update($_POST);

		//var_dump($res);exit;
		
		//判断
		if($res || $flag){
			echo '<script>alert("链接修改成功");
			location="index.php?m=link&a=show"</script>';
		}else{
			echo '<script>alert("链接修改失败");
			location="index.php?m=link&a=add";</script>';
		}
		// &id='.$_POST['uid'].'&u='.$_POST['username'].'

	}
	//多重删除
	function dels(){
		if(isset($_POST['dels']) ){
			$m = new MysqlModel('link');
			$str = implode(',',$_POST['dels']);
			$where = 'id in('.$str.')';
			$ids = $m->field('img')->where($where)->select();
			echo $m->sql;

			foreach($ids as $k=>$v){
				unlink("./uploadimg/links/{$v['img']}");
			}
		}elseif(isset($_GET['id'])){
			$m = new MysqlModel('link');
			$where = 'id ='.$_GET['id'];
			$ids = $m->field('img')->where($where)->select();
			echo $m->sql;
			unlink("./uploadimg/links/{$ids[0]['img']}");
		}else{
			echo '<script>alert("请选择要删除的链接");location="./index.php?m=link&a=show"</script>';
		}	
			$res = $m->where($where)->del();
			if($res){
					//同时要删除商品图片

					echo '<script>alert("删除成功");location="./index.php?m=link&a=show"</script>';

			}else{
				echo '<script>alert("删除失败");location="./index.php?m=link&a=show"</script>';
			}
			
		
			
		
		
	}
}




?>


















?>