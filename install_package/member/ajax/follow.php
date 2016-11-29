<?php 
/**
 * 会员加关注/取消关注
 * @param string $userid	用户ID
 * @return $status {-3:不能关注自己 ;-2:用户ID不合法 ;-1:用户名不存在 ;0:用户未登录 ;1:关注成功 ;2:取消关注成功}
 */

session_start(); 
define('MEMBER_AJAX_PATH', dirname(dirname(str_replace("\\", '/', dirname(__FILE__)))));
require(MEMBER_AJAX_PATH.'/config/common.inc.php');

if(empty($_SESSION['_userid'])) exit('0');

$userid = isset($_POST['userid']) ? intval($_POST['userid']) : exit('-2');

if($userid == $_SESSION['_userid']) exit('-3');

$memberinfo = M('member_detail')->field('yzmcms_member.username,yzmcms_member_detail.userpic')->join('yzmcms_member ON yzmcms_member.userid=yzmcms_member_detail.userid')->where("yzmcms_member.userid = $userid")->find();
if(!$memberinfo)  exit('-1'); 

$member_follow = M('member_follow');
$r = $member_follow->where(array('userid'=>$_SESSION['_userid'], 'followid'=>$userid))->find();
if($r){
	$member_follow->delete(array('userid'=>$_SESSION['_userid'], 'followid'=>$userid));
	exit('2');
}else{
	$member_follow->insert(array('userid'=>$_SESSION['_userid'],'followid'=>$userid,'followname'=>$memberinfo['username'],'followpic'=>$memberinfo['userpic'],'inputtime'=>time()));
	exit('1'); 
}
