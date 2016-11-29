<?php
require('check.php');

$collection_node = M('collection_node');
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$data = $collection_node->where(array('nodeid' => $id))->find();
		
if(isset($_POST['dosubmit'])){	
	if($collection_node->update($_POST, array('nodeid'=>$id))){
		showmsg("操作成功", 1, 'manage_collection.php');
	}else{
		showmsg("数据未修改！");
	}	
}	
include('templets/edit_collection.htm');