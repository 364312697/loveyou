<?php
require('check.php');

$member_group = M('member_group');
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$data = $member_group->where(array('groupid'=>$id))->find();
		
if(isset($_POST['dosubmit'])){
	$_POST['point'] = isset($_POST['point']) ? intval($_POST['point']) : 0;
    if($_POST['name'] =='') showmsg("您的操作有误！");
	
	if(isset($_POST['authority'])){
		$_POST['authority'] = join(',',$_POST['authority']);
	}else{
		$_POST['authority'] = '';
	}
	
	$member_group->update($_POST,array('groupid' => $id),1);
    showmsg("操作成功！",'1','member_group.php');	
}
include('templets/member_group_edit.htm');