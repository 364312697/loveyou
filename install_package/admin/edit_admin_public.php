<?php
require('check.php');

$admin = M('admin');

$data = $_SESSION['userinfo'];

if(isset($_POST['dosubmit'])){
	$arr = array();
	if($_POST['pwd'] != ''){
		$r = $admin->where(array('uname' =>$data['uname'], 'pwd' => password($_POST['oldpwd'])))->find();
		if(!$r) showmsg("旧密码错误！");
		$arr['pwd'] = password($_POST['pwd']);
	}
	
	if($_POST["email"]!=''){
		if(!is_email($_POST["email"])) showmsg("邮箱格式错误！");		
	}
	$arr['email'] = $_POST["email"];
	$arr['nickname'] = $_POST["nickname"];
			
    $r = $admin->update($arr, array('id' => $data['id']));
	if($r){
		$_SESSION['userinfo']['email'] = $arr['email'];
		$_SESSION['userinfo']['nickname'] = $arr['nickname'];
		showmsg("操作成功！", 1, 'edit_admin_public.php');
	}else{
		showmsg("数据未修改！");
	}
}


include('templets/edit_admin_public.htm');