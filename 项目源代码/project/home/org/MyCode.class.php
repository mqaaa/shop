<?php
session_start();
class MyCode
{
	public $width;
	public $height;
	public $font;
	public $num;
	public $img;
	//构造方法赋值
	function __construct($font='',$num=4,$width=100,$height=40){
		$this->width = $width;
		$this->height = $height;
		$this->font = $font;
		$this->num = $num;
		$this->cimage();
		
	}
	//创建画布函数
	function cimage(){
		//创建画布
		$this->img = imagecreatetruecolor($this->width,$this->height);
		//制作背景颜色
		$bgcolor = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
		//填充背景色
		imagefill($this->img,0,0,$bgcolor);
		//边框
		$red = imagecolorallocate($this->img,255,0,0);
		imagerectangle($this->img,0,0,$this->width-1,$this->height-1,$red);
		$this->dian();
		$this->xian();
		$this->zifu();
		
		$this->shengming();
	 }
	 //干扰点
	 function dian(){
		 for ($i=0; $i < 99; $i++) { 
			$diancolor = imagecolorallocate($this->img,mt_rand(130,169),mt_rand(130,159),mt_rand(130,169));
			imagesetpixel($this->img,mt_rand(2,98),mt_rand(2,38),$diancolor);
		}
	 }
	 //干扰线
	 function xian(){
		 for ($i=0; $i < 9; $i++) { 
			$line = imagecolorallocate($this->img,mt_rand(170,199),mt_rand(170,199),mt_rand(170,199));
			imageline($this->img,mt_rand(2,98),mt_rand(2,38),mt_rand(38,98),mt_rand(2,38),$line);
		}
	 }
	 //画字
	 function zifu(){
		$newstr = '';
		$str = 'qwertyuopasdfghjklmnbvcxzQWERTYUIPKLHGFDSAZXCVBNM23456789';
		$houzhui = strrchr($this->font,'.');
		for($i=0;$i<$this->num;$i++){
			$char = $str[mt_rand(0,strlen($str)-1)];
			$newstr .= $char; 
			$fontcolor = imagecolorallocate($this->img,mt_rand(80,129),mt_rand(80,129),mt_rand(80,129));
			if($houzhui != '.ttf'){
				
				$x = ($this->width/$this->num)*$i+mt_rand(5,10);
				$y = mt_rand(10,20);
				imagechar($this->img,mt_rand(3,5),$x,$y,$char,$fontcolor);//左上角	
			}else{
				$x = ($this->width/$this->num-3)*$i+mt_rand(10,15);//左下角
				$y = mt_rand(25,30);
				imagefttext($this->img,mt_rand($this->height/3,$this->height/2),mt_rand(-30,30),$x,$y,$fontcolor,$this->font,$char);
			}
			
		}
		$_SESSION['code'] = $newstr;
	 }
	//声明类型函数
	function shengming(){
		header('Content-type:image/jpeg');
		imagejpeg($this->img);
	}
	//关闭资源函数
	function __destruct(){
		imagedestroy($this->img);
	}
}

$code = new MyCode();

//$code->cimage();

//var_dump($code);


//var_dump($_SESSION);







?>