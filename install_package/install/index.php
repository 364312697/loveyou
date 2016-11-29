<?php
/**
 * @author         袁志蒙
 * @package        YzmCMS.Install
 * @copyright      Copyright (c) 2013 - 2016
 * @link           http://www.yzmcms.com
 * @notice		   本文件仅支持YzmCMS v2.6及以上版本
 * @lastmodify	   2016-05-19
 */
 
@set_time_limit(0);
//error_reporting(E_ALL);
error_reporting(E_ALL || ~E_NOTICE);
$verMsg = '2.6';	    //版本信息
$s_lang = 'utf-8';	    //语言编码
$dfDbname = 'yzmcms';	//数据库名称
$errmsg = '';
$insLockfile = dirname(__FILE__).'/install_lock.txt';
$moduleCacheFile = dirname(__FILE__).'/modules.tmp.inc';
define('YZMCMS_ROOT',preg_replace("#[\\\\\/]install#", '', dirname(__FILE__)));
header("Content-Type:text/html;charset=utf-8");
require(YZMCMS_ROOT.'/core/functions/global.func.php');
require(YZMCMS_ROOT.'/install/install.inc.php');

if(file_exists($insLockfile))
{
    exit(" 程序已运行安装，如果你确定要重新安装，请先从FTP中删除 install/install_lock.txt！");
}

foreach(Array('_GET','_POST','_COOKIE') as $_request)
{
	 foreach($$_request as $_k => $_v) ${$_k} = RunMagicQuotes($_v);
}

