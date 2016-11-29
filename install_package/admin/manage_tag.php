<?php
require('check.php');

$tag = M('tag');
$column = M('column');

if(isset($_GET["id"])){
	$id = intval($_GET["id"]);
	if($tag->delete(array('id'=>$id)))
		showmsg("操作成功！",1);
	else
		showmsg("操作失败！");
}

/* 批量删除 */
if(isset($_POST["sub"])){
    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	
	for($i = 0 ; $i < count($_POST['fx']) ; $i++){ 						
	    $tag->delete(array('id' => $_POST['fx'][$i]));				 
	}
	showmsg('操作成功！',1);
}

if(isset($_GET['search'])){
	
	$searinfo = isset($_GET["searinfo"]) ? $_GET["searinfo"] : '';
	$searkey = $_GET["searkey"];
	$order = $searkey.' DESC';
	$total = $tag->where(array('tag' => '%'.$searinfo.'%'))->total();
	$page = new spage($total,10);
	$start = $page->start_rows();
	$res = $tag->limit("$start,10")->order($order)->select();	
	
}else{
	
	$total = $tag->total();
	$page = new spage($total,10);
	$start = $page->start_rows();
	$res = $tag->limit("$start,10")->order('id DESC')->select();
	
}

include('templets/manage_tag.htm');
