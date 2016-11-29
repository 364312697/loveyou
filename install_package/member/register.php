<?php 
define('MEMBER_PATH', dirname(str_replace("\\", '/', dirname(__FILE__))));
require(MEMBER_PATH.'/config/common.inc.php');

//开启SESSION
session_start(); 

$webconfig = get_sysinfo();

//检查是否允许新会员注册
if($webconfig["member_register"] == 0){
	showmsg("管理员关闭了新会员注册！", 3, $webconfig["wroot"]);
}

$member = M('member');
$member_detail = M('member_detail');

//检查是否是否有存在已登录的用户
if(!empty($_SESSION['_userid'])){
	showmsg("已经有一个用户在登录！",3,"index.php");
}

if(isset($_POST["dosubmit"])){
$_POST['username'] = isset($_POST['username']) && trim($_POST['username']) ? trim($_POST['username']) : showmsg("用户名不能为空！");		
if(!empty($_SESSION['code']) && strtolower($_POST['code']) == $_SESSION['code']){
	
    if(!is_username($_POST['username'])) showmsg("用户名不符合规范");
    if(!is_email($_POST['email'])) showmsg("邮箱格式不正确");
    if($_POST['password'] != $_POST['password2']) showmsg("两次密码不一致");
	if(!is_password($_POST['password'])) showmsg("密码不符合规范");
	
	$result = $member->where(array('username'=>$_POST['username']))->find();
	if($result) showmsg("用户名已存在！");
	$result = $member->where(array('email'=>$_POST['email']))->find();
	if($result) showmsg("邮箱已存在！");
	
	$loginip = getip();
	if(!isset($_POST['nickname'])) $_POST['nickname'] = $_POST['username'];
	$_POST["password"] = password($_POST['password']);
	$_POST['regdate'] = $_POST['lastdate'] = time();
	$_POST['regip'] = $_POST['lastip'] = getip();
	$_POST['loginnum'] = '0';
	$_POST['groupid'] = '1';
	$_POST['amount'] = '0.00';
	$_POST['point'] = $webconfig['member_point'];
	$_POST['status'] = ($webconfig['member_check'] || $webconfig['member_email']) ? 0 : 1;
	$_POST['email_status'] = '0';		
	$_POST['vip'] = '0';		
	$_POST['userid'] = $member->insert($_POST,1);		
	if($_POST['userid']){
		$member_detail->insert($_POST,1); //插入附表
		
		if($webconfig['member_email']){  //是否需要邮件验证
		    $mail_code = $_SESSION['mail_code'] = md5(microtime(true).$_POST['userid']);
			$_SESSION['userid'] = $_POST['userid'];
		    $url = $webconfig['wroot'].'member/register.php?mail_code='.$mail_code.'&userid='.$_POST['userid'].'&verify=1';
		    $message = '请点击邮箱验证地址：<a href="'.$url.'">'.$url.'</a>';
			$res = sendmail($_POST['email'], '会员邮箱验证', $message);
			if(!$res) showmsg("邮件发送失败，请联系网站管理员！");
			showmsg("我们已将邮件发送到您的邮箱，请尽快完成验证！");
		}elseif($webconfig['member_check']){  //是否需要管理员审核
			showmsg("注册成功，由于管理员开启审核机制，请耐心等待！");
		}
		
		$_SESSION["_username"] = $_POST['username'];
		$_SESSION["_userid"] = $_POST['userid'];
		$forward = isset($_GET['forward']) && trim($_GET['forward']) ? urldecode($_GET['forward']) : 'index.php';			
		showmsg("注册成功！", 1, $forward);
	}else{
		showmsg("注册失败！");
	}
}else{
	$_SESSION['code'] = '';
	showmsg("验证码错误！",1);
}
}else{
	if(isset($_GET['verify'])){
		$mail_code = isset($_GET['mail_code']) ? trim($_GET['mail_code']) : showmsg('非法访问！');
		$userid = isset($_GET['userid']) ? intval($_GET['userid']) : showmsg('非法访问！');
		if(isset($_SESSION['mail_code']) && $mail_code==$_SESSION['mail_code'] && $userid==$_SESSION['userid']){
			unset($_SESSION['mail_code'],$_SESSION['userid']);
			$member->update(array('status' => 1, 'email_status' => 1),array('userid'=>$userid));
			showmsg("邮箱验证成功！",2,'login.php');
		}else{
			showmsg("验证失败，可能是验证时间已过期！",3,"register.php");
		}
	}
	include("templets/register.html");
}