if(empty($step))
{
	$step = 1;
}
/*------------------------
使用协议书
function _1_Agreement()
------------------------*/
if($step==1)
{
	include('./templates/step-1.html');
	exit();
}
/*------------------------
环境测试
function _2_TestEnv()
------------------------*/
else if($step==2)
{
	 $phpv = phpversion();
	 $sp_os = PHP_OS;
     $sp_gd = gdversion();
	 $sp_server = $_SERVER['SERVER_SOFTWARE'];
	 $sp_host = (empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_HOST'] : $_SERVER['REMOTE_ADDR']);
	 $sp_name = $_SERVER['SERVER_NAME'];
	 $sp_max_execution_time = ini_get('max_execution_time');
	 if (substr(PHP_VERSION, 0, 1) == '5') {
            $sp_php_version= "<font color=#0588c1>[√]".phpversion()."</font>";
     } else {
			$sp_php_version= "<font color=red>[×]Off</font>" ;
     } 
	 $sp_allow_reference = (ini_get('allow_call_time_pass_reference') ? '<font color=#0588c1>[√]On</font>' : '<font color=red>[×]Off</font>');
     $sp_safe_mode = (ini_get('safe_mode') ? '<font color=red>[×]On</font>' : '<font color=#0588c1>[√]Off</font>');
     $sp_gd = ($sp_gd>0 ? '<font color=#0588c1>[√]On</font>' : '<font color=red>[×]Off</font>');
     //$sp_mysql = (function_exists('mysql_connect') ? '<font color=#0588c1>[√]On</font>' : '<font color=red>[×]Off</font>');
     $sp_mysql = (extension_loaded('mysqli') ? '<font color=#0588c1>[√]On</font>' : '<font color=red>[×]Off</font>'); //2.0以上版本则改用mysqli扩展

   if($sp_mysql=='<font color=red>[×]Off</font>')
   {
   		$sp_mysql_err = true;
   }
   else
   {
   		$sp_mysql_err = false;
   }

   $sp_testdirs = array(
        '/',
        '/config/*',
        '/install/',
        '/plus/*',
        '/admin/*',
        '/html/*',
        '/uploads/*'		
   );
	 include('./templates/step-2.html');
	 exit();
}
/*------------------------
设置参数
function _3_WriteSeting()
------------------------*/
else if($step==3)
{

    if(!empty($_SERVER['REQUEST_URI']))
    $scriptName = $_SERVER['REQUEST_URI'];
    else
    $scriptName = $_SERVER['PHP_SELF'];

    $basepath = preg_replace("#\/install(.*)$#i", '', $scriptName);
    $basepath = rtrim($basepath,'/').'/';
  
  
    if(!empty($_SERVER['HTTP_HOST']))
        $baseurl = 'http://'.$_SERVER['HTTP_HOST'];
    else
        $baseurl = "http://".$_SERVER['SERVER_NAME'];

    include('./templates/step-3.html');
	exit();
}
/*------------------------
普通安装
function _4_Setup()
------------------------*/
else if($step==4)
{

  $conn = @mysqli_connect($dbhost,$dbuser,$dbpwd) or die("<script>alert('数据库服务器或登录密码无效，\\n\\n无法连接数据库，请重新设定！');history.go(-1);</script>");
  mysqli_query($conn, "SET NAMES utf8"); 
   
  mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `".$dbname."`");	//创建数据库

  mysqli_select_db($conn, $dbname) or die("<script>alert('选择数据库失败，可能是你没权限，请预先创建一个数据库！');history.go(-1);</script>");

  //获得数据库版本信息


  $fp = fopen(dirname(__FILE__)."/config.php","r");
  $configStr1 = fread($fp,filesize(dirname(__FILE__)."/config.php"));
  fclose($fp);

  //common.inc.php
	$configStr1 = str_replace("~dbhost~",$dbhost,$configStr1);
	$configStr1 = str_replace("~dbname~",$dbname,$configStr1);
	$configStr1 = str_replace("~dbuser~",$dbuser,$configStr1);
	$configStr1 = str_replace("~dbprefix~",$dbprefix,$configStr1);
	$configStr1 = str_replace("~dbpwd~",$dbpwd,$configStr1);

  @chmod(YZMCMS_ROOT.'/',0777);
  
  $fp = fopen(YZMCMS_ROOT."/config/database.php","w") or die("<script>alert('写入配置失败，请检查../config目录是否可写入！');history.go(-1);</script>");
  fwrite($fp,$configStr1);
  fclose($fp);
  
  //创建数据表
  
  $query = '';
  $fp = fopen(dirname(__FILE__).'/sql.txt','r');
	while(!feof($fp))
	{
		$query.=str_replace('#@_',$dbprefix,fgets($fp));
	}
		$arr = preg_split("/[;]+/",$query,-1,PREG_SPLIT_NO_EMPTY);

	    foreach($arr as $path){
			 mysqli_query($conn, $path);
	    } 

	fclose($fp);

	//增加管理员帐号
	$adminquery = "INSERT INTO `".$dbprefix."admin` (`uname`, `pwd`, `usertype`, `email`,`remark`,`addtime`,`addpeople`) VALUES ('".$adminuser."', '".password($adminpwd)."','1','214243830@qq.com','超级管理员','".time()."','系统')";
	mysqli_query($conn, $adminquery);
	
	//增加网站信息
	$adminquery = "INSERT INTO `".$dbprefix."webinfo` (`wname`,`wroot`,`wpath`) VALUES ('".$webname."','".$baseurl.$cmspath."','".$cmspath."')";	
	mysqli_query($conn, $adminquery);
	
    mysqli_close($conn);

  	//锁定安装程序
  	$fp = fopen($insLockfile,'w');
  	fwrite($fp,'ok');
  	fclose($fp);
  	include('./templates/step-4.html');
	@rename("index.php", "index.php.bak");
  	exit();
}

/*------------------------
检测数据库是否有效
function _10_TestDbPwd()
------------------------*/
else if($step==10)
{
  header("Pragma:no-cache\r\n");
  header("Cache-Control:no-cache\r\n");
  header("Expires:0\r\n");
	$conn = @mysqli_connect($dbhost,$dbuser,$dbpwd);
	if($conn)
	{
	  $rs = mysqli_select_db($conn, $dbname);
	  if(!$rs)
	  {
		   $rs = mysqli_query($conn, " CREATE DATABASE `$dbname` ");
		   if($rs)
		   {
		  	  mysqli_query($conn, " DROP DATABASE `$dbname`; ");
		  	  echo "<font color='#0588c1'>信息正确</font>";
		   }
		   else
		   {
		      echo "<font color='red'>数据库不存在，也没权限创建新的数据库！</font>";
		   }
	  }
	  else
	  {
		    echo "<font color='#0588c1'>信息正确</font>";
	  }
	}
	else
	{
		echo "<font color='red'>数据库连接失败！</font>";
	}
	@mysqli_close($conn);
	exit();
}
?>