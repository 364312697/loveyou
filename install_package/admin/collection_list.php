<?php
require('check.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$nodeid = isset($_GET['nodeid']) ? intval($_GET['nodeid']) : 0;

$collection_content = M('collection_content');
$where = '1 = 1';
if(isset($_GET['search'])){
	
	$status = isset($_GET["status"]) ? intval($_GET["status"]) : 0;
	$searinfo = isset($_GET["searinfo"]) ? $_GET["searinfo"] : '';
    $searkey = isset($_GET["searkey"]) ? $_GET["searkey"] : '';
	
	if($searinfo != ''){
		if($searkey != 'nodeid')
		    $where .= ' AND '.$searkey.' LIKE \'%'.$searinfo.'%\'';
	    else
			$where .= ' AND nodeid = \''.$searinfo.'\'';
	}
	
	//从内容发布页过来的搜索
	if($nodeid) {
		$where .= ' AND nodeid = \''.$nodeid.'\'';
	}
	
	if($status != 99) {
		$where .= ' AND status = '.$status;
	}
	
}else if(isset($_POST['dosubmit_all'])){    //节点删除 

    $nodeid = isset($_POST['nodeid']) ? intval($_POST['nodeid']) : 0;
    if(!$nodeid) showmsg("你没有选择节点！");
	
	$num = $collection_content->delete(array('nodeid' => $nodeid));
	showmsg('操作成功，共删除'.$num.'条数据');
	
}else if(isset($_POST['dosubmit'])){
	
    if(!isset($_POST['fx']) || !is_array($_POST['fx'])) showmsg("你没有选择项目！");
	
	for($i = 0 ; $i < count($_POST['fx']) ; $i++){ 						
	    $collection_content->delete(array('id' => $_POST['fx'][$i]));				 
	}
	showmsg('操作成功！',1);
	
}

$total = $collection_content->where($where)->total();
$page = new spage($total,10);
$start = $page->start_rows();
$res = $collection_content->limit("$start,10")->order('id DESC')->select();

include('templets/collection_list.htm'); 