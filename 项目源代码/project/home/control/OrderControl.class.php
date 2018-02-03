<?php


class OrderControl{

	

	function getorder(){
		if($_SESSION['god']['islogin']!=true){
			echo '<script>alert("请登录");location="index.php?m=user&a=login"</script>';
			die();
		}
		//这里面处理结算后的订单
			//也就是跳转到这里,处理session
		//var_dump($_SESSION['car']);
		//根据传过来的ID判断选中的商品
		// 需要多表联查进行订单制造
		// select user_info.id,user_info.name,user_info.address,user_info.code,user_info.phone where uid = 会员id
		// insert into orders(uid,linkman,phone,code,address,addtime,total,status)
		
		try {
			
			$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
			$pdo = new PDO($dsn,DB_USER,DB_PWD);
			$pdo->exec("set names ".DB_CHARSET);
			//准备与处理对象
			
			//var_dump($_POST);
			//首先判断
			if(isset($_POST['ids']) && isset($_POST['linkman'])){

				//var_dump($uinfo);

				$total = 0;//商品总价
				$status = 0;//0新添加
				//遍历生成订单
				//$pdo->setAttribute(PDO::ATTR_AUTOCOMMIT,FALSE);
				//$pdo->beginTransaction();
				foreach($_POST['ids'] as $k=>$v){
					$total = $_SESSION['car'][$v]['addnum']*$_SESSION['car'][$v]['price'];
					$goodsname = $_SESSION['car'][$v]['name'];
					
					$time = time();
					$sql1 = "insert into orders(uid,linkman,phone,code,address,addtime,total,status) values({$_SESSION['god']['id']},'{$_POST['linkman']}','{$_POST['phone']}','{$_POST['code']}','{$_POST['address']}',{$time},{$total},{$status})";
					//echo $sql1.'<br>';
					// //执行语句
					$res1 = $pdo->exec($sql1);
					$newid = $pdo->lastInsertId();
					//订单详情表生成
					$sql = "insert into order_info(orderid,goodsid,name,price,gnum,uid) values({$newid},{$v},'{$goodsname}',{$_SESSION['car'][$v]['price']},{$_SESSION['car'][$v]['addnum']},{$_SESSION['god']['id']})";
					//执行语句
					//echo $sql;
					$res2 = $pdo->exec($sql);
					if($res2){
						
						//同时商品的被购买数量增加						
						$sqlg = "update goods set num=num+{$_SESSION['car'][$v]['addnum']} where id={$v}";						
						$resg = $pdo->exec($sqlg);
						$sqln = "update goods set store=store-{$_SESSION['car'][$v]['addnum']} where id={$v}";
						$resn = $pdo->exec($sqln);
						
						//订单提交成功，清除该商品的session
						unset($_SESSION['car'][$v]);
					}
					
				}	
				//$pdo->setAttribute(PDO::ATTR_AUTO_COMMIT,TRUE);
				echo '<script>location="./index.php?m=order&a=show";</script>';
				
			}else{
				echo '<script>alert("请填写联系人,以及收货地址");location="./index.php?m=car&a=show";</script>';
			}
	
			
		} catch (PDOException $e) {
				echo $e->getMessage();
		}


	} 

