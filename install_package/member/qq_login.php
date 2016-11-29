<?php 
define('MEMBER_PATH', dirname(str_replace("\\", '/', dirname(__FILE__))));
require(MEMBER_PATH.'/config/common.inc.php');

//开启SESSION
session_start(); 

$webinfo = get_sysinfo();
$appid = $webinfo['qq_app_id'];
$appkey = $webinfo['qq_app_key'];
$callback = $webinfo['wroot'].'member/qq_login.php'; //回调地址 默认此文件，不能更改

if($appid=='' || $appkey=='') showmsg("QQ配置项为空，请联系管理员！");

$qq_info = new qqapi($appid,$appkey,$callback);

if(!isset($_GET['code'])){
	$qq_info->redirect_to_login();
}else{
	$code = $_GET['code'];
    $_SESSION['fromlogin'] = 'qq';
    $openid = $_SESSION['openid'] = $qq_info->get_openid($code);
	if(!empty($openid)){
        $member = M('member');
        $res = $member->where(array('connectid' => $openid, 'fromlogin' => 'qq'))->find();		
		if(!empty($res)){
			//QQ已存在于数据库，则直接转向登陆操作
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
			if($last_day != date("d")  &&  time()>$res['lastdate']){
				 $member->update('`point`=`point`+2', array('userid'=>$res['userid']));  //每日登陆积分 加 2
				 M('pay')->insert(array('trade_sn'=>create_tradenum(),'userid'=>$res['userid'],'username'=>$res['username'],'money'=>'2','creat_time'=>time(),'msg'=>'每日登陆','payment'=>'自动获取','type'=>'1','ip'=>$loginip,'status'=>'1'));
				 $res['point'] += 2;
				 update_group($res);	//检查更新会员组			
			}

			$member->update(array('lastip'=>$loginip,'lastdate'=>time()), array('userid'=>$res['userid']));
			$member->update('`loginnum`=`loginnum`+1', array('userid'=>$res['userid']));
			$forward = isset($_GET['forward']) && trim($_GET['forward']) ? urldecode($_GET['forward']) : 'index.php';
			showmsg("登录成功！", 1, $forward);				
		}else{	
			$userinfo = $qq_info->get_user_info();
			$_SESSION['userinfo'] = $userinfo;
			$_SESSION['connectid'] = $openid;
			$_SESSION['fromlogin'] = 'qq';
			include("templets/bind.html");
		}
	}else if(isset($_SESSION['userinfo'])){  //防止刷新
		$userinfo = $_SESSION['userinfo'];
		include("templets/bind.html");
	}
}