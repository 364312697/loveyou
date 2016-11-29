<?php 
/**
 *	文件名称：ajax.class.php
 *  用途: 后台ajax验证
 *  作者：袁志蒙
 *	编写时间：2016.06.03
 *  版权所有：(c) 2014-2016 http://www.yzmcms.com All rights reserved.
*/
  
class ajax{
	
	//检查文章标题是否重复
	public function check_title(){
		if(M('article')->field('title')->where(array('title'=>$_POST['title']))->total() > 0)
		    exit('0');  //标题重复
		else
		    exit('1');  //标题不重复
	}
	
	//检查表名是否存在
	public function check_model(){
		$tablename = isset($_POST['tablename']) ? DB_PREFIX.$_POST['tablename'] : '';
		if(!$tablename) exit('-1');  //表名称为空
		if(!M('model')->table_exists($tablename))
		   exit('1');  //表不存在
		else
		   exit('0');  //表已存在		
	}
	
	//检查表字段名是否存在
	public function check_field(){
		$tablename = isset($_POST['tablename']) ? DB_PREFIX.$_POST['tablename'] : '';
		$field = isset($_POST['field']) ? $_POST['field'] : '';
		if(!$tablename || !$field) exit('-1');  //表名称或者字段名为空
		if(!M('model')->field_exists($tablename, $field))
		   exit('1');  //表字段名不存在
		else
		   exit('0');  //表字段名已存在		
	}	
}














require('../check.php');
$method = isset($_GET['a']) ? $_GET['a'] : '';
if(!$method) exit;	
$controller = new ajax();
if(method_exists($controller, $method)){
	call_user_func(array($controller, $method));
}else{
	exit($method.'method does not exist!');
}