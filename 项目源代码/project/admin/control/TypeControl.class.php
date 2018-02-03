<?php


class TypeControl
{


	function show(){


		/********搜索*******/
		$search = "";	
		$wherelist = array();
	    if(!empty($_GET['search'])){
	        $wherelist[] = 'name like "%'.$_GET['search'].'%"';
	        $search = '&search='.$_GET['search'];
	        $sousuo = $_GET['search']; //保持搜索框的值
	    }

	    /*搜索权限条件*/
	   //当选择权限时，把它下面的都显示出来 	
	    $searchlev="";
		if(isset($_GET['lev']) && $_GET['lev'] != ''){
			$wherelist[] = 'concat(path,id) like "%'.$_GET['lev'].'%"';
			$searchlev = '&lev='.$_GET['lev'];
			$lev = $_GET['lev'];  //保持等级搜索框的值
		
		}
		/*搜索区间条件*/
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
			$like = implode(' and ',$wherelist);
			//var_dump($like);
		}
		
	


   		 /********搜索*******/


		//这里需要查询出表中的栏目
		$t = new MysqlModel('type');
		$t->where($like);
		$row1 = $t->select();
		$arr1 =array(','=>'->','0'=>'顶级');
		if($row1){
			foreach($row1 as $k=>$v){
			
       		 	$arr1[$v['id']] = $v['name'];
			}
		}
		
		
		//分页类调用
		$page= new Page(count($row1),5);

		//var_dump($page->limit());
		$limit = $page->limit();

		$t->where($like);
		

		$lanmu = $t->limit($limit)->order('concat(path,id) asc')->select();

