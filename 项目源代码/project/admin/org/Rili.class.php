<?php

//万年历
/*定义两个变量
1.每个月的天数
$tianshu
2.每个月一号是周几。
$week
*/
//将它们封装成函数
//1.这个函数将返回上面的两个变量 
class WanNianLi
{
	public $year ;
	public $month;
	private function getday(){
		
		//得出当前年
		if(isset($_GET['year'])){
			if($_GET['year']>2037 || $_GET['year']<1970){
				echo '<script>alert("日期(年)出错")</script>';
				$year = date('Y'); 
				$_GET['month']=date('m'); //防止两次弹框
			}else{
				$year = $_GET['year'];
			}
			
		
		}else{
			$year = date('Y');
			
		}
		
		//得出当前月
		if(isset($_GET['month'])){
			if($_GET['month']>12 || $_GET['month']<1){
					echo '<script>alert("日期(月)出错")</script>';
					$month = date('m');
					$year = date('Y'); 
				}else{
					$month = $_GET['month'];
				}
		}else{
			$month = date('m');
		}
		$this->year = $year;
		$this->month = $month;
		return $year.','.$month;
		
	}
	//2. 这个函数用来输出表格
	public function gettable(){
		
		$res = $this->getday();
		//var_dump($res);
		list($year,$month)=explode(',',$res); //将得到的值用list（）赋值给变量。
		//echo $year,$month;
		$tianshu = date('t',mktime(0,0,0,$month,1,$year));
		$week = date('w',mktime(0,0,0,$month,1,$year));
		
		echo '<table border="1" width="500" align="center" style="text-align:center;">';
		echo '<caption><h2>'.$year.'年'.$month.'月</h2></caption>';
		echo '<tr>';
		echo '<th>星期日</th>';	
		echo '<th>星期一</th>';	
		echo '<th>星期二</th>';	
		echo '<th>星期三</th>';	
		echo '<th>星期四</th>';	
		echo '<th>星期五</th>';	
		echo '<th>星期六</th>';	
		echo '</tr>';
		
		//循环输出天数
		for($day=1;$day<=$tianshu;){
			echo '<tr>';
			for($i=0;$i<7;$i++){ //这里是打七个单元格
				
				if(($i<$week && $day==1) || $day>$tianshu){ //判断打空格的地方
					echo '<td></td>';
				}else{
					$bg = $day==date('d')?'yellow':''; //变色
					echo '<td bgcolor="'.$bg.'">'.$day.'</td>'; //输出有效天数就开始计数
					$day++;
				}
			}
			echo '</tr>';	
		}
		
		$this->fanye($year,$month);
		
		
		
	}
	/* //调用输出表格。
	gettable(); */


	//4. 年月转换功能函数
	private function fanye($year,$month){
		echo '<tr align="center">';
		echo '<td colspan="7">';
		
		echo '<a href="?m=rili&a=show&'.$this->qianyear($year,$month).'"><上一年</a>&nbsp;';
		echo '<a href="?m=rili&a=show&'.$this->qianmonth($year,$month).'"><<上一月</a>&nbsp;';
		echo '<a href="?m=rili&a=show&'.$this->nextmonth($year,$month).'">下一月>></a>&nbsp;';
		echo '<a href="?m=rili&a=show&'.$this->nextyear($year,$month).'">下一年</a>&nbsp;';
		
		echo '</td>';
		echo '</tr>';
		
		
	}

	//5. 上一年判断函数
	private function qianyear($year,$month){

		if($year == 1970){
			$year = 1970;
			
		}else{
			$year--;
		}
		return 'year='.$year.'&month='.$month;
	}

	//6. 上一月判断函数
	private function qianmonth($year,$month){

		if($month == 1){
			$month = 12;
			if($year == 1970){
				$year = 1970;
				$month = 1;
			}else{
				$year--;
			}
			
		}else{
			$month--;
		}
		return 'year='.$year.'&month='.$month;
	}

	//7. 下一月判断函数
	private function nextmonth($year,$month){
	//echo $year,$month;
		if($month == 12){		
			if($year == 2038){
				$year = 2038;
				$month = 1;
			}else{
				$year++;
			}
			$month = 1;
		}else{
			if($year == 2038){
				$month = 1;
			}else{
				$month++;
			} 
			
		}
		return 'year='.$year.'&month='.$month;
	}

	//8. 下一年判断函数
	private function nextyear($year,$month){

		if($year == 2038){
			$year = 2038;	
			$month = 1;
			
		}else{
			$year++;
		}
		return 'year='.$year.'&month='.$month;
	}
	
		/* $this->gettable($this->year,$this->month); */
	function __construct(){
		$this->gettable();
	}
	
}
//$rili = new WanNianLi();
//9.选择查看指定年份功能
class Rili extends WanNianLi
{
	
	public function search($year,$month){
				//echo '<table border="1" width="500" align="center" style="text-align:center;">';
				echo '<tr valign="middle" align="center">';
				echo '<td colspan="7" >';
				echo '请输入要查找的时间：<br><br>';
				echo '<form action="index.php" method="get">';
				echo '<input type="hidden" name="m" value="rili">';
				echo '<input type="hidden" name="a" value="show">';
				echo '年：<select name="year">';
				for($i=1970;$i<=2037;$i++){
					if($i==$year){
						echo '<option selected>'.$i.'</option>';
					}else{
						echo '<option>'.$i.'</option>';	
					}
					
				}
				echo '</select>';

				echo '月：<select name="month">';
				for($i=1;$i<=12;$i++){
					if($i == $month){
						echo '<option selected>'.$i.'</option>';
					}else{
						echo '<option>'.$i.'</option>';
					}
						
				}
				echo '</select>';
				echo '<input type="submit" value="查找">';	
				echo '</td>';	
				echo '</tr>';
				echo '</table>';

		}
			
			//自动进行显示了日历主题
			function __construct(){
				//$this->search($this->year,$this->month);
				parent::__construct();
				$this->search($this->year,$this->month);
				//$this->search();
			}
}

//$rili = new Search();

?>