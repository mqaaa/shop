<?php


class CarControl
{


	function show(){
		//这里是购物车商品显示函数

		
		session_start();
		if($_SESSION['god']['islogin']!=true){
			echo '<script>alert("请登录");location="index.php?m=user&a=login"</script>';
			die();
		}
		//显示库存
		// try {
		// 		$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
		// 		$pdo = new PDO($dsn,DB_USER,DB_PWD);
		// 		$pdo->exec('set names '.DB_CHARSET);
		// 		$sql = "select store from goods where id="

		// } catch (PDOException $e) {
			
		// }
		//var_dump($_SESSION['car']);
		include('./view/shopcar.html');
		include('./view/footer.html');

	}

	function addcar(){
		//这里是添加商品到购物车函数
		session_start();
		if($_SESSION['god']['islogin']!=true){
			echo '<script>alert("请登录");location="index.php?m=user&a=login"</script>';
			die();
		}
		if(isset($_GET['id'])){

			try {
				$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
				$pdo = new PDO($dsn,DB_USER,DB_PWD);
				$pdo->exec('set names '.DB_CHARSET);
				//点击量+1
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
				//var_dump($_POST['addnum']);
				if(!empty($_POST['addnum'])){
					//执行商品点击量+1
					
					$row['addnum'] = $_POST['addnum'];

				}else{
					$row['addnum'] = 1;
				}
				


				if(isset($_SESSION['car'][$row['id']]) && empty($_POST['addnum'])){
					//说明有商品过来了
					$_SESSION['car'][$row['id']]['addnum'] += 1; 

				}elseif(!empty($_POST['addnum']) && isset($_SESSION['car'][$row['id']])){
					//把商品的ID当做session car 的一个键来存储该商品的信息
					$_SESSION['car'][$row['id']]['addnum'] += $_POST['addnum'];

				}else{
					$_SESSION['car'][$row['id']] = $row;
				}
				
				header('location:./index.php?m=car&a=show');

				
			} catch (PDOException $e) {
				echo $e->getMessage();
			}

		}else{
			echo '<script>alert("请选择商品");location="./index.php";</script>';
		}	

	} 	


	function setnum(){
		//控制商品数量加减函数
		session_start();
		if($_SESSION['god']['islogin']!=true){
			echo '<script>alert("请登录");location="index.php?m=user&a=login"</script>';
			die();
		}
		if($_GET['addnum'] && $_GET['id']){
				//判断是否为负
				$allnum = $_SESSION['car'][$_GET['id']]['addnum'];
				$allnum += $_GET['addnum'];			
				if($allnum<0){
					$_SESSION['car'][$_GET['id']]['addnum'] = 0; 
				}elseif($allnum>$_SESSION['car'][$_GET['id']]['store']){
					$_SESSION['car'][$_GET['id']]['addnum'] = $_SESSION['car'][$_GET['id']]['store'];
				}else{
					$_SESSION['car'][$_GET['id']]['addnum'] += $_GET['addnum'];
				}

		}

		header('location:./index.php?m=car&a=show');

	}

	function delcar(){
		//清空或者删除商品

		session_start();
		if($_SESSION['god']['islogin']!=true){
			echo '<script>alert("请登录");location="index.php?m=user&a=login"</script>';
			die();
		}
		if(isset($_GET['id'])){
			//说明要删除指定商品
			unset($_SESSION['car'][$_GET['id']]);
		}elseif(isset($_POST['ids'])){
			//说明批量删除
			foreach($_POST['ids'] as $k=>$v){
				unset($_SESSION['car'][$v]);
			}
			
		}else{
			//说明要清空购物车
			$_SESSION['car'] = array();
		}

		header('location:index.php?m=car&a=show');
	}

	function removeout(){
		//这里用来清除购物车下柜商品
		session_start();
		if($_SESSION['god']['islogin']!=true){
			echo '<script>alert("请登录");location="index.php?m=user&a=login"</script>';
			die();
		}
		//获取到所有的商品
		//var_dump($_SESSION);
		foreach($_SESSION['car'] as $k=>$v){
			if($v['state']=='2'){

				unset($_SESSION['car'][$k]);
				
			}
		}
		header('location:index.php?m=car&a=show');

	}

}












?>