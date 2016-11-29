<?php
require('check.php');

$singlepage = M('singlepage');


//生成单页函数
function mkpage($catid){
	
	require(YZMCMS_PATH.'/common/frontend.inc.php');

	$cid = $catid; //获取栏目ID,网站头部用到
	
	if(!$column_res = getcache($cid, 1, 'column')){
		$column_res = $column->where(array('display'=>1, 'id'=>$cid))->find();
		setcache($cid, $column_res, 1, 'column');
	}
	
	if(!$column_res) return false;
	
	$title = !empty($column_res['seo_title']) ? $column_res['seo_title'] : $column_res['title'];
	$wkeyword = !empty($column_res['seo_keywords']) ? $column_res['seo_keywords'] : $wkeyword;
	$wdescription = !empty($column_res['seo_description']) ? $column_res['seo_description'] : $wdescription;
	
	$mkdir_path = YZMCMS_PATH.'/'.$html_path.$column_res['dir'];
	if(!is_dir($mkdir_path)) mkdir($mkdir_path, 0755, true);

	
	ob_start();
	include(YZMCMS_PATH.'/templets/'.$tem_style.'/'.$column_res['list_template']);
	$data = ob_get_contents();				
	ob_clean();
	return file_put_contents($mkdir_path.'/index.html', $data);
}



if(isset($_GET["catid"])){
	$strlen = mkpage(intval($_GET["catid"]));
	if($strlen){
		showmsg('生成HTML成功，大小为：'.sizecount($strlen));
	}else{
		showmsg('生成HTML失败，请检查是否有写入权限！');
	}

}else if(isset($_POST["dosubmit"])){
	
	$r = M('column')->field('id,pclink,dir')->where(array('type' => 1))->select();
	if(empty($r))  showmsg('单页栏目不存在！');
	foreach($r as $v){
		$strlen = mkpage($v['id']);
	}
	
	if($strlen){
		showmsg('生成HTML成功，共'.count($r).'个文件！');
	}else{
		showmsg('生成HTML失败，请检查是否有写入权限！');
	}

}else{
	$data = $singlepage->select();
	include('templets/make_pagehtml.htm');
}
