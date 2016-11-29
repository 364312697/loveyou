<?php
session_start(); 

require('../config/common.inc.php');

if(isset($_POST['dosubmit'])){
    
	if(trim($_POST['content']) == '') showmsg("你不打算说点什么吗？", 1);
	
	$webinfo = get_sysinfo();
		
	$userid = $_POST['userid'] = isset($_SESSION["_userid"]) ? $_SESSION["_userid"] : 0;
	$username = $_POST['username'] = isset($_SESSION["_username"]) ? $_SESSION["_username"] : $webinfo['wname'].'网友';
	
	$ip = getip();
	
	if($userid > 0){
		$member = M('member');
		$res = $member->field('yzmcms_member.userid, yzmcms_member.point, yzmcms_member.groupid, b.authority')->join('`yzmcms_member_group` b ON yzmcms_member.groupid=b.groupid', 'LEFT')->where(array('userid' => $userid))->find();
		if(strpos($res['authority'], '2') === false) showmsg("你没有权限发布评论，请升级会员组！");
		
		$pay = M('pay');
		$total = $pay->where(array('userid' => $userid, 'type' => '1', 'creat_time>' => strtotime(date('Y-m-d'))))->total(); //今日获取积分的次数
		//发布评论奖励积分 [奖励条件为每日获取积分次数不超过5次]
		if($webinfo['comment_point'] > 0 && $total < 5){
			$member->update('`point`=`point`+'.$webinfo['comment_point'], array('userid' => $userid));  
			$pay->insert(array('trade_sn'=>create_tradenum(),'userid'=>$userid,'username'=>$username,'money'=>$webinfo['comment_point'],'creat_time'=>time(),'msg'=>'发布评论','payment'=>'自动获取','type'=>'1','ip'=>$ip,'status'=>'1'));
			update_group($res);	//检查更新会员组
		}
		 	
	}else{
		if(!$webinfo['comment_tourist']){
			showmsg("请先登录！", 1, $webinfo['wroot'].'member/login.php?forward='.urlencode($_SERVER["HTTP_REFERER"]));
		}
	}
	
	
	$_POST = new_html_special_chars($_POST);
	
	$_POST['content'] = preg_replace('[\[em_([0-9]*)\]]', '<img src="'.$webinfo['wroot'].'common/images/face/$1.gif"/>', $_POST['content']);
	$_POST['userpic'] = $userid ? get_memberavatar($userid) : '';
	$_POST['inputtime'] = time();
	$_POST['ip'] = $ip;	
	$_POST['reply'] = isset($_POST['reply']) ? intval($_POST['reply']) : 0;
	$_POST['status'] = intval(!$webinfo['comment_check']); 
	$_POST['total'] = 1;
	
	//如果是回复
	if($_POST['reply'] > 0){
		$uname = $_POST['username'];
		if(isset($_SESSION['is_syslogin'])){
			$uname = '<strong style="color:#DE4C1C">管理员</strong>';
			$_POST['username'] = '管理员';
		} 
		$_POST['content'] = '<a href="javascript:void(0);" class="user_name" rel="nofollow">'.$uname.'</a> 回复 <a href="javascript:void(0);" class="user_name" rel="nofollow">'.$_POST['reply_to'].'</a> ：'.$_POST['content'];
	}
	
	M('comment_data')->insert($_POST); //评论表
	
	$articleid = $_POST['articleid'] = intval($_POST['articleid']);
	$comment = M('comment');
	if($comment->where(array('articleid' => $articleid))->find()){
		$comment->update('`total`=`total`+1', array('articleid' => $articleid));
	}else{
		$comment->insert($_POST);
	}
	
	if(!$webinfo['comment_check']){
		if($webinfo['article_html']) make_html($articleid);
		showmsg("评论成功！", 1);	
	}else{
		showmsg("评论成功,等待管理员审核！", 3);	
	}

}