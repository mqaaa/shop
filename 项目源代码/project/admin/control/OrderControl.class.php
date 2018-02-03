<?php


class OrderControl
{
	//后台订单控制器
	function show(){


		/********搜索*******/
		//按照订单号搜索
		$search = "";	
		$wherelist = array();
	    if(!empty($_GET['search'])){
	        $wherelist[] = 'id ='.$_GET['search'];
	        $search = '&search='.$_GET['search'];
	        $sousuo = $_GET['search']; //保持搜索框的值
	    }

	    /*搜索状态条件*/
	   //当选择状态时，把它下面的都显示出来 	
	    $searchlev="";
		if(isset($_GET['lev']) && $_GET['lev'] != ''){
			$wherelist[] = 'status ='.$_GET['lev'];
			$searchlev = '&lev='.$_GET['lev'];
			$lev = $_GET['lev'];  //保持等级搜索框的值
		
		}
		/*搜索区间条件*/
		$qujian = $num1 = $num2 = '';
		if(isset($_GET['num1']) && isset($_GET['num2']) && $_GET['num1']!='' ){
			if( $_GET['num2']=='' || $_GET['num2']<$_GET['num1']){
				$wherelist[] = 'total > '.$_GET['num1'];
			}else{
				$wherelist[] = 'total between '.$_GET['num1'].' and '.''.$_GET['num2'].'';
			}
			
			$qujian = '&num1='.$_GET['num1'].'&num2='.$_GET['num2']; //要放在翻页那里
			$num1 = $_GET['num1']; //保持搜索框的值
			$num2 = $_GET['num2']; //保持搜索框的值
		}
		// 按照会员ID搜索
		$vipid = $searchvip='';
		if(isset($_GET['vipid']) && !empty($_GET['vipid'])){
			$wherelist[] = 'uid ='.$_GET['vipid'];
			$searchvip = '&vipid='.$_GET['vipid'];
			$vipid = $_GET['vipid'];  //保持等级搜索框的值
		}
			
		$like = '';
	    if(count($wherelist)>0){    
	    //如果条件数组有东西 则让like 开始拼接
			$like = implode(' and ',$wherelist);
			//var_dump($like);
		}

   		 /********搜索*******/

		
		
		

		
		$m = new MysqlModel('orders');
		$m->where($like);
		$res = $m->select();

		//分页类调用
		$page= new Page(count($res),3);

		//var_dump($page->limit());
		$limit = $page->limit();
		
		// -------------分页完--------------------
		$m = new MysqlModel('orders');
		$m->where($like);
		$m->limit($limit);
		//搜索条件决定显示的内容(正反排序);
		if(isset($_GET['order'])){
			$m->order($_GET['order']);
		}
		$res = $m->select();
		echo $m->sql;
		//var_dump($res);
		//echo $m->sql;exit;
		//分页类调用
		if(!empty($res) && count($res)>0){
			foreach($res as $key=>$val){
				$order[$val['id']] = $val; 
			}
		}
		//var_dump($res);
		

		//var_dump($orderid);
		//查商品信息
		$g = new MysqlModel('goods');
		$good = $g->select();
		if(!empty($good) && count($good)>0){
			foreach($good as $k1=>$v1){
				$goods[$v1['id']] = $v1;
			}
		}
		
		//var_dump($goods);
		//查找订单号下面的详情表中的商品ID 
		$info = new MysqlModel('order_info');
		$oinfo = $info->select();
		if(!empty($oinfo) && count($oinfo)>0){
			foreach($oinfo as $k=>$v){
				$orderinfo[$v['orderid']] = $v;
			}
		}

		//select * from order_info where orderid=
		// var_dump($orderinfo);
		date_default_timezone_set('PRC');
		include('./view/order/orderlist.html');
	}

	function update(){

		//接受该订单的ID

		$o = new MysqlModel('orders');
		$res = $o->where("id={$_GET['id']}")->select();
		//var_dump($res);


		include('./view/order/updateorder.html');
	}
	function do_update(){
		//这里修改订单状态，其他的不能修改(若要修改需要修改表单)
		//var_dump($_POST);
		$o = new MysqlModel('orders');

		$res = $o->update($_POST);
		
		if($res){
			echo '<script>alert("修改状态成功");location="index.php?m=order&a=update&id='.$_POST['id'].'"</script>';
		}else{
			echo '<script>alert("修改状态失败");location="index.php?m=order&a=update&id='.$_POST['id'].'"</script>';
		}

	}
	//多重删除无效订单
	function dels(){
		if(isset($_POST['dels']) ){
			$m = new MysqlModel('orders');
			$str = implode(',',$_POST['dels']);
			$where = 'id in('.$str.') and status=3';
			
		}elseif(isset($_GET['id'])){
			$m = new MysqlModel('orders');
			$where = 'id ='.$_GET['id'].' and status=3';
			
		}else{
			echo '<script>alert("请选择要删除的订单");location="./index.php?m=order&a=show"</script>';
		}	
			$res = $m->where($where)->del();
			
			if($res){

				echo '<script>alert("删除成功");location="./index.php?m=order&a=show"</script>';

			}else{
				echo '<script>alert("删除失败,请注意是否存在有效订单");location="./index.php?m=order&a=show"</script>';
			}
			
		
			
		
		
	}

}




