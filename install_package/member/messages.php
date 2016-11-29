<?php 
require('include.inc.php');
$message = M('message');


/* 删除发件箱 */
if(isset($_POST["dosubmit"])){ 
    
	if(!isset($_POST['fx'])) showmsg('您没有选择项目！');
    if(!is_array($_POST['fx'])) showmsg('非法操作！');
	
	foreach($_POST['fx'] as $v){
		$message->delete(array('messageid' => intval($v), 'send_from' => $username));
	}
	
    showmsg('操作成功！',1);
}


/* 删除收件箱 */
if(isset($_POST["dosubmit2"])){ 

    if(!isset($_POST['fx'])) showmsg('您没有选择项目！');
    if(!is_array($_POST['fx'])) showmsg('非法操作！');
	
	foreach($_POST['fx'] as $v){
		$message->update(array('status' => 0), array('send_to' => $username, 'messageid' => intval($v))); //只是隐藏，不执行删除操作	
	}
	
    showmsg('操作成功！',1);
}


$status = isset($_GET['status']) ? intval($_GET['status']) : 0;
if($status){
	$where = "send_from = '$username' ";
	$title = '会员中心 - 发件箱';
	$filename = 'outbox';
}else{
	$where = "send_to = '$username' AND `status` = 1 "; //收件箱中只有收件人未删除[未隐藏]的信息
	$title = '会员中心 - 收件箱';
	$filename = 'inbox';
}

$total = $message->where($where)->total(); 

$page = new spage($total,20);
$start = $page->start_rows();
$data = $message->limit("$start,20")->order('messageid DESC')->select(); 


$cssarr = array('index');
$jsarr = array('jquery-1.8.2.min');
include("templets/$filename.html");
?> 