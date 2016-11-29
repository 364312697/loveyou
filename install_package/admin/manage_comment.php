<?php
require('check.php');

$comment = M('comment');
$comment_data = M('comment_data');

//删除评论
if(isset($_GET["id"])){ 

	$id = intval($_GET['id']);
	$comment_data_info = $comment_data ->field('articleid')->where(array('id'=>$id))->find();	
	//判断评论是否存在，防止文章评论total出现负数情况 
	if(!$comment_data_info) showmsg('该评论不存在，请返回检查！');
	$comment->update('`total`=`total`-1', array('articleid'=>$comment_data_info['articleid']));
	$comment_data ->delete(array('id'=>$id));
	showmsg('操作成功！',1);
	
}else if(isset($_POST["dosubmit"])){ 

    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");

	foreach($_POST['fx'] as $key => $val){
		$comment->update('`total`=`total`-1', array('articleid'=>$val));		
		$comment_data->delete(array('id' => $key));	
	}
	
	showmsg('操作成功！',1);
	
}else if(isset($_POST["dosubmit2"])){    //通过审核并更新文档

    $sysinfo = get_sysinfo();
	
    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	
	foreach($_POST['fx'] as $key => $val){
		$comment_data->update(array('status' => '1'), array('id' => $key));
		if($sysinfo['article_html']) make_html($val);
	}

	showmsg('操作成功！',1);

	
}else if(isset($_POST["dosubmit3"])){    //不通过审核

    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	
	foreach($_POST['fx'] as $key => $val){
		$comment_data->update(array('status' => '-1'), array('id' => $key));
	}	
	
	showmsg('操作成功！',1);
	
}



//评论搜索
$where = '1=1';
$status = isset($_GET["status"]) ? intval($_GET["status"]) : 99 ;

if(isset($_GET["search"])){
	
	$searinfo = isset($_GET["searinfo"]) ? $_GET["searinfo"] : '';
	$searkey = isset($_GET["searkey"]) ? $_GET["searkey"] : '';

	if($searinfo != ''){
		if($searkey != 'id')
		    $where .= ' AND '.$searkey.' LIKE \'%'.$searinfo.'%\'';
		else
			$where .= ' AND a.articleid = '.intval($searinfo);
	}

	if($status != 99){
		$where .= ' AND status = '.$status;
	}	
	
}

$total = $comment_data->join('yzmcms_comment b ON yzmcms_comment_data.articleid=b.articleid')->where($where)->total();
$page = new spage($total,10);
$start = $page->start_rows();
$res = $comment_data->field('yzmcms_comment_data.*,b.title,b.url')->join('yzmcms_comment b ON yzmcms_comment_data.articleid=b.articleid')->where($where)->order('id DESC')->limit("$start, 10")->select();

include('templets/manage_comment.htm');