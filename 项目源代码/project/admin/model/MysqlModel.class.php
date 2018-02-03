<?php 
	//include 'dbconfig.php';
	//实现数据库操作类

	class MysqlModel{
		//成员属性
		//数据库服务器地址
		protected $host;
		//数据库服务器用户名
		protected $user;
		//数据库密码
		protected $pwd;
		//字符集
		protected $charset;
		//数据库名
		protected $dbname;
		//要操作的表名
		protected $tabName;
		//表前缀
		protected $prefix;
		//要操作的SQL语句
		//protected $sql;
		//所有的合法字段。
		protected $field;
		//数据里链接资源
		protected $link = null;
		//定义一个数组 允许调用不存在的方法关键字
		
		protected $method = array('where','order','limit','field');
		//定义where
		protected $where;
		//定义limit
		protected $limit;
		//定义order
		protected $order;
		//定义一个用户可以查询的指定字段
		protected $fields;
		
		public $sql;
		//成员方法
		//构造方法   初始化成员属性
		function __construct($tabName){
			//第一初始化配置相关属性
			$this->host = DB_HOST;
			$this->user = DB_USER;
			$this->pwd = DB_PWD;
			$this->charset = DB_CHARSET;
			$this->dbname = DB_NAME;
			$this->prefix = DB_PREFIX;
			//第二：初始化运行中需要的属性。
			//初始化表名
			if($tabName == ''){
				//没有参数表名， 他有可能继承。 想办法扣出表名
				//get_class() 获取对象的类名
				//标前缀 拼接 获取的表名
				$this->tabName = $this->prefix.strtolower(substr(get_class($this),0,-5));
				//var_dump($a);
			}else{
				$this->tabName = $this->prefix.$tabName;
			} 
			//第三：初始化数据库链接
			$this->link = $this->connect();
			//初始化合法字段信息
			//var_dump($this->link);
			$this->field = $this->getField();
			//$this->field('gz.name,gz.gongzi,bumen.name')->r_select('gz','bumen');
		
		}
		//定义链接数据库的方法
		protected function connect(){
			//第一贱：链接数据库和选择数据库
			$link = mysqli_connect($this->host,$this->user,$this->pwd,$this->dbname);
			//少一个东西   加 一个判断
			if(!$link){
				
				return false;
			}
			//第二贱：设置字符集
			mysqli_set_charset($link,$this->charset);

			return $link;
		}

		//获取合法字段的方法

		protected function getField(){
			//设置缓存文件的路径
			$cache = './cache/'.$this->tabName.'Cache.php';
			//echo $cache;
			//检测是否缓存过数据库字段
			if(file_exists($cache)){
				//直接读取缓存数据
				return include $cache;
			}else{
				//没有缓存文件， 查询数据库 缓存一次。
				//准备查询的SQL语句
				$sql = "DESC ".$this->tabName;
				$this->sql = $sql;
				$result = $this->query($sql);
				//var_dump($result);
				//将查询到数据字段的结果 缓存起来   写入文件
				return $this->saveField($result);
				
			}
		}
		//生成缓存的方法 (就是把查询的字段 写入到文件中存储)
		protected function saveField($data){
			//设置路径
			$cache = './cache/'.$this->tabName.'Cache.php';
			//echo $cache;
			//定义写入文件的数组
			$fields = array();
			//var_dump($data);
			foreach($data as $key=>$val){
				//获取主键
				if($val['Key'] == 'PRI'){
					$fields['_pk'] = $val['Field'];
				}
				//获取自增键
				if($val['Extra'] == 'auto_increment'){
					$fields['_auto'] = $val['Field'];
				}
				$fields[] = $val['Field'];

			}
			//var_dump($fields);
			//将$fields数组原样写到文件中
			file_put_contents($cache,"<?php\r\n return ".var_export($fields,true)."\r\n?>");
			return $fields;
		}
		//mysql执行数据的方法，返回数据的方法  执行 查询
		public function query($sql){ //成功返回一个二维数组
			
			//定义返回数组变量
			$array = array();
			//发送sql
			$result = mysqli_query($this->link,$sql);
			//清空条件
			//var_dump($result);
			$this->clearWhere();
			//判断
			if($result && mysqli_num_rows($result)>0){
				while($row = mysqli_fetch_assoc($result)){
					$array[] = $row;
				}
				return $array;
			}else{
				return false;
			}
		}
		//mysql执行数据的方法，返回受影响行方法     执行 增 、删、改
		public function affected($sql){
			//将SQL语句保存
			$this->sql = $sql;
			//送sql语句
			$result = mysqli_query($this->link,$sql);
			//清空条件
			$this->clearWhere();
			//判断
			if($result && mysqli_affected_rows($this->link)>0){
				//返回受影响行  表示成功
				return mysqli_affected_rows($this->link);
			}else{
				//返回 false
				return false;
			}
		}

		//制作添加数据的方法 返回二维数组
		public function insert($data){
			//声明变量
			$keys = '';
			$vals = '';
			//遍历用户传入的数组  将数组 和 sql语句拼接
			foreach($data as $key=>$val){
				//判断当前遍历的下标(字段名)是否在缓存的合法字段中出现
				if(in_array($key,$this->field)){
					//组合字段字符串
					$keys .= '`'.$key.'`,';
					$vals .= "'".$val."',";
				}
			}
			//删除字段字符串中最后一个逗号
			$keys = rtrim($keys,',');
			$vals = rtrim($vals,',');
			//
			//添加数据的SQL语句
			$sql = "INSERT INTO {$this->tabName}({$keys}) VALUES({$vals})";
			$this->sql = $sql;
			//echo $sql;
			//将SQL语句发送到数据库执行
			return $this->affected($sql);
		}
		//修改数据的方法
		public  function update($data){
			$where = $order = $limit = '';
			//定义一个变量
			$update = '';
			$con = '';
			foreach($data as $k=>$v){
				//过滤掉非法字段   过滤掉主键
				if(in_array($k,$this->field) && $k!=$this->field['_pk']){
					$update .= '`'.$k.'`="'.$v.'",';
				}elseif($k == $this->field['_pk']){
					$con = '`'.$k.'`="'.$v.'"';
				}
			}
			$update = rtrim($update,',');
			//判断是否有where条件
			if(!empty($this->where)){
				//有条件
				$where = $this->where;
			}else{
				//没有条件   拼接成 id = 1;
				$where = 'where '.$con;
			}
			
			if(!empty($this->limit)){
				$limit = $this->limit;
				//单纯的排序没有用，只有和限制语句结合才行
				if(!empty($this->order)){
					$order = $this->order;
				}
			}
			//echo $update;
			$sql = "UPDATE {$this->tabName} SET {$update} {$where} {$order} {$limit}";
			//echo $sql;
			$this->sql = $sql;
			return $this->affected($sql);
		}
		//定义一个删除的方法
		public function  del(){
			//给条件  用条件
			$where = $order = $limit = '';
			if(!empty($this->where)){
				//有删除条件
				$where = $this->where;
			}else{
				////全删
				
				if(!empty($_GET)){
					//var_dump($_GET);
					// //在缓存字段中获取主键
					$pk = $this->field['_pk'];
					
					//第二种方法  遍历
					foreach($_GET as $k=>$v){
						if($pk == $k){
							//把当前值存入变量中
							$val = $v;
						}
					}
					$where = 'WHERE '.$pk.'='.$val;
				}
			}
			if(!empty($this->order)){
				$order = $this->order;
			}
			if(!empty($this->limit)){
				$limit = $this->limit;
			}

			$sql = "DELETE FROM {$this->tabName} {$where} {$order} {$limit}";
			//echo $sql;
			$this->sql = $sql;
			return $this->affected($sql);
		}
		//为多表联合查询备份合法字段
		private function r_filed($args){
			$arr = array();
			//2.将多张表的合法字段 缓存
			foreach($args as $k=>$v){
				$this->tabName = $v;
				//初始化合法字段信息
				$arr[$v] = $this->getField();
			}
			return $arr;
		}
		//多表联合查询
		public function r_select($tabname1,$tabname2){
			//1.如果需要多表联合查询  必须制定表名
			$args = func_get_args();
			$result =$this->r_filed($args);
			//var_dump($result);
			
			//2.拼接表名
			$tabnames = implode(',',$args);
			//3.判断是否需要查询指定的字段
			$fields = '*';
				if(!empty($this->fields)){
					$fields = $this->fields;
					//如果需要查询指定字段
					//1.把得到的参数列表进行过滤
						
						
					//安全过滤
					$strfiled = $left = $right = $hefastr = '';
					foreach($fields as $k=>$v){
						//var_dump(strstr('as',$v,true));exit;
						//首先按照as进行字符串截取
						if(strstr($v,'as',true) == false){
							//说明没有as
							//然后再根据 . 来进行截取
							$left = strstr($v,'.',true);
						
							$right = ltrim(strstr($v,'.'),'.');
							
						}else{
							$strfiled = rtrim(strstr($v,'as',true),' ');
							
							//然后再根据 . 来进行截取
							$left = strstr($strfiled,'.',true);
						
							$right = ltrim(strstr($strfiled,'.'),'.');
						}
						
						//判断左边的表名是否合法
						if(in_array($right,$result[$left])){
							//说明参数合法，都在存储的字段中；
							$hefastr .= $v.',';
							
						}
							
					}
					
					$str = rtrim($hefastr,',');
					
				}
				//加条件
				$where = $order = $limit = '';
				if(!empty($this->where)){
					$where = $this->where;
				}
				if(!empty($this->order)){
					$order = $this->order;
				}
				if(!empty($this->limit)){
					$limit = $this->limit;
				}
				
				
				$sql = "SELECT {$str} FROM {$tabnames} {$where} {$order} {$limit}";
				//echo $sql;
				$this->sql  =$sql;
				return $this->query($sql);
			
		}	
		//单表查询
		public function select(){
			
			$where = $limit = $order = '';
			//查询的条件
			if(!empty($this->where)){
				$where = $this->where;
			}
			if(!empty($this->limit)){
				$limit = $this->limit;
			}
			if(!empty($this->order)){
				$order = $this->order;
			}
			//用户是否需要查询指定的字段
			$fields = '*';	
			if(!empty($this->fields)){
				//过滤安全字段   取数组的交集 (前面的键会覆盖后面的)
				$hefa = array_intersect($this->fields,$this->field);
				//var_dump($hefa);
				$fields = implode(',',$hefa);
			}
			
			
			$sql = "SELECT {$fields} FROM {$this->tabName} {$where} {$order} {$limit}";
			//var_dump($sql);
			$this->sql  = $sql;
			return $this->query($sql);
		}
		//定义一个魔术方法  call  调用一个不存在的方法时自动触发，
		//where()  order()  limit(); 
		function __call($methodName,$args){
			//var_dump(empty($args));
			if(in_array($methodName,$this->method)){
				if(!empty($args) && !empty($args[0])){
					//判断到底是调用哪一个
					if($methodName == 'where'){

						$this->where = "where {$args[0]}";

						//var_dump($this->where);
					}
					if($methodName == 'order'){
						//判断传入的参数
						$this->order = "order by $args[0]";
						
					}
					if($methodName == 'limit'){
						//var_dump($args);
						if(is_string($args[0])){
								$args = explode(',',$args[0]);
							}
						if(count($args) == 2 ){
							//var_dump($args);
							if($args[0]>=0 && $args[1]>=0){
								$this->limit = 'limit '.$args[0].','.$args[1];
							}
							
						}elseif(count($args) == 1 && $args[0]>0){
							$this->limit = 'limit '.$args[0];
						}
					}
					if($methodName == 'field'){

							//用户需要查询指定字段
							//var_dump($vals);
							$this->fields = $args;
					}
				}
			}
			return $this;
		}
		//获取总条数
		public function rowcount(){
			$where = $limit = '';
			//会有where
			if(!empty($this->where)){
				$where = $this->where;
			}
			if(!empty($this->limit)){
				$limit = $this->limit;
			}
			//获取主键
			if(isset($this->field['_pk'])){
				$pk = $this->field['_pk'];
			}else{
				$pk = '*';
			}
			$sql = "SELECT COUNT({$pk}) as total FROM {$this->tabName} {$where}";
			//echo $sql;
			$this->sql = $sql;
			//var_dump(intval($this->query($sql)[0]['total']));
			
			/*	$this->query($sql) 看输出结果:
				D:\wamp\www\baoan\oop\oop 5\Model.class (2).php:83:
					array (size=1)
					 0 => 
						array (size=1)
							'total' => string '22' (length=2)
			*/
			//所以要这么写
			return intval($this->query($sql)[0]['total']);
		}
		//清除条件
		public function clearWhere(){
			$this->where = null;
			$this->limit = null;
			$this->order = null;
			$this->fields = null;
		}
		
		//析构方法
		function __destruct(){
			if($this->link != null){
				mysqli_close($this->link);
			}
		}
	}

