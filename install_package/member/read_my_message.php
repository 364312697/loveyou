<?php 
require('include.inc.php');

$messageid = isset($_GET['messageid']) ? intval($_GET['messageid']) : 0;

if($messageid){
	
	$message = M('message');
	$data = $message->where(array('messageid' => $messageid, 'send_from' => $username))->find(); 
	if(!$data) showmsg('你查看的信息不存在！');

}else{
	showmsg('你的操作有误！');
}


//分配样式及加载模板
$filename = 'read_message';
$cssarr = array('index');
$jsarr = array('jquery-1.8.2.min');
include("templets/$filename.html");
?> 