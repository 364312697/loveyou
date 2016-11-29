<?php
require('check.php');

$result = get_sysinfo();

if(isset($_POST['sub'])){
	foreach($_POST as $key => $value){
			M('otherconfig')->update(array('value'=>$value),array('varname'=>$key));		
	}
	delcache('sysinfo');
	showmsg("保存成功",'1','email.php');
}

if(isset($_GET['send'])){
	if(!is_email($_GET['mail_to'])) showmsg("邮箱地址不合法！",'3','email.php');
	
	$res = sendmail($_GET['mail_to'], '这是一封测试邮件', '如果您成功接收此邮件，说明您的邮件配置正确！<br> <b>YzmCMS</b>');
	if(!$res){
	   echo '<script>alert("发送邮件失败！");</script>';
	}else{
	   echo '<script>
	   alert("发送邮件成功！");
	   window.location.href="email.php";
	   </script>';  
	}	
	
	
}


include('templets/email.htm');
