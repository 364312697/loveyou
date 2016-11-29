<?php
/**
 * Ajax文件上传及处理.
 * All rights reserved Powered by YzmCMS
 * @lastmodify			2016-06-30
 */

require('../check.php');

$filepath = 'uploads/thumbnail/';
$sysinfo = get_sysinfo();

if(!isset($_GET['del'])){
	$upfile = new fileupload(array('FilePath' => YZMCMS_PATH.'/'.$filepath));
	if($upfile->uploadFile('thumb')){
		$arr = array(
			'name' => $upfile->getNewFileName(),
			'path' => $sysinfo['wroot'].$filepath,
			'size' => round($upfile->getNewFileSize()/1024,2),
		);
		echo json_encode($arr);	
	}else{ 
		echo $upfile->getErrormsg();
	}	
}else{
	$filename = $_POST['imagename'];
	if(!empty($filename) && in_array(fileext($filename),array('jpg','jpeg','png','gif'))){
		@unlink(YZMCMS_PATH.'/'.$filepath.$filename);
		echo '1';
	}else{
		echo '删除失败.';
	}
}