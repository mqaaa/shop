<?php


class Search
{


	//成员属性
	public $wherelist = array('R'=>'remove=1');
		//按姓名
	public $search; //搜索参数
	public $sousuo; //保持搜索框
		//按权限
	public $searchlev;//搜索参数
	public $lev;
		//ID区间
	public $qujian;
	public $num1;
	public $num2;
		//注册时间
	public $shijianchuo;
		//性别
	public $sexcanshu;//翻页参数
	public $sex0;
	public $sex1;
	public $sex2;
	//方法
	function sou(){
		
			
	    if(!empty($_GET['search'])){
	        $this->wherelist[] = 'username like "%'.$_GET['search'].'%"';
	        $this->search = '&search='.$_GET['search'];
	        $this->sousuo = $_GET['search']; //保持搜索框的值
	    }

	    /*搜索权限条件*/
	    	

		if(isset($_GET['lev']) && $_GET['lev'] != ''){
			$this->wherelist[] = 'level='.$_GET['lev'].'';
			$this->searchlev = '&lev='.$_GET['lev'];
			$this->lev = $_GET['lev'];  //保持等级搜索框的值
		
		}
		/*搜索区间条件*/
	
		if(isset($_GET['num1']) && isset($_GET['num2']) && $_GET['num1']!='' && $_GET['num2']!=''){
			$this->wherelist[] = 'id between '.$_GET['num1'].' and '.''.$_GET['num2'].'';
			$this->qujian = '&num1='.$_GET['num1'].'&num2='.$_GET['num2']; //要放在翻页那里
			$this->num1 = $_GET['num1']; //保持搜索框的值
			$this->num2 = $_GET['num2']; //保持搜索框的值
		}
		/*搜索注册时间条件*/
		
		  if(!empty($_GET['time']) && $_GET['time']!=''){
			$times= strtotime($_GET['time']);
			$timestr= 'addtime>'.$times;  //大于几天前即可
			$this->wherelist[] = "id in(select id from user where {$timestr})";
			$this->shijianchuo = '&time='.$_GET['time']; //要放在翻页那里
			
			
		}
		
		/*搜索性别条件*/
		//var_dump($_GET['sex']);
		$sexcanshu = $sexstr ='';
			
		 if(isset($_GET['sex'])){	
		 		if(is_array($_GET['sex'])){
                        $sexstr = implode(',',$_GET['sex']);
                            
                }else{
                        $sexstr = $_GET['sex'];             
                }
                
                //统计在字符串中出现的次数，出现就是大于0；
                substr_count($sexstr,'0')>0?$this->sex0 = 'checked':'';    
                //保持搜索框的值
                substr_count($sexstr,'1')>0?$this->sex1 = 'checked':'';   
                substr_count($sexstr,'2')>0?$this->sex2 = 'checked':'';  
			//select * from user where id between 1 and 20 and id in(1,3,6) 这都可以啊
			$this->wherelist[] = "id in(select uid from user_info where sex in({$sexstr}))";
			$this->sexcanshu = '&sex='.$sexstr;  //要放在翻页那里
			
		}
			

		$like = '';
	    if(count($this->wherelist)>0){    
	    //如果条件数组有东西 则让like 开始拼接
			$like = implode(' and ',$this->wherelist);
			//var_dump($like);
		}
		return $like;
	}
	



}




?>