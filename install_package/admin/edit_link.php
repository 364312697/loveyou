<?php
require('check.php');

$link = M('link');
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$data = $link->where(array('id'=>$id))->find();
		
if(isset($_POST['dosubmit'])){		
	$sysinfo = get_sysinfo();
	$_POST['logo'] = $data['logo'];
	$logo = $_FILES['logo']['name'];
	
	if($_POST['weburl']=='' || $_POST['webname']=='') showmsg('网站名称或网站地址不能为空！');
	
	if($logo){					  
		$uppic = new fileupload(array('FilePath'=>YZMCMS_PATH.'/uploads/link/'));	
		if($uppic->uploadFile('logo')){
			$_POST['logo'] = $sysinfo['wroot'].'uploads/link/'.$uppic->getNewFileName();
		}else{
			showmsg($uppic->getErrormsg(),5);
		}					  
	}
	$link->update($_POST,array('id'=>$id),1);
	showmsg('修改成功！',1,'manage_link.php');	
}	
include('templets/edit_link.htm');