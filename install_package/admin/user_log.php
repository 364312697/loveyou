<?php
require('check.php');

$adminlog = M('adminlog');

//如果不是超级管理员只能看自己的登录记录
if($usertype != 1){
	$adminname = $_SESSION['adminname'];
	$where = "`user` = '$adminname'";
}else{
	$where = '1=1';
}

$total = $adminlog->where($where)->total();

$page = new spage($total,10);
$start = $page->start_rows();
$res = $adminlog->limit("$start,10")->order('id DESC')->select();

if(isset($_POST['sub'])){
	
    if($usertype != 1) showmsg("非超级管理员！"); //如果不是超级管理员不能删除登录记录
		
	$num = $adminlog->delete(array('login_time<' => strtotime('-1 month')));	
	if($num){	  		
	  showmsg("操作成功，共删除记录{$num}条！");		 
	}else{	
	  showmsg("没有数据被删除！");				 
	}
}
include('templets/user_log.htm');
