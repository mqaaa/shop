<?php
	//实现文件上传

class Upload{


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

	function upload(){ //得到一个数组;
		//接受上传的内容数组
		$file = $_FILES[$this->pic];
		// var_dump($_FILES);
		// var_dump($pic);
		//1.判断上传的错误号
		if($file['error'] > 0){
			switch($file['error']){
				case 1:
					return '超过了pHP.ini配置文件中的upload_max_filesize设置的值';
				case 2:
					return '超过了HTML表单中设置的MAX_FILE_SIZE设置的值';
				case 3:
					return '只有部分文件被上传';
				case 4:
					return '没有文件上传';
				case 6:
					return '找不到临时目录';
				case 7:
					return '写入失败';
			}
		}
		//2.判断上传的类型
		if(!in_array($file['type'],$this->type)){
			return '类型不合法';
		}
		//3.判断上传的大小
		if($file['size'] > $this->size){
			return '文件上传过大';
		}
		//4.重命名
		do{
			//1.获取图片名后缀
			$suffix = strrchr($file['name'],'.');
			//2.拼接图片名称
			$newName = md5(time().mt_rand(1,999).uniqid()).$suffix;
		//5.判断保存的目录
			//1.删除目录中 最后的/部分
			$path = rtrim($this->path,'/').'/';
			//2判断路径是否存在
			if(!file_exists($path)){
				mkdir($path);
			}
			//拼接路径和图片名
			$newPath = $path.$newName;
		}while(file_exists($newPath));

		//定义返回数组变量
		$pathinfo = array();
		//6.移动图片
		if(move_uploaded_file($file['tmp_name'],$newPath)){
			//返回一个数组
			$pathinfo['pathinfo'] = $newPath;
			$pathinfo['path'] = $path;
			$pathinfo['name'] = $newName;
			$this->pathinfo = $pathinfo;
			return $pathinfo;
		}else{
			return false;
		}
	}



}
	