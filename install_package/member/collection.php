<?php 
require('include.inc.php');

$favorite = M('favorite');


/* 删除 */
if(isset($_POST["dosubmit"])){ 

    if(!isset($_POST['fx'])) showmsg('您没有选择项目！');
	if(!is_array($_POST['fx'])) showmsg('非法操作！');
	
	foreach($_POST['fx'] as $v){
		$favorite->delete(array('id' => intval($v), 'userid' => $userid));
	}
	
	showmsg('操作成功！',1);
}



$total = $favorite->where(array('userid' => $userid))->total(); 

$page = new spage($total,20);
$start = $page->start_rows();
$data = $favorite->limit("$start,20")->order('id DESC')->select();

//分配样式及加载模板
$filename = get_file_name();
$title = '会员中心-收藏夹';
$cssarr = array('index');
include("templets/$filename.html");
?> 