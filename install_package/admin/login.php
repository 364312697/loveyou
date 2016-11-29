<?php
session_start(); 

define('WEB_PATH', dirname(str_replace("\\", '/', dirname(__FILE__))));

require(WEB_PATH.'/config/common.inc.php');

$admin = M('admin');

//注销登录
if(isset($_GET['zx']) && $_GET['zx'] == 'out'){
	$_SESSION = array();
	session_destroy();
	showmsg("您已经成功注销！",1,"login.php");
}

//检查是否是否有存在已登录的用户
if(!empty($_SESSION['is_syslogin']) && $_SESSION['is_syslogin']){
	showmsg("已经有一个用户在登录！",3,"index.php");
}

//检测安装目录安全性
if(is_dir(dirname(__FILE__).'/../install')){
    if(!file_exists(dirname(__FILE__).'/../install/install_lock.txt')){
      $fp = fopen(dirname(__FILE__).'/../install/install_lock.txt', 'w') or die('安装目录无写入权限，无法进行写入锁定文件，请安装完毕删除安装目录！');
      fwrite($fp,'ok');
      fclose($fp);
    }
    //为了防止未知安全性问题，强制禁用安装程序的文件
    if(file_exists("../install/index.php")){
        @rename("../install/index.php", "../install/index.php.bak");
    }
	$fileindex = "../install/index.html";
	if(!file_exists($fileindex)) {
		$fp = @fopen($fileindex,'w');
		fwrite($fp,'YzmCMS Installation success.');
		fclose($fp);
	}
}

if(isset($_POST['dosubmit'])){
	
	$user = $_POST['user'];
	if(!is_username($user)) showmsg("用户名不合法！");  //其实这段代码是可加可不加的，但是为了后台的绝对安全还是加上吧~
	
	$pwd = isset($_COOKIE["YZMCMS_PWD"]) ? str_replace('%', '', $_COOKIE["YZMCMS_PWD"]) : password($_POST['pwd']);		
	$remember = isset($_POST["remember"]) ? $_POST["remember"] : false;

	if(empty($_SESSION['code']) || strtolower($_POST["code"])!=$_SESSION['code']){
		$_SESSION['code'] = '';
		showmsg("验证码错误！",1);	
	}

	$adminlog = M('adminlog');
	$loginip = getip();
	$address = get_address($loginip);
	$result = $admin->where(array('uname' => $user))->find();
	if($result){
		$res = $admin->field('yzmcms_admin.*,atype.purviews')->join('`yzmcms_admintype` atype ON atype.rank=yzmcms_admin.usertype')->where(array('uname' => $user, 'pwd' => $pwd))->find();
		if($res){
			if($remember){    
				setcookie("YZMCMS_USER", $user, time()+3600*24*30);   
				setcookie("YZMCMS_PWD", $pwd, time()+3600*24*30);  
			}else{                    
				setcookie("YZMCMS_USER", '', time()-100);  
				setcookie("YZMCMS_PWD", '', time()-100); 				
			} 			
			$_SESSION['adminname'] = $user;
			$_SESSION['adminid'] = $res['id'];
			$_SESSION['ontime'] = time();			
			$_SESSION['userinfo'] = $res;						
			$_SESSION['is_syslogin'] = true;			
			$admin->update(array('loginip'=>$loginip,'logintime'=>time()),array('id'=>$res['id']));
			$adminlog->insert(array('user'=>$user,'login_time'=>time(),'ip'=>$loginip,'address'=>$address,'pwd'=>'***','result'=>'1','cause'=>'登录成功'));
			showmsg("登录成功！正在转向主页面...",1,"index.php");
		}else{
			$adminlog->insert(array('user'=>$user,'login_time'=>time(),'ip'=>$loginip,'address'=>$address,'pwd'=>$_POST['pwd'],'result'=>'0','cause'=>'密码错误'));
			$_SESSION['code'] = '';
			showmsg("密码错误！",1);
		}
	}else{
	    $adminlog->insert(array('user'=>$user,'login_time'=>time(),'ip'=>$loginip,'address'=>$address,'pwd'=>$_POST['pwd'],'result'=>'0','cause'=>'用户名不存在'));	
		$_SESSION['code'] = '';
	    showmsg("用户名不存在！",1);
	}
}
include('templets/login.htm');