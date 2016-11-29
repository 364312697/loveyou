<?php 
require('include.inc.php');


$oldpic = $userpic;
$userpic = $oldpic!='' ? $oldpic : 'templets/images/default.gif';
if(isset($_POST['dosubmit'])){
	$user_pic = $_FILES['user_pic']['name'];
	if($user_pic == '') showmsg("请上传头像！");
	
	$uppic = new fileupload(array('FilePath'=>YZMCMS_PATH.'/uploads/member/'.date('Ym/d/')));				  
	if($uppic->uploadFile('user_pic')){
		$sysinfo = get_sysinfo();
		$weburl = $sysinfo['wroot'];
		$picname = $weburl.'uploads/member/'.date('Ym/d/').$uppic->getNewFileName();
		if($member_detail->update(array('userpic'=>$picname),array('userid'=>$userid))){
			$oldpic = YZMCMS_PATH.'/'.str_replace($weburl,'',$oldpic);
			if($oldpic!=YZMCMS_PATH.'/' && file_exists($oldpic)) @unlink($oldpic); //删除原来的头像
			showmsg("更新资料成功！",1);
		}		
	}else{
		showmsg($uppic->getErrormsg());
	}
}



//分配样式及加载模板
$filename = get_file_name();
$title = '会员中心-修改头像';
$cssarr = array('index');
$jsarr = array('jquery-1.8.2.min');
include("templets/$filename.html");
?> 