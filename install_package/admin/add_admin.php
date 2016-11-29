<?php
require('check.php');

$admin = M('admin');
$admintype = M('admintype');
$type = $admintype->select();
if(isset($_POST['dosubmit'])){	
	if(!is_username($_POST["uname"])) showmsg("用户名格式错误！");
	if(!is_password($_POST["pwd"])) showmsg("密码格式错误！");
	if($_POST["email"]!=''){
		if(!is_email($_POST["email"])) showmsg("邮箱格式错误！");
	}
	if($_POST['uname']!='' && $_POST['pwd']==$_POST['repwd']){
		$res = $admin->where(array('uname'=>$_POST["uname"]))->find();
		if($res){	
            showmsg("该用户已存在！");		
		}else{
			$_POST['pwd'] = password($_POST['pwd']);	
			$r = $admintype->field('typename')->where(array('rank' => $_POST['usertype']))->find();
			$_POST['remark'] = $r['typename'];	
			$_POST['addtime'] = time();	
			$_POST['addpeople'] = $_SESSION['adminname'];	
			$admin->insert($_POST);
			showmsg("成功添加一个用户！", 1, 'manage_admin.php');		 
		}

	}else{
	   showmsg("您的输入有误，请检查！");
	}
}

include('templets/add_admin.htm');