<?php 
require('include.inc.php');


if(isset($_POST['dosubmit'])){
	if(strtolower($_POST["code"]) != $_SESSION['code']) showmsg("验证码错误！",1);
	if(!$member->where(array('username'=>$username, 'password'=>password($_POST['password'])))->find()) showmsg("密码错误！");

	if(!$email_status){
		if(!is_email($_POST['email'])) showmsg("邮箱格式不正确！");
		$member->update((array('email'=>$_POST['email'])),array('userid'=>$userid));
	}else{
		unset($_POST['email']);
	}
	
	$problem = $_POST['problem'];
	$answer = strip_tags(trim($_POST['answer']));

	if($problem !== 0 && $answer !== ''){ 
		$member_detail->update((array('problem'=>$problem,'answer'=>$answer)),array('userid'=>$userid)); //安全问题
	}else{
		showmsg("安全问题未修改！");
	}  
	
	showmsg("操作成功！",1);
}else{
	$problemarr = array('你最喜欢的格言什么？','你家乡的名称是什么？','你读的小学叫什么？','你的父亲叫什么名字？','你的母亲叫什么名字？','你的配偶叫什么名字？','你最喜欢的歌曲是什么？');

	$problemstr = '<select name="problem"><option value="0">没有安全问题</option>';
				
		foreach($problemarr as $val){
			$str = $problem == $val ? 'selected="selected"' : '';
			$problemstr .= '<option value="'.$val.'" '.$str.'>'.$val.'</option>';
		}
				
	$problemstr .= '</select>';	
}



//分配样式及加载模板
$filename = get_file_name();
$title = '会员中心-修改邮箱/安全问题';
$cssarr = array('index');
$jsarr = array('jquery-1.8.2.min');
include("templets/$filename.html");
?> 