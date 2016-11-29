<?php 
require('include.inc.php');

if(isset($_POST['dosubmit'])){
	if(strtolower($_POST["code"]) != $_SESSION['code']) showmsg("验证码错误！",1);
	if($_POST['oldpass'] == '') showmsg("原密码不能为空");
	if(!$member->where(array('username'=>$username, 'password'=>password($_POST['oldpass'])))->find()) showmsg("原密码错误");
	if(!is_password($_POST['password'])) showmsg("新密码不符合规范");
	if($member->update(array('password'=>password($_POST['password'])),array('userid'=>$userid))){
		showmsg("操作成功！",1);
	}else{
		showmsg("操作失败！");
	}
}

//分配样式及加载模板
$filename = get_file_name();
$title = '会员中心-修改密码';
$cssarr = array('index');
$jsarr = array('jquery-1.8.2.min');
include("templets/$filename.html");
?> 