<?php
/*
 *	文件名称：分页类
 *  用途: 用来指定数据显示分页
 *  作者：袁志蒙
 *	编写时间：2013.12.18
 *	最后修改时间：2013.12.18
 *  版权所有：(c) 2014-2016 http://www.yzmcms.com All rights reserved.
*/

class spage{
	
	private $url;        //当前URL
	private $rows;       //一共多少条数据
	private $list_rows;  //每页显示的条数
	
	function __construct($rows,$list_rows){
		$this->url = $this->getUrl();
		$this->rows = $rows;	
		$this->list_rows = $list_rows;       		
	}
	
    //获得当前地址
	protected function getUrl(){
		if($_SERVER['QUERY_STRING']){
			if($_SERVER['QUERY_STRING']=='cpage='.$_GET['cpage']){
				$str=$_SERVER['SCRIPT_NAME'].'?';
			}else{
				$str=str_replace('&cpage='.$_GET['cpage'],'',$_SERVER['REQUEST_URI']).'&';
				$str=rtrim($str,'&').'&';
			}
		}else{
			$str=$_SERVER['REQUEST_URI'].'?';
		}
		return $str;
	}
	
	//一共有多少页
	function total(){
		return ceil($this->rows/$this->list_rows);

	}
	
	//获得当前页
	public function getCpage(){
		$_GET['cpage'] = isset($_GET['cpage']) ? intval($_GET['cpage']) : 1;
		$_GET['cpage'] = $_GET['cpage']<1 ? 1 : ($_GET['cpage']>$this->total() ? $this->total() : $_GET['cpage']);

		return  $_GET['cpage'];

	}
		
	//获得首页
	public function getHome(){
	
		return '<a href="'.$this->url.'cpage=1">首页</a>';
	}
	//获得尾页
	function getEnd(){
	
		return '<a href="'.$this->url.'cpage='.$this->total().'">尾页</a>';
	}
	
	//获得上页
	public function getPre(){

		if($this->getCpage()<=1){
			return '上一页';
		}
		return '<a href="'.$this->url.'cpage='.($this->getCpage()-1).'">上一页</a>';
	}
	
	//获得下页
	public function getNext(){

		if($this->getCpage()>=$this->total()){
			return '下一页';		
		}
		return '<a href="'.$this->url.'cpage='.($this->getCpage()+1).'">下一页</a>';
	}
	

	//数字数字列表页------[1][2][3][4][5][6]
	public function getList(){
		$str='';
		if($this->getCpage()<=4){
			if($this->total()<5){
				$t=$this->total();			
			}else{
				$t=5;
			}
			for($i=1;$i<=$t;$i++){
			$style = $this->getCpage()==$i ? 'class="on"' : '';
			$str.=' [<a '.$style.' href="'.($this->url).'cpage='.$i.'">'.$i.'</a>] ';
			
			}
		}else{
			for($i=$this->getCpage()-4;$i<=$this->getCpage();$i++){
				$style = $this->getCpage()==$i ? 'class="on"' : '';
				$str.=' [<a '.$style.' href="'.($this->url).'cpage='.$i.'">'.$i.'</a>] ';
			}
		}
		return $str;
	
	}
	
	//数字数字列表页--UL版的----[1][2][3][4][5][6] 
	public function getListUl(){
		$str='';
		if($this->getCpage()<=4){
			if($this->total()<5){
				$t=$this->total();
			
			}else{
				$t=5;
			}
			for($i=1;$i<=$t;$i++){
				if($i==$this->getCpage()){
					$str.=" <li class='cur'>{$i}</li> ";
				}else{
					$str.=' <li><a href="'.($this->url).'cpage='.$i.'">'.$i.'</a></li> ';				
				}			
			}
		}else{
			for($i=$this->getCpage()-4;$i<=$this->getCpage();$i++){
				if($i==$this->getCpage()){
					$str.=" <li class='cur'>{$i}</li> ";
				}else{
					$str.=' <li><a href="'.($this->url).'cpage='.$i.'">'.$i.'</a></li> ';				
				}
			}
		}
		return $str;
	
	}	
	
	
	//显示全部---首页上页[1][2][3][4][5][6]下页尾页
	public function showFull(){
	    return ($this->getHome()).($this->getPre()).($this->getList()).($this->getNext()).($this->getEnd());
	}
	
	//SELECT * FROM tablename WHERE LIMIT '.$start.',10'
	public function start_rows(){
		if($this->rows == 0) return 0;
		return ($this->getCpage()-1)*($this->list_rows);
	}
}