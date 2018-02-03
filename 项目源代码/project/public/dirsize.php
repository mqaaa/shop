<?php

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
					$size += dirsize($newpath);
					
				}else{
					$size += filesize($newpath); 
				}
			}
			
		}
		
		
		closedir($dir);
		return $size;
	}



$res = dirsize('./yidong');


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

echo exchange($res);
echo exchange(10000);







?>