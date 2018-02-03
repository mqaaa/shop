<?php


class Page
{
	public $num ; //总条数
	public $list; //显示多少条

	public $page; //当前页
	public $prepage; //上一页
	public $nextpage; //下一页
	public $allpage; //总页数
	function __construct($num,$list){
		$this->list = $list;

		$this->num = $num;
		$this->allpage = ceil($num/$list);
		$this->page = isset($_GET['page'])?$_GET['page']:1;

		$this->prepage = $this->page<1?$this->page=1:$this->page-1;
		$this->nextpage = $this->page>$this->allpage?$this->page=$this->allpage:$this->page+1;
	}
	function style(){

		echo '<a href="?page='.$this->prepage.'">上一页</a> ';
		echo '<a href="?page='.$this->nextpage.'">下一页</a> ';

	}

	function limit(){
		
		$xianshi = ($this->page-1)*$this->list; //下一页开始的位置。（当前页-1）*一页的条数

		//$limit = "limit {$xianshi},{$this->list}"; //第四个参数


		return $xianshi.','."{$this->list}";
	}
	

}












?>