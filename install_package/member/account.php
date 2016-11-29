<?php 
require('include.inc.php');

if($area){
	list($cmbProvince,$cmbCity,$cmbArea) = explode('|',$area); //分配地区
}else{
	$cmbProvince = $cmbCity = $cmbArea ='';
}


if(isset($_POST['dosubmit'])){
	if(!is_mobile($_POST['mobile'])) showmsg("手机号不正确！",3);
	unset($_POST['userid'],$_POST['userpic']);
	$res = $member_detail->update($_POST,array('userid'=>$userid),'1');
	if($res){
		showmsg("更新资料成功！",1);
	}else{
		showmsg("数据未修改！");
	}
}

//分配样式及加载模板


$filename = get_file_name();
$title = '会员中心-修改资料';
$cssarr = array('index');
$jsarr = array('jquery-1.8.2.min','address');
include("templets/$filename.html");
?> 