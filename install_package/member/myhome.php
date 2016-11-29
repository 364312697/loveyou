<?php 
session_start(); 
define('MEMBER_PATH', dirname(str_replace("\\", '/', dirname(__FILE__))));
require(MEMBER_PATH.'/config/common.inc.php');

$userid = isset($_GET['userid']) ? intval($_GET['userid']) : 0;
$member = M('member');
$memberinfo = get_memberinfo($userid);

if(!$memberinfo) showmsg('会员不存在或已被删除！');

M('member_detail')->update('`guest`=`guest`+1', array('userid'=>$userid));

$sysinfo = get_sysinfo();
$member_group_info = M('member_group')->where(array('groupid'=>$memberinfo['groupid']))->find();
$memberinfo['icon'] = $member_group_info['icon'];
$memberinfo['groupname'] = $member_group_info['name'];

$member_guest = M('member_guest');
$r = $member_guest->field('guest_id')->where(array('space_id'=>$userid))->order('id DESC')->find();

//如果访客已登陆，并且访问的不是自己的主页，并且访客表的最后一个访客不是自己，则插入数据
if(isset($_SESSION['_userid']) && $_SESSION['_userid']!=$userid && $r['guest_id']!=$_SESSION['_userid']){
	$data['space_id'] = $userid;
	$data['guest_id'] = $_SESSION['_userid'];
	$data['guest_name'] = $_SESSION['_username'];
	$data['guest_pic'] = get_memberavatar($_SESSION['_userid']);
	$data['inputtime'] = time();
	$data['ip'] = getip();
	$member_guest->insert($data);
}

$guest_data = $member_guest->field('*')->where(array('space_id'=>$userid))->order('id DESC')->limit('9')->select();

//文章列表
$article = M('article');
$_GET['cpage'] = isset($_GET['cpage']) ? intval($_GET['cpage']) : 1;
$total = $article->where(array('display' => 1, 'username' => $memberinfo['username'], 'system' => '0'))->total(); 
$page = new spage($total,10);
$start = $page->start_rows();
$data = $article->field('title, url, inputtime')->limit("$start,10")->order('id DESC')->select();

include("templets/myhome.html");
?> 