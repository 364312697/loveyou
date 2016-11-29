<?php
require('check.php');

$collection_content = M('collection_content');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if(!isset($_GET['fx']) || !is_array($_GET['fx'])) showmsg("你没有选择项目！");

//新建发布方案
if(isset($_POST['dosubmit'])){ 
    if(!isset($_GET['fx']) || !is_array($_GET['fx'])) showmsg("你没有选择项目！");	

	$sysinfo = get_sysinfo();				 				
	$dir = M('column')->field('dir')->where(array('id'=>$_POST['catid']))->find();					 
	$_POST['wroot'] = $sysinfo['wroot'];
	$_POST['columnpath'] = $sysinfo['html_path'].$dir['dir'].'/';       //获取文章的存放路径
	$_POST['status'] = 7;
	$_POST['keyword'] = $sysinfo['wname'];	
	
	$_POST['ip'] = getip();
	$_POST['system'] = $_POST['display'] = '1';
	$_POST['username'] = $_SESSION['adminname'];
	
	$where = to_sqls($_GET["fx"], '', 'id').' AND `status`=1';
	
	$collection_content = M('collection_content');
	$data = $collection_content->field('id AS cid,data')->where($where)->select(); 
	
	$article = M('article');
	
	$i = 0;
	foreach($data as $v){
		$_POST = array_merge($_POST, string2array($v['data']));
		
		if(!$_POST['content']) continue;
		
		$_POST['abstract'] = $_POST['auto_abstract'] ? cut_str(trim(strip_tags($_POST['content'])),70) : '';
		if($_POST['auto_thumb']){
			$r = match_img($_POST['content']);
			$_POST['thumbnail'] = $r ? $r : '';
		}

		$lastid = $article->insert($_POST);
		
		$url = $sysinfo['wroot'].$_POST['columnpath'].$lastid.'.html';
		$article->update(array('url' => $url), array('id'=>$lastid));
		$collection_content->update(array('status' => 2), array('id'=>$v['cid']));
		$i++;
	}
	
	$html_message =  '<p>导入完成，共成功导入'.$i.'篇文档</p><p>您还可以去：<a href="make_articlehtml.php?catid='.$_POST['catid'].'">更新HTML文档</a> 或者 <a href="collection_list.php?status=1&search=1">继续导入</a></p>';
	include('templets/message.htm');exit();
	
}

//导入选中
if(isset($_GET['dosubmit'])){
	include('templets/collection_content_import.htm'); 	
}

