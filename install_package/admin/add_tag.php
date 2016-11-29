<?php
require('check.php');

$tag_db = M('tag');
$res = $tag_db->select();

if(isset($_POST['sub'])){	
	$arr = explode(',',$_POST['inputTagator']);
	foreach($arr as $val){
		$tag_db->insert(array('tag'=>$val,'catid'=>$_POST['catid'],'tag_click'=>0,'article_total'=>0,'inputtime'=>time()),1);
	}
	showmsg('操作成功，共添加'.count($arr).'条记录！','3','manage_tag.php');
}
include('templets/add_tag.htm');