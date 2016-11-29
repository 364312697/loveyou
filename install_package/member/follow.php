<?php 
require('include.inc.php');
$member_follow = M('member_follow');

if(isset($_GET['followid'])){
	$followid = intval($_GET['followid']);
	if($member_follow->delete(array('userid'=>$userid, 'followid'=>$followid))){
		showmsg('操作成功！',1);
	}else{
		showmsg('操作失败！');
	}
}

$total = $member_follow->where(array('userid' => $userid))->total(); 

$page = new spage($total,12);
$start = $page->start_rows();
$data = $member_follow->limit("$start,12")->order('id DESC')->select(); 




//分配样式及加载模板
$filename = get_file_name();
$title = '会员中心-我的关注';
$cssarr = array('index');
include("templets/$filename.html");
?> 