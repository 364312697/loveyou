<?php 
require('check.php');

$password = 'yzmcms'; //设置文件锁的密码

if(!empty($_SESSION['ftpname'])){
	header('location:fileftp.php');
	exit;
}

if(isset($_POST["sub"])){
	if($_POST["psss"] == $password){
	    $_SESSION["ftpname"] = true;
	    showmsg("文件管理登录成功！", 1, "fileftp.php");
	}else{
	    showmsg("密码错误！");
	}
}

include('templets/filelock.htm');