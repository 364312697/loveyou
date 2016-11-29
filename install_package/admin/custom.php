<?php
require('check.php');

$custom = M('custom');
//初始化 $re 数组
$re = array('name'=>'','val'=>'','description'=>'');
if(isset($_POST["addsub"])){
	$_POST['inputtime']=time();
	if($_POST['name'] && $_POST['val']){
		$_POST['val'] = str_replace('<?', '&lt;?', $_POST['val']);
	    $_POST['val'] = str_replace('?>', '?&gt;', $_POST['val']);
		$custom->insert($_POST);
		delcache('custom');
		showmsg("添加新变量成功！",1,"manage_custom.php");			
	}
}		
if(isset($_GET["editid"])){
	$id = intval($_GET["editid"]);
	$re = $custom->where(array('id'=>$id))->find();
}

if(isset($_POST["editsub"])){
	if($_POST['name'] && $_POST['val']){
		$_POST['val'] = str_replace('<?', '&lt;?', $_POST['val']);
	    $_POST['val'] = str_replace('?>', '?&gt;', $_POST['val']);
		
		$custom->update($_POST,array('id'=>$id));
		delcache('custom');
		showmsg("修改变量成功！",1,"manage_custom.php");			
	}		
}
include('templets/custom.htm');