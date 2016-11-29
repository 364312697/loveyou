<?php
require('check.php');

if(isset($_POST['dosubmit'])){
	
	$sysinfo = get_sysinfo();
	
	if(!is_email($_POST['email_to'])) showmsg("邮箱地址不合法！");
	
	$res = sendmail($_POST['email_to'], $_POST['email_title'], $_POST['email_content'].'<br><b>'.$sysinfo['wname'].'</b>');
	if($res){
		$html_message = '<p>发送邮件成功！</p><p>您还可以：<a href="send_email.php">继续发送</a></p>';
	}else{
		$html_message = '<p style="color:red;">发送邮件失败！</p><p>请检查配置：<a href="email.php">点击检查配置</a></p>';
	}
	include('templets/message.htm');
}else{
	include('templets/send_email.htm');
}
