<?php 
require('check.php');

$admin = M('admin');

if(isset($_GET['id'])){ 
	$id = intval($_GET['id']);
	$adminid = $_SESSION['adminid'];
	if($id==$adminid || $id==1){
	     showmsg("不能删除ID为1的管理员，或不能删除自己！", '3', 'manage_admin.php');
	}else{
		 $admin ->delete(array('id' => $id));
		 showmsg("删除成功！", '1', 'manage_admin.php');
	} 			
}else{
	$usertype = isset($_GET['usertype']) ? intval($_GET['usertype']) : 0;
	if(!$usertype){
		$data = $admin->order('id ASC')->select();
	}else{
		$data = $admin->where(array('usertype' => $usertype))->order('id ASC')->select();	
	}
	
}

include('templets/manage_admin.htm');