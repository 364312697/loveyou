<?php 
define('IN_YZMCMS',true);
require('../config/common.inc.php');
$sysinfo = get_sysinfo();
extract($sysinfo);
$column = M('column');

$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
if(!$column_res = getcache($cid, 1, 'column')){
	$column_res = $column->where(array('display'=>'1', 'id'=>$cid))->find();
	setcache($cid, $column_res, 1, 'column');
}
if(!$column_res) showmsg('栏目不存在或禁止访问！',3,$wroot);

$title = !empty($column_res['seo_title']) ? $column_res['seo_title'] : $column_res['title'];
$wkeyword = !empty($column_res['seo_keywords']) ? $column_res['seo_keywords'] : $wkeyword;
$wdescription = !empty($column_res['seo_description']) ? $column_res['seo_description'] : $wdescription;

//获取网站导航
if(!$navigation = getcache('column_nav',1,'column')){
	$navigation = $column->where(array('display'=>1, 'pid'=>0))->order('ord ASC')->select();
	setcache('column_nav', $navigation, 1, 'column');
}

//获取本栏目及子栏目的where条件
$artile_where = get_sql_catid($cid);

$article = M('article');

//调用本栏目及子栏目下的前25篇文章
$_GET['cpage'] = isset($_GET['cpage']) ? intval($_GET['cpage']) : 1;				
//总条数
$total = $article->where($artile_where)->total();
$page = new spage($total,25);
$start = $page->start_rows();
$res = $article->field('id,title,url,inputtime,thumbnail,status')->where($artile_where)->order('`status` ASC,`inputtime` DESC')->limit("$start,25")->select();

if($column_res['type'] == 0){
	include('templets/list.htm');
}else{
	include('templets/singlepage.htm');
}