		include('./view/type/index.html');
	}


	function add(){

		
		//var_dump($_POST);
		$t = new MysqlModel('type');
		//首先查找到父级的ID 用来拼接成路径 父亲的路径+本身的ID 就是自己的路径

		$res = $t->order('CONCAT(path,id) asc')->select(); //得到一个二维数组

		//var_dump($res);
		//这里查询到的信息在下拉列表显示
		include('./view/type/form.html');

	}
	function son_add(){

			$t = new MysqlModel('type');
			//首先查找到父级的ID 用来拼接成路径 父亲的路径+本身的ID 就是自己的路径

			$res = $t->order('CONCAT(path,id) asc')->select(); //得到一个二维数组
			include('./view/type/add.html');
	}
	function do_add(){
		//这里接受传过来的post值  有要添加的栏目名name
		//$_POST[type] 为父亲的路径加父亲的ID
		//首先查找到父级的ID 用来拼接成路径 父亲的路径+父亲的ID 就是自己的路径
		//添加栏目函数
		//$t = new MysqlModel('type');
		if($_POST['type'] != 'xz' ){
			if(empty($_POST['name'])){

				echo '<script>alert("添加的子栏目不能为空");location="./index.php?m=type&a=son_add&id='.$_POST['sid'].'"</script>';
				die();
			}
			if($_POST['type'] === '0'){ //这里要注意  0=='0,' 相等 所以要全等

				$_POST['pid'] = '0';
				$_POST['path'] = '0,';

			}else{
				
				$_POST['pid'] = ltrim(strrchr($_POST['type'],','),',');
				$_POST['path'] = $_POST['type'].',';

			}
			
			$t = new MysqlModel('type');
			$res = $t->insert($_POST); //返回影响行
			//echo $t->sql;exit;
			if($res){
				echo '<script>alert("添加成功");location="./index.php?m=type&a=show"</script>';
			}else{
				if(isset($_POST['sid'])){
					echo '<script>alert("添加失败");location="./index.php?m=type&a=son_add&id='.$_POST['sid'].'"</script>';
				}else{
					echo '<script>alert("添加失败");location="./index.php?m=type&a=add"</script>';
				}
				
			}	

		}else{
			echo '<script>alert("请选择要添加的栏目");location="./index.php?m=type&a=add"</script>';
		}
			//执行插入操作
			
	}
	function del(){
		//这个函数是要删除栏目
		// 删除时要判断栏目下面有没有商品
		//select * from goods where typeid=id;
		$g = new MysqlModel('goods');
		$have = $g->where("typeid={$_GET['id']}")->select();
		if(!empty($have)){
			//说明有商品，不能删除，
			echo '<script>alert("栏目下有商品，不可删除.");location="./index.php?m=type&a=show";</script>';
			die();
		}
		$t = new MysqlModel('type');
		//判断栏目是否是主栏目，要是那么下面的也要全部删除
		//传过来要删除的ID
		//查找数据库中的路径中出现男装ID的都要删除
		$ids = $_GET['id'];
		if(isset($_GET['id']) ){
			$id = $_GET['id'];
			$row = $t->select();
			foreach($row as $k=>$v){
				$arr = explode(',',$v['path']);
				if(in_array($_GET['id'],$arr)){
					$ids .= ','.$v['id'];
				}
			}
			// delete from type where id in();			
			$res = $t->where("id in($ids)")->del();
			if($res){

				echo '<script>alert("删除成功");location="./index.php?m=type&a=show";</script>';
			}else{
				echo '<script>alert("删除失败");location="./index.php?m=type&a=show";</script>';
			}


		}
		
		

		
		
		
	}

	function update(){


		//var_dump($_POST);
		$t = new MysqlModel('type');
		//首先查找到父级的ID 用来拼接成路径 父亲的路径+本身的ID 就是自己的路径
		
		$res = $t->where("id={$_GET['id']}")->select();
		
		$lanmu = $t->order('CONCAT(path,id) asc')->select(); //得到一个二维数组

		//这里查询到的信息在下拉列表显示
		include('./view/type/update.html');

	}

	function do_update(){
		//select * from type where concat(path,id) like '%10%';
		//var_dump($_POST);
		if($_POST['type'] === 'xz' ){
			//判断是不是只修改名称
			if($_POST['name'] != $_GET['oldname']){
				//说明要修改名称
				unset($_POST['type']);//删除数组中的类型，以防修改
				//var_dump($_POST);
				$t = new MysqlModel('type');
				$res1 = $t->update($_POST);
				if($res1){
					echo '<script>alert("修改成功");location="./index.php?m=type&a=show";</script>';
					die();
				}else{
					echo '<script>alert("修改名称失败");location="./index.php?m=type&a=update&id='.$_POST['id'].'";</script>';
					die();
				}

			}else{
				echo '<script>alert("请选择栏目进行修改或者修改名称");location="./index.php?m=type&a=update&id='.$_POST['id'].'";</script>';
				die();
			}
				
		}
		$t = new MysqlModel('type');
		//修改时需要把新的栏目放到选择的下面  修改新路径
		if($_POST['type'] === '0'){//如果把子类添加到顶级类提示
			//update  type set pid=0 path=0, where id= $_POST['pid']
			$_POST['pid']=0;
			$_POST['path'] = '0,';
			$pres = $t->update($_POST);
		}else{
			// update  type set pid=选择的栏目 path=选择的栏目路径+选择栏目ID， where id=get id
			//选择栏目ID 
			$pid = ltrim(strrchr($_POST['type'],','),',');
			$_POST['pid'] = $pid;
			//选择的栏目路径 + ,
			$_POST['path'] = $_POST['type'].',';

			//var_dump($_POST);
			
			$pres = $t->update($_POST);
		}
		
		//同时它下面的所有栏目都要加到新的下面，重新拼接路径
		// update  type set pid=不变 path=选择的栏目路径+选择栏目ID+id， where concat(path,id) like '%,id,%'
		//先查看有没有子栏目，有就进行修改
		//echo '<hr>';
		$like = 'path like "%,'.$_POST['id'].',%"';
		$count = $t->where($like)->select();
		var_dump($count);
		$flag = false;
		if($count){
			//删除$_POST中不需要修改的
			unset($_POST['pid']);
			unset($_POST['name']);
			$_POST['path'] = $_POST['type'].','.$_POST['id'].',';
			$sonres = $t->where($like)->update($_POST);
			echo $t->sql;
			// var_dump($sonres);
			if($sonres){
				$flag = true;
			}else{
				
				echo '<script>alert("修改失败");location="./index.php?m=type&a=update&id='.$_POST['id'].'";</script>';
				die();
			}
		}
		
		$flag = true;
		if($pres && $flag){
			echo '<script>alert("修改成功");location="./index.php?m=type&a=show";</script>';
		}else{
			echo '<script>alert("修改失败");location="./index.php?m=type&a=update&id='.$_POST['id'].'";</script>';
		}
		
		


	}
}











?>