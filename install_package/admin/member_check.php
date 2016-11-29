<?php
require('check.php');
$member = M('member');

if(isset($_GET["status"])){
	$status = intval($_GET["status"]);
	$total = $member->where(array('status'=>$status))->order("userid DESC")->total();
}else{
	$total = $member->where(array('status'=>'0'),array('status'=>'2'))->order("userid DESC")->total();
}

$page = new spage($total,10);
$start = $page->start_rows();
$res = $member->limit("$start,10")->select();	

//批量删除
if(isset($_POST["pldel_sub"])){ 

    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	
	$where = to_sqls($_POST['fx'], '', 'a.userid');
	$member->query("DELETE a,b from yzmcms_member AS a LEFT JOIN yzmcms_member_detail AS b ON a.userid=b.userid WHERE $where"); 
	showmsg("操作成功！",1);

}

//批量通过
if(isset($_POST["tongguo"])){ 

    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	$where = to_sqls($_POST["fx"], '', 'userid');
	$member->query("UPDATE yzmcms_member SET status = '1' WHERE $where"); 
	showmsg("操作成功！",1);
}

//批量拒绝
if(isset($_POST["jujue"])){

    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");	
	$where = to_sqls($_POST["fx"], '', 'userid');
	$member->query("UPDATE yzmcms_member SET status = '3' WHERE $where");
	showmsg("操作成功！",1);
}

include('templets/member_check.htm');