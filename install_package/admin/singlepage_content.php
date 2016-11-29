<?php
require('check.php');

$singlepage = M('singlepage');
$catid = isset($_GET["catid"]) ? intval($_GET["catid"]) : 0;
$data = $singlepage->where(array('catid'=>$catid))->find();
		
if(isset($_POST['dosubmit'])){
	$_POST['addtime']=time();					
	$singlepage->update($_POST, array('catid' => $catid));
	showmsg("操作成功！",'1','manage_column.php');
}
include('templets/singlepage_content.htm');