<?php 
define('MEMBER_PATH', dirname(str_replace("\\", '/', dirname(__FILE__))));
require(MEMBER_PATH.'/config/common.inc.php');

//开启SESSION
session_start(); 

if(isset($_SESSION["userinfo"]) && isset($_POST["dosubmit"])){
	
	$webinfo = get_sysinfo();
	
	$member = M('member');
	$username = $_POST["username"];
	if($username == '' || $_POST["password"] == '') showmsg("用户名或密码不能为空！");
	$password = password($_POST["password"]);	
	if(!is_username($username)) showmsg("用户名不符合规范");
	
	$result = $member->where(array('username'=>$username))->find();
	if($result){
		$res = $member->where(array('username'=>$username,'password'=>$password))->find();
		if($res){
			//先绑定第三方账号，可以是QQ、微信、新浪、百度等
			$member->update(array('connectid'=>$_SESSION['connectid'], 'fromlogin'=>$_SESSION['fromlogin']), array('userid'=>$res['userid']));
			unset($_SESSION["userinfo"], $_SESSION["connectid"], $_SESSION["fromlogin"]);

			if($res['status'] == '0') 
				showmsg("用户未通过审核！");		
			else if($res['status'] == '2') 
				showmsg("用户已锁定！");		
			else if($res['status'] == '3') 
				showmsg("用户已被管理员拒绝！");	
			
			$_SESSION["_username"] = $res['username'];
			$_SESSION["_userid"] = $res['userid'];	

			$loginip = getip();
			
			$last_day = date("d",$res['lastdate']);
			if($last_day != date("d")  &&  time()>$res['lastdate'] && $webinfo['login_point']>0){
				 $member->update('`point`=`point`+'.$webinfo['login_point'], array('userid'=>$res['userid']));  //每日登陆积分
				 M('pay')->insert(array('trade_sn'=>create_tradenum(),'userid'=>$res['userid'],'username'=>$res['username'],'money'=>$webinfo['login_point'],'creat_time'=>time(),'msg'=>'每日登陆_来自第三方登录','payment'=>'自动获取','type'=>'1','ip'=>$loginip,'status'=>'1'));
				 $res['point'] += $webinfo['login_point'];
				 update_group($res);	//检查更新会员组			
			}

			$member->update(array('lastip'=>$loginip,'lastdate'=>time()), array('userid'=>$res['userid']));
			$member->update('`loginnum`=`loginnum`+1', array('userid'=>$res['userid']));
			$forward = isset($_GET['forward']) && trim($_GET['forward']) ? urldecode($_GET['forward']) : 'index.php';
			showmsg("登录成功！", 1, $forward);
		}else{		
		  showmsg("密码错误！",1);
		}
	}else{	
		showmsg("用户名不存在！",1);
	}
}