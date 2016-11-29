<?php 
define('IN_YZMCMS',true);
require('../config/common.inc.php');
$sysinfo = get_sysinfo();
extract($sysinfo);
$column = M('column');

//获取网站导航
if(!$navigation = getcache('column_nav',1,'column')){
	$navigation = $column->where(array('display'=>1, 'pid'=>0))->order('ord ASC')->select();
	setcache('column_nav', $navigation, 1, 'column');
}

$article = M('article');

include('templets/index.htm');