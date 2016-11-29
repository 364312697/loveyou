<?php 
define('MEMBER_PATH', dirname(str_replace("\\", '/', dirname(__FILE__))));
require(MEMBER_PATH.'/config/common.inc.php');

//开启SESSION
session_start(); 

$member = M('member');

//注销登录
if(isset($_GET['zx']) && $_GET['zx'] == 'out'){
  unset($_SESSION["_username"], $_SESSION["_userid"]);
  showmsg("您已经安全退出！",1,"login.php");
}

//检查是否是否有存在已登录的用户
if(!empty($_SESSION['_userid'])){
	showmsg("已经有一个用户在登录！",3,"index.php");
}

$webinfo = get_sysinfo();

if(isset($_POST["dosubmit"])){

	//检查是否开启验证码
	if($webinfo['member_yzm']){
		if(empty($_SESSION['code']) || strtolower($_POST["code"])!==$_SESSION['code']){
		    $_SESSION['code'] = '';
		    showmsg("验证码错误！",1);
	    }
	}

	$username = $_POST["username"];
	$password = password($_POST["password"]);
	if(!is_username($username)) showmsg("用户名格式不正确！",1);
	
	$loginip = getip();
	$result = $member->where(array('username'=>$username))->find();
	if($result){
		$res = $member->where(array('username'=>$username,'password'=>$password))->find();
		if($res){
			if($res['status'] == '0') 
				showmsg("用户未通过审核！");		
			else if($res['status'] == '2') 
				showmsg("用户已锁定！");		
			else if($res['status'] == '3') 
				showmsg("用户已被管理员拒绝！");	
			
			$_SESSION["_username"] = $res['username'];
			$_SESSION["_userid"] = $res['userid'];	

			$last_day = date("d",$res['lastdate']);
			if($last_day != date("d")  &&  time()>$res['lastdate'] && $webinfo['login_point']>0){
				 $member->update('`point`=`point`+'.$webinfo['login_point'], array('userid'=>$res['userid']));  //每日登陆积分
				 M('pay')->insert(array('trade_sn'=>create_tradenum(),'userid'=>$res['userid'],'username'=>$res['username'],'money'=>$webinfo['login_point'],'creat_time'=>time(),'msg'=>'每日登陆','payment'=>'自动获取','type'=>'1','ip'=>$loginip,'status'=>'1'));
				 $res['point'] += $webinfo['login_point'];
				 update_group($res);	//检查更新会员组		
			}

			$member->update(array('lastip'=>$loginip,'lastdate'=>time()), array('userid'=>$res['userid']));
			$member->update('`loginnum`=`loginnum`+1', array('userid'=>$res['userid']));
			$forward = isset($_POST['forward']) && trim($_POST['forward']) ? urldecode($_POST['forward']) : 'index.php';
			showmsg("登录成功！", 1, $forward);
		}else{		
		  showmsg("密码错误！",1);
		}
	}else{	
		showmsg("用户名不存在！",1);
	}

}


$forward = isset($_GET['forward']) && trim($_GET['forward']) ? urlencode($_GET['forward']) : '';
include("templets/login.html");
?> 