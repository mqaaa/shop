<?php


class GoodsControl
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

	    // /*搜索权限条件*/where typeid in(
	   //当选择权限时，把它下面的都显示出来 	
	    $searchlev= $lev="";
		if(isset($_GET['lev']) && $_GET['lev'] != ''){
		$wherelist[] = 'typeid in(select id from type where concat(path,id) like "%'.$_GET['lev'].'%")';
			$searchlev = '&lev='.$_GET['lev'];
			$lev = $_GET['lev'];  //保持等级搜索框的值
		
		}
		/*搜索区间条件*/
		$qujian = $num1 = $num2 = '';
		if(isset($_GET['num1']) && isset($_GET['num2']) && $_GET['num1']!='' ){
			if( $_GET['num2']=='' || $_GET['num2']<$_GET['num1']){
				$wherelist[] = 'price > '.$_GET['num1'];
			}else{
				$wherelist[] = 'price between '.$_GET['num1'].' and '.''.$_GET['num2'].'';
			}
			
			$qujian = '&num1='.$_GET['num1'].'&num2='.$_GET['num2']; //要放在翻页那里
			$num1 = $_GET['num1']; //保持搜索框的值
			$num2 = $_GET['num2']; //保持搜索框的值
		}
			

		$like = '';
	    if(count($wherelist)>0){    
	    //如果条件数组有东西 则让like 开始拼接
			$like = implode(' and ',$wherelist);
			//var_dump($like);
		}
   		 /********搜索*******/


		$g = new MysqlModel('goods');
		$g->where($like);	
		$row1 = $g->select();
		$t = new MysqlModel('type');
		$lanmu = $t->order('concat(path,id) asc')->select();
		$arr1 =array(','=>'->','0'=>'顶级');
		if($lanmu){
			foreach($lanmu as $k=>$v){
			
       			 $arr1[$v['id']] = $v['name'];
			}
		}
		
		
		//分页类调用
		$page= new Page(count($row1),4);
		//var_dump($page->limit());
		$limit = $page->limit();
		//搜索条件决定显示的内容(正反排序);
		if(isset($_GET['order'])){
			$g->order($_GET['order']);
		}
		$g->where($like);	
		
		

		$goods = $g->limit($limit)->select();
		echo $g->sql;	
		date_default_timezone_set('PRC');
		include('./view/goods/goodslist.html');


	}

	function add(){

		if(isset($_POST['typeid']) && $_POST['typeid']=='xz'){
			echo '<script>alert("请输入商品类别");
			location="index.php?m=goods&a=add";</script>';
		}
		//传入一个ID，表明要修改,
		if(isset($_GET['id'])){
			$t = new MysqlModel('type');
			$lanmu = $t->order('concat(path,id) asc')->select();
			date_default_timezone_set('PRC');
			$g = new MysqlModel('goods');
			$goods = $g->where("id={$_GET['id']}")->select();
			$title = '商品详情';
			$method = 'update';
			$bixu = '';
		}else{
			$t = new MysqlModel('type');
			$lanmu = $t->order('concat(path,id) asc')->select();
			$title = '添加商品';
			$method = 'do_add';
			$bixu = 'required';
		}


		include('./view/goods/add.html');


	}

	function do_add(){

		//判断传过来的物品有没有值 有就是修改

		//这里接受商品详情页传过来的添加信息
		//var_dump($_POST);

		$m = new MysqlModel('goods');
		//要判断传过来的图片
		$tu = new Upload('pic','./uploadimg/goods');

		//这个函数返回存入的图片信息(路径，名字)
		$img = $tu->upload();
		$msg = '';
		if(is_array($img)){
			$_POST['pic'] = $img['name'];
		}else{
			$msg = $img; 
		}
		
		
		//添加时间 转换为时间戳
		$_POST['addtime'] = time();
		
		//var_dump($_POST);
		$res = $m->insert($_POST);
		echo $m->sql;
		//var_dump($res);
		
		//判断
		if($res){
			echo '<script>alert("商品添加成功");
			location="index.php?m=goods&a=show"</script>';
		}else{
			echo '<script>alert("商品添加失败,'.$msg.'");
			location="index.php?m=goods&a=add";</script>';
		}
		// &id='.$_POST['uid'].'&u='.$_POST['username'].'

	}

	function update(){
		//这里用来修改
		$m = new MysqlModel('goods');
		//如果传过来的图片就表示要修改
		$flag = false;
		if($_FILES['pic']['name']){
			$tu = new Upload('pic','./uploadimg/goods');

			//这个函数返回存入的图片信息(路径，名字)
			$img = $tu->upload();
			$flag = true;
			$msg = '';
			if(is_array($img)){
				unlink("./uploadimg/goods/{$_POST['img']}");
				$_POST['pic'] = $img['name'];
			}else{
				$msg = $img; 
			}	
			
		}else{
			unset($_POST['pic']);
		}
		//不修改时间
		unset($_POST['addtime']);
		//var_dump($_POST);
		$res = $m->update($_POST);

		//var_dump($res);
		
		//判断
		if($res || $flag){
			echo '<script>alert("商品修改成功");
			location="index.php?m=goods&a=show"</script>';
		}else{
			echo '<script>alert("商品修改失败,'.$msg.'");
			location="index.php?m=goods&a=add";</script>';
		}
		// &id='.$_POST['uid'].'&u='.$_POST['username'].'

	}
	//多重删除
	function dels(){
		if(isset($_POST['dels']) ){
			$m = new MysqlModel('goods');
			$str = implode(',',$_POST['dels']);
			$where = 'id in('.$str.')';
			$ids = $m->field('pic')->where($where)->select();
			echo $m->sql;

			foreach($ids as $k=>$v){
				unlink("./uploadimg/goods/{$v['pic']}");
			}
		}elseif(isset($_GET['id'])){
			$m = new MysqlModel('goods');
			$where = 'id ='.$_GET['id'];
			$ids = $m->field('pic')->where($where)->select();
			echo $m->sql;
			unlink("./uploadimg/goods/{$ids[0]['pic']}");
		}else{
			echo '<script>alert("请选择要删除的商品");location="./index.php?m=goods&a=show"</script>';
		}	
			$res = $m->where($where)->del();
			if($res){
					//同时要删除商品图片

					echo '<script>alert("删除成功");location="./index.php?m=goods&a=show"</script>';

			}else{
				echo '<script>alert("删除失败");location="./index.php?m=goods&a=show"</script>';
			}
			
		
			
		
		
	}
}















?>
