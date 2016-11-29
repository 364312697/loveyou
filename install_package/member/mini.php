<?php 
/**
 * mini登陆条
 */
 
session_start();

require('../config/common.inc.php');
$webinfo = get_sysinfo();
$webroot = $webinfo['wroot'];

$forward = isset($_GET['forward']) && trim($_GET['forward']) ? urlencode($_GET['forward']) : 'index.php';
$html = '';
if(!empty($_SESSION['_userid'])){
	$html .= '你好：'.$_SESSION['_username'].'，';
	$html .= '<a href="'.$webroot.'member/" target="_blank">会员中心</a> <a href="'.$webroot.'member/login.php?zx=out" target="_top">退出</a>';
}else{
	$html .= '<a href="'.$webroot.'member/login.php?forward='.$forward.'" target="_top">登录</a> | <a href="'.$webroot.'member/register.php?forward='.$forward.'" target="_blank">注册</a>';
}

include("templets/mini.html");
?> 