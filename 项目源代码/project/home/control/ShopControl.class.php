<?php

class ShopControl{
	//这个类用来显示商品
	// function nav(){
	// 	try {
			
	// 		//在这里点击会显示出数据库的类别
	// 		$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.'';
	// 		$pdo = new PDO($dsn,DB_USER,DB_PWD);
	// 		$pdo->exec('set names '.DB_CHARSET);

	// 		//准备预处理对象
	// 	//查询所有类别 显示在左侧导航条
			
	// 		$sql = 'select * from '.DB_PREFIX.'type';
	// 		//echo $sql;
	// 		$stmt = $pdo->prepare($sql);
	// 		$stmt->execute();
	// 		if($stmt->rowCount()>0){
	// 			$types = $stmt->fetchAll(PDO::FETCH_ASSOC);//得到二维数组
	// 			$pids = $types;
	// 			//var_dump($types);
	// 		}
			


	// 	} catch (PDOException $e) {
	// 		echo $e->getMessage();
	// 	}
		

		

	// }
	

	function show(){
		
		try {
				
				//在这里点击会显示出数据库的类别
				$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.'';
				$pdo = new PDO($dsn,DB_USER,DB_PWD);
				$pdo->exec('set names '.DB_CHARSET);


				//导航栏
				//准备预处理对象
				//查询所有类别 显示在左侧导航条
				
				$sql = 'select * from '.DB_PREFIX.'type';
				//echo $sql;
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				if($stmt->rowCount()>0){
					$types = $stmt->fetchAll(PDO::FETCH_ASSOC);//得到二维数组
					$pids = $types;
					//var_dump($types);
				}
				/********搜索*******/
					$search = "";	
					$wherelist = array();
				    if(!empty($_GET['search'])){
				        $wherelist[] = 'name like "%'.$_GET['search'].'%"';
				        $search = '&search='.$_GET['search'];
				        $sousuo = $_GET['search']; //保持搜索框的值
				    }

					/*搜索价格区间条件*/
					$qujian = '';
					if(isset($_GET['num1']) && isset($_GET['num2']) && $_GET['num1']!='' && $_GET['num2']!=''){
						$wherelist[] = 'id between '.$_GET['num1'].' and '.''.$_GET['num2'].'';
						$qujian = '&num1='.$_GET['num1'].'&num2='.$_GET['num2']; //要放在翻页那里
						$num1 = $_GET['num1']; //保持搜索框的值
						$num2 = $_GET['num2']; //保持搜索框的值
					}
						

					$like = '';
				    if(count($wherelist)>0){    
				    //如果条件数组有东西 则让like 开始拼接
						$like = 'and '.implode(' and ',$wherelist);
						//var_dump($like);
					}

   		 		/********搜索*******/
   		 		//仅显示有货
   		 		$store = '';
   		 		if(!empty($_GET['store'])){
   		 			$store = ' and store>0';
   		 		}
				//准备预处理对象
		//查询所有商品 
   		 		//搜索条件决定显示的内容(正反排序);
				if(isset($_GET['order'])){
					$order = ' order by '.$_GET['order'];
				}
				

				if(isset($_GET['id'])){
					$pid = $_GET['id']; //这里传入要遍历的父类  然后查找下面的所有商品
					$sql = "select * from goods where state!=2 and (typeid={$pid} or typeid in(select id from type where path like '%,{$pid},%') {$like}{$store}{$order})";
				}else{
					//查询所有
					$sql  = "select * from goods where state!=2 {$like}{$store}{$order}";
				}
				
				$stmt = $pdo->prepare($sql);
				$stmt->execute(); //查询出此条件下的所有商品
				$count = $stmt->rowCount(); //得到总个数
				// 准备分页条件

				$page = new Page($count,8);
				$limit = $page->limit();
				//echo $sql;exit;
				//再次执行限制查询
				$sql .= ' limit '.$limit;
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				if($stmt->rowCount()>0){
					$goods = $stmt->fetchAll(PDO::FETCH_ASSOC);//得到二维数组
					
					//var_dump($goods);
				}
				


			} catch (PDOException $e) {
				echo $e->getMessage();
			}
		//传过来商品的路径   显示出来模块 完美!!
		$path = $_GET['path'];
		$pathstr = '';
		$path2 = '';
		$a = explode(',',$path);
		foreach($a as $v){
			
			$v1 = explode('-',$v);
			$path2 .= $v1[0].'-'.$v1[1].',';
			//var_dump($path2);
			$path1 = rtrim($path2,',');
			$pathstr .= '<a href="index.php?m=shop&a=show&path='.$path1.'&id='.$v1[1].'" style="font-size:17px;color:blue">'.$v1[0].'</a> &gt; ';
		
		}
		$pathstr = rtrim($pathstr,' &gt;');
		//echo $pathstr;
		include('./view/shoplist.html');
		include('./view/footer.html');
	}
		
	function desc(){
		//商品详情
		//这里需要传入商品的ID
		if(isset($_GET['id'])){

			try {
				$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
				$pdo = new PDO($dsn,DB_USER,DB_PWD);
				$pdo->exec('set names '.DB_CHARSET);
				//执行商品点击量+1
				$pdo->exec("update goods set clicknum=clicknum+1 where id={$_GET['id']}");
				//准备查询一条商品的语句
				$sql = "select * from goods where id={$_GET['id']}";
				$stmt = $pdo->prepare($sql);
				$stmt->execute();
				if($stmt->rowCount()>0){
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
				}
				//var_dump($row); //一维数组
				//如果要买多件，需要添加一个标志，给session添加一个变量，如果这个变量再次被提交到购物车，说明是买多件
			
				
			} catch (PDOException $e) {
				echo $e->getMessage();
			}

		}else{
			echo '<script>alert("请选择商品");location="./index.php";</script>';
		}	



		include('./view/shopdescription.html');
		include('./view/footer.html');
	}
	
	function addbutton(){

		//这里传过来商品的信息，进行处理

	}
		


}









?>