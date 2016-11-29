<?php
/**
 * 
 * 作者：袁志蒙
 * 作用：会员中心公共部分
 * 版权：YzmCMS版权所有
 * 网址：http://www.yzmcms.com
 * 最后修改时间：2015-12-18
 * 
 */ 
 
session_start(); 

define('MEMBER_PATH', dirname(str_replace("\\", '/', dirname(__FILE__))));
require(MEMBER_PATH.'/config/common.inc.php');

if(empty($_SESSION['_userid'])){
	$url = urlencode(get_url());
	showmsg('请先登录！',1,'login.php?forward='.$url);
}

$member = M('member');
$member_detail = M('member_detail');
$member_group = M('member_group');


$userid = $_SESSION["_userid"];

$data = $member->where(array('userid'=>$userid))->find();
$data1 = $member_detail->where(array('userid'=>$userid))->find();

if(!$data) $data = array();
if(!$data1) $data1 = array();

$memberinfo = array_merge($data,$data1);
extract($memberinfo);

$member_group_info = $member_group->where(array('groupid'=>$groupid))->find();
$icon = $member_group_info['icon'];  //会员组图标
$groupname = $member_group_info['name'];  //会员等级

//系统消息[群发]
$system_totnum = M('message_group')->where(array('groupid' => $memberinfo['groupid']))->total(); //总条数

$data = $member->fetch_array($member->query("SELECT COUNT(*) AS total FROM yzmcms_message_group a LEFT JOIN yzmcms_message_data b ON a.id=b.group_message_id WHERE a.groupid='$memberinfo[groupid]' AND a.`status`=1 AND b.userid=$memberinfo[userid]"));  //已读信息

$system_unread = $system_totnum - $data['total']; //系统消息，未读条数

$inbox_unread = M('message')->where(array('send_to' => $memberinfo['username'], 'isread' => '0', 'status' => '1'))->total(); //收件箱消息，未读条数