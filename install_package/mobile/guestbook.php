<?php 
define('IN_YZMCMS',true);
require('../config/common.inc.php');
$sysinfo = get_sysinfo();
extract($sysinfo);

$title = '留言反馈-'.$wname;

//获取当前位置
$location = '<a href="'.$wroot.'">首页</a> &gt; 留言反馈';

//获取网站导航
if(!$navigation = getcache('column_nav',1,'column')){
	$navigation = $column->where(array('display'=>1, 'pid'=>0))->order('ord ASC')->select();
	setcache('column_nav', $navigation, 1, 'column');
}

include('templets/guestbook.htm');