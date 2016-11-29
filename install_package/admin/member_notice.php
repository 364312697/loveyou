<?php
require('check.php');
$message_group = M('message_group'); //群发
$message = M('message'); //单发
$res =  M('member_group')->field('groupid,name')->select();
$webinfo = get_sysinfo();
$data = $message_group->order('id DESC')->limit('20')->select();
$data2 = $message->where(array('issystem' => '1'))->order('messageid DESC')->limit('20')->select();

//群发信息
if(isset($_POST['submit1'])){
	$_POST['inputtime'] = time();
	$message_group->insert($_POST,1);
	showmsg("操作成功！",1);
}

//单发信息
if(isset($_POST['submit2'])){
	$_POST['send_from'] = '系统管理员';
	$_POST['issystem'] = '1';
	$_POST['message_time'] = time();
	$message->insert($_POST,1);
	showmsg("操作成功！",1);
}

//群发信息 批量删除
if(isset($_POST['submit3'])){
	
    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	
	for($i = 0 ; $i < count($_POST['fx']) ; $i++){ 						
	    $message_group->delete(array('id' => $_POST['fx'][$i]));				 
	}
	showmsg('操作成功！',1);	
	
}

//单发信息 批量删除
if(isset($_POST['submit4'])){
	
    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	
	for($i = 0 ; $i < count($_POST['fx']) ; $i++){ 						
	    $message->delete(array('messageid' => $_POST['fx'][$i]));				 
	}
	showmsg('操作成功！',1);
	
}

include('templets/member_notice.htm');