<?php

//多文件上传
class Rupload{


	//成员属性
	public $pic;
	public $path;
	public $size;
	public $type;

	public $pathinfo;

	function __construct($pic,$path = './imgupload',$size = 500000,array $type = array('image/jpeg','image/png','image/gif')){
			$this->pic = $pic;
			$this->path = $path;
			$this->size = $size;
			$this->type = $type;
	}


	function upload(){
	
		$img = $_FILES[$pic];
		//var_dump($img);
		//判断错误号
		foreach($img['error'] as $k=>$v){
			if($v>0){
				switch($img['error']){
					case 1:
						return '有的文件大小超出了php.ini设置的upload_max_file的值';
					case 2:
						return '有的文件大小超出了HTML表单设置的max_file_size的值';
					case 3:
						return '只有部分文件上传';
					case 4:
						return '没有文件上传';
					case 6:
						return '文件';
					case 7:
						return '文件写入失败';
				}
			}
		}
		
		//判断文件类型
		foreach($img['type'] as $v){
			if(!in_array($v,$type)){
				return '有的文件类型不符合';
			}
		}
		
			
		
		//判断文件大小
		foreach($img['size'] as $v){
			if($v>$size){
				return '有的文件过大';
			}
		}
			
		//给文件命名
			foreach($img['name'] as $k=>$v){
				do{
					$name = $v;	
					$houzhui = strrchr($name,'.');
					$newname = md5(time().uniqid().mt_rand(1,9999)).$houzhui;
					//var_dump($newname);
					//拼接路径
					if(!file_exists($path)){
						mkdir($path);
					}
					$path = rtrim($path,'/');
					$newpath = $path.'/'.$newname;
					//var_dump($newpath);
				}while(file_exists($newpath));
				if(move_uploaded_file($img['tmp_name'][$k],$newpath)){
					$imginfo[$k]['name'] = $newname;
					$imginfo[$k]['dir'] = $path;
					$imginfo[$k]['path'] = $newpath; 
				}else{
					return false;
				}
			}

		
		return $imginfo; //返回一个多维数组
	}
}

?>