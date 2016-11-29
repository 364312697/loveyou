<?php
require('check.php');

$collection_node = M('collection_node');

if(isset($_GET["id"])){
	$id = intval($_GET["id"]);
	if($collection_node->delete(array('nodeid' => $id)))
		showmsg("操作成功！",1);
	else
		showmsg("操作失败！");
}
  
$total = $collection_node->total();
$page = new spage($total,10);
$start = $page->start_rows();
$res = $collection_node->limit("$start,10")->order('nodeid DESC')->select();
include('templets/manage_collection.htm');  