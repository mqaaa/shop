<?php



class IndexControl{


	function nav(){

			//这里遍历导航条里的分类
			try {
				
				$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME;
				$pdo = new PDO($dsn,DB_USER,DB_PWD);
				$pdo->exec("set names ".DB_CHARSET);
				//准备与处理对象
				$sql = "select * from ".DB_PREFIX."type where pid=0";
				//查询出所有顶级分类
				//$stmt = $pdo->prepare($sql);




			} catch (PDOException $e) {
				echo $e->getMessage();
			}


	}




	//这里是控制首页
	function index(){
			
		//使用PDO
		try {
			
			//在这里点击会显示出数据库的类别
			$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.'';
			$pdo = new PDO($dsn,DB_USER,DB_PWD);
			$pdo->exec('set names '.DB_CHARSET);

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
			//模块显示商品

			 //这里传入要遍历的父类  然后查找下面的所有商品
			// 秒杀模块 6件
				$sqlm = "select * from goods where state!=2 and typeid=73";
				$stmtm = $pdo->prepare($sqlm);
				$stmtm->execute(); //查询出此条件下的所有商品
				// 准备分页条件
				if($stmtm->rowCount()>0){
					$miaosha = $stmtm->fetchAll(PDO::FETCH_ASSOC);//得到二维数组
					
				}
				
			//模块显示商品	







		} catch (PDOException $e) {
			echo $e->getMessage();
		}
		




		//var_dump($_SESSION);

		include('./view/index.html');
		include('./view/footer.html');

	}
	


}










?>