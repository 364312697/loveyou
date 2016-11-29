<?php 
require('include.inc.php');
$message = M('message');

$messageid = isset($_GET['messageid']) ? intval($_GET['messageid']) : 0;

if(isset($_POST['dosubmit'])){
	
	if(strtolower($_POST["code"]) != $_SESSION['code']) showmsg("验证码错误！",1);
	
	//判断当前会员，是否可发信息．
	if(strpos($member_group_info['authority'], '1') === false) showmsg("你没有权限发信息!");

	$_POST['send_to'] = safe_str($_POST['send_to']);
	if(!is_username($_POST['send_to'])) showmsg("收件人格式不正确！");
	if($_POST['send_to'] == $username) showmsg("禁止给自己发送短信息！");
	if(!$member->where(array('username'=>$_POST['send_to']))->find()) showmsg("收件人不存在！");

	$_POST['send_from'] = $username;
	$_POST['content'] = remove_xss($_POST['content']);
	$_POST['message_time'] = time();
	$_POST['replyid'] = $messageid;
	$_POST['isread'] = '0';
	if($message->insert($_POST, 1)){
		showmsg("操作成功！",1,'messages.php?status=1');
	}else{
		showmsg("操作失败！");
	}
	
}else{
	$data = array();
	if($messageid){
		$data = $message->where(array('messageid' => $messageid, 'send_to' => $username, 'status' => '1'))->find();
		$data['subject'] = !empty($data['subject']) ? '回复：'.$data['subject'] : '';	
	}
}



//分配样式及加载模板
$filename = get_file_name();
$title = '会员中心-发信息';
$cssarr = array('index');
$jsarr = array('jquery-1.8.2.min');
include("templets/$filename.html");
?> 