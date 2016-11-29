<?php
require('check.php');

$admin = M('admin');
$admintype = M('admintype');
$type = $admintype->select();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$data = $admin->where(array('id' => $id))->find();

if(isset($_POST['dosubmit'])){
	if($_POST['pwd'] != ''){
		$_POST['pwd'] = password($_POST['pwd']);
	}else{
		unset($_POST['pwd']);
	}
	
	if($_POST["email"]!=''){
		if(!is_email($_POST["email"])) showmsg("邮箱格式错误！");
	}
	
	$r = $admintype->field('typename')->where(array('rank' => $_POST['usertype']))->find();
    $_POST['remark'] = $r['typename'];
			
    $r = $admin->update($_POST, array('id' => $id));
	if($r){
		showmsg("成功修改一个用户！", 1, 'manage_admin.php');
	}else{
		showmsg("数据未修改！", 3, 'manage_admin.php');
	}
}


include('templets/edit_admin.htm');