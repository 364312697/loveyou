<?php
/**
 * 
 * 作者：袁志蒙
 * 作用：后台登录及权限检查
 * 版权：YzmCMS版权所有
 * 网址：http://www.yzmcms.com
 * 最后修改时间：2016.04.19
 * 
 */
 
session_start(); 

define('WEB_PATH', dirname(str_replace("\\", '/', dirname(__FILE__))));

require(WEB_PATH.'/config/common.inc.php');

if(empty($_SESSION['is_syslogin']) || !$_SESSION['is_syslogin']){
	showmsg('请先登录！', 1, 'login.php');
}
	
if($_SESSION['is_syslogin'] && time() - $_SESSION['ontime'] >= 7200){
	$_SESSION = array();
	session_destroy();
	showmsg('会话已过期，请从新登录！', 1, 'login.php');
}


$usertype = $_SESSION['userinfo']['usertype'];
$purviews = $_SESSION['userinfo']['purviews'];


/**
 * 检验用户是否有权使用某功能,这个函数是一个回值函数
 * check_purview函数只是对他回值的一个处理过程
 *
 * @param     string  $n  功能名称
 * @return    bool  
 */
function test_purview($n){
    $rs = false;
	
	if(in_array($n, array('index', 'left', 'main', 'top', 'ajax.class', 'default', 'about', 'transfer', 'filelock'))){
        return true;
    }
	
	global $purviews;
    if($purviews == 'admin_allowall'){
        return true;
    }
	
    $purview_arr = explode(',', $purviews);
    if(in_array($n, $purview_arr)){
        $rs = true;
    }
    return $rs;
}

/**
 * 对权限检测后返回操作对话框
 *
 * @param     string  $n  功能名称
 * @return    string
 */
function check_purview($n){
    if(!test_purview($n)){
        showmsg('对不起，你没有权限执行此操作！', 3, 'main.php');
    }
}
check_purview(get_file_name());