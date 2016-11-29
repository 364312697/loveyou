<?php
require('check.php');

if(isset($_POST['dosubmit'])){
	$collection_node = M('collection_node');									
	if($collection_node->insert($_POST)){
		showmsg("操作成功", 1, 'manage_collection.php');
	}else{
		showmsg("操作失败！");
	}
}
include('templets/add_collection.htm');