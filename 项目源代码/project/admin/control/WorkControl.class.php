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
						$this->bianli($newpath,$this->numdir,$this->numfile);
						
					}else{
						$this->numfile++;
					}
				}
			}
			closedir($dir);
		}
		
			//统计目录的大小
		function dirsize($olddir){
			$size = 0;
			if(is_file($olddir)){
				return $size += filesize($olddir);
			}
			
			$dir = opendir($olddir);
			while($path = readdir($dir)){
				if($path != '.' && $path != '..'){
					$newpath = $olddir.'/'.$path;
					if(is_dir($newpath)){
						$size += $this->dirsize($newpath);
						
					}else{
						$size += filesize($newpath); 
					}
				}
				
			}
			
			
			closedir($dir);
			return $size;
		}

		function exchange($size){
			
			if($size > pow(1024,3)){
				$dw = 'G';
				$newsize = round($size / pow(1024,3),2);
			}elseif($size > pow(1024,2)){
				$dw = 'M';
				$newsize = round($size / pow(1024,2),2);
			}elseif($size > 1024){
				$dw = 'K';
				$newsize = round($size / 1024,2);
			}else{
				$dw = 'b';
				$newsize = $size;
			}
			return $newsize.$dw;
	
		}







}












?>