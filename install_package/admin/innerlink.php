<?php
require('check.php');

$innerlink = M('innerlink');
$re = array('val'=>'', 'link'=>'');

if(isset($_POST["addsub"])){	
	if($_POST['link'] && $_POST['val']){
		$_POST['lasttime']=time();
		$innerlink->insert($_POST);
		showmsg("添加成功！",1,"manage_innerlink.php");			
	}
}
		
if(isset($_GET['editid'])){	
	$id = intval($_GET['editid']);
	$re = $innerlink->where(array('id'=>$id))->find();
}

if(isset($_POST["editsub"])){
	if($_POST['link'] && $_POST['val']){
		$innerlink->update($_POST,array('id'=>$id));
		showmsg("修改成功！",1,"manage_innerlink.php");			
	}		
}

include('templets/innerlink.htm');