<?php 
if(!defined('IN_YZMCMS')){
	session_start();
	require('./common/frontend.inc.php');
} 

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

//如果不是后台管理，则只能查看 “显示” 的信息
if(isset($_SESSION['is_syslogin']))
    $article_res = $article->where(array('id'=>$id))->find();
else
    $article_res = $article->where(array('display'=>'1','id'=>$id))->find();


if(!$article_res) showmsg('你没有权限访问该信息！',3,$wroot);
extract($article_res);

//获取附加字段,type为1的是文章模型附加字段
if(!$article_data = getcache('article_data',2,'model')){
	$article_data = M('model_field')->where(array('type' => 1, 'disabled' => 0))->order('listorder ASC,field ASC')->limit('100')->select();
	setcache('article_data', $article_data, 2, 'model');
}

//如果存在附加字段
if($article_data){
	 $article_res = M('article_data')->where(array('id'=>$id))->find();
	 if($article_res) extract($article_res);
}


$wkeywords = $keyword;
$wdescription = $abstract;


//获取栏目信息
$column_res = $column->where(array('id'=>$catid))->find();

//获取栏目ID,网站头部用到
$cid = $catid;

//获取当前位置
$where = 'id IN ('.$column_res["path"].','.$column_res["id"].')';
$res = $column->field('id,title,pclink')->where($where)->select();
$location = '<a href="'.$wroot.'">首页</a> ';
foreach($res as $val){
    $location .= '&gt; <a href="'.$val['pclink'].'" title="'.$val['title'].'">'.$val['title'].'</a>';				  
}



//获取和当前文章在同一个类别中的上一篇/下一篇显示的文章	
$pre = $article->field('id,title,url,inputtime,thumbnail')->where(array('id<'=>$id , 'display'=>'1' , 'catid'=>$catid))->order('id DESC')->find();
$next = $article->field('id,title,url,inputtime,thumbnail')->where(array('id>'=>$id , 'display'=>'1', 'catid'=>$catid))->order('id ASC')->find();
$pre = $pre ? '<a href="'.$pre['url'].'">'.$pre['title'].'</a>' : '已经是第一篇';
$next = $next ? '<a href="'.$next['url'].'">'.$next['title'].'</a>' : '已经是最后一篇';



//获取当前文章的评论内容
$comment_data = M('comment_data');
$webinfo = get_sysinfo();
$comment_res = $comment_data->where(array('articleid'=>$id , 'status>='=>$webinfo['comment_check']))->order('inputtime DESC')->limit('20')->select();  //防止评论过多，暂且只展示20条
$comment_total = $comment_data->total();

include template($column_res['article_template']);