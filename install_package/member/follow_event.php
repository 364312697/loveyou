<?php 
require('include.inc.php');
$member_event = M('member_event');

$total = $member_event->field('yzmcms_member_event.*')->join('yzmcms_member_follow ON 
yzmcms_member_follow.followid=yzmcms_member_event.userid','LEFT')->where("yzmcms_member_follow.userid=$userid AND eventstatus=1")->total(); 

$page = new spage($total,15);
$start = $page->start_rows();
$data = $member_event->limit("$start,15")->order('id DESC')->select(); 


//分配样式及加载模板
$filename = get_file_name();
$title = '会员中心-TA的动态';
$cssarr = array('index');
include("templets/$filename.html");
?> 