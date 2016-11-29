<?php
require('check.php');

$article = M('article');
$article_data = M('article_data');
$column = M('column');
		
/* 批量删除 */
if(isset($_POST["sub"])){

    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	
	$del_num = count($_POST["fx"]);
		
	//判断是否有删除任意文档的权限
	if(test_purview('del_all_article')){
		for($i = 0 ; $i < $del_num ; $i++){ 
			$r = $article->field('columnpath')->where(array('id' => $_POST['fx'][$i]))->find();
            $htmlpath = YZMCMS_PATH.'/'.$r['columnpath'].$_POST['fx'][$i].'.html';	
            if(is_file($htmlpath)) @unlink($htmlpath);			
			$article->delete(array('id' => $_POST['fx'][$i]));				 
			$article_data->delete(array('id' => $_POST['fx'][$i]));				 
		}
		showmsg('操作成功！', 1);
	}else{
		for($i = 0 ; $i < $del_num ; $i++){ 
			$r = $article->field('columnpath')->where(array('id' => $_POST['fx'][$i], 'username' => $_SESSION['adminname']))->find();
			$htmlpath = YZMCMS_PATH.'/'.$r['columnpath'].$_POST['fx'][$i].'.html';	
			if(is_file($htmlpath)) @unlink($htmlpath);	
			$article->delete(array('id' => $_POST['fx'][$i], 'username' => $_SESSION['adminname']));			
		}
		showmsg('权限不足，只删除了您自己发布的信息！', 3);
	}
		
}

$where = '1=1';
$catid = isset($_GET["catid"]) ? intval($_GET["catid"])	 : 0 ;

if(isset($_GET["search"])){
	
	$searinfo = isset($_GET["searinfo"]) ? $_GET["searinfo"] : '';
	$searkey = isset($_GET["searkey"]) ? $_GET["searkey"] : '';


	if($searinfo != ''){
		$where .= ' AND '.$searkey.' LIKE \'%'.$searinfo.'%\'';
	}

	if($catid != '0'){
		$where .= ' AND catid='.$catid;
	}

	if(isset($_GET["start"]) && $_GET["start"] == ''){	
		$where .= ' AND inputtime < '.strtotime($_GET["end"]);
	}else if(isset($_GET["start"]) && $_GET["start"] != ''){		
		$where .= ' AND inputtime BETWEEN '.strtotime($_GET["start"]).' AND '.strtotime($_GET["end"]);
	}

	if(isset($_GET["status"]) && $_GET["status"] != '0'){
		$where .= ' AND FIND_IN_SET('.$_GET["status"].',status)';
	}

	if(isset($_GET["display"]) && $_GET["display"] != '99'){
		$where .= ' AND display = '.$_GET["display"];
	}	
	
}

$total = $article->where($where)->total();	

$page = new spage($total,10);
$start = $page->start_rows();
$res = $article->field('id,title,click,thumbnail,status,system,display,username,catid,inputtime')->order('id DESC')->limit("$start,10")->select();
include('templets/manage_info.htm');
