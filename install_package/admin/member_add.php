<?php
require('check.php');
$res =  M('member_group')->field('groupid,name')->select();
$webinfo = get_sysinfo();

if(isset($_POST['dosubmit'])){
    $member = M('member');
    if(!is_username($_POST['username'])) showmsg("用户名不符合规范");
	if(!is_password($_POST['password'])) showmsg("密码不符合规范");
	
	$result = $member->where(array('username'=>$_POST['username']))->find();
	if(!$result){		
		$loginip = getip();
		$_POST["password"] = password($_POST['password']);
		$_POST['regdate'] = time();
		$_POST['regip'] = getip();
		$_POST['loginnum'] = '0';
		$_POST['amount'] = '0.00';
		$_POST['status'] = '1';
		$_POST['vip'] = '0';			
		$_POST['userid'] = $member->insert($_POST);		
		if($_POST['userid']){
			M('member_detail')->insert($_POST,1); //插入附表		
			showmsg("操作成功！",1,'member_list.php');
		}else{
			showmsg("操作失败！");
		}
		
	}else{	
	     showmsg("用户名已存在！");
	}	
}
include('templets/member_add.htm');