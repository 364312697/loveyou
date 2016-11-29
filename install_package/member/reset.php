<?php 
define('MEMBER_PATH', dirname(str_replace("\\", '/', dirname(__FILE__))));
require(MEMBER_PATH.'/config/common.inc.php');

session_start(); 

$member = M('member');

//检查是否是否有存在已登录的用户
if(!empty($_SESSION['_userid'])){
	showmsg("已经有一个用户在登录！",3,"index.php");
}

$type = isset($_GET['type']) ? $_GET['type'] : 0;
if($type == 1) {
	$title = '通过电子邮箱找回密码';
}else{
	$title = '通过安全问题找回密码';
}
$_SESSION['step'] = isset($_SESSION['step']) ? $_SESSION['step'] : 1;



if($type == 0){
	include("templets/reset_type.html");
}else if($type == 1){
	if($_SESSION['step']==1 && isset($_POST["dosubmit"])){
		
		if(!empty($_SESSION['code']) && strtolower($_POST["code"]) == $_SESSION['code']){
			
			if(!is_username($_POST['username'])) showmsg("用户名格式错误！");
			
			$result = $member->where(array('username' => $_POST["username"]))->find();
			if(!$result) showmsg("用户名不存在！",1);
				
            //判断用户是否被锁定
			if($result['status'] == '2') showmsg("您已被锁定，请联系管理员！",2);
		    if(empty($result['email'])) showmsg("您没有绑定邮箱，请选择其他方式找回密码！",2);
		   
			$email_code = $_SESSION['email_code'] = create_randomstr();
			$message = '您正通过此邮件找回密码，如非本人操作，请忽略！<br>本次验证码为：'.$email_code;
			$res = sendmail($result['email'], '邮箱找回密码验证', $message);
			if(!$res) showmsg("邮件发送失败，请联系网站管理员！");
			
			$_SESSION['email_arr'] = explode('@',$result['email']);
			$_SESSION['userid'] = $result['userid'];
			$_SESSION['emc_times'] = 5;
			$_SESSION['step'] = 2;

		}else{
		    $_SESSION['code'] = '';
		    showmsg("验证码错误！",1);
		}
		
	}else if($_SESSION['step']==2 && isset($_POST["dosubmit"])){
		
		if($_SESSION['emc_times']=='' || $_SESSION['emc_times']<=0){
			 $_SESSION['step'] = 1;
		     showmsg("验证次数超过5次,请重新获取邮箱验证码！", 3 );
		}
		
		if(!empty($_SESSION['email_code']) && strtolower($_POST["email_code"]) == strtolower($_SESSION['email_code'])){
			 unset($_SESSION['emc_times']);
			 $_SESSION['step'] = 3;
		}else{
			 $_SESSION['emc_times'] = $_SESSION['emc_times']-1;
		     showmsg("邮箱校验码错误！",1);
		}
	}else if($_SESSION['step']==3 && isset($_POST["dosubmit"])){
		
		if(!isset($_POST['password']) || !is_password($_POST['password'])) showmsg("密码格式不正确！");
		
	    $member->update(array('password' => password($_POST['password'])),array('userid'=>$_SESSION['userid']));
		unset($_SESSION['step'], $_SESSION['code'], $_SESSION['email_code'], $_SESSION['email_arr'], $_SESSION['userid']);
		showmsg("更新密码成功！", 3, "login.php");
		
	}	

	include("templets/reset_email.html");   //通过电子邮箱找回密码 
}else{
	
	if($_SESSION['step']==1 && isset($_POST["dosubmit"])){
		
		if(!empty($_SESSION['code']) && strtolower($_POST["code"]) == $_SESSION['code']){
			
			if(!is_username($_POST['username'])) showmsg("用户名格式错误！");
			$result = $member->where(array('username' => $_POST["username"]))->find();
			if(!$result) showmsg("用户名不存在！",1);
				
			//判断用户是否被锁定
			if($result['status'] == '2') showmsg("您已被锁定，请联系管理员！",2);
			
			$result = M('member_detail')->field('userid,problem,answer')->where(array('userid' => $result["userid"]))->find();
			if(empty($result['problem']) || empty($result['answer'])) showmsg("您没有设置安全问题，请选择其他方式找回密码！",2);
		   
			$_SESSION['problem'] = $result['problem'];
			$_SESSION['answer'] = $result['answer'];
			$_SESSION['userid'] = $result['userid'];
			$_SESSION['emc_times'] = 5;
			$_SESSION['step'] = 2;

		}else{
		    $_SESSION['code'] = '';
		    showmsg("验证码错误！",1);
		}
		
	}else if($_SESSION['step']==2 && isset($_POST["dosubmit"])){

		if($_SESSION['emc_times']=='' || $_SESSION['emc_times']<=0){			
			 $member->update(array('status' => 2), array('userid'=>$_SESSION['userid']));  //锁定用户
			 unset($_SESSION['step'], $_SESSION['problem'], $_SESSION['answer'], $_SESSION['emc_times'], $_SESSION['userid']);
		     showmsg("验证次数超过5次，您已被锁定，请联系管理员！" );
		}
		
		if(!empty($_SESSION['answer']) && $_POST["answer"] == $_SESSION['answer']){
			 unset($_SESSION['emc_times']);
			 $_SESSION['step'] = 3;
		}else{
			 $_SESSION['emc_times'] = $_SESSION['emc_times']-1;
		     showmsg("回答错误！",1);
		}
		
	}else if($_SESSION['step']==3 && isset($_POST["dosubmit"])){
		
		if(!isset($_POST['password']) || !is_password($_POST['password'])) showmsg("密码格式不正确！");
		
		$member->update(array('password' => password($_POST['password'])),array('userid'=>$_SESSION['userid']));
		unset($_SESSION['step'], $_SESSION['problem'], $_SESSION['answer'], $_SESSION['userid']);
		showmsg("更新密码成功！", 3, "login.php");
	
	}	
	
	include("templets/reset_problem.html");   //通过安全问题找回密码  
}

?> 