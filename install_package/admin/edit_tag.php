<?php
require('check.php');

$tag_db = M('tag');
$res = $tag_db->select(); //全部的标签
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$re = $tag_db->where(array('id'=>$id))->find();
		
if(isset($_POST["sub"])){		
	if($_POST["tag"]!=''){
		$tag_db->update($_POST,array('id'=>$id),1);
		showmsg('修改成功！',1,'manage_tag.php');				
	}else{
	    showmsg("TAG名称不能为空！");
	}
}	
include('templets/edit_tag.htm');