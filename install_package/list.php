<?php 
require('./common/frontend.inc.php');

//判断是否开启静态模式
if($is_pathinfo && isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO']!='/'){ 
	$pathinfo = explode('/', trim($_SERVER['PATH_INFO'], "/"));	
	$column_dir = str_replace('.', '', $pathinfo[0]);
	$_GET['page'] = isset($pathinfo[1]) ? intval($pathinfo[1]) : 1;
	
	if(!$column_res = getcache($column_dir, 1, 'column')){
		$column_res = $column->where(array('dir' => $column_dir))->find();
		if(!$column_res) showmsg('栏目不存在或禁止访问！',3,$wroot);
		setcache($column_dir, $column_res, 1, 'column');
	}
		
	$cid = $column_res['id'];	//获取栏目ID,网站头部用到
}else{
	$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0; //获取栏目ID,网站头部用到
	
	if(!$column_res = getcache($cid, 1, 'column')){
		$column_res = $column->where(array('id' => $cid))->find();
		if(!$column_res) showmsg('栏目不存在或禁止访问！',3,$wroot);
		setcache($cid, $column_res, 1, 'column');
	}
	
}
$title = !empty($column_res['seo_title']) ? $column_res['seo_title'] : $column_res['title'];
$wkeyword = !empty($column_res['seo_keywords']) ? $column_res['seo_keywords'] : $wkeyword;
$wdescription = !empty($column_res['seo_description']) ? $column_res['seo_description'] : $wdescription;

//获取当前位置
if(!$location = getcache($cid.'_location', 1, 'column')){
	$where = 'id IN ('.$column_res["path"].','.$column_res["id"].')';
	$res = $column->field('id,title,pclink')->where($where)->select();
	$location = '<a href="'.$wroot.'">首页</a> ';
	foreach($res as $val){
		 $location .= '&gt; <a href="'.$val['pclink'].'" title="'.$val['title'].'">'.$val['title'].'</a>';				  
	}
	setcache($cid.'_location', $location, 1, 'column');
}


//获取本栏目及子栏目的where条件
$artile_where = get_sql_catid($cid);

/** 
*---------------------
*用户自定义开始  //文章属性：1置顶,2头条,3特荐,4推荐,5热点,6幻灯
*---------------------
**/






/** 
*---------------------
*用户自定义结束
*---------------------
**/

include template($column_res['list_template']);