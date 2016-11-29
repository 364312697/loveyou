<?php
require('check.php');

$article = M('article');
$column = M('column');
		
/* 批量删除 */
if(isset($_POST["submit1"])){ 

    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");

	foreach($_POST['fx'] as $val){
		$article->delete(array('id' => $val));
	}
	
	showmsg('操作成功！',1);

}

/* 审核通过 */
if(isset($_POST["submit2"])){ 
   
    $sysinfo = get_sysinfo();

    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	
	foreach($_POST['fx'] as $val){
		$article->update(array('display' => '1'), array('id' => $val));
		M('member_event')->update(array('eventstatus' => 1), array('articleid' => $val)); //更新会员动态
		if($sysinfo['article_html']) make_html($val);
	}
	
	showmsg('操作成功！',1);

}

/* 退稿 */
if(isset($_POST["submit3"])){
    
    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");	

    $_POST['send_from'] = '系统管理员';
	$_POST['issystem'] = '1';
	$_POST['message_time'] = time();
	$_POST['subject'] = '您的稿件被退回，请修改后重新提交';

	if($_POST['content_c']=='请输入退稿理由，退稿理由将会以短消息方式发送！'){
		$_POST['content_c'] = '';
	}

    $message = M('message');
	
	foreach($_POST['fx'] as $val){
		$article->update(array('display' => '2'), array('id' => $val));  //更新退稿状态
		$r = $article->field('title,username')->where(array('id' => $val))->find();
		if(!$r) showmsg("文章不存在！");
		$_POST['send_to'] = $r['username'];  //收件人
		$_POST['content'] = '您发送的投稿不满足我们的要求，请重新编辑投稿！<br>标题：'.$r['title'].'<br><a href="edit_through.php?id='.$val.'" style="color:red">点击这里修改</a><br>'.$_POST['content_c'];
		$message->insert($_POST);		  //发送短信息		
	}	

	showmsg('操作成功！',1);
}


$total = $article->where(array('system' =>0 ))->total();	

$page = new spage($total,10);
$start = $page->start_rows();
$res = $article->field('id,title,click,thumbnail,status,system,display,username,catid,inputtime')->order('display ASC')->limit("$start,10")->select();
include('templets/manage_publish.htm');
