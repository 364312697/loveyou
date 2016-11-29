<?php
session_start();

require('../config/common.inc.php');
$sysinfo = get_sysinfo();
$url = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '../index.php';

if($sysinfo['is_words'] && isset($_POST["submit"])){
	
	foreach ($_POST as $key=>$val){
		$_POST[$key] = remove_xss($val); 
	}
	
	if($_SESSION['code'] != strtolower($_POST['code'])) showmsg("验证码错误！", 1, $url);
	if($_POST["bookmsg"] == '' || $_POST["name"] == '' || $_POST["title"] == '') showmsg("留言主题，留言人，留言内容不能为空！", 3, $url);	
	
	$_POST['booktime'] = time();
	$_POST['ip'] = getip();
	$_POST['ischeck'] = $_POST['isread'] = $_POST['replyid'] = 0;
	M('guestbook')->insert($_POST, 1);
	
	//发送邮件通知
	sendmail($sysinfo['default_email'], '您的网站有新留言', '您的网站有新留言，<a href="'.$sysinfo['wroot'].'">请查看</a>！<br> <b>'.$sysinfo['wname'].'</b>');
	
	showmsg("留言成功，请耐心等待管理员回复！", 3, $url);
	
}else{
	showmsg("管理员已关闭留言功能！", 3, $url);
}