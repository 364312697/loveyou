<?php 
require('include.inc.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0; //系统[群发]消息
$messageid = isset($_GET['messageid']) ? intval($_GET['messageid']) : 0;

if($id){
	
    $message_group = M('message_group');
	$data = $message_group->where(array('id' => $id, 'groupid' => $groupid, 'status' => '1'))->find(); 
	if(!$data) showmsg('你查看的信息不存在！');
	$message_data = M('message_data');
	$result = $message_data->where(array('group_message_id' => $id, 'userid' => $userid))->find();
	if(!$result){
		$message_data->insert(array('userid' => $userid, 'group_message_id' => $id));   //更新为已读状态,插入到已读表
	}
    $filename = 'read_system_msg'; 
	
}elseif($messageid){
	
	$message = M('message');
	$data = $message->where(array('messageid' => $messageid, 'send_to' => $username, 'status' => '1'))->find(); 
	if(!$data) showmsg('你查看的信息不存在！');
	$message->update(array('isread' => '1'),array('send_to' => $username, 'messageid' => $messageid));  //更新为已读状态
	
	$filename = 'read_message'; 
	
}else{
	showmsg('你的操作有误！');
}


//分配样式及加载模板
$cssarr = array('index');
$jsarr = array('jquery-1.8.2.min');
include("templets/$filename.html");
?> 