<?php
require('check.php');
$member = M('member');
$member_detail = M('member_detail');
$member_group = M('member_group');
$res =  $member_group->field('groupid,name')->select();

$userid = isset($_GET['id']) ? intval($_GET['id']) : 0;
$data1= $member->where(array('userid'=>$userid))->find();
$data2= $member_detail->where(array('userid'=>$userid))->find();
$data3 = $member_group->where(array('groupid'=>$data1['groupid']))->find();
$data1['groupname'] = $data3['name'];

$data = array_merge($data1, $data2);
if($data['area'] == '') $data['area'] = '||';
list($cmbProvince,$cmbCity,$cmbArea) = explode('|',$data['area']); //分配地区



if(isset($_POST['dosubmit'])){
	if($_POST['password'] == ''){
		unset($_POST['password']);
	}else{
		$_POST['password'] = password($_POST['password']);
	}
	
	if(isset($_POST['del_userpic']) && $_POST['del_userpic'] == '1'){		
		if($data['userpic'] != ''){
			$webinfo = get_sysinfo();
			$picpath = str_replace($webinfo['wroot'], '../', $data['userpic']);
			@unlink($picpath);  //删除头像
		}
		$_POST['userpic'] = '';
	}	
	
	$member->update($_POST,array('userid'=>$userid),'1');
	$member_detail->update($_POST,array('userid'=>$userid),'1');
	showmsg("操作成功！",1,'member_list.php');
}

include('templets/member_edit.htm');