	function show(){
		//za这里获取到当前会员下的订单信息 在session中
		if($_SESSION['god']['islogin']!=true){
			echo '<script>alert("请登录");location="index.php?m=user&a=login"</script>';
			die();
		}
			try {

				$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
				$pdo = new PDO($dsn,DB_USER,DB_PWD);
				$pdo->exec("set names ".DB_CHARSET);
				/********搜索*******/
					$search = "";	
					$wherelist = array();
				    if(!empty($_GET['search'])){
				        $wherelist[] = 'name like "%'.$_GET['search'].'%"';
				        $search = '&search='.$_GET['search'];
				        $sousuo = $_GET['search']; //保持搜索框的值
				    }

					$like = '';
				    if(count($wherelist)>0){    
				    //如果条件数组有东西 则让like 开始拼接
						$like = 'and '.implode(' and ',$wherelist);
						//var_dump($like);
					}

   		 		/********搜索*******/
   		 		// 分页
   		 		//准备与处理对象
				$sql = "select * from ".DB_PREFIX."orders where uid={$_SESSION['god']['id']}";
				//echo $sql;
				//$stmt = $pdo->prepare($sql);
				$stmt = $pdo->query($sql);
				//var_dump($stmt);
   		 		$count = $stmt->rowCount(); //得到总个数
				// 准备分页条件
   		 		if($count==0){
   		 			$count = 1;
   		 		}
				$page = new Page($count,2);
				//var_dump($page);
				$limit = $page->limit();
				//echo $sql;exit;
				//再次执行限制查询
				$limit = ' limit '.$limit;
				//echo $limit;	
				
				//准备与处理对象
				$sql = "select * from ".DB_PREFIX."orders where uid={$_SESSION['god']['id']}".$limit;
				//查询出所有
				//echo $sql;
				//$stmt = $pdo->prepare($sql);
				$stmt = $pdo->query($sql);
				//var_dump($stmt);
				$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
				//if($res)
				//var_dump($res);
				foreach($res as $k=>$v){    //将其转换成两个表键相同
					$arrs[$v['id']] = $v;
				}
				//var_dump($arrs);
				$sql2 = "select * from ".DB_PREFIX."order_info where uid={$_SESSION['god']['id']}";
				
				//$stmt = $pdo->prepare($sql);
				$stmt2 = $pdo->query($sql2);
				$res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
				//把得到的订单详情中 orderid 当做表名 这样就可以和订单表对应
				foreach($res2 as $k=>$v){
					$arr[$v['orderid']] = $v;
				}

				//在拿到此会员商品 商品的图片和ID,厂家等信息
				$sql3 = "select * from goods where id in (select goodsid from order_info where uid={$_SESSION['god']['id']})";
				$stmt3 = $pdo->query($sql3);
				$goods = $stmt3->fetchAll(PDO::FETCH_ASSOC);
				foreach($goods as $k=>$v){  //把商品ID作为键
					$good[$v['id']] = $v;
				}
				//var_dump($good);
				 
			} catch (PDOException $e) {
				echo $e->getMessage();
			}


		include('./view/dingdan.html');
		include('./view/footer.html');
	}

	function info(){
		if($_SESSION['god']['islogin']!=true){
			echo '<script>alert("请登录");location="index.php?m=user&a=login"</script>';
			die();
		}
		//这个页面是用来显示订单详情
		//需要传入的订单ID
		try {

				$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
				$pdo = new PDO($dsn,DB_USER,DB_PWD);
				$pdo->exec("set names ".DB_CHARSET);

				
				//准备与处理对象
				$sql = "select * from ".DB_PREFIX."orders where uid={$_SESSION['god']['id']} and id={$_GET['id']}";
				//查询出所有
				//$stmt = $pdo->prepare($sql);
				$stmt = $pdo->query($sql);
				$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

				//var_dump($res);//二维数组，
				foreach($res as $k=>$v){    //将其转换成两个表键相同
					$arrs[$v['id']] = $v;
				}
				$arrs = $arrs[$_GET['id']];
				//var_dump($arrs);//二维数组
				$sql2 = "select * from ".DB_PREFIX."order_info where uid={$_SESSION['god']['id']} and orderid={$_GET['id']}";
				
				//$stmt = $pdo->prepare($sql);
				$stmt2 = $pdo->query($sql2);
				$res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
				//把得到的订单详情中 orderid 当做表名 这样就可以和订单表对应
				foreach($res2 as $k=>$v){
					$arr[$v['orderid']] = $v;
				}
				$arr = $arr[$_GET['id']];
				//var_dump($arr);
				//在拿到此会员商品 商品的图片和ID,厂家等信息
				$sql3 = "select * from goods where id in (select goodsid from order_info where uid={$_SESSION['god']['id']})";
				$stmt3 = $pdo->query($sql3);
				$goods = $stmt3->fetchAll(PDO::FETCH_ASSOC);
				foreach($goods as $k=>$v){  //把商品ID作为键
					$good[$v['id']] = $v;
				}
				 //var_dump($good);

			} catch (PDOException $e) {
				echo $e->getMessage();
			}




		include('./view/orderinfo.html');
		include('./view/footer.html');

	}

	function jiesuan(){
		if($_SESSION['god']['islogin']!=true){
			echo '<script>alert("请登录");location="index.php?m=user&a=login"</script>';
			die();
		}
		//这个页面提交一些
		// 订单的收货人信息
		//var_dump($_POST);
		//var_dump($_SESSION['car']);
		if(isset($_POST['ids'])){
			//进行
			//遍历session 生成简单信息



			include('./view/order.html');
			include('./view/footer.html');
		}else{
			echo '<script>alert("请选择商品");location="./index.php?m=car&a=show";</script>';
		}

		
	}

}














?>