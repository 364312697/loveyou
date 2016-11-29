<?php 
define('IN_YZMCMS',true);
session_start();
require('../config/common.inc.php');
$sysinfo = get_sysinfo();
extract($sysinfo);
$article = M('article');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

//如果不是后台管理，则只能查看 “显示” 的信息
if($_SESSION['is_syslogin'] && $_SESSION['adminid'])
  $article_res = $article->where(array('id'=>$id))->find();
else
  $article_res = $article->where(array('display'=>'1','id'=>$id))->find();


if(!$article_res) showmsg('你没有权限访问该信息！',3,$wroot);
extract($article_res);

$wkeywords = $keyword;
$wdescription = $abstract;

//获取网站导航
if(!$navigation = getcache('column_nav',1,'column')){
	$navigation = $column->where(array('display'=>1, 'pid'=>0))->order('ord ASC')->select();
	setcache('column_nav', $navigation, 1, 'column');
}

//更新点击量
$article->update('`click`=`click`+1',array('id'=>$id));

//获取栏目信息
$column = M('column');
$column_res = $column->where(array('id'=>$catid))->find();

//获取当前位置
$where = 'id IN ('.$column_res["path"].','.$column_res["id"].')';
$res = $column->field('id,title,pclink')->where($where)->select();
$location = '<a href="'.$wroot.'">首页</a> ';
foreach($res as $val){
     $location .= '&gt; <a href="'.$wroot.$val['pclink'].'" title="'.$val['title'].'">'.$val['title'].'</a>';				  
}



//获取和当前文章在同一个类别中的上一篇/下一篇显示的文章	
$pre = $article->field('id,title,url,inputtime,thumbnail')->where(array('id<'=>$id , 'display'=>'1' , 'catid'=>$catid))->order('id DESC')->find();
$next = $article->field('id,title,url,inputtime,thumbnail')->where(array('id>'=>$id , 'display'=>'1', 'catid'=>$catid))->order('id ASC')->find();
$pre = $pre ? '<a href="article.php?id='.$pre['id'].'">'.$pre['title'].'</a>' : '已经是第一篇';
$next = $next ? '<a href="article.php?id='.$next['id'].'">'.$next['title'].'</a>' : '已经是最后一篇';

//默认缩略图
$default_thumb = $wroot.'common/images/thumbnail.jpg';

//获取当前文章的评论内容
$comment_data = M('comment_data');
$webinfo = get_sysinfo();
$comment_res = $comment_data->where(array('articleid'=>$id , 'status>='=>$webinfo['comment_check']))->order('inputtime DESC')->limit('20')->select();  //防止评论过多，暂且只展示20条
$comment_total = $comment_data->total();

/** 
---------------------
用户自定义开始
---------------------
**/

//相关推荐 调用本类下的推荐的4篇文章
$tuijian = $article->where("`display`=1 AND FIND_IN_SET('4',status) AND catid=$catid")->order('inputtime DESC')->limit('0,4')->select();

//随机文章 调用本类下随机抽取的4篇文章
$rand = $article->where(array('display'=>'1','catid'=>$catid))->order('RAND()')->limit('0,4')->select();

//点击排行榜 调用本类下点击最高的4篇文章
$dianji = $article->where(array('display'=>'1','catid'=>$catid))->order('click DESC')->limit('0,4')->select();

/** 
---------------------
用户自定义结束
---------------------
**/

include('templets/article.htm');
