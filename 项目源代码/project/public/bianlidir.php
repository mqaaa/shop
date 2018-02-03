<?php 
class WorkControl
{

public $numdir ;	//目录数
public $numfile;	//目录文件数

	//这里要遍历目录统计出遍历
		function _construct(){
			$this->numdir = 0;
			$this->numfile = 0;
		}

		//遍历目录
		function bianli($olddir){
			if(is_file($olddir)){
				$this->numfile = 1;
				return;
			}
			$dir = opendir($olddir);
			while($path = readdir($dir)){
				if($path != '.' && $path != '..'){
					$newpath = $olddir.'/'.$path;
					if(is_dir($newpath)){
						$this->numdir++;
						bianli($newpath,$this->numdir,$this->numfile);
						
					}else{
						$this->numfile++;
					}
				}
			}
			closedir($dir);
		}
		


}
$f= new WorkControl();
$f->bianli('../oop 4');
echo $f->numdir;
echo $f->numfile;
