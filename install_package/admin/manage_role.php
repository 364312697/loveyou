<?php 
require('check.php');

$admintype = M('admintype');

if(isset($_GET['id'])){ 
	$id = intval($_GET['id']);

	if(in_array($id, array(1, 2, 3))){
	     showmsg("不能删除系统角色！", '3', 'manage_role.php');
	}else{
		 $total = M('admin')->where(array('usertype' => $id))->total();
		 if($total > 0){
			 showmsg("请先删除该角色下的管理员！", '3', 'manage_role.php');
		 }else{
			 $admintype ->delete(array('rank' => $id));
		     showmsg("删除成功！", '1', 'manage_role.php');
		 }		 
	} 			
}else{
	$data = $admintype->order('rank ASC')->select();	
}
	

include('templets/manage_role.htm');