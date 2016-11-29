<?php 
require('include.inc.php');

$article = M('article');
$column = M('column');

/* 删除 */
if(isset($_GET["deleteid"])){ 
	$deleteid = intval($_GET["deleteid"]);
	$result = $article->delete(array('id' => $deleteid, 'display!=' => 1, 'username' => $username, 'system' => '0'));	//只能删除自己有权限删除的 且 未通过审核的
	if($result){
		showmsg('操作成功！',1);
	}else{
		showmsg('操作失败！');
	}
}

$status = isset($_GET['status']) ? intval($_GET['status']) : 0;

if($status){
	$title = '会员中心 - 已通过的稿件';
	$filename = 'publish_through';
	$total = $article->where(array('display' => 1, 'username' => $username, 'system' => '0'))->total(); 
}else{
	$title = '会员中心 - 未通过的稿件';
	$filename = 'publish_not_through';
	$total = $article->where(array('display!=' => 1, 'username' => $username, 'system' => '0'))->total(); 
}


$page = new spage($total,20);
$start = $page->start_rows();
$data = $article->field('id, title, catid, url, inputtime, display')->limit("$start,20")->order('id DESC')->select();

//分配样式及加载模板
$cssarr = array('index');
$jsarr = array('jquery-1.8.2.min');
include("templets/$filename.html");
?> 