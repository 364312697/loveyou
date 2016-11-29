<?php
/**
 * introduce:网站前端公共文件
 * 一些前端共有的功能在这里定义.
 * All rights reserved Powered by YzmCMS
 * @lastmodify			2016-01-18
 */
 
if(!defined("IN_YZMCMS")) define('IN_YZMCMS',true);

if(!defined("HOME_PATH")) define('HOME_PATH', dirname(str_replace("\\", '/', dirname(__FILE__))));

require_once(HOME_PATH.'/config/common.inc.php');

//栏目和文章数据表初始化
$column = M('column');
$article = M('article'); //文章属性：1置顶,2头条,3特荐,4推荐,5热点,6幻灯

//网站全局配置
$sysinfo = get_sysinfo();
extract($sysinfo);

//默认缩略图
$default_thumb = $wroot.'common/images/thumbnail.jpg';

//获取用户自定义变量
if(!$data = getcache('custom', 2)){
	$data = array();
	$custom_data = M("custom")->select();
	foreach($custom_data as $val){
		$data[$val['name']] = $val['val'];
	}
	setcache('custom', $data, 2);
}
extract($data);	

//获取网站导航
if(!$navigation = getcache('column_nav',1,'column')){
	$navigation = $column->where(array('display'=>1, 'pid'=>0))->order('ord ASC')->limit('8')->select();
	setcache('column_nav', $navigation, 1, 'column');
}

//代表本模板路径 格式为：http://开头
$self_path = $wroot.'templets/'.$tem_style.'/';