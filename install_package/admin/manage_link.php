<?php
require('check.php');

$link = M('link');

if(isset($_GET["id"])){
	$id = intval($_GET["id"]);
	if($link->delete(array('id'=>$id)))
		showmsg("操作成功！",1);
	else
		showmsg("操作失败！");
}

/* 批量删除 */
if(isset($_POST["sub"])){
    
    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	for($i = 0 ; $i < count($_POST['fx']) ; $i++){ 						
	    $link->delete(array('id' => $_POST['fx'][$i]));				 
	}
	showmsg('操作成功！',1);
	
}

$total = $link->total();
$page = new spage($total,10);
$start = $page->start_rows();
$res = $link->order('sortrank ASC')->limit("$start,10")->select();
include('templets/manage_link.htm');
