<?php
require('check.php');

$article = M('article');
$column = M('column');
	
if(isset($_GET["id"])){
	$id = intval($_GET["id"]);	
	$r = $article->field('username,columnpath')->where(array('id' => $id))->find();
	//判断是否有删除任意文档的权限
	if(test_purview('del_all_article') || $r['username']==$_SESSION['adminname']){		
		$htmlpath = YZMCMS_PATH.'/'.$r['columnpath'].$id.'.html';	
		if(is_file($htmlpath)) @unlink($htmlpath);
        $article->delete(array('id'=>$id));		
		showmsg('操作成功！', 1);
	}else{
		showmsg('权限不足，您只有权限删除自己发布的信息！', 3);
	}	
	
}

$total = $article->field('id')->where(array('thumbnail!='=>''))->total();

$page = new spage($total,5);
$start = $page->start_rows();
$res = $article->field('id,thumbnail,catid,title,inputtime,username,system,click')->order('id DESC')->limit("$start,5")->select();
include('templets/img_table.htm');