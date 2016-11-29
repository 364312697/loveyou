<?php
/*
 *	文件名称：分页类
 *  用途: 用来指定数据显示分页 [前台：静态地址分页]
 *  作者：袁志蒙
 *	编写时间：2013.12.18
 *	最后修改时间：2013.12.18
 *  版权所有：(c) 2014-2016 http://www.yzmcms.com All rights reserved.
*/

class page{
	
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
	   $url =  rtrim(get_url(),'/').'/';
	   if($_GET['page'] == 1){ 
			$url =  str_replace('/1.html', '', $url);
			return $url;
	   }else{
			$arr = explode('/',$url);
			$num = count($arr);
			unset($arr[$num-1],$arr[$num-2]);
			$url = join($arr,'/').'/';
			return $url;		  
	   }		
	}
	
	//一共有多少页
	function total(){
		return ceil($this->rows/$this->list_rows);

	}
	
	//获得当前页
	public function getCpage(){
		
		$_GET['page'] = $_GET['page']<1 ? 1 : ($_GET['page']>$this->total() ? $this->total() : $_GET['page']); //这里是page，不是cpage

		return  $_GET['page'];

	}
		
	//获得首页
	public function getHome(){
	
		return '<a href="'.$this->url.'">首页</a>';
	}
	
	//获得尾页
	function getEnd(){
	
		return '<a href="'.$this->url.$this->total().'.html">尾页</a>';
	}
	
	//获得上一页
	public function getPre(){
		
		if($this->getCpage()<=1){
			return '上一页';		
		}
		return '<a href="'.$this->url.($this->getCpage()-1).'.html">上一页</a>';
	}
	
	//获得下一页
	public function getNext(){

		if($this->getCpage()>=$this->total()){
			return '下一页';		
		}
		return '<a href="'.$this->url.($this->getCpage()+1).'.html">下一页</a>';
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
				if($i==$this->getCpage()){
					$str.=" <li class='cur'>{$i}</li> ";
				}else{
					$str.=' <li><a href="'.($this->url).$i.'.html">'.$i.'</a></li> ';				
				}			
			}
		}else{
			for($i=$this->getCpage()-4;$i<=$this->getCpage();$i++){
				if($i==$this->getCpage()){
					$str.=" <li class='cur'>{$i}</li> ";
				}else{
					$str.=' <li><a href="'.($this->url).$i.'.html">'.$i.'</a></li> ';				
				}
			}
		}
		return $str;
	
	}
	
	//显示全部---首页上一页[1][2][3][4][5][6]下一页尾页
	public function showFull(){
	    return ($this->getHome()).($this->getPre()).($this->getList()).($this->getNext()).($this->getEnd());
	}
	
	//SELECT * FROM tablename WHERE LIMIT '.$start.',10'
	public function start_rows(){
		if($this->rows == 0) return 0;
		return ($this->getCpage()-1)*($this->list_rows);
	}
}