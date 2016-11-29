<?php 
require('include.inc.php');
$message_group = M('message_group');
$message_data = M('message_data');






$total = $message_group->where(array('groupid' => $groupid, 'status' => '1'))->total(); 

$page = new spage($total,20);
$start = $page->start_rows();
$data = $message_group->limit("$start,20")->order('id DESC')->select(); 

$read = array();
foreach($data as $val){
	$d = $message_data->where(array('userid'=>$userid, 'group_message_id'=>$val['id']))->find();
	if(!$d){
		$read[$val['id']] = 0;//未读 红色
	}else {
		$read[$val['id']] = 1;
	}
}


//分配样式及加载模板
$filename = get_file_name();
$title = '会员中心-系统消息';
$cssarr = array('index');
$jsarr = array('jquery-1.8.2.min');
include("templets/$filename.html");
?> 