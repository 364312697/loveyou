<?php
require('check.php');

$db = new dbmanage();
if(isset($_GET['filename']) && fileext($_GET['filename']) == 'sql'){
	 if(@unlink($_GET['filename'])) 
		 showmsg('操作成功！',1);
	 else
		 showmsg('操作成功！');
}

if(isset($_GET['down']) && fileext($_GET['down']) == 'sql'){
	 file_down($_GET['down']);
}

include('templets/data_recover.htm');
