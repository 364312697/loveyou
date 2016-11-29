<?php
require('check.php');

if(isset($_POST['dosubmit'])){
	$sysinfo = get_sysinfo();
	$link = M('link');
	$logo = $_FILES['logo']['name'];
	
	if($_POST['weburl']=='' || $_POST['webname']=='') showmsg('网站名称或网站地址不能为空！');
	
	$res = $link->where(array('weburl' => $_POST['weburl']))->find();
	if($res) showmsg("该网站地址已存在！");
	
	if($logo){					  
		$uppic = new fileupload(array('FilePath'=>YZMCMS_PATH.'/uploads/link/'));				  
		if($uppic->uploadFile('logo')){
			$_POST['logo'] = $sysinfo['wroot'].'uploads/link/'.$uppic->getNewFileName();
		}else{
			showmsg($uppic->getErrormsg());
		}						
	}					
	$_POST["inputtime"]=time();										
	$link->insert($_POST,'1');
	showmsg("添加友情链接成功",'1','manage_link.php');
}
include('templets/add_link.htm');