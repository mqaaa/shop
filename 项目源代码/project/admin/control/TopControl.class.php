<?php

//头部显示控制器

class TopControl
{
	function show(){

		include('./view/top.html');

	}
	function workdesk(){
		include('./view/top/default.html');
	}
	function imglist(){
		include('./view/top/imglist.html');
	}
	function imglist1(){
		include('./view/top/imglist1.html');
	}
	function tools(){
		include('./view/top/tools.html');
	}
	function computer(){
		include('./view/top/computer.html');
	}
}










